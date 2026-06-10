<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComboPackDiscountSlab extends Model
{
    protected $table = 'combo_pack_discount_slabs';
    
    protected $fillable = ['min_amount', 'max_amount', 'discount_percentage', 'status'];
    
    protected $casts = [
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'status' => 'boolean',
    ];
}
