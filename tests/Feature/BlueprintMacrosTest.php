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
    Schema::table('products', fn ($table) => $table->dropUnit('volume'));

    expect(Schema::getColumnListing('products'))->toBe(['id', 'weight_amount', 'weight_unit']);
});
