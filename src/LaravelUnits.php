<?php

declare(strict_types=1);

namespace Knppy\LaravelUnits;

use Illuminate\Contracts\Foundation\Application;
use InvalidArgumentException;
use Knppy\Units\Contract\UnitConverter;
use Knppy\Units\Contract\UnitFormatter;
use Knppy\Units\Contract\UnitNames;
use Knppy\Units\Contract\UnitParser;
use Knppy\Units\Converter\CookingUnitConverter;
use Knppy\Units\Converter\SIUnitConverter;
use Knppy\Units\Formatter\LocalizedUnitFormatter;
use Knppy\Units\Parser\AggregateUnitParser;
use Knppy\Units\Parser\LocalizedUnitParser;
use Knppy\Units\Parser\SymbolUnitParser;
use Knppy\Units\Unit;
use Knppy\Units\UnitNames\CookingUnitNames;
use Knppy\Units\UnitNames\SIUnitNames;
use Knppy\Units\UnitOfMeasurement;

class LaravelUnits
{
    public function __construct(private readonly Application $container) {}

    /**
     * Create a unit from an amount and a symbol.
     */
    public function make(int|string $amount, string $symbol): Unit
    {
        return new Unit($amount, new UnitOfMeasurement($symbol));
    }

    /**
     * Parse localized names first, then symbols.
     */
    public function parse(string $input, ?string $fallbackSymbol = null, ?string $locale = null): Unit
    {
        return $this->parser($locale)->parse(
            $input,
            $fallbackSymbol === null ? null : new UnitOfMeasurement($fallbackSymbol),
        );
    }

    public function format(Unit $unit, ?string $locale = null): string
    {
        return $this->formatter($locale)->format($unit);
    }

    /**
     * Convert a unit to the unit with the given symbol, with the configured converter unless given.
     */
    public function convert(Unit $unit, string $symbol, ?string $converter = null): Unit
    {
        return $unit->convertTo(new UnitOfMeasurement($symbol), $this->converter($converter));
    }

    public function parser(?string $locale = null): UnitParser
    {
        return new AggregateUnitParser([
            new LocalizedUnitParser($this->names(), $this->locale($locale)),
            new SymbolUnitParser,
        ]);
    }

    public function formatter(?string $locale = null): UnitFormatter
    {
        return new LocalizedUnitFormatter(
            $this->names(),
            $this->locale($locale),
            grouping: (bool) $this->config('grouping', true),
        );
    }

    public function converter(?string $converter = null): UnitConverter
    {
        $converter ??= (string) $this->config('converter', 'si');

        return match ($converter) {
            'si' => new SIUnitConverter,
            'cooking' => new CookingUnitConverter,
            default => $this->instance($converter, UnitConverter::class),
        };
    }

    public function names(): UnitNames
    {
        $names = (string) $this->config('names', 'si');

        return match ($names) {
            'si' => new SIUnitNames,
            'cooking' => new CookingUnitNames,
            default => $this->instance($names, UnitNames::class),
        };
    }

    public function locale(?string $locale = null): string
    {
        $configured = $this->config('locale');

        return $locale
            ?? (is_string($configured) ? $configured : null)
            ?? $this->container->getLocale();
    }

    private function config(string $key, mixed $default = null): mixed
    {
        return $this->container->make('config')->get('units-laravel.'.$key, $default);
    }

    /**
     * @template T of object
     *
     * @param  class-string<T>  $contract
     * @return T
     */
    private function instance(string $class, string $contract): object
    {
        $instance = $this->container->make($class);

        if (! $instance instanceof $contract) {
            throw new InvalidArgumentException('"'.$class.'" must implement '.$contract.'.');
        }

        return $instance;
    }
}
