<?php

declare(strict_types=1);

namespace Knppy\LaravelUnits\Database;

use Illuminate\Database\Schema\Blueprint;

class BlueprintMacros
{
    /**
     * Amounts are arbitrary length integers, so the amount column is a decimal without a scale.
     */
    public const int AMOUNT_PRECISION = 38;

    public const int UNIT_LENGTH = 32;

    public static function register(): void
    {
        Blueprint::macro('unit', function (string $column, bool $nullable = false, int $precision = BlueprintMacros::AMOUNT_PRECISION): void {
            /** @var Blueprint $this */
            $amount = $this->decimal($column.BlueprintMacros::suffix('amount'), $precision, 0);
            $unit = $this->string($column.BlueprintMacros::suffix('unit'), BlueprintMacros::UNIT_LENGTH);

            if ($nullable) {
                $amount->nullable();
                $unit->nullable();
            }
        });

        Blueprint::macro('dropUnit', function (string $column): void {
            /** @var Blueprint $this */
            $this->dropColumn([
                $column.BlueprintMacros::suffix('amount'),
                $column.BlueprintMacros::suffix('unit'),
            ]);
        });
    }

    public static function suffix(string $column): string
    {
        $suffix = config('units-laravel.columns.'.$column);

        return is_string($suffix) ? $suffix : '_'.$column;
    }
}
