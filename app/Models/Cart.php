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

        $selectedSize = null;
        if ($p->has_variants) {
            if ($this->options) {
                $optionsObj = is_string($this->options) ? json_decode($this->options, true) : $this->options;
                if (isset($optionsObj['size'])) {
                    $selectedSize = $optionsObj['size'];
                }
            }

            // Fallback: If no size option is specified but the product has size options defined, default to the first in-stock size option
            if (!$selectedSize && $p->size) {
                $sizesObj = is_string($p->size) ? json_decode($p->size, true) : $p->size;
                if (is_array($sizesObj) && count($sizesObj) > 0) {
                    $selectedSize = array_key_first($sizesObj);
                    foreach ($sizesObj as $key => $val) {
                        $stock = is_array($val) ? (int)($val['stock'] ?? 0) : 0;
                        if ($stock > 0) {
                            $selectedSize = $key;
                            break;
                        }
                    }
                }
            }
        }

        if ($selectedSize && $p->size) {
            $sizesObj = is_string($p->size) ? json_decode($p->size, true) : $p->size;
            if (is_array($sizesObj)) {
                // Case-insensitive lookup
                $foundSize = null;
                if (isset($sizesObj[$selectedSize])) {
                    $foundSize = $sizesObj[$selectedSize];
                } else {
                    foreach ($sizesObj as $key => $val) {
                        if (strcasecmp($key, $selectedSize) === 0) {
                            $foundSize = $val;
                            break;
                        }
                    }
                }

                if ($foundSize) {
                    $sizePrice = is_array($foundSize) ? ($foundSize['price'] ?? null) : $foundSize;
                    $sizeSalePrice = is_array($foundSize) ? ($foundSize['sale_price'] ?? null) : null;
                    if ($sizePrice !== null && $sizePrice > 0) {
                        $price = $sizePrice;
                        if ($sizeSalePrice !== null && $sizeSalePrice > 0 && $sizeSalePrice < $sizePrice) {
                            $price = $sizeSalePrice;
                        }
                    }
                }
            }
        }

        return $price;
    }

    public function getCalculatedWeightAttribute()
    {
        if ($this->combo_pack_id) {
            return $this->comboPack->weight ?? 0;
        }

        $p = $this->product;
        if (!$p) return 0;

        $weight = $p->weight ?? 0;

        $selectedSize = null;
        if ($p->has_variants) {
            if ($this->options) {
                $optionsObj = is_string($this->options) ? json_decode($this->options, true) : $this->options;
                if (isset($optionsObj['size'])) {
                    $selectedSize = $optionsObj['size'];
                }
            }

            // Fallback: If no size option is specified but the product has size options defined, default to the first in-stock size option
            if (!$selectedSize && $p->size) {
                $sizesObj = is_string($p->size) ? json_decode($p->size, true) : $p->size;
                if (is_array($sizesObj) && count($sizesObj) > 0) {
                    $selectedSize = array_key_first($sizesObj);
                    foreach ($sizesObj as $key => $val) {
                        $stock = is_array($val) ? (int)($val['stock'] ?? 0) : 0;
                        if ($stock > 0) {
                            $selectedSize = $key;
                            break;
                        }
                    }
                }
            }
        }

        if ($selectedSize && $p->size) {
            $sizesObj = is_string($p->size) ? json_decode($p->size, true) : $p->size;
            if (is_array($sizesObj)) {
                // Case-insensitive lookup
                $foundSize = null;
                if (isset($sizesObj[$selectedSize])) {
                    $foundSize = $sizesObj[$selectedSize];
                } else {
                    foreach ($sizesObj as $key => $val) {
                        if (strcasecmp($key, $selectedSize) === 0) {
                            $foundSize = $val;
                            break;
                        }
                    }
                }

                if ($foundSize) {
                    $sizeWeight = is_array($foundSize) ? ($foundSize['weight'] ?? null) : null;
                    if ($sizeWeight !== null && $sizeWeight > 0) {
                        $weight = $sizeWeight;
                    }
                }
            }
        }

        return $weight;
    }

    public function getOriginalPriceAttribute()
    {
        if ($this->combo_pack_id) {
            return $this->comboPack->total_price ?? 0;
        }

        $p = $this->product;
        if (!$p) return 0;

        $price = $p->price;

        $selectedSize = null;
        if ($p->has_variants) {
            if ($this->options) {
                $optionsObj = is_string($this->options) ? json_decode($this->options, true) : $this->options;
                if (isset($optionsObj['size'])) {
                    $selectedSize = $optionsObj['size'];
                }
            }

            // Fallback: If no size option is specified but the product has size options defined, default to the first in-stock size option
            if (!$selectedSize && $p->size) {
                $sizesObj = is_string($p->size) ? json_decode($p->size, true) : $p->size;
                if (is_array($sizesObj) && count($sizesObj) > 0) {
                    $selectedSize = array_key_first($sizesObj);
                    foreach ($sizesObj as $key => $val) {
                        $stock = is_array($val) ? (int)($val['stock'] ?? 0) : 0;
                        if ($stock > 0) {
                            $selectedSize = $key;
                            break;
                        }
                    }
                }
            }
        }

        if ($selectedSize && $p->size) {
            $sizesObj = is_string($p->size) ? json_decode($p->size, true) : $p->size;
            if (is_array($sizesObj)) {
                // Case-insensitive lookup
                $foundSize = null;
                if (isset($sizesObj[$selectedSize])) {
                    $foundSize = $sizesObj[$selectedSize];
                } else {
                    foreach ($sizesObj as $key => $val) {
                        if (strcasecmp($key, $selectedSize) === 0) {
                            $foundSize = $val;
                            break;
                        }
                    }
                }

                if ($foundSize) {
                    $sizePrice = is_array($foundSize) ? ($foundSize['price'] ?? null) : $foundSize;
                    if ($sizePrice !== null && $sizePrice > 0) {
                        $price = $sizePrice;
                    }
                }
            }
        }

        return $price;
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