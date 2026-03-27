<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class BlogCategoryController extends Controller
{
    public function index()
    {
        $categories = BlogCategory::withCount('blogs')->latest()->paginate(10);
        return view('admin.blog_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.blog_categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:blog_categories,name',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = 'cat_' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/blog_categories'), $filename);
            $data['image'] = 'uploads/blog_categories/' . $filename;
        }

        BlogCategory::create($data);

        return redirect()->route('admin.blog-categories.index')->with('success', 'Category created successfully!');
    }

    public function edit($id)
    {
        $category = BlogCategory::findOrFail($id);
        return view('admin.blog_categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = BlogCategory::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:blog_categories,name,' . $id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($category->image && file_exists(public_path($category->image))) {
                File::delete(public_path($category->image));
            }

            $image = $request->file('image');
            $filename = 'cat_' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/blog_categories'), $filename);
            $data['image'] = 'uploads/blog_categories/' . $filename;
        }

        $category->update($data);

        return redirect()->route('admin.blog-categories.index')->with('success', 'Category updated successfully!');
    }

    public function destroy($id)
    {
        $category = BlogCategory::findOrFail($id);
        
        if ($category->image && file_exists(public_path($category->image))) {
            File::delete(public_path($category->image));
        }

        $category->delete();

        return redirect()->route('admin.blog-categories.index')->with('success', 'Category deleted successfully!');
    }
}
