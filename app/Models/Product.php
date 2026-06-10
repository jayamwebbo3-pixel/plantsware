<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'subcategory_id',
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'sale_price',
        'sku',
        'stock_quantity',
        'image',
        'gallery_images',
        'is_featured',
        'is_active',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'sort_order',
        'size',
        'shape',
        'material',
        'color',
        'gsm',
        'has_handles',
        'uv_treated',
        'shade_percentage',
        'width_meters',
        'length_meters',
        'pack_quantity',
        'warranty_months',
        'avg_rating',
        'total_reviews',
        'weight',
        'product_code',
        'batch_code',
        'combo_pack_eligible',
        'has_variants',
    ];
    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'gallery_images' => 'array',
        'size' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'weight' => 'decimal:2',
        'has_variants' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Latest products (by created_at)
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Scope: Ordered by sort_order then name
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')
            ->orderBy('name', 'asc');
    }

    /**
     * Relationship: Category this product belongs to
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relationship: Subcategory this product belongs to
     */
    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function getDiscountPercentageAttribute()
    {
        if ($this->sale_price && $this->price) {
            return round((($this->price - $this->sale_price) / $this->price) * 100);
        }
        return 0;
    }
    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }
    public function wishlistedBy()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function isWishlisted()
    {
        return auth()->check() && auth()->user()->inWishlist($this);
    }
    public function reviews()
    {
        return $this->hasMany(ProductReview::class)->where('is_approved', true);
    }

    public function getHasVariantsAttribute($value)
    {
        return (bool)$value;
    }

    public function getStockQuantityAttribute($value)
    {
        if ($this->has_variants && $this->size) {
            $sizesObj = is_string($this->size) ? json_decode($this->size, true) : $this->size;
            if (is_array($sizesObj)) {
                $totalStock = 0;
                foreach ($sizesObj as $val) {
                    $totalStock += is_array($val) ? (int)($val['stock'] ?? 0) : 0;
                }
                return $totalStock;
            }
        }
        return $value;
    }

    public function getPriceAttribute($value)
    {
        if ($this->has_variants && $this->size) {
            $sizesObj = is_string($this->size) ? json_decode($this->size, true) : $this->size;
            if (is_array($sizesObj) && count($sizesObj) > 0) {
                $first = reset($sizesObj);
                $price = is_array($first) ? ($first['price'] ?? null) : $first;
                if ($price !== null && $price > 0) {
                    return $price;
                }
            }
        }
        return $value;
    }

    public function getSalePriceAttribute($value)
    {
        if ($this->has_variants && $this->size) {
            return null; // When variants are enabled, use the variant price directly
        }
        return $value;
    }

    public function getWeightAttribute($value)
    {
        if ($this->has_variants && $this->size) {
            $sizesObj = is_string($this->size) ? json_decode($this->size, true) : $this->size;
            if (is_array($sizesObj) && count($sizesObj) > 0) {
                $first = reset($sizesObj);
                $weight = is_array($first) ? ($first['weight'] ?? null) : null;
                if ($weight !== null && $weight > 0) {
                    return $weight;
                }
            }
        }
        return $value;
    }
}

