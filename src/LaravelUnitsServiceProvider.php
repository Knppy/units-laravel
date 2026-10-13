<?php

declare(strict_types=1);

namespace Knppy\LaravelUnits;

use Illuminate\Support\ServiceProvider;

class LaravelUnitsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/units-laravel.php', 'units-laravel');

        $this->app->singleton(LaravelUnits::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__.'/../config/units-laravel.php' => config_path('units-laravel.php'),
        ], ['units-laravel', 'units-laravel-config']);
    }
}
