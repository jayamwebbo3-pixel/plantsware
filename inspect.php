<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$p = \App\Models\Product::find(25);
if ($p) {
    echo "Product found!\n";
    echo "Name: " . $p->name . "\n";
    echo "Is Active: " . ($p->is_active ? 'Yes' : 'No') . "\n";
    echo "Image: " . $p->image . "\n";
} else {
    echo "Product NOT found!\n";
}
