# Release Notes

## [Unreleased](https://github.com/knppy/units-laravel/compare/0.2.0...HEAD)

- `unit()` takes a `scale` and `UnitCast::using($fallbackSymbol, $scale)` casts decimal amounts. Requires `knppy/units` ^0.2.
- `UnitRule::decimals()` allows decimals in validated input (none by default).
- `units-laravel.cooking` config (`us` or `metric`) picks the spoons and cups of the `cooking` converter; `LaravelUnits::make()` takes a scale.

## [0.1.0](https://github.com/knppy/units-laravel/compare/v0.1.0...0.1.0) - 2026-11-10

<!-- Release notes generated using configuration in .github/release.yml at master -->
**Full Changelog**: https://github.com/Knppy/units-laravel/commits/0.1.0

## [0.2.0](https://github.com/knppy/units-laravel/compare/v0.1.0...0.2.0) - 2026-11-11

<!-- Release notes generated using configuration in .github/release.yml at master -->
### What's Changed

#### Other Changes

* Scale for unit columns and casts, decimals in the rule, cooking kitchen setting by @michaelbeers in https://github.com/Knppy/units-laravel/pull/3

### New Contributors

* @michaelbeers made their first contribution in https://github.com/Knppy/units-laravel/pull/3

**Full Changelog**: https://github.com/Knppy/units-laravel/compare/0.1.0...0.2.0
