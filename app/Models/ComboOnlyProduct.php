<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ComboOnlyProduct extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'image',
        'stock_quantity',
        'is_active',
        'weight',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'stock_quantity' => 'integer',
        'weight' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }
}
