<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class ResetDemoDatabase extends Command
{
    protected $signature = 'app:reset-demo-database
        {--prepare : Only prepare the fresh schema (migrate + seed) without swapping}
        {--swap : Only swap the previously prepared fresh schema into public}';

    protected $description = 'Reset the demo database using schema swapping for near-zero downtime.';

    public function handle(): int
    {
        $prepareOnly = $this->option('prepare');
        $swapOnly = $this->option('swap');

        if ($prepareOnly && $swapOnly) {
            $this->error('Cannot use --prepare and --swap together.');

            return self::FAILURE;
        }

        if (! $swapOnly) {
            $this->prepare();
        }

        if (! $prepareOnly) {
            if (! $this->freshSchemaExists()) {
                $this->error('Fresh schema does not exist. Run with --prepare first.');

                return self::FAILURE;
            }

            $this->swap();
        }

        return self::SUCCESS;
    }

    protected function prepare(): void
    {
        $this->info('Preparing fresh schema...');

        DB::statement('DROP SCHEMA IF EXISTS fresh CASCADE');
        DB::statement('CREATE SCHEMA fresh');

        config(['database.connections.pgsql.search_path' => 'fresh']);
        DB::purge();
        DB::reconnect();

        $this->info('Running migrations and seeders in fresh schema...');

        Artisan::call('migrate', ['--seed' => true, '--force' => true]);
        $this->info(Artisan::output());

        config(['database.connections.pgsql.search_path' => 'public']);
        DB::purge();
        DB::reconnect();
    }

    protected function swap(): void
    {
        $database = config('database.connections.pgsql.database');

        Artisan::call('down', ['--render' => 'maintenance']);
        $this->info('Maintenance mode enabled, waiting for connections to finish...');

        sleep(1);

        DB::statement('
            SELECT pg_terminate_backend(pid)
            FROM pg_stat_activity
            WHERE datname = ?
              AND pid != pg_backend_pid()
        ', [$database]);

        $this->info('Swapping schemas...');

        DB::transaction(function () {
            DB::statement('ALTER SCHEMA public RENAME TO old');
            DB::statement('ALTER SCHEMA fresh RENAME TO public');
        });

        DB::statement('DROP SCHEMA IF EXISTS old CASCADE');

        Artisan::call('up');

        $this->info('Demo database has been reset.');
    }

    protected function freshSchemaExists(): bool
    {
        return DB::scalar("SELECT EXISTS (SELECT 1 FROM information_schema.schemata WHERE schema_name = 'fresh')");
    }
}
