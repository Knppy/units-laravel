<?php

declare(strict_types=1);

namespace Knppy\LaravelUnits\Tests;

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
}
