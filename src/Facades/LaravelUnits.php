<?php

declare(strict_types=1);

namespace Knppy\LaravelUnits\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Knppy\LaravelUnits\LaravelUnits
 */
class LaravelUnits extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Knppy\LaravelUnits\LaravelUnits::class;
    }
}
