<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Services\ImageService;

class ProductController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index()
    {
        $search = request('search');
        $perPage = request('per_page', 20);

        $products = Product::with(['category', 'subcategory'])
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            })
            ->orderBy(
                request('sort', 'sort_order'),
                request('direction', 'asc')
            )
            ->paginate($perPage)
            ->appends(request()->query());

        return view('admin.products-management.products', compact('products'));
    }

    public function create(Request $request)
    {
        $categories = Category::where('is_active', true)->get();
        $subcategories = Subcategory::where('is_active', true)->get();

        $selectedSubcategoryId = $request->query('subcategory_id');
        $selectedCategoryId = null;
        $selectedSubcategory = null;

        if ($selectedSubcategoryId) {
            $selectedSubcategory = Subcategory::find($selectedSubcategoryId);
            if ($selectedSubcategory) {
                $selectedCategoryId = $selectedSubcategory->category_id;
            }
        }

        $next_sort_order = 1;
        if ($selectedSubcategoryId) {
            $next_sort_order = Product::where('subcategory_id', $selectedSubcategoryId)->max('sort_order') + 1;
        }

        return view('admin.products.create', compact('categories', 'subcategories', 'selectedCategoryId', 'selectedSubcategoryId', 'selectedSubcategory', 'next_sort_order'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string',
            'price' => $request->boolean('has_variants') ? 'nullable|numeric|min:0' : 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string|unique:products,sku',
            'stock_quantity' => 'nullable|integer|min:0',
            'stock_alert_qty' => 'nullable|integer|min:0',
            'image' => 'nullable|image|max:2048',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|max:2048',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'shape' => 'nullable|string|in:Circular,Rectangular,Square',
            'material' => 'nullable|string|in:HDPE,Fabric,Non-woven',
            'color' => 'nullable|string|max:50',
            'gsm' => 'nullable|integer|min:0',
            'has_handles' => 'boolean',
            'uv_treated' => 'boolean',
            'shade_percentage' => 'nullable|string|max:50',
            'width_meters' => 'nullable|numeric|min:0',
            'length_meters' => 'nullable|numeric|min:0',
            'pack_quantity' => 'integer|min:1',
            'warranty_months' => 'nullable|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'product_code' => 'nullable|string|max:100',
            'batch_code' => 'nullable|string|max:100',
            'combo_pack_eligible' => 'nullable|in:Yes,No',
        ];

        if ($request->boolean('has_variants')) {
            $rules['sizes'] = 'required|array|min:1';
            $rules['sizes.*.price'] = 'required|numeric|min:0';
            $rules['sizes.*.stock'] = 'required|integer|min:0';
            $rules['sizes.*.weight'] = 'required|numeric|min:0';
            $rules['sizes.*.sale_price'] = 'nullable|numeric|min:0';
            $rules['sizes.*.stock_alert_qty'] = 'nullable|integer|min:0';
        }

        $validated = $request->validate($rules, [
            'sizes.required' => 'At least one product attribute/variant must be added when variants are enabled.',
            'sizes.*.price.required' => 'Price override is required for all added variants.',
            'sizes.*.stock.required' => 'Stock quantity is required for all added variants.',
            'sizes.*.weight.required' => 'Weight is required for all added variants.',
        ]);

        if ($request->boolean('has_variants') && $request->input('combo_pack_eligible') === 'Yes') {
            $sizes = $request->input('sizes', []);
            $hasEligibleVariant = false;
            foreach ($sizes as $sizeData) {
                if (!empty($sizeData['checked']) && ($sizeData['combo_eligible'] ?? 'No') === 'Yes') {
                    $hasEligibleVariant = true;
                    break;
                }
            }
            if (!$hasEligibleVariant) {
                return back()->withErrors(['sizes' => 'At least one attribute variant must be marked as Combo Eligible when the product itself is Combo Pack Eligible.'])->withInput();
            }
        }

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active', true); // Default true if not in form
        $validated['has_handles'] = $request->boolean('has_handles');
        $validated['uv_treated'] = $request->boolean('uv_treated');
        
        $validated['has_variants'] = $request->boolean('has_variants');
        $validatedSizes = [];
        if ($validated['has_variants'] && $request->has('sizes') && is_array($request->input('sizes'))) {
            foreach ($request->input('sizes') as $sizeKey => $sizeData) {
                if (!empty($sizeData['checked'])) {
                    $sizeEntry = [
                        'price' => $sizeData['price'] ?? null,
                        'sale_price' => (!empty($sizeData['sale_price']) && $sizeData['sale_price'] > 0) ? $sizeData['sale_price'] : null,
                        'stock' => $sizeData['stock'] ?? null,
                        'stock_alert_qty' => $sizeData['stock_alert_qty'] ?? null,
                        'weight' => $sizeData['weight'] ?? null,
                        'combo_eligible' => $sizeData['combo_eligible'] ?? 'No',
                        'type' => $sizeData['type'] ?? 'size',
                        'image' => null
                    ];

                    // Handle size-specific image upload
                    if ($request->hasFile("sizes.$sizeKey.image")) {
                        $tempPath = $request->file("sizes.$sizeKey.image")->store('products/attributes', 'public');
                        $sizeEntry['image'] = $this->imageService->applyWatermark($tempPath);
                    }

                    $validatedSizes[$sizeKey] = $sizeEntry;
                }
            }
        }
        $validated['size'] = !empty($validatedSizes) ? $validatedSizes : null;

        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('image')) {
            $tempPath = $request->file('image')->store('products', 'public');
            $validated['image'] = $this->imageService->applyWatermark($tempPath);
        }

        if ($request->hasFile('gallery_images')) {
            $galleryPaths = [];
            foreach ($request->file('gallery_images') as $image) {
                $tempPath = $image->store('products/gallery', 'public');
                $galleryPaths[] = $this->imageService->applyWatermark($tempPath);
            }
            $validated['gallery_images'] = $galleryPaths;
        }

        $validated['price'] = $validated['price'] ?? 0;
        $validated['sale_price'] = (!empty($validated['sale_price']) && $validated['sale_price'] > 0) ? $validated['sale_price'] : null;
        $validated['stock_quantity'] = $validated['stock_quantity'] ?? 0;
        $validated['stock_alert_qty'] = $validated['stock_alert_qty'] ?? 0;
        $validated['weight'] = $validated['weight'] ?? 0;

        // Auto-increment sort_order within the subcategory
        $query = Product::query();
        if (isset($validated['subcategory_id'])) {
            $query->where('subcategory_id', $validated['subcategory_id']);
        } elseif (isset($validated['category_id'])) {
            $query->where('category_id', $validated['category_id']);
        }
        $validated['sort_order'] = $request->input('sort_order', $query->max('sort_order') + 1);

        // Conflict handling for manual sort_order entry within category/subcategory
        if ($request->has('sort_order')) {
            $shiftQuery = Product::where('sort_order', '>=', $validated['sort_order']);
            if (isset($validated['subcategory_id'])) {
                $shiftQuery->where('subcategory_id', $validated['subcategory_id']);
            } elseif (isset($validated['category_id'])) {
                $shiftQuery->where('category_id', $validated['category_id']);
            }
            $shiftQuery->increment('sort_order');
        }

        Product::create($validated);

        if (isset($validated['subcategory_id'])) {
            return redirect()->route('admin.subcategories.products', $validated['subcategory_id'])->with('success', 'Product created successfully');
        }
        return redirect()->route('admin.products.index')->with('success', 'Product created successfully');
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        // Get subcategories - filter by product's category if set, otherwise show all
        $subcategories = $product->category_id
            ? Subcategory::where('category_id', $product->category_id)->where('is_active', true)->orderBy('sort_order')->get()
            : Subcategory::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.products.edit', compact('product', 'categories', 'subcategories'));
    }

    public function update(Request $request, Product $product)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string',
            'price' => $request->boolean('has_variants') ? 'nullable|numeric|min:0' : 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string|unique:products,sku,' . $product->id,
            'stock_quantity' => 'nullable|integer|min:0',
            'stock_alert_qty' => 'nullable|integer|min:0',
            'image' => 'nullable|image|max:2048',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|max:2048',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'shape' => 'nullable|string|in:Circular,Rectangular,Square',  // Enforce options
            'material' => 'nullable|string|in:HDPE,Fabric,Non-woven',
            'color' => 'nullable|string|max:50',
            'gsm' => 'nullable|integer|min:0',
            'has_handles' => 'boolean',
            'uv_treated' => 'boolean',
            'shade_percentage' => 'nullable|string|max:50',
            'width_meters' => 'nullable|numeric|min:0',
            'length_meters' => 'nullable|numeric|min:0',
            'pack_quantity' => 'integer|min:1',
            'warranty_months' => 'nullable|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'product_code' => 'nullable|string|max:100',
            'batch_code' => 'nullable|string|max:100',
            'combo_pack_eligible' => 'nullable|in:Yes,No',
        ];

        if ($request->boolean('has_variants')) {
            $rules['sizes'] = 'required|array|min:1';
            $rules['sizes.*.price'] = 'required|numeric|min:0';
            $rules['sizes.*.stock'] = 'required|integer|min:0';
            $rules['sizes.*.weight'] = 'required|numeric|min:0';
            $rules['sizes.*.sale_price'] = 'nullable|numeric|min:0';
            $rules['sizes.*.stock_alert_qty'] = 'nullable|integer|min:0';
        }

        $validated = $request->validate($rules, [
            'sizes.required' => 'At least one product attribute/variant must be added when variants are enabled.',
            'sizes.*.price.required' => 'Price override is required for all added variants.',
            'sizes.*.stock.required' => 'Stock quantity is required for all added variants.',
            'sizes.*.weight.required' => 'Weight is required for all added variants.',
        ]);

        if ($request->boolean('has_variants') && $request->input('combo_pack_eligible') === 'Yes') {
            $sizes = $request->input('sizes', []);
            $hasEligibleVariant = false;
            foreach ($sizes as $sizeData) {
                if (!empty($sizeData['checked']) && ($sizeData['combo_eligible'] ?? 'No') === 'Yes') {
                    $hasEligibleVariant = true;
                    break;
                }
            }
            if (!$hasEligibleVariant) {
                return back()->withErrors(['sizes' => 'At least one attribute variant must be marked as Combo Eligible when the product itself is Combo Pack Eligible.'])->withInput();
            }
        }

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active', $product->is_active);
        $validated['has_handles'] = $request->boolean('has_handles');
        $validated['uv_treated'] = $request->boolean('uv_treated');
        
        $validated['has_variants'] = $request->boolean('has_variants');
        $validatedSizes = [];
        $currentSizes = $product->size;
        if (is_string($currentSizes)) {
            $currentSizes = json_decode($currentSizes, true) ?? [];
        }
        $currentSizes = is_array($currentSizes) ? $currentSizes : [];
        
        if ($validated['has_variants'] && $request->has('sizes') && is_array($request->input('sizes'))) {
            foreach ($request->input('sizes') as $sizeKey => $sizeData) {
                if (!empty($sizeData['checked'])) {
                    $sizeEntry = [
                        'price' => $sizeData['price'] ?? null,
                        'sale_price' => (!empty($sizeData['sale_price']) && $sizeData['sale_price'] > 0) ? $sizeData['sale_price'] : null,
                        'stock' => $sizeData['stock'] ?? null,
                        'stock_alert_qty' => $sizeData['stock_alert_qty'] ?? null,
                        'weight' => $sizeData['weight'] ?? null,
                        'combo_eligible' => $sizeData['combo_eligible'] ?? 'No',
                        'type' => $sizeData['type'] ?? 'size',
                        'image' => $sizeData['existing_image'] ?? null
                    ];

                    // Handle new size-specific image upload
                    if ($request->hasFile("sizes.$sizeKey.image")) {
                        // Delete old image if exists (Commented out to preserve for order history)
                        if ($sizeEntry['image']) {
                            // Storage::disk('public')->delete($sizeEntry['image']);
                        }
                        $tempPath = $request->file("sizes.$sizeKey.image")->store('products/attributes', 'public');
                        $sizeEntry['image'] = $this->imageService->applyWatermark($tempPath);
                    }

                    $validatedSizes[$sizeKey] = $sizeEntry;
                }
            }
        }

        // Cleanup: Delete images for sizes that were removed or if variants were completely disabled (Commented out to preserve for order history)
        foreach($currentSizes as $name => $data) {
            if ((!$validated['has_variants'] || !isset($validatedSizes[$name])) && isset($data['image']) && $data['image']) {
                // Storage::disk('public')->delete($data['image']);
            }
        }

        $validated['size'] = $validated['has_variants'] && !empty($validatedSizes) ? $validatedSizes : null;

        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('image')) {
            if ($product->image) {
                // Storage::disk('public')->delete($product->image); // Preserve old image for order history
            }
            $tempPath = $request->file('image')->store('products', 'public');
            $validated['image'] = $this->imageService->applyWatermark($tempPath);
        }

        // Handle Gallery Images (Append new, Delete selected)
        $currentGallery = $product->gallery_images ?? [];
        
        // 1. Delete selected images
        if ($request->has('deleted_gallery_images')) {
            foreach ($request->deleted_gallery_images as $path) {
                if (($key = array_search($path, $currentGallery)) !== false) {
                    Storage::disk('public')->delete($path);
                    unset($currentGallery[$key]);
                }
            }
            $currentGallery = array_values($currentGallery); // Re-index
        }

        // 2. Add new images
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $tempPath = $image->store('products/gallery', 'public');
                $currentGallery[] = $this->imageService->applyWatermark($tempPath);
            }
        }
        $validated['gallery_images'] = $currentGallery;

        $validated['price'] = $validated['price'] ?? 0;
        $validated['sale_price'] = (!empty($validated['sale_price']) && $validated['sale_price'] > 0) ? $validated['sale_price'] : null;
        $validated['stock_quantity'] = $validated['stock_quantity'] ?? 0;
        $validated['stock_alert_qty'] = $validated['stock_alert_qty'] ?? 0;
        $validated['weight'] = $validated['weight'] ?? 0;

        if ($request->has('sort_order') && $validated['sort_order'] != $product->sort_order) {
            $newOrder = $validated['sort_order'];
            $oldOrder = $product->sort_order;

            $query = Product::where('id', '!=', $product->id);
                
            if ($product->subcategory_id) {
                $query->where('subcategory_id', $product->subcategory_id);
            } elseif ($product->category_id) {
                $query->where('category_id', $product->category_id);
            }

            if ($newOrder < $oldOrder) {
                // Moving up: Shift items between new and old position down
                (clone $query)->where('sort_order', '>=', $newOrder)
                    ->where('sort_order', '<', $oldOrder)
                    ->increment('sort_order');
            } else {
                // Moving down: Shift items between old and new position up
                (clone $query)->where('sort_order', '>', $oldOrder)
                    ->where('sort_order', '<=', $newOrder)
                    ->decrement('sort_order');
            }
        }

        $product->update($validated);

        return redirect()->route('admin.subcategories.products', $product->subcategory_id)->with('success', 'Product updated successfully');
    }

    public function updateStatus(Request $request, Product $product)
    {
        $validated = $request->validate([
            'is_active' => 'required|boolean'
        ]);

        $product->update(['is_active' => $validated['is_active']]);

        return response()->json([
            'success' => true,
            'message' => 'Product status updated successfully',
            'is_active' => $product->is_active
        ]);
    }

    public function destroy(Product $product)
    {
        // Optional: Check if product exists (extra safety)
        if (!$product->exists) {
            return redirect()->route('admin.products.management') // or 'admin.products.index'
                ->with('error', 'Product not found.');
        }

        if ($product->image) {
            // Storage::disk('public')->delete($product->image);
        }
        if ($product->gallery_images) {
            foreach ($product->gallery_images as $image) {
                // Storage::disk('public')->delete($image);
            }
        }
        if ($product->size && is_array($product->size)) {
            foreach ($product->size as $sizeData) {
                if (isset($sizeData['image']) && $sizeData['image']) {
                    // Storage::disk('public')->delete($sizeData['image']);
                }
            }
        }

        $product->delete();

        // Redirect to a safe list page (hierarchical or flat)
        return redirect()->route('admin.products.management') // Main categories page
            ->with('success', 'Product deleted successfully');
    }
}