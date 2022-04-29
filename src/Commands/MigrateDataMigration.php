<?php

namespace AliRahimiCoder\LaravelDataMigrations\Commands;

use Illuminate\Console\Command;
use AliRahimiCoder\LaravelDataMigrations\DataMigration;

class MigrateDataMigration extends Command
{
    protected $signature = 'data-migration:migrate
                            {path : The path to the migration file.}';

    public function handle()
    {
        $path = $this->argument('path');

        $class = require base_path().'/'.$path;

        if (!$class instanceof DataMigration) {
            $this->error('The migration file does not contain a valid DataMigration class.');
            return self::INVALID;
        }

        /** @var DataMigration $migration */
        $migration = new $class;

        if ($migration->isExclusive()) {
            if (!
            $this->confirm(
                'This migration is exclusive and will delete data from the database. Do you want to continue?',
                false
            )
            ) {
                $this->error('Migration cancelled.');
                return self::FAILURE;
            }
        }

        $migration->run();

        $this->info('Migration completed successfully.');
        return self::SUCCESS;
    }
}