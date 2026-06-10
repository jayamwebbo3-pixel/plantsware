<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ComboPackSetting;
use App\Models\ComboPackDiscountSlab;
use App\Models\Cart;
use Illuminate\Http\Request;

class ComboBuilderController extends Controller
{
    public function index(Request $request)
    {
        $settings = ComboPackSetting::first();
        if (!$settings || !$settings->is_enabled) {
            return redirect()->route('home')->with('error', 'Combo Pack feature is currently disabled.');
        }

        $products = Product::active()
            ->where('combo_pack_eligible', 'Yes')
            ->orderBy('sort_order', 'asc')
            ->orderBy('name', 'asc')
            ->get()
            ->filter(function ($product) {
                if ($product->has_variants && $product->size) {
                    $sizesObj = is_string($product->size) ? json_decode($product->size, true) : $product->size;
                    if (is_array($sizesObj)) {
                        foreach ($sizesObj as $data) {
                            if (($data['combo_eligible'] ?? 'No') === 'Yes') {
                                return true;
                            }
                        }
                        return false;
                    }
                }
                return true;
            });

        $slabs = ComboPackDiscountSlab::where('status', true)
            ->orderBy('min_amount', 'asc')
            ->get();

        // Fetch current custom combos in cart for persistence/edit
        $cartItems = Cart::current()
            ->whereNotNull('options')
            ->get()
            ->filter(function($item) {
                return !empty($item->custom_combo_id);
            });

        $customCombos = [];
        foreach ($cartItems->groupBy('custom_combo_id') as $comboId => $items) {
            $productsList = [];
            foreach ($items as $item) {
                if ($item->product) {
                    $imgData = is_string($item->product->image) ? json_decode($item->product->image, true) : $item->product->image;
                    $firstImg = is_array($imgData) && count($imgData) > 0 ? $imgData[0] : $item->product->image;
                    
                    $optionsObj = [];
                    if ($item->options) {
                        $optionsObj = is_string($item->options) ? json_decode($item->options, true) : $item->options;
                    }
                    $selectedSize = $optionsObj['size'] ?? null;

                    $productsList[] = [
                        'id' => (int)$item->product_id,
                        'name' => $item->product->name,
                        'price' => (float)$item->calculated_price,
                        'originalPrice' => (float)$item->original_price,
                        'image' => $firstImg ? asset('storage/' . $firstImg) : asset('assets/images/product/product1.jpg'),
                        'size' => $selectedSize,
                    ];
                }
            }
            $customCombos[$comboId] = $productsList;
        }

        $editComboId = $request->query('edit_combo');
        if (!$editComboId && !empty($customCombos)) {
            $keys = array_keys($customCombos);
            $editComboId = $keys[0];
        }

        return view('view.combo-builder', compact('products', 'slabs', 'settings', 'customCombos', 'editComboId'));
    }
}
