<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComboPack extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'subcategory_id',
        'description',
        'short_description',
        'sku',
        'stock_quantity',
        'total_price',
        'offer_price',
        'image',
        'is_featured',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'sort_order',
        'is_combo_only',
        'is_active',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'offer_price' => 'decimal:2',
        'is_combo_only' => 'boolean',
        'is_active' => 'boolean',
        'category_id' => 'array',
        'subcategory_id' => 'array',
    ];

    public function comboProduct()
    {
        return $this->hasOne(ComboPackProduct::class);
    }
}
