<?php

declare(strict_types=1);

namespace Knppy\LaravelUnits\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;
use InvalidArgumentException;
use Knppy\LaravelUnits\LaravelUnits;
use Knppy\Units\Contract\Exception;
use Knppy\Units\Contract\UnitOfMeasurements;
use Knppy\Units\Quantity;
use Knppy\Units\UnitOfMeasurements\SIUnitOfMeasurements;

class UnitRule implements ValidationRule
{
    /** @var list<string>|null */
    private ?array $symbols = null;

    private ?Quantity $quantity = null;

    private ?UnitOfMeasurements $list = null;

    private ?string $fallbackSymbol = null;

    private ?string $locale = null;

    public static function make(): self
    {
        return new self;
    }

    /**
     * Only allow units with one of these symbols.
     */
    public function in(string ...$symbols): self
    {
        $this->symbols = array_values($symbols);

        return $this;
    }

    /**
     * Only allow units that measure the quantity, according to the list (the SI units by default).
     */
    public function quantity(Quantity $quantity, ?UnitOfMeasurements $list = null): self
    {
        $this->quantity = $quantity;
        $this->list = $list;

        return $this;
    }

    /**
     * Accept amounts without a unit and use this symbol for them.
     */
    public function fallback(string $symbol): self
    {
        $this->fallbackSymbol = $symbol;

        return $this;
    }

    public function locale(string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) && ! is_int($value)) {
            $fail('units-laravel::validation.unit')->translate();

            return;
        }

        try {
            $unit = app(LaravelUnits::class)->parse((string) $value, $this->fallbackSymbol, $this->locale);
        } catch (Exception|InvalidArgumentException) {
            $fail('units-laravel::validation.unit')->translate();

            return;
        }

        $symbol = $unit->getUnitOfMeasurement()->getSymbol();

        if ($this->symbols !== null && ! in_array($symbol, $this->symbols, true)) {
            $fail('units-laravel::validation.unit_in')->translate(['units' => implode(', ', $this->symbols)]);

            return;
        }

        if ($this->quantity === null) {
            return;
        }

        $list = $this->list ?? new SIUnitOfMeasurements;

        try {
            $matches = $list->quantityFor($unit->getUnitOfMeasurement()) === $this->quantity;
        } catch (Exception) {
            $matches = false;
        }

        if (! $matches) {
            $fail('units-laravel::validation.unit_quantity')->translate(['quantity' => $this->quantity->value]);
        }
    }
}
