<?php

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function (): void {
    if (DB::connection()->getDriverName() !== 'pgsql') {
        $this->markTestSkipped('Schema swapping requires PostgreSQL.');
    }

    $this->originalConnection = config('database.connections.pgsql');
    $this->databaseName = 'demo_reset_test_' . bin2hex(random_bytes(8));
    DB::statement('CREATE DATABASE ' . $this->databaseName);

    config([
        'cache.default' => 'array',
        'database.connections.pgsql.database' => $this->databaseName,
        'database.connections.pgsql.url' => null,
        'database.connections.pgsql.search_path' => 'public',
    ]);
    DB::purge();

    DB::statement('CREATE TABLE customers (name TEXT NOT NULL)');
    DB::table('customers')->insert(['name' => 'Original customer']);

    config(['database.connections.observer' => config('database.connections.pgsql')]);
    $this->observer = DB::connection('observer');
    $this->observer->getPdo();

    Artisan::command('migrate {--force}', function (): int {
        DB::statement('CREATE TABLE customers (name TEXT NOT NULL)');

        return 0;
    });

    Artisan::command('db:seed {--force}', function (): int {
        DB::table('customers')->insert(['name' => 'Fresh customer']);

        return 0;
    });
});

afterEach(function (): void {
    if (! isset($this->databaseName)) {
        return;
    }

    DB::connection()->getEventDispatcher()->forget(QueryExecuted::class);
    Artisan::call('up');
    DB::purge('observer');
    DB::purge();
    config(['database.connections.pgsql' => $this->originalConnection]);
    DB::statement('DROP DATABASE ' . $this->databaseName);
});

it('keeps the live schema visible to existing connections throughout the swap', function (): void {
    $observedDuringSwap = false;

    DB::listen(function (QueryExecuted $query) use (&$observedDuringSwap): void {
        if ($query->sql !== 'ALTER SCHEMA public RENAME TO old') {
            return;
        }

        expect($this->observer->table('customers')->pluck('name')->all())
            ->toBe(['Original customer']);

        $observedDuringSwap = true;
    });

    $this->artisan('app:reset-demo-database')->assertSuccessful();

    expect($observedDuringSwap)->toBeTrue()
        ->and($this->observer->table('customers')->pluck('name')->all())->toBe(['Fresh customer'])
        ->and(app()->isDownForMaintenance())->toBeFalse();
});

it('rolls back the swap and leaves maintenance mode when the second rename fails', function (): void {
    DB::listen(function (QueryExecuted $query): void {
        if ($query->sql === 'ALTER SCHEMA public RENAME TO old') {
            DB::statement('DROP SCHEMA fresh CASCADE');
        }
    });

    expect(fn () => Artisan::call('app:reset-demo-database'))
        ->toThrow(QueryException::class);

    expect($this->observer->table('customers')->pluck('name')->all())->toBe(['Original customer'])
        ->and(app()->isDownForMaintenance())->toBeFalse()
        ->and(DB::scalar("SELECT EXISTS (SELECT 1 FROM pg_namespace WHERE nspname = 'fresh')"))->toBeTrue();
});

it('preserves the live database and restores the connection when preparation fails', function (string $command, bool $throws): void {
    Artisan::command($command . ' {--force}', function () use ($throws): int {
        if ($throws) {
            throw new RuntimeException('Preparation failed.');
        }

        return 1;
    });

    expect(fn () => Artisan::call('app:reset-demo-database'))
        ->toThrow(RuntimeException::class);

    expect(DB::table('customers')->pluck('name')->all())->toBe(['Original customer'])
        ->and($this->observer->table('customers')->pluck('name')->all())->toBe(['Original customer'])
        ->and(config('database.connections.pgsql.search_path'))->toBe('public')
        ->and(app()->isDownForMaintenance())->toBeFalse()
        ->and(cache()->lock('demo-database-reset', 600)->get())->toBeTrue();
})->with([
    'migration exit code' => ['migrate', false],
    'migration exception' => ['migrate', true],
    'seeder exit code' => ['db:seed', false],
    'seeder exception' => ['db:seed', true],
]);

it('does not swap when maintenance mode cannot be enabled', function (): void {
    Artisan::command('down {--render=}', fn (): int => 1);

    expect(fn () => Artisan::call('app:reset-demo-database'))
        ->toThrow(RuntimeException::class, 'Could not enable maintenance mode.');

    expect(DB::table('customers')->pluck('name')->all())->toBe(['Original customer'])
        ->and(app()->isDownForMaintenance())->toBeFalse();
});

it('leaves maintenance mode when enabling it only partially succeeds', function (): void {
    Artisan::command('down {--render=}', function (): int {
        app()->maintenanceMode()->activate([]);

        return 1;
    });

    expect(fn () => Artisan::call('app:reset-demo-database'))
        ->toThrow(RuntimeException::class, 'Could not enable maintenance mode.');

    expect(DB::table('customers')->pluck('name')->all())->toBe(['Original customer'])
        ->and(app()->isDownForMaintenance())->toBeFalse();
});

it('serves the fresh schema and defers cleanup while an existing transaction reads old data', function (): void {
    $this->observer->beginTransaction();
    $this->observer->table('customers')->get();

    DB::statement('ALTER DATABASE ' . $this->databaseName . " SET statement_timeout = '3s'");
    DB::statement("SET statement_timeout = '3s'");

    $this->artisan('app:reset-demo-database')
        ->expectsOutputToContain('Old schema is still in use. Cleanup will be retried on the next reset.')
        ->assertSuccessful();

    expect(DB::table('customers')->pluck('name')->all())->toBe(['Fresh customer'])
        ->and($this->observer->table('old.customers')->pluck('name')->all())->toBe(['Original customer'])
        ->and(app()->isDownForMaintenance())->toBeFalse();

    $this->observer->rollBack();

    $this->artisan('app:reset-demo-database')->assertSuccessful();

    expect(DB::scalar("SELECT EXISTS (SELECT 1 FROM pg_namespace WHERE nspname = 'old')"))->toBeFalse();
});

it('aborts preparation without disturbing the live schema if deferred cleanup is still blocked', function (): void {
    DB::statement('CREATE SCHEMA old');
    DB::statement('CREATE TABLE old.customers (name TEXT NOT NULL)');
    $this->observer->beginTransaction();
    $this->observer->table('old.customers')->get();
    DB::statement("SET statement_timeout = '3s'");

    try {
        Artisan::call('app:reset-demo-database');
        $this->fail('Reset should fail when the old schema cannot be removed.');
    } catch (QueryException $exception) {
        expect($exception->getCode())->toBe('55P03');
    }

    expect(DB::table('customers')->pluck('name')->all())->toBe(['Original customer'])
        ->and(app()->isDownForMaintenance())->toBeFalse()
        ->and(cache()->lock('demo-database-reset', 600)->get())->toBeTrue();

    $this->observer->rollBack();
});
