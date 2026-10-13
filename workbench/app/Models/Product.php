<?php

namespace Workbench\App\Models;

use Illuminate\Database\Eloquent\Model;
use Knppy\LaravelUnits\Casts\UnitCast;

class Product extends Model
{
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
        ];
    }
}
