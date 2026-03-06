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
     * Dynamic Stock Quantity Accessor
     * Calculates minimum stock across all constituent items.
     */
    public function getStockQuantityAttribute()
    {
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
