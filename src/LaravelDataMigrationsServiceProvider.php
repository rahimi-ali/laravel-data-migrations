<?php

namespace AliRahimiCoder\LaravelDataMigrations;

use Illuminate\Support\ServiceProvider;
use AliRahimiCoder\LaravelDataMigrations\Providers\ConsoleServiceProvider;

class LaravelDataMigrationsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/data-migrations.php' => config_path('data-migrations.php'),
        ], 'data-migrations-paths');
    }

    public function register(): void
    {
        $this->registerProviders();
    }

    protected function registerProviders()
    {
        $this->app->register(ConsoleServiceProvider::class);
    }
}