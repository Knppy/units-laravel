<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;

it('creates the amount and unit columns', function (): void {
    Schema::create('items', function ($table): void {
        $table->unit('required');
        $table->unit('optional', nullable: true);
    });

    expect(Schema::getColumnListing('items'))->toBe(['required_amount', 'required_unit', 'optional_amount', 'optional_unit']);

    $columns = collect(Schema::getColumns('items'))->keyBy('name');

    expect($columns['required_unit']['nullable'])->toBeFalse()
        ->and($columns['optional_amount']['nullable'])->toBeTrue()
        ->and($columns['optional_unit']['nullable'])->toBeTrue();
});

it('drops the columns', function (): void {
    Schema::table('test_products', fn ($table) => $table->dropUnit('volume'));

    expect(Schema::getColumnListing('test_products'))->toBe(['id', 'weight_amount', 'weight_unit', 'quantity_amount', 'quantity_unit']);
});

it('creates a decimal amount column with a scale', function (): void {
    Schema::create('ingredients', function ($table): void {
        $table->unit('measure', scale: 2);
    });

    expect(collect(Schema::getColumns('ingredients'))->keyBy('name')['measure_amount']['type_name'])->toBeIn(['decimal', 'numeric']);
});
