<?php

declare(strict_types=1);

use Knppy\LaravelUnits\LaravelUnits;

it('resolves the singleton', function () {
    expect(app(LaravelUnits::class))->toBeInstanceOf(LaravelUnits::class);
});

it('returns the same instance from the container', function () {
    expect(app(LaravelUnits::class))->toBe(app(LaravelUnits::class));
});

it('merges the package config', function () {
    expect(config('units-laravel.placeholder'))->toBe('default');
});
