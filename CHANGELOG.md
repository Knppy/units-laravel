# Release Notes

## [Unreleased](https://github.com/knppy/units-laravel/compare/v0.1.0...master)

- `unit()` takes a `scale` and `UnitCast::using($fallbackSymbol, $scale)` casts decimal amounts. Requires `knppy/units` ^0.2.
- `UnitRule::decimals()` allows decimals in validated input (none by default).
- `units-laravel.cooking` config (`us` or `metric`) picks the spoons and cups of the `cooking` converter; `LaravelUnits::make()` takes a scale.
