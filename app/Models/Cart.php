<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function comboPack()
    {
        return $this->belongsTo(ComboPack::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope for current user or session
    public function scopeCurrent($query)
    {
        if (auth()->check()) {
            return $query->where('user_id', auth()->id());
        }

        return $query->where('session_id', session()->getId());
    }

    public function getCalculatedPriceAttribute()
    {
        if ($this->combo_pack_id) {
            return $this->comboPack->offer_price ?? 0;
        }

        $p = $this->product;
        if (!$p) return 0;

        $price = ($p->sale_price && $p->sale_price > 0 && $p->sale_price < $p->price) ? $p->sale_price : $p->price;

        if ($this->options) {
            $optionsObj = json_decode($this->options, true);
            if (isset($optionsObj['size']) && $p->size) {
                $sizesObj = json_decode($p->size, true);
                if (is_array($sizesObj) && isset($sizesObj[$optionsObj['size']]) && $sizesObj[$optionsObj['size']] > 0) {
                    $price = $sizesObj[$optionsObj['size']];
                }
            }
        }

        return $price;
    }
}