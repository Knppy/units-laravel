# Release Notes

## [Unreleased](https://github.com/knppy/units-laravel/compare/v0.1.0...master)

- `unit()` takes a `scale` and `UnitCast::using($fallbackSymbol, $scale)` casts decimal amounts. Requires `knppy/units` ^0.2.
- `UnitRule::decimals()` allows decimals in validated input (none by default).
- `units-laravel.cooking` config (`us` or `metric`) picks the spoons and cups of the `cooking` converter; `LaravelUnits::make()` takes a scale.

## [0.1.0](https://github.com/knppy/units-laravel/compare/v0.1.0...0.1.0) - 2026-11-10

<!-- Release notes generated using configuration in .github/release.yml at master -->
**Full Changelog**: https://github.com/Knppy/units-laravel/commits/0.1.0
