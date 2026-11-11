<?php

declare(strict_types=1);

namespace Knppy\LaravelUnits\Tests\Support;

use Illuminate\Database\Eloquent\Model;
use Knppy\LaravelUnits\Casts\UnitCast;

class Product extends Model
{
    public $timestamps = false;

    protected $table = 'test_products';

    protected $guarded = [];

    protected $appends = ['weight'];

    protected $hidden = ['weight_amount', 'weight_unit'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'weight' => UnitCast::class,
            'volume' => UnitCast::class.':ml',
            'quantity' => UnitCast::using('g', 2),
        ];
    }
}
