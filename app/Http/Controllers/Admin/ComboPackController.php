<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ComboPack;
use App\Models\ComboPackProduct;
use App\Models\ComboOnlyProduct;
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
            'product_ids' => 'required|array|min:1',
        ]);

        try {
            $comboPack = new ComboPack();
            $comboPack->name = $validated['name'];
            $comboPack->slug = $this->generateSlug($validated['name']);
            $comboPack->total_price = $validated['total_price'];
            $comboPack->offer_price = $validated['offer_price'];

            $images = [];
            $categoryIds = [];
            $subcategoryIds = [];

            foreach ($validated['product_ids'] as $identifier) {
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
                        $c_img = json_decode($c->image);
                        $imgArr = is_array($c_img) ? $c_img : ($c->image ? [$c->image] : []);
                        foreach ($imgArr as $img) {
                            if (count($images) < 2)
                                $images[] = $img;
                        }
                        if (is_array($c->category_id))
                            $categoryIds = array_merge($categoryIds, $c->category_id);
                        if (is_array($c->subcategory_id))
                            $subcategoryIds = array_merge($subcategoryIds, $c->subcategory_id);
                    }
                } elseif (str_starts_with($identifier, 'co_')) {
                    $co = ComboOnlyProduct::find(str_replace('co_', '', $identifier));
                    if ($co && $co->image && count($images) < 2) {
                        $images[] = $co->image;
                    }
                }
            }

            $comboPack->image = json_encode(array_slice($images, 0, 2));
            $comboPack->category_id = array_values(array_unique($categoryIds));
            $comboPack->subcategory_id = array_values(array_unique($subcategoryIds));
            $comboPack->save();

            ComboPackProduct::create([
                'combo_pack_id' => $comboPack->id,
                'product_ids' => $validated['product_ids'],
            ]);

            return redirect()->route('admin.combo-packs.index')->with('success', 'Standard Combo Pack created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error saving combo pack: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(ComboPack $combo)
    {
        $combo->load('comboProduct');

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
                } elseif (str_starts_with($id, 'co_')) {
                    $co = ComboOnlyProduct::find(str_replace('co_', '', $id));
                    if ($co)
                        $itemData[$id] = $co->price;
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'offer_price' => 'required|numeric|min:0',
            'total_price' => 'required|numeric|min:0',
            'product_ids' => 'required|array|min:1',
        ]);

        try {
            $combo->name = $validated['name'];
            $combo->total_price = $validated['total_price'];
            $combo->offer_price = $validated['offer_price'];

            $images = [];
            $categoryIds = [];
            $subcategoryIds = [];

            foreach ($validated['product_ids'] as $identifier) {
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
                        $c_img = json_decode($c->image);
                        $imgArr = is_array($c_img) ? $c_img : ($c->image ? [$c->image] : []);
                        foreach ($imgArr as $img) {
                            if (count($images) < 2)
                                $images[] = $img;
                        }
                        if (is_array($c->category_id))
                            $categoryIds = array_merge($categoryIds, $c->category_id);
                        if (is_array($c->subcategory_id))
                            $subcategoryIds = array_merge($subcategoryIds, $c->subcategory_id);
                    }
                } elseif (str_starts_with($identifier, 'co_')) {
                    $co = ComboOnlyProduct::find(str_replace('co_', '', $identifier));
                    if ($co && $co->image && count($images) < 2) {
                        $images[] = $co->image;
                    }
                }
            }

            if (!empty($images)) {
                $combo->image = json_encode(array_slice($images, 0, 2));
            }
            $combo->category_id = array_values(array_unique($categoryIds));
            $combo->subcategory_id = array_values(array_unique($subcategoryIds));
            $combo->save();

            ComboPackProduct::where('combo_pack_id', $combo->id)->delete();
            ComboPackProduct::create([
                'combo_pack_id' => $combo->id,
                'product_ids' => $validated['product_ids'],
            ]);

            return redirect()->route('admin.combo-packs.index')->with('success', 'Combo Pack updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating: ' . $e->getMessage())->withInput();
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
            ComboPack::findOrFail($id)->delete();
            return redirect()->back()->with('success', 'Combo Pack deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting combo pack');
        }
    }

    /**
     * Returns products AND combo-only products for the item selector.
     * Products use p_ prefix, standard combos use c_, combo-only use co_.
     */
    public function getItems(Request $request)
    {
        $categoryId = $request->category_id;
        $subcategoryId = $request->subcategory_id;
        $search = $request->search;

        // Regular products
        $productsQuery = Product::query()->where('is_active', true);
        if ($categoryId)
            $productsQuery->where('category_id', $categoryId);
        if ($subcategoryId)
            $productsQuery->where('subcategory_id', $subcategoryId);
        if ($search)
            $productsQuery->where('name', 'LIKE', "%{$search}%");
        $products = $productsQuery->select('id', 'name', 'price', 'sale_price', 'image')->get();

        // Combo-only products (replacing combo-only from combo_packs)
        $comboOnlyQuery = ComboOnlyProduct::where('is_active', true);
        if ($search)
            $comboOnlyQuery->where('name', 'LIKE', "%{$search}%");
        $comboOnlyProducts = $comboOnlyQuery->select('id', 'name', 'price', 'image')->get()
            ->map(fn($co) => [
                'id' => $co->id,
                'name' => $co->name . ' [Combo Only]',
                'price' => $co->price,
                'image' => $co->image,
            ]);

        return response()->json([
            'products' => $products,
            'combo_only_items' => $comboOnlyProducts,
        ]);
    }

    private function generateSlug(string $name): string
    {
        $slug = Str::slug($name);
        $counter = 1;
        $base = $slug;
        while (ComboPack::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $counter++;
        }
        return $slug;
    }
}
