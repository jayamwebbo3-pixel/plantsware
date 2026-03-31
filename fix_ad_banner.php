<?php

use App\Models\Page;
use Illuminate\Support\Str;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$slug = 'ad-banner';
$page = Page::where('slug', $slug)->first();

if (!$page) {
    Page::create([
        'title' => 'Ad Banner',
        'slug' => $slug,
        'content' => '<h1 class="big-title">Fresh Plant-Based Goodness</h1><p class="small-title">Discover our organic, sustainable products for a healthier lifestyle</p>',
        'extra_content' => [
            'button_text' => 'Shop Now',
            'button_link' => 'categories'
        ]
    ]);
    echo "Ad Banner page created successfully.\n";
} else {
    echo "Ad Banner page already exists.\n";
}
