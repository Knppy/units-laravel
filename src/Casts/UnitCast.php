<?php

declare(strict_types=1);

namespace Knppy\LaravelUnits\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Knppy\LaravelUnits\LaravelUnits;
use Knppy\Units\Unit;
use Knppy\Units\UnitOfMeasurement;

/**
 * Casts the "{key}_amount" and "{key}_unit" columns to a Unit.
 *
 * @implements CastsAttributes<Unit, mixed>
 */
class UnitCast implements CastsAttributes
{
    /**
     * @param  string|null  $fallbackSymbol  Unit used for assigned strings without a unit, such as "5".
     */
    public function __construct(private readonly ?string $fallbackSymbol = null) {}

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?Unit
    {
        $amount = $attributes[$key.self::suffix('amount')] ?? null;
        $symbol = $attributes[$key.self::suffix('unit')] ?? null;

        if ($amount === null || $symbol === null || $symbol === '') {
            return null;
        }

        return new Unit(self::normalize($amount), new UnitOfMeasurement((string) $symbol));
    }

    /**
     * @param  array<string, mixed>  $attributes
     * @return array<string, int|string|null>
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): array
    {
        if (is_string($value)) {
            $value = app(LaravelUnits::class)->parse($value, $this->fallbackSymbol);
        }

        if ($value !== null && ! $value instanceof Unit) {
            throw new InvalidArgumentException('The value of "'.$key.'" must be a '.Unit::class.', a string or null.');
        }

        return [
            $key.self::suffix('amount') => $value?->getAmount(),
            $key.self::suffix('unit') => $value?->getUnitOfMeasurement()->getSymbol(),
        ];
    }

    private static function suffix(string $column): string
    {
        $suffix = config('units-laravel.columns.'.$column);

        return is_string($suffix) ? $suffix : '_'.$column;
    }

    /**
     * Databases return integer amounts as integers, floats or decimal strings such as "100.00".
     */
    private static function normalize(mixed $amount): string
    {
        if (is_float($amount)) {
            return number_format($amount, 0, '', '');
        }

        $amount = (string) $amount;

        return (string) preg_replace('/^(-?\d+)\.0+$/', '$1', $amount);
    }
}
