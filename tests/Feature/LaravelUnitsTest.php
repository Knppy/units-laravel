<?php

declare(strict_types=1);

use Knppy\LaravelUnits\Facades\LaravelUnits;
use Knppy\Units\Contract\UnitConverter;
use Knppy\Units\Contract\UnitFormatter;
use Knppy\Units\Contract\UnitNames;
use Knppy\Units\Contract\UnitParser;
use Knppy\Units\Converter\CookingUnitConverter;
use Knppy\Units\Converter\SIUnitConverter;
use Knppy\Units\UnitNames\CookingUnitNames;
use Knppy\Units\UnitNames\SIUnitNames;

it('makes units', function (): void {
    expect((string) LaravelUnits::make(5, 'kg')->getUnitOfMeasurement())->toBe('kg');
});

it('parses symbols and localized names', function (): void {
    expect(LaravelUnits::parse('5 kg')->getAmount())->toBe('5')
        ->and((string) LaravelUnits::parse('5 kilogram', locale: 'nl')->getUnitOfMeasurement())->toBe('kg')
        ->and((string) LaravelUnits::parse('5', 'm')->getUnitOfMeasurement())->toBe('m');
});

it('formats with the application locale', function (): void {
    $unit = LaravelUnits::make(1500, 'm');

    expect(LaravelUnits::format($unit))->toBe('1,500 metres');

    app()->setLocale('nl');

    expect(LaravelUnits::format($unit))->toBe('1.500 meters')
        ->and(LaravelUnits::format($unit, 'de'))->toBe('1.500 Meter');
});

it('prefers the configured locale and grouping', function (): void {
    config()->set('units-laravel.locale', 'nl');
    config()->set('units-laravel.grouping', false);

    expect(LaravelUnits::format(LaravelUnits::make(1500, 'm')))->toBe('1500 meters');
});

it('converts with the configured converter', function (): void {
    expect(LaravelUnits::convert(LaravelUnits::make(5, 'km'), 'm')->getAmount())->toBe('5000');

    config()->set('units-laravel.converter', 'cooking');

    expect(LaravelUnits::convert(LaravelUnits::make(1, 'tbsp'), 'tsp')->getAmount())->toBe('3')
        ->and(LaravelUnits::convert(LaravelUnits::make(1, 'lb'), 'oz', 'cooking')->getAmount())->toBe('16');
});

it('binds the contracts', function (): void {
    expect(app(UnitParser::class))->toBeInstanceOf(UnitParser::class)
        ->and(app(UnitFormatter::class))->toBeInstanceOf(UnitFormatter::class)
        ->and(app(UnitConverter::class))->toBeInstanceOf(SIUnitConverter::class)
        ->and(app(UnitNames::class))->toBeInstanceOf(SIUnitNames::class);
});

it('resolves names and converters from the config', function (): void {
    config()->set('units-laravel.names', 'cooking');
    config()->set('units-laravel.converter', CookingUnitConverter::class);

    expect(app(UnitNames::class))->toBeInstanceOf(CookingUnitNames::class)
        ->and(app(UnitConverter::class))->toBeInstanceOf(CookingUnitConverter::class);
});

it('rejects classes that do not implement the contract', function (): void {
    config()->set('units-laravel.names', stdClass::class);

    LaravelUnits::names();
})->throws(InvalidArgumentException::class);
