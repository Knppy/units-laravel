<?php

declare(strict_types=1);

namespace Knppy\LaravelUnits;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Knppy\LaravelUnits\Database\BlueprintMacros;
use Knppy\Units\Contract\UnitConverter;
use Knppy\Units\Contract\UnitFormatter;
use Knppy\Units\Contract\UnitNames;
use Knppy\Units\Contract\UnitParser;

class LaravelUnitsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/units-laravel.php', 'units-laravel');

        $this->app->singleton(LaravelUnits::class);

        $this->app->bind(UnitParser::class, fn (Application $app): UnitParser => $app->make(LaravelUnits::class)->parser());
        $this->app->bind(UnitFormatter::class, fn (Application $app): UnitFormatter => $app->make(LaravelUnits::class)->formatter());
        $this->app->bind(UnitConverter::class, fn (Application $app): UnitConverter => $app->make(LaravelUnits::class)->converter());
        $this->app->bind(UnitNames::class, fn (Application $app): UnitNames => $app->make(LaravelUnits::class)->names());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'units-laravel');

        BlueprintMacros::register();

        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__.'/../config/units-laravel.php' => config_path('units-laravel.php'),
        ], ['units-laravel', 'units-laravel-config']);

        $this->publishes([
            __DIR__.'/../resources/lang' => $this->app->langPath('vendor/units-laravel'),
        ], ['units-laravel', 'units-laravel-lang']);
    }
}
