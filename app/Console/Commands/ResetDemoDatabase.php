<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Throwable;

class ResetDemoDatabase extends Command
{
    protected $signature = 'app:reset-demo-database
        {--prepare : Only prepare the fresh database (migrate + seed) without swapping}
        {--swap : Only swap the previously prepared fresh database into use}';

    protected $description = 'Reset the demo database using database swapping for near-zero downtime.';

    private const LOCK_KEY = 'demo-database-reset';

    public function handle(): int
    {
        $prepareOnly = $this->option('prepare');
        $swapOnly = $this->option('swap');

        if ($prepareOnly && $swapOnly) {
            $this->error('Cannot use --prepare and --swap together.');

            return self::FAILURE;
        }

        $lock = cache()->lock(self::LOCK_KEY, 600);

        if (! $lock->get()) {
            $this->warn('Another reset is already in progress. Skipping.');

            return self::SUCCESS;
        }

        try {
            $this->recover();

            if (! $swapOnly) {
                $this->prepare();
            }

            if (! $prepareOnly) {
                if (! $this->databaseExists('fresh')) {
                    $this->error('Fresh database does not exist. Run with --prepare first.');

                    return self::FAILURE;
                }

                $this->swap();
            }
        } finally {
            $lock->release();
        }

        return self::SUCCESS;
    }

    protected function recover(): void
    {
        $database = config('database.connections.pgsql.database');

        $this->onPostgresConnection(function () use ($database) {
            if (! $this->databaseExists($database) && $this->databaseExists('old')) {
                $this->warn("Database \"{$database}\" missing, recovering from previous failed swap...");
                DB::statement("ALTER DATABASE old RENAME TO \"{$database}\"");
                $this->info('Recovery complete.');
            }
        });
    }

    protected function prepare(): void
    {
        $this->info('Preparing fresh database...');

        $this->onPostgresConnection(function () {
            DB::statement('DROP DATABASE IF EXISTS old');
            DB::statement('DROP DATABASE IF EXISTS fresh');
            DB::statement('CREATE DATABASE fresh');
        });

        $database = config('database.connections.pgsql.database');
        config(['database.connections.pgsql.database' => 'fresh']);
        DB::purge();
        DB::reconnect();

        $this->info('Running migrations and seeders...');

        Artisan::call('migrate', ['--seed' => true, '--force' => true]);
        $this->info(Artisan::output());

        config(['database.connections.pgsql.database' => $database]);
        DB::purge();
        DB::reconnect();
    }

    protected function swap(): void
    {
        $database = config('database.connections.pgsql.database');

        Artisan::call('down', ['--render' => 'maintenance']);
        $this->info('Maintenance mode enabled, waiting for in-flight requests...');

        sleep(2);

        DB::purge();

        $this->onPostgresConnection(function () use ($database) {
            // Terminate idle connections held by PHP-FPM workers and queue workers.
            // Maintenance mode prevents new requests, so these are all idle.
            // FPM workers survive this and will lazily reconnect on the next request.
            DB::statement("
                SELECT pg_terminate_backend(pid)
                FROM pg_stat_activity
                WHERE datname IN (?, 'fresh')
                  AND pid != pg_backend_pid()
            ", [$database]);

            $this->info('Swapping databases...');

            DB::statement("ALTER DATABASE \"{$database}\" RENAME TO old");

            try {
                DB::statement("ALTER DATABASE fresh RENAME TO \"{$database}\"");
            } catch (Throwable $e) {
                // Roll back: restore the original database name
                DB::statement("ALTER DATABASE old RENAME TO \"{$database}\"");

                throw $e;
            }
        });

        config(['database.connections.pgsql.database' => $database]);
        DB::purge();
        DB::reconnect();

        Artisan::call('up');
        Artisan::call('queue:restart');

        $this->info('Demo database has been reset.');
    }

    protected function databaseExists(string $name): bool
    {
        $this->onPostgresConnection(function () use ($name, &$exists) {
            $exists = DB::scalar('SELECT EXISTS (SELECT 1 FROM pg_database WHERE datname = ?)', [$name]);
        });

        return $exists;
    }

    /**
     * Execute a callback on the `postgres` maintenance database.
     *
     * Database-level DDL (CREATE/DROP/ALTER DATABASE) cannot target
     * the database you're currently connected to, so we temporarily
     * connect to the default `postgres` database instead.
     */
    protected function onPostgresConnection(callable $callback): void
    {
        $original = config('database.connections.pgsql.database');

        config(['database.connections.pgsql.database' => 'postgres']);
        DB::purge();
        DB::reconnect();

        try {
            $callback();
        } finally {
            config(['database.connections.pgsql.database' => $original]);
            DB::purge();
            DB::reconnect();
        }
    }
}
