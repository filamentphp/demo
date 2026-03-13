<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Throwable;

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

        DB::statement('DROP SCHEMA IF EXISTS old CASCADE');
        DB::statement('DROP SCHEMA IF EXISTS fresh CASCADE');
        DB::statement('CREATE SCHEMA fresh');
        DB::statement('GRANT ALL ON SCHEMA fresh TO PUBLIC');

        config(['database.connections.pgsql.search_path' => 'fresh']);
        DB::purge();
        DB::reconnect();

        $this->info('Running migrations and seeders...');

        Artisan::call('migrate', ['--seed' => true, '--force' => true]);
        $this->info(Artisan::output());

        config(['database.connections.pgsql.search_path' => 'public']);
        DB::purge();
        DB::reconnect();
    }

    protected function swap(): void
    {
        Artisan::call('down', ['--render' => 'maintenance']);
        $this->info('Maintenance mode enabled, waiting for in-flight requests...');

        sleep(2);

        // Terminate other connections so schema rename can proceed
        DB::statement('
            SELECT pg_terminate_backend(pid)
            FROM pg_stat_activity
            WHERE datname = current_database()
              AND pid != pg_backend_pid()
        ');

        DB::purge();
        DB::reconnect();

        $this->info('Swapping schemas...');

        try {
            DB::statement('ALTER SCHEMA public RENAME TO old');
            DB::statement('ALTER SCHEMA fresh RENAME TO public');
        } catch (Throwable $e) {
            // If public was renamed but fresh rename failed, restore
            if (! $this->schemaExists('public') && $this->schemaExists('old')) {
                DB::statement('ALTER SCHEMA old RENAME TO public');
            }

            throw $e;
        }

        DB::statement('DROP SCHEMA IF EXISTS old CASCADE');

        DB::purge();
        DB::reconnect();

        Artisan::call('up');
        Artisan::call('queue:restart');

        $this->info('Demo database has been reset.');
    }

    protected function schemaExists(string $name): bool
    {
        return DB::scalar('SELECT EXISTS (SELECT 1 FROM pg_namespace WHERE nspname = ?)', [$name]);
    }
}
