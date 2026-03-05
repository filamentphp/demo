<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

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

        $lock = cache()->lock(self::LOCK_KEY, 300);

        if (! $lock->get()) {
            $this->warn('Another reset is already in progress. Skipping.');

            return self::SUCCESS;
        }

        try {
            if (! $swapOnly) {
                $this->prepare();
            }

            if (! $prepareOnly) {
                if (! $this->freshDatabaseExists()) {
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

    protected function prepare(): void
    {
        $this->info('Preparing fresh database...');

        // Connect to the maintenance `postgres` database for DDL operations
        $this->onPostgresConnection(function () {
            DB::statement('DROP DATABASE IF EXISTS fresh');
            DB::statement('CREATE DATABASE fresh');
        });

        // Point the app connection at the fresh database for migrations
        $database = config('database.connections.pgsql.database');
        config(['database.connections.pgsql.database' => 'fresh']);
        DB::purge();
        DB::reconnect();

        $this->info('Running migrations and seeders...');

        Artisan::call('migrate', ['--seed' => true, '--force' => true]);
        $this->info(Artisan::output());

        // Restore the original database connection
        config(['database.connections.pgsql.database' => $database]);
        DB::purge();
        DB::reconnect();
    }

    protected function swap(): void
    {
        $database = config('database.connections.pgsql.database');

        Artisan::call('down', ['--render' => 'maintenance']);
        $this->info('Maintenance mode enabled, waiting for connections to finish...');

        sleep(1);

        // Disconnect from the app database so we can rename it
        DB::purge();

        $this->onPostgresConnection(function () use ($database) {
            // Terminate remaining connections to both databases
            DB::statement("
                SELECT pg_terminate_backend(pid)
                FROM pg_stat_activity
                WHERE datname IN (?, 'fresh')
                  AND pid != pg_backend_pid()
            ", [$database]);

            $this->info('Swapping databases...');

            DB::statement("ALTER DATABASE \"{$database}\" RENAME TO old");
            DB::statement("ALTER DATABASE fresh RENAME TO \"{$database}\"");
        });

        // Reconnect to the now-swapped database
        DB::reconnect();

        Artisan::call('up');

        $this->info('Demo database has been reset.');

        // Drop the old database in the background — no autovacuum triggered
        $this->onPostgresConnection(function () {
            DB::statement('
                SELECT pg_terminate_backend(pid)
                FROM pg_stat_activity
                WHERE datname = \'old\'
                  AND pid != pg_backend_pid()
            ');
            DB::statement('DROP DATABASE IF EXISTS old');
        });

        $this->info('Old database dropped.');
    }

    protected function freshDatabaseExists(): bool
    {
        return DB::scalar("SELECT EXISTS (SELECT 1 FROM pg_database WHERE datname = 'fresh')");
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
