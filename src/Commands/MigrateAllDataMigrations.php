<?php

namespace AliRahimiCoder\LaravelDataMigrations\Commands;

use Illuminate\Console\Command;
use AliRahimiCoder\LaravelDataMigrations\DataMigration;

class MigrateAllDataMigrations extends Command
{
    protected $signature = 'data-migration:all 
                            {--exclusive : Run the exclusive migrations}';
    
    protected $description = 'Migrate All Data Migrations as configured in data-migrations.php config file';

    public function handle(): int
    {
        $exclusive = $this->option('exclusive');

        if ($exclusive) {
            if (!
            $this->confirm(
                'Are you sure you want to run the exclusive migrations? They will DELETE your data.',
                false
            )
            ) {
                $this->error('Command aborted.');
                return self::FAILURE;
            }
        }

        $paths = $this->getDataMigrationPaths();

        $migrationsRun = 0;
        foreach ($paths as $path) {
            $class = require base_path() . $path;
            if ($class instanceof DataMigration) {
                /** @var DataMigration $migration */
                $migration = new $class;
                if ($migration->isExclusive() === $this->option('exclusive')) {
                    $migration->run();
                    $migrationsRun++;
                }
            }
        }

        $this->info("$migrationsRun data migrations run.");
        return self::SUCCESS;
    }

    protected function getDataMigrationPaths()
    {
        return config('data-migrations.paths');
    }
}
