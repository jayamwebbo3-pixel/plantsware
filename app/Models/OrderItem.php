<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'product_id', 'combo_pack_id', 'product_name', 'product_image', 'price', 'quantity', 'total', 'options'];
    
    protected function casts(): array
    {
        return [
            'options' => 'array',
        ];
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function comboPack()
    {
        return $this->belongsTo(ComboPack::class);
    }

    public function getCustomComboIdAttribute()
    {
        if ($this->options) {
            $optionsObj = is_string($this->options) ? json_decode($this->options, true) : $this->options;
            return $optionsObj['custom_combo_id'] ?? null;
        }
        return null;
    }
}
