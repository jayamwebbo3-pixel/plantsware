<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ComboPack;
use App\Models\ComboPackProduct;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ComboPackController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $query = ComboPack::with(['comboProduct'])->latest();

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $comboPacks = $query->get();
        $allCategories = Category::orderBy('name')->get();

        return view('admin.combo-packs.index', compact('allCategories', 'comboPacks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'offer_price' => 'required|numeric|min:0',
            'total_price' => 'required|numeric|min:0',
            'product_ids' => 'required_if:action_type,standard|array',
            'action_type' => 'required|in:standard,combo_only',
            'image' => 'nullable|image|max:2048',
            'sku' => 'nullable|string|max:255|unique:combo_packs,sku',
            'stock_quantity' => 'nullable|integer|min:0',
            'short_description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ]);

        try {
            $comboPack = new ComboPack();
            $comboPack->name = $validated['name'];
            $comboPack->slug = Str::slug($validated['name']);
            $comboPack->category_id = $validated['category_id'] ?? null;
            $comboPack->subcategory_id = $validated['subcategory_id'] ?? null;
            $comboPack->total_price = $validated['total_price'];
            $comboPack->offer_price = $validated['offer_price'];
            $comboPack->is_combo_only = ($validated['action_type'] === 'combo_only');

            $comboPack->sku = $validated['sku'] ?? null;
            $comboPack->stock_quantity = $validated['stock_quantity'] ?? 0;
            $comboPack->short_description = $validated['short_description'] ?? null;
            $comboPack->meta_title = $validated['meta_title'] ?? null;
            $comboPack->meta_description = $validated['meta_description'] ?? null;
            $comboPack->meta_keywords = $validated['meta_keywords'] ?? null;

            if ($comboPack->is_combo_only) {
                if ($request->hasFile('image')) {
                    $comboPack->image = $request->file('image')->store('combo_packs', 'public');
                } else {
                    return redirect()->back()->withErrors(['image' => 'Image is required for Combo Only products.'])->withInput();
                }
                $comboPack->category_id = isset($validated['category_id']) ? [$validated['category_id']] : [];
                $comboPack->subcategory_id = isset($validated['subcategory_id']) ? [$validated['subcategory_id']] : [];
            } else {
                $images = [];
                $categoryIds = [];
                $subcategoryIds = [];
                $productIds = $validated['product_ids'] ?? [];

                foreach ($productIds as $identifier) {
                    if (str_starts_with($identifier, 'p_')) {
                        $p = Product::find(str_replace('p_', '', $identifier));
                        if ($p) {
                            if ($p->image && count($images) < 2)
                                $images[] = $p->image;
                            if ($p->category_id)
                                $categoryIds[] = (string) $p->category_id;
                            if ($p->subcategory_id)
                                $subcategoryIds[] = (string) $p->subcategory_id;
                        }
                    } elseif (str_starts_with($identifier, 'c_')) {
                        $c = ComboPack::find(str_replace('c_', '', $identifier));
                        if ($c) {
                            // Extract images
                            $c_img = json_decode($c->image);
                            if (is_array($c_img)) {
                                if (count($images) < 2)
                                    $images = array_merge($images, $c_img);
                            } else if ($c->image) {
                                if (count($images) < 2)
                                    $images[] = $c->image;
                            }
                            // Extract categories
                            if (is_array($c->category_id))
                                $categoryIds = array_merge($categoryIds, $c->category_id);
                            elseif ($c->category_id)
                                $categoryIds[] = (string) $c->category_id;

                            if (is_array($c->subcategory_id))
                                $subcategoryIds = array_merge($subcategoryIds, $c->subcategory_id);
                            elseif ($c->subcategory_id)
                                $subcategoryIds[] = (string) $c->subcategory_id;
                        }
                    }
                }
                $comboPack->image = json_encode(array_slice($images, 0, 2));
                $comboPack->category_id = array_values(array_unique($categoryIds));
                $comboPack->subcategory_id = array_values(array_unique($subcategoryIds));
            }

            $comboPack->save();

            if (!empty($validated['product_ids'])) {
                ComboPackProduct::create([
                    'combo_pack_id' => $comboPack->id,
                    'product_ids' => $validated['product_ids'],
                ]);
            }

            return redirect()->route('admin.combo-packs.index')->with('success', 'Combo Pack created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error saving combo pack: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(ComboPack $combo)
    {
        $combo->load('comboProduct');

        // Fetch prices for selected items to support JS calculation
        $itemData = [];
        if ($combo->comboProduct && $combo->comboProduct->product_ids) {
            foreach ($combo->comboProduct->product_ids as $id) {
                if (str_starts_with($id, 'p_')) {
                    $p = Product::find(str_replace('p_', '', $id));
                    if ($p)
                        $itemData[$id] = $p->sale_price ?? $p->price;
                } elseif (str_starts_with($id, 'c_')) {
                    $c = ComboPack::find(str_replace('c_', '', $id));
                    if ($c)
                        $itemData[$id] = $c->offer_price;
                }
            }
        }

        return response()->json([
            'combo' => $combo,
            'item_prices' => $itemData
        ]);
    }

    public function update(Request $request, ComboPack $combo)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'offer_price' => 'required|numeric|min:0',
            'total_price' => 'required|numeric|min:0',
            'product_ids' => 'required_if:action_type,standard|array',
            'action_type' => 'required|in:standard,combo_only',
            'image' => 'nullable|image|max:2048',
            'sku' => 'nullable|string|max:255|unique:combo_packs,sku,' . $combo->id,
            'stock_quantity' => 'nullable|integer|min:0',
            'short_description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            \Log::error('Validation failed for update. Errors: ', $validator->errors()->toArray());
            \Log::error('Request data: ', $request->all());
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $validated = $validator->validated();

        try {
            $combo->name = $validated['name'];
            $combo->slug = Str::slug($validated['name']);
            $combo->category_id = $validated['category_id'] ?? null;
            $combo->subcategory_id = $validated['subcategory_id'] ?? null;
            $combo->total_price = $validated['total_price'];
            $combo->offer_price = $validated['offer_price'];
            $combo->is_combo_only = ($validated['action_type'] === 'combo_only');

            $combo->sku = $validated['sku'] ?? null;
            $combo->stock_quantity = $validated['stock_quantity'] ?? 0;
            $combo->short_description = $validated['short_description'] ?? null;
            $combo->meta_title = $validated['meta_title'] ?? null;
            $combo->meta_description = $validated['meta_description'] ?? null;
            $combo->meta_keywords = $validated['meta_keywords'] ?? null;

            if ($combo->is_combo_only) {
                if ($request->hasFile('image')) {
                    $combo->image = $request->file('image')->store('combo_packs', 'public');
                }
                $combo->category_id = isset($validated['category_id']) ? [$validated['category_id']] : [];
                $combo->subcategory_id = isset($validated['subcategory_id']) ? [$validated['subcategory_id']] : [];
            } else {
                $images = [];
                $categoryIds = [];
                $subcategoryIds = [];
                $productIds = $validated['product_ids'] ?? [];

                foreach ($productIds as $identifier) {
                    if (str_starts_with($identifier, 'p_')) {
                        $p = Product::find(str_replace('p_', '', $identifier));
                        if ($p) {
                            if ($p->image && count($images) < 2)
                                $images[] = $p->image;
                            if ($p->category_id)
                                $categoryIds[] = (string) $p->category_id;
                            if ($p->subcategory_id)
                                $subcategoryIds[] = (string) $p->subcategory_id;
                        }
                    } elseif (str_starts_with($identifier, 'c_')) {
                        $c = ComboPack::find(str_replace('c_', '', $identifier));
                        if ($c) {
                            // Extract images
                            $c_img = json_decode($c->image);
                            if (is_array($c_img)) {
                                if (count($images) < 2)
                                    $images = array_merge($images, $c_img);
                            } else if ($c->image) {
                                if (count($images) < 2)
                                    $images[] = $c->image;
                            }
                            // Extract categories
                            if (is_array($c->category_id))
                                $categoryIds = array_merge($categoryIds, $c->category_id);
                            elseif ($c->category_id)
                                $categoryIds[] = (string) $c->category_id;

                            if (is_array($c->subcategory_id))
                                $subcategoryIds = array_merge($subcategoryIds, $c->subcategory_id);
                            elseif ($c->subcategory_id)
                                $subcategoryIds[] = (string) $c->subcategory_id;
                        }
                    }
                }
                if (!empty($images)) {
                    $combo->image = json_encode(array_slice($images, 0, 2));
                }
                $combo->category_id = array_values(array_unique($categoryIds));
                $combo->subcategory_id = array_values(array_unique($subcategoryIds));
            }

            $combo->save();

            // Sync products
            ComboPackProduct::where('combo_pack_id', $combo->id)->delete();
            if (!empty($validated['product_ids'])) {
                ComboPackProduct::create([
                    'combo_pack_id' => $combo->id,
                    'product_ids' => $validated['product_ids'],
                ]);
            }

            return redirect()->route('admin.combo-packs.index')->with('success', 'Combo Pack updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating combo pack: ' . $e->getMessage())->withInput();
        }
    }

    public function updateStatus(Request $request, ComboPack $combo)
    {
        $combo->is_active = $request->status;
        $combo->save();
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        try {
            $combo = ComboPack::findOrFail($id);
            $combo->delete();
            return redirect()->back()->with('success', 'Combo Pack deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting combo pack');
        }
    }

    public function getItems(Request $request)
    {
        $categoryId = $request->category_id;
        $subcategoryId = $request->subcategory_id;
        $search = $request->search;

        $productsQuery = Product::query();
        $combosQuery = ComboPack::where('is_combo_only', true);

        if ($categoryId) {
            $productsQuery->where('category_id', $categoryId);
            $combosQuery->whereJsonContains('category_id', $categoryId);
        }

        if ($subcategoryId) {
            $productsQuery->where('subcategory_id', $subcategoryId);
            $combosQuery->whereJsonContains('subcategory_id', $subcategoryId);
        }

        if ($search) {
            $productsQuery->where('name', 'LIKE', "%{$search}%");
            $combosQuery->where('name', 'LIKE', "%{$search}%");
        }

        $products = $productsQuery->select('id', 'name', 'price', 'sale_price', 'image')->get();
        $combos = $combosQuery->select('id', 'name', 'offer_price', 'image')->get();

        return response()->json([
            'products' => $products,
            'combos' => $combos
        ]);
    }
}
