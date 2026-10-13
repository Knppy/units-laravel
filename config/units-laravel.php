<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Locale
    |--------------------------------------------------------------------------
    |
    | The locale used to parse and format localized unit names. When null, the
    | application locale is used.
    |
    */

    'locale' => null,

    /*
    |--------------------------------------------------------------------------
    | Unit names
    |--------------------------------------------------------------------------
    |
    | The unit names used for localized parsing and formatting. Use "si",
    | "cooking" or the class name of a Knppy\Units\Contract\UnitNames.
    |
    */

    'names' => 'si',

    /*
    |--------------------------------------------------------------------------
    | Converter
    |--------------------------------------------------------------------------
    |
    | The converter used by LaravelUnits::convert(). Use "si", "cooking" or the
    | class name of a Knppy\Units\Contract\UnitConverter.
    |
    */

    'converter' => 'si',

    /*
    |--------------------------------------------------------------------------
    | Digit grouping
    |--------------------------------------------------------------------------
    |
    | Whether formatted amounts are grouped the way the locale does, e.g.
    | "1.500 theelepels". Disable it to format output the parsers can read back.
    |
    */

    'grouping' => true,

    /*
    |--------------------------------------------------------------------------
    | Column suffixes
    |--------------------------------------------------------------------------
    |
    | A unit attribute "weight" is stored in the "weight_amount" and
    | "weight_unit" columns. This applies to the Eloquent cast and to the
    | Blueprint macros.
    |
    */

    'columns' => [
        'amount' => '_amount',
        'unit' => '_unit',
    ],

];
