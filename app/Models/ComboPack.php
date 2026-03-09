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
        'total_price',
        'offer_price',
        'image',
        'is_active',
        'avg_rating',
        'total_reviews',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'offer_price' => 'decimal:2',
        'is_active' => 'boolean',
        'category_id' => 'array',
        'subcategory_id' => 'array',
    ];

    public function comboProduct()
    {
        return $this->hasOne(ComboPackProduct::class);
    }

    /**
     * Get dynamic images from constituent products/combo-only items
     */
    public function getImagesAttribute()
    {
        $comboProduct = $this->comboProduct;
        if (!$comboProduct || !$comboProduct->product_ids) {
            return [];
        }

        $images = [];
        foreach ($comboProduct->product_ids as $id) {
            if (count($images) >= 4)
                break; // Limit to 4 for performance/UI

            if (str_starts_with($id, 'p_')) {
                $p = Product::find(str_replace('p_', '', $id));
                if ($p && $p->image)
                    $images[] = $p->image;
            } elseif (str_starts_with($id, 'co_')) {
                $co = ComboOnlyProduct::find(str_replace('co_', '', $id));
                if ($co && $co->image)
                    $images[] = $co->image;
            } elseif (str_starts_with($id, 'c_')) {
                $c = self::find(str_replace('c_', '', $id));
                if ($c) {
                    $nestedImgs = $c->images;
                    if (!empty($nestedImgs))
                        $images[] = $nestedImgs[0];
                }
            }
        }
        return array_values(array_unique($images));
    }

    /**
     * Compatibility accessor for single image field
     */
    public function getImageAttribute()
    {
        return json_encode(array_slice($this->images, 0, 2));
    }

    /**
     * Dynamic Stock Quantity Accessor
     * Calculates minimum stock across all constituent items.
     */
    public function getStockQuantityAttribute()
    {
        if ($this->is_combo_only) {
            return (int) $this->attributes['stock_quantity'];
        }

        $comboProduct = $this->comboProduct;
        if (!$comboProduct || !$comboProduct->product_ids) {
            return 0;
        }

        return $this->calculateConstituentStock($comboProduct->product_ids);
    }

    /**
     * Calculate minimum stock among all constituents
     * Supports: p_ (product), c_ (combo pack), co_ (combo-only product)
     */
    private function calculateConstituentStock(array $productIds): int
    {
        $minStock = null;

        foreach ($productIds as $id) {
            $currentStock = 0;

            if (str_starts_with($id, 'p_')) {
                $realId = str_replace('p_', '', $id);
                $product = Product::find($realId);
                $currentStock = $product ? (int) $product->stock_quantity : 0;

            } elseif (str_starts_with($id, 'co_')) {
                $realId = str_replace('co_', '', $id);
                $comboOnly = ComboOnlyProduct::find($realId);
                $currentStock = $comboOnly ? (int) $comboOnly->stock_quantity : 0;

            } elseif (str_starts_with($id, 'c_')) {
                $realId = str_replace('c_', '', $id);
                $nestedCombo = self::find($realId);
                $currentStock = $nestedCombo ? (int) $nestedCombo->stock_quantity : 0;
            }

            if ($minStock === null || $currentStock < $minStock) {
                $minStock = $currentStock;
            }
        }

        return $minStock ?? 0;
    }
}
