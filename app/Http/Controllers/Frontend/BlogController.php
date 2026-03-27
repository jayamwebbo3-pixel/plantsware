<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog; 
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogController extends Controller
{

 

    //  Multiple blogs 

public function allBlogs()
{
    $blogs = Blog::with('category')->active()
                 ->latest()
                 ->paginate(9);

    return view('view.blog', compact('blogs'));
}
    // end here 
    public function show($slug) 
    {
        $blog = Blog::with(['category', 'tags'])->where('slug', $slug)->active()->firstOrFail();
        
        $relatedBlogs = Blog::where('blog_category_id', $blog->blog_category_id)
            ->where('id', '!=', $blog->id)
            ->active()
            ->take(3)
            ->get();
        
        return view('view.singleblog', compact('blog', 'relatedBlogs'));
    }

    public function categories()
    {
        $categories = BlogCategory::active()->withCount('blogs')->get();

        return view('view.blogcategory', compact('categories'));
    }

    public function category($slug)
    {
        $category = BlogCategory::where('slug', $slug)->active()->firstOrFail();
        $blogs = Blog::where('blog_category_id', $category->id)->active()->latest()->paginate(12);

        return view('view.blog', [
            'blogs' => $blogs,
            'title' => 'Category: ' . $category->name,
            'description' => 'Explore posts in ' . $category->name
        ]);
    }
}
