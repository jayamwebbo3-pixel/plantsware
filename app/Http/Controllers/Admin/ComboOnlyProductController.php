<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ComboOnlyProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ComboOnlyProductController extends Controller
{
    public function index(Request $request)
    {
        $query = ComboOnlyProduct::latest();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->paginate(20)->withQueryString();

        return view('admin.combo-only-products.index', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'nullable|integer|min:0',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        $slug = Str::slug($validated['name']);
        $counter = 1;
        $baseSlug = $slug;
        while (ComboOnlyProduct::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('combo-only-products', 'public');
        }

        ComboOnlyProduct::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'] ?? 0,
            'stock_quantity' => $validated['stock_quantity'] ?? 0,
            'image' => $imagePath,
            'is_active' => $request->has('is_active') ? 1 : 1,
        ]);

        return redirect()->route('admin.combo-only-products.index')->with('success', 'Combo Only Product created successfully.');
    }

    public function edit($id)
    {
        $product = ComboOnlyProduct::findOrFail($id);
        return response()->json(['product' => $product]);
    }

    public function update(Request $request, $id)
    {
        $product = ComboOnlyProduct::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'nullable|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $product->image = $request->file('image')->store('combo-only-products', 'public');
        }

        $product->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'] ?? 0,
            'stock_quantity' => $validated['stock_quantity'] ?? 0,
            'image' => $product->image,
        ]);

        return redirect()->route('admin.combo-only-products.index')->with('success', 'Updated successfully.');
    }

    public function destroy($id)
    {
        $product = ComboOnlyProduct::findOrFail($id);
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        return redirect()->route('admin.combo-only-products.index')->with('success', 'Deleted successfully.');
    }

    public function toggleStatus(Request $request, $id)
    {
        $product = ComboOnlyProduct::findOrFail($id);
        $product->is_active = $request->status;
        $product->save();

        return response()->json(['success' => true]);
    }
}
