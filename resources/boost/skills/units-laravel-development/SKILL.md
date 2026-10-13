---
name: units-laravel-development
description: >
  Configure and apply the Units For  Laravel package in Laravel applications.
license: MIT
metadata:
  author: Michael Beers
---

# Units For  Laravel

Use this skill when a Laravel application needs to integrate the Units For  Laravel package.

## Primary Goal

- apply the `knppy/units-laravel` package's public API in the smallest correct way

## Workflow

### 1. Inspect the Laravel app context

- confirm the app is a Laravel project
- inspect the target code paths where the package should be applied

### 2. Apply the package's public API

- Migrations: `$table->unit('weight')` creates `weight_amount` and `weight_unit`; use `nullable: true` for optional units.
- Models: cast with `Knppy\LaravelUnits\Casts\UnitCast::class` (`:ml` sets the unit for strings without one). Add the attribute to `$appends` and hide its two columns to serialize it.
- Validation: `UnitRule::make()->in('kg', 'g')`, `->quantity(Quantity::Mass)`, `->fallback('kg')`, `->locale('nl')`.
- Parsing and formatting: `LaravelUnits::parse()`, `format()`, `make()`, `convert()`; contracts `UnitParser`, `UnitFormatter`, `UnitConverter`, `UnitNames` resolve from the container.
- Config in `config/units-laravel.php`: `locale`, `names`, `converter`, `grouping`, `columns`.

## Rules, References, and Templates

Read before executing:

- no additional resource files for this skill

## Examples

- a `products` table with `$table->unit('weight')`, a `Product` model casting `weight` with `UnitCast`, and a form request validating it with `UnitRule::make()->quantity(Quantity::Mass)`

## Anti-patterns

- do not document package internals here; keep the skill focused on adoption in Laravel apps
