<?php

declare(strict_types=1);

namespace Knppy\LaravelUnits\Tests;

use Illuminate\Support\Facades\Schema;
use Knppy\LaravelUnits\LaravelUnitsServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            LaravelUnitsServiceProvider::class,
        ];
    }

    protected function defineDatabaseMigrations(): void
    {
        Schema::create('products', function ($table): void {
            $table->id();
            $table->unit('weight', nullable: true);
            $table->unit('volume', nullable: true);
        });
    }
}
