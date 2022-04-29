<?php

namespace AliRahimiCoder\LaravelDataMigrations\Commands;

use AliRahimiCoder\LaravelDataMigrations\Support\Stub;

class MakeDataMigration extends GeneratorCommand
{
    protected $signature = 'data-migration:make
                            {name : The name of the migration}
                            {table : The table}
                            {path : Path to the migration directory relative to project root}
                            {--exclusive : Whether any value not in the migrations should be deleted}
                            {--F|force : Create the migration even if the migration file already exists}';

    protected $description = 'Create a new data migration';

    protected function getContents(): string
    {
        return (new Stub('/data-migration.stub', [
            'TABLE' => $this->argument('table'),
            'EXCLUSIVE' => $this->option('exclusive') ? 'true' : 'false',
        ]))->render();
    }

    protected function getDestinationFilePath(): string
    {
        return base_path() . $this->argument('path') . '/' . $this->argument('name') . '.php';
    }
}