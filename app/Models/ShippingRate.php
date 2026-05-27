<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'state_name',
        'base_weight',
        'base_cost',
        'additional_weight_unit',
        'additional_cost_per_unit',
    ];

    protected $casts = [
        'base_weight' => 'decimal:2',
        'base_cost' => 'decimal:2',
        'additional_weight_unit' => 'decimal:2',
        'additional_cost_per_unit' => 'decimal:2',
    ];
}
