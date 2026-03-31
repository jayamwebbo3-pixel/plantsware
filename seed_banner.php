<?php
use App\Models\Page;

$banner = Page::where('slug', 'ad-banner')->first();

if (!$banner) {
    Page::create([
        'title' => 'Ad Banner',
        'slug' => 'ad-banner',
        'content' => 'Discover our organic, sustainable products for a healthier lifestyle',
        'extra_content' => [
            'button_text' => 'Shop Now',
            'button_link' => 'categories'
        ]
    ]);
    echo "Ad Banner record created successfully.\n";
} else {
    echo "Ad Banner record already exists.\n";
}
