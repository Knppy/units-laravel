<?php

declare(strict_types=1);

namespace Knppy\LaravelUnits\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Knppy\Units\Unit make(int|string $amount, string $symbol)
 * @method static \Knppy\Units\Unit parse(string $input, ?string $fallbackSymbol = null, ?string $locale = null)
 * @method static string format(\Knppy\Units\Unit $unit, ?string $locale = null)
 * @method static \Knppy\Units\Unit convert(\Knppy\Units\Unit $unit, string $symbol, ?string $converter = null)
 * @method static \Knppy\Units\Contract\UnitParser parser(?string $locale = null)
 * @method static \Knppy\Units\Contract\UnitFormatter formatter(?string $locale = null)
 * @method static \Knppy\Units\Contract\UnitConverter converter(?string $converter = null)
 * @method static \Knppy\Units\Contract\UnitNames names()
 * @method static string locale(?string $locale = null)
 *
 * @see \Knppy\LaravelUnits\LaravelUnits
 */
class LaravelUnits extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Knppy\LaravelUnits\LaravelUnits::class;
    }
}
