<?php

namespace AliRahimiCoder\LaravelDataMigrations\Providers;

use Illuminate\Support\ServiceProvider;
use AliRahimiCoder\LaravelDataMigrations\Commands\MigrateDataMigration;
use AliRahimiCoder\LaravelDataMigrations\Commands\MakeDataMigration;
use AliRahimiCoder\LaravelDataMigrations\Commands\MigrateAllDataMigrations;

class ConsoleServiceProvider extends ServiceProvider
{
    protected array $commands = [
        MakeDataMigration::class,
        MigrateDataMigration::class,
        MigrateAllDataMigrations::class,
    ];

    public function register(): void
    {
        $this->commands($this->commands);
    }

    public function provides(): array
    {
        return $this->commands;
    }
}