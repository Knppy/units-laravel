<?php

declare(strict_types=1);

use Knppy\LaravelUnits\Tests\Support\Product;
use Knppy\Units\Exception\ParserException;
use Knppy\Units\Unit;
use Knppy\Units\UnitOfMeasurement;

it('stores a unit in an amount and a unit column', function (): void {
    $product = Product::create(['weight' => new Unit(100, new UnitOfMeasurement('kg'))]);

    $this->assertDatabaseHas('products', ['id' => $product->id, 'weight_amount' => 100, 'weight_unit' => 'kg']);

    $weight = $product->fresh()->weight;

    expect($weight)->toBeInstanceOf(Unit::class)
        ->and($weight->equals(new Unit(100, new UnitOfMeasurement('kg'))))->toBeTrue();
});

it('returns null without a unit', function (): void {
    $product = Product::create([]);

    expect($product->fresh()->weight)->toBeNull();

    $product->weight = new Unit(5, new UnitOfMeasurement('g'));
    $product->weight = null;
    $product->save();

    expect($product->fresh()->weight)->toBeNull();
});

it('parses strings', function (): void {
    $product = Product::create(['weight' => '5 kg', 'volume' => '250']);

    expect($product->weight->getAmount())->toBe('5')
        ->and($product->weight->getUnitOfMeasurement()->getSymbol())->toBe('kg')
        ->and((string) $product->volume->getUnitOfMeasurement())->toBe('ml');
});

it('rejects unsupported values', function (): void {
    Product::create(['weight' => 1.5]);
})->throws(InvalidArgumentException::class);

it('rejects strings without a unit when there is no fallback', function (): void {
    Product::create(['weight' => '5']);
})->throws(ParserException::class);

it('serializes to an amount and a unit', function (): void {
    $product = Product::create(['weight' => '5 kg']);

    expect(json_decode($product->toJson(), true)['weight'])->toBe(['amount' => '5', 'unit' => 'kg'])
        ->and($product->toArray())->not->toHaveKeys(['weight_amount', 'weight_unit']);
});

it('tracks changes', function (): void {
    $product = Product::create(['weight' => '5 kg']);
    $product->weight = '6 kg';

    expect($product->isDirty('weight_amount'))->toBeTrue()
        ->and($product->isDirty('weight_unit'))->toBeFalse();
});

it('normalizes decimal amounts returned by the database', function (): void {
    $product = (new Product)->setRawAttributes(['weight_amount' => '100.00', 'weight_unit' => 'kg']);

    expect($product->weight->getAmount())->toBe('100');

    $product->setRawAttributes(['weight_amount' => 1.0E+15, 'weight_unit' => 'kg']);

    expect($product->weight->getAmount())->toBe('1000000000000000');
});

it('uses the configured column suffixes', function (): void {
    config()->set('units-laravel.columns', ['amount' => '_value', 'unit' => '_symbol']);

    $product = new Product(['weight' => '5 kg']);

    expect($product->getAttributes())->toBe(['weight_value' => '5', 'weight_symbol' => 'kg']);
});
