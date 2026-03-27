<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Slider;
use App\Models\Blog;
use App\Models\Testimonial;
use App\Models\HeaderFooter;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
{
    $categories = Category::active()->ordered()->get();
    $sliders = Slider::active()->ordered()->get();
    $newArrivals = Product::active()->latest()->take(12)->get();

    // Fix 1: Filter by category name (through relationship)
    $gardenProducts = Product::active()
        ->whereHas('category', function ($query) {
            $query->where('name', 'like', '%Garden%');
        })
        ->orWhereHas('subcategory', function ($query) {
            $query->where('name', 'like', '%Garden%');
        })
        ->take(12)
        ->get();

    // Fix 2: Aquarium products
    $aquariumProducts = Product::active()
        ->whereHas('category', function ($query) {
            $query->where('name', 'like', '%Aquarium%');
        })
        ->orWhereHas('subcategory', function ($query) {
            $query->where('name', 'like', '%Aquarium%');
        })
        ->take(12)
        ->get();

    // Fix 3: Natural products
    $naturalProducts = Product::active()
        ->whereHas('category', function ($query) {
            $query->where('name', 'like', '%Natural%');
        })
        ->orWhereHas('subcategory', function ($query) {
            $query->where('name', 'like', '%Natural%');
        })
        ->take(12)
        ->get();

    $dbTestimonials = Testimonial::active()->latest()->take(20)->get();
    $productReviews = \App\Models\ProductReview::where('is_approved', true)
        ->where('rating', '>=', 1)
        ->with('user')
        ->latest()
        ->take(20)
        ->get()
        ->map(function($review) {
            return (object)[
                'name' => $review->user->name ?? 'User',
                'rating' => $review->rating,
                'title' => 'Product Review',
                'content' => $review->review ?? 'Great product!',
                'is_verified' => true,
                'date' => $review->created_at,
                'created_at' => $review->created_at
            ];
        });

    $testimonials = $dbTestimonials->concat($productReviews)->sortByDesc('created_at')->take(8);

    $blogs = Blog::active()->latest()->take(3)->get();

    $gardenCategory = Category::where('name', 'like', '%Garden%')->first();
    $aquariumCategory = Category::where('name', 'like', '%Aquarium%')->first();
    $naturalCategory = Category::where('name', 'like', '%Natural%')->first();
    $servicesPage = \App\Models\Page::where('slug', 'services')->first();
    $serviceHighlights = $servicesPage->extra_content['features'] ?? [];

    return view('view.index', compact(
        'categories', 'sliders', 'newArrivals',
        'gardenProducts', 'aquariumProducts', 'naturalProducts',
        'gardenCategory', 'aquariumCategory', 'naturalCategory',
        'testimonials', 'blogs', 'serviceHighlights'
    ));
}
}
