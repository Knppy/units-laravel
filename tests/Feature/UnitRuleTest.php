<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Validator;
use Knppy\LaravelUnits\Rules\UnitRule;
use Knppy\Units\Quantity;

function unitErrors(mixed $value, UnitRule $rule): array
{
    return Validator::make(['weight' => $value], ['weight' => $rule])->errors()->get('weight');
}

it('accepts amounts with a unit', function (): void {
    expect(unitErrors('5 kg', UnitRule::make()))->toBe([]);
});

it('rejects other input', function (mixed $value): void {
    expect(unitErrors($value, UnitRule::make()))->toBe(['The weight field must be a whole amount with a unit, such as 5 kg.']);
})->with(['text' => ['text'], 'decimal' => ['5.5 kg'], 'no unit' => ['5'], 'array' => [['5 kg']], 'null' => [null]]);

it('limits the decimals', function (): void {
    $rule = UnitRule::make()->decimals(2);

    expect(unitErrors('5.25 kg', $rule))->toBe([])
        ->and(unitErrors('5 kg', $rule))->toBe([])
        ->and(unitErrors('5.255 kg', $rule))->toBe(['The weight field can have at most 2 decimals.']);
});

it('accepts amounts without a unit with a fallback', function (): void {
    expect(unitErrors('5', UnitRule::make()->fallback('kg')))->toBe([])
        ->and(unitErrors(5, UnitRule::make()->fallback('kg')))->toBe([]);
});

it('limits the units', function (): void {
    $rule = UnitRule::make()->in('kg', 'g');

    expect(unitErrors('5 g', $rule))->toBe([])
        ->and(unitErrors('5 m', $rule))->toBe(['The weight field must use one of these units: kg, g.']);
});

it('limits the quantity', function (): void {
    $rule = UnitRule::make()->quantity(Quantity::Mass);

    expect(unitErrors('5 kg', $rule))->toBe([])
        ->and(unitErrors('5 m', $rule))->toBe(['The weight field must be a unit of mass.'])
        ->and(unitErrors('5 bogus', $rule))->toBe(['The weight field must be a unit of mass.']);
});

it('reads localized names', function (): void {
    expect(unitErrors('5 kilogram', UnitRule::make()->locale('nl')))->toBe([]);
});

it('translates messages', function (): void {
    app()->setLocale('nl');

    expect(unitErrors('text', UnitRule::make()))->toBe(['Het veld weight moet een geheel aantal met een eenheid zijn, bijvoorbeeld 5 kg.']);
});
