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

        if ($selectedSubcategoryId) {
            $subcategory = Subcategory::find($selectedSubcategoryId);
            if ($subcategory) {
                $selectedCategoryId = $subcategory->category_id;
            }
        }

        return view('admin.products.create', compact('categories', 'subcategories', 'selectedCategoryId', 'selectedSubcategoryId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string|unique:products,sku',
            'stock_quantity' => 'nullable|integer|min:0',
            'image' => 'nullable|image|max:2048',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|max:2048',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'sizes' => 'nullable|array',
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
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active', true); // Default true if not in form
        $validated['has_handles'] = $request->boolean('has_handles');
        $validated['uv_treated'] = $request->boolean('uv_treated');
        
        $validatedSizes = [];
        if ($request->has('sizes') && is_array($request->input('sizes'))) {
            foreach ($request->input('sizes') as $sizeKey => $sizeData) {
                if (!empty($sizeData['checked'])) {
                    $validatedSizes[$sizeKey] = $sizeData['price'] ?? null;
                }
            }
        }
        $validated['size'] = !empty($validatedSizes) ? json_encode($validatedSizes) : null;

        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
            $this->imageService->applyWatermark($validated['image']);
        }

        if ($request->hasFile('gallery_images')) {
            $galleryPaths = [];
            foreach ($request->file('gallery_images') as $image) {
                $path = $image->store('products/gallery', 'public');
                $this->imageService->applyWatermark($path);
                $galleryPaths[] = $path;
            }
            $validated['gallery_images'] = $galleryPaths;
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string|unique:products,sku,' . $product->id,
            'stock_quantity' => 'nullable|integer|min:0',
            'image' => 'nullable|image|max:2048',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|max:2048',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'sizes' => 'nullable|array',
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
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active', $product->is_active);
        $validated['has_handles'] = $request->boolean('has_handles');
        $validated['uv_treated'] = $request->boolean('uv_treated');
        
        $validatedSizes = [];
        if ($request->has('sizes') && is_array($request->input('sizes'))) {
            foreach ($request->input('sizes') as $sizeKey => $sizeData) {
                if (!empty($sizeData['checked'])) {
                    $validatedSizes[$sizeKey] = $sizeData['price'] ?? null;
                }
            }
        }
        $validated['size'] = !empty($validatedSizes) ? json_encode($validatedSizes) : null;

        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
            $this->imageService->applyWatermark($validated['image']);
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
                $path = $image->store('products/gallery', 'public');
                $this->imageService->applyWatermark($path);
                $currentGallery[] = $path;
            }
        }
        $validated['gallery_images'] = $currentGallery;

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
            Storage::disk('public')->delete($product->image);
        }
        if ($product->gallery_images) {
            foreach ($product->gallery_images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $product->delete();

        // Redirect to a safe list page (hierarchical or flat)
        return redirect()->route('admin.products.management') // Main categories page
            ->with('success', 'Product deleted successfully');
    }
}