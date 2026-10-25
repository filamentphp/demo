<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ResetDemoDatabase extends Command
{
    protected $signature = 'app:reset-demo-database';

    protected $description = 'Reset the demo database using schema swapping for near-zero downtime.';

    private const LOCK_KEY = 'demo-database-reset';

    public function handle(): int
    {
        $lock = cache()->lock(self::LOCK_KEY, 600);

        if (! $lock->get()) {
            $this->warn('Another reset is already in progress. Skipping.');

            return self::SUCCESS;
        }

        try {
            $this->recover();
            $this->prepare();
            $this->swap();
        } finally {
            $lock->release();
        }

        return self::SUCCESS;
    }

    protected function recover(): void
    {
        if (! $this->schemaExists('public') && $this->schemaExists('old')) {
            $this->warn('Schema "public" missing, recovering from previous failed swap...');
            DB::statement('ALTER SCHEMA old RENAME TO public');
            $this->info('Recovery complete.');
        }
    }

    protected function prepare(): void
    {
        $this->info('Preparing fresh schema...');

        $this->dropOldSchema();
        DB::statement('DROP SCHEMA IF EXISTS fresh CASCADE');
        DB::statement('CREATE SCHEMA fresh');
        DB::statement('GRANT ALL ON SCHEMA fresh TO PUBLIC');

        $originalSearchPath = config('database.connections.pgsql.search_path');

        config(['database.connections.pgsql.search_path' => 'fresh']);
        DB::purge();

        try {
            $this->info('Running migrations and seeders...');

            foreach (['migrate', 'db:seed'] as $command) {
                $exitCode = Artisan::call($command, ['--force' => true]);
                $this->info(Artisan::output());

                if ($exitCode !== self::SUCCESS) {
                    throw new RuntimeException("The [{$command}] command failed. The demo database has not been swapped.");
                }
            }
        } finally {
            config(['database.connections.pgsql.search_path' => $originalSearchPath]);
            DB::purge();
            DB::reconnect();
        }
    }

    protected function swap(): void
    {
        try {
            if (Artisan::call('down', ['--render' => 'maintenance']) !== self::SUCCESS) {
                throw new RuntimeException('Could not enable maintenance mode. The demo database has not been swapped.');
            }

            $this->info('Maintenance mode enabled, waiting for in-flight requests...');

            sleep(2);

            $this->info('Swapping schemas...');

            DB::transaction(function (): void {
                DB::statement('ALTER SCHEMA public RENAME TO old');
                DB::statement('ALTER SCHEMA fresh RENAME TO public');
            });

            DB::purge();
            DB::reconnect();
        } finally {
            Artisan::call('up');
        }

        try {
            $this->dropOldSchema();
        } catch (QueryException $exception) {
            if ($exception->getCode() !== '55P03') {
                throw $exception;
            }

            $this->warn('Old schema is still in use. Cleanup will be retried on the next reset.');
        }

        Artisan::call('queue:restart');

        $this->info('Demo database has been reset.');
    }

    protected function dropOldSchema(): void
    {
        DB::transaction(function (): void {
            DB::statement("SET LOCAL lock_timeout = '1s'");
            DB::statement('DROP SCHEMA IF EXISTS old CASCADE');
        });
    }

    protected function schemaExists(string $name): bool
    {
        return DB::scalar('SELECT EXISTS (SELECT 1 FROM pg_namespace WHERE nspname = ?)', [$name]);
    }
}
