<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $search = request('search');
        $perPage = request('per_page', 20);

        $categories = Category::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', "%{$search}%");
        })
            ->orderBy(
                request('sort', 'sort_order'),
                request('direction', 'asc')
            )
            ->paginate($perPage)
            ->appends(request()->query());

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $next_sort_order = Category::max('sort_order') + 1;
        return view('admin.categories.create', compact('next_sort_order'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'badge_type' => 'nullable|in:sale,new,offer,combo',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        // Auto-increment sort_order if not provided
        $validated['sort_order'] = $request->input('sort_order', Category::max('sort_order') + 1);

        // Conflict handling for manual sort_order entry
        if ($request->has('sort_order')) {
            Category::where('sort_order', '>=', $validated['sort_order'])
                ->increment('sort_order');
        }

        Category::create($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'badge_type' => 'nullable|in:sale,new,offer,combo',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        if ($request->has('sort_order') && $validated['sort_order'] != $category->sort_order) {
            $newOrder = $validated['sort_order'];
            $oldOrder = $category->sort_order;

            if ($newOrder < $oldOrder) {
                // Moving up (e.g., 3 -> 1): Shift items between new and old position down
                Category::where('sort_order', '>=', $newOrder)
                    ->where('sort_order', '<', $oldOrder)
                    ->where('id', '!=', $category->id)
                    ->increment('sort_order');
            } else {
                // Moving down (e.g., 1 -> 3): Shift items between old and new position up
                Category::where('sort_order', '>', $oldOrder)
                    ->where('sort_order', '<=', $newOrder)
                    ->where('id', '!=', $category->id)
                    ->decrement('sort_order');
            }
        }

        $category->update($validated);

        return redirect()->route('admin.products.management')->with('success', 'Category updated successfully');
    }

    public function destroy(Category $category)
    {
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully');
    }
}
