<div align="center">
    <h1>Units For Laravel</h1>
</div>

<p align="center">
    <a href="https://packagist.org/packages/knppy/units-laravel"><img src="https://img.shields.io/packagist/v/knppy/units-laravel.svg?style=flat-square" alt="Packagist"></a>
    <a href="https://packagist.org/packages/knppy/units-laravel"><img src="https://img.shields.io/packagist/php-v/knppy/units-laravel.svg?style=flat-square" alt="PHP from Packagist"></a>
    <a href="https://packagist.org/packages/knppy/units-laravel"><img src="https://badge.laravel.cloud/badge/knppy/units-laravel?style=flat" alt="Laravel versions"></a>
    <a href="https://github.com/knppy/units-laravel/actions"><img alt="GitHub Workflow Status (master)" src="https://img.shields.io/github/actions/workflow/status/knppy/units-laravel/tests.yml?branch=master&label=Tests&style=flat-square"></a>
    <a href="https://packagist.org/packages/knppy/units-laravel"><img src="https://img.shields.io/packagist/dt/knppy/units-laravel.svg?style=flat-square" alt="Total Downloads"></a>
</p>

Laravel integration for [knppy/units](https://github.com/knppy/units): Eloquent casts, validation, localized parsing and formatting, and migration helpers.

## Installation

You can install the package via Composer:

```bash
composer require knppy/units-laravel
```

You may publish all of the package's resources at once:

```bash
php artisan vendor:publish --tag="units-laravel"
```

Or, you may publish each resource individually:

### Publishing the Configuration File

```bash
php artisan vendor:publish --tag="units-laravel-config"
```

### Publishing the Translations

```bash
php artisan vendor:publish --tag="units-laravel-lang"
```

## Usage

### Migrations

`unit()` adds `{name}_amount` (a `decimal(38, 0)`) and `{name}_unit` (a string) columns. Pass `nullable: true` for optional units and `scale: 2` for amounts with decimals (`decimal(38, 2)`); `dropUnit()` drops both.

```php
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->unit('weight');
    $table->unit('volume', nullable: true);
});
```

### Eloquent

Cast the attribute with `UnitCast`. It reads and writes both columns and returns a `Knppy\Units\Unit`:

```php
use Knppy\LaravelUnits\Casts\UnitCast;

class Product extends Model
{
    protected function casts(): array
    {
        return [
            'weight' => UnitCast::class,
            'volume' => UnitCast::class.':ml', // "250" is stored as 250 ml
            'quantity' => UnitCast::using('g', 2), // decimals up to the scale of the column
        ];
    }
}

$product->weight = new Unit(100, new UnitOfMeasurement('kg'));
$product->weight = '100 kg';                    // strings are parsed
$product->weight->getAmount();                  // "100"
$product->weight->convertTo(new UnitOfMeasurement('g')); // 100000 g
```

Because the value lives in two columns, add the attribute to `$appends` and hide the columns to include it in `toArray()` and `toJson()` as `{"amount": "100", "unit": "kg"}`:

```php
protected $appends = ['weight'];
protected $hidden = ['weight_amount', 'weight_unit'];
```

### Validation

```php
use Knppy\LaravelUnits\Rules\UnitRule;
use Knppy\Units\Quantity;

$request->validate([
    'weight' => ['required', UnitRule::make()->quantity(Quantity::Mass)],
    'volume' => [UnitRule::make()->in('ml', 'l')->fallback('ml')],
]);
```

Input is read with the localized names of the application locale first, then as symbols. Use `->locale('nl')` to override it.

### Parsing, formatting and converting

```php
use Knppy\LaravelUnits\Facades\LaravelUnits;

LaravelUnits::parse('2 kilogram');                  // Unit(2, kg)
LaravelUnits::format(LaravelUnits::make(1500, 'm')); // "1,500 metres", or "1.500 meters" with the nl locale
LaravelUnits::convert(LaravelUnits::make(5, 'km'), 'm'); // 5000 m
```

`UnitParser`, `UnitFormatter`, `UnitConverter` and `UnitNames` are bound in the container and follow the configuration (`locale`, `names`, `converter`, `grouping`, `columns`).

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Thank you for considering contributing to Units For Laravel! Please review our [contributing guide](.github/CONTRIBUTING.md) to get started.

## Security Vulnerabilities

Please review [our security policy](.github/SECURITY.md) on how to report security vulnerabilities.

## Credits

- [Michael Beers](https://github.com/knppy)
- [All Contributors](../../contributors)

## License

Units For Laravel is open-sourced software licensed under the [MIT license](LICENSE.md).
