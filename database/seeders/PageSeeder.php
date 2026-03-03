<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            ['title' => 'About Us', 'slug' => 'about-us', 'content' => '<p>About us content...</p>'],
            ['title' => 'Contact Us', 'slug' => 'contact-us', 'content' => '<p>Contact us details...</p>'],
            ['title' => 'Services', 'slug' => 'services', 'content' => '<p>Our services...</p>'],
            ['title' => 'Management Team', 'slug' => 'management-team', 'content' => '<p>Meet our team...</p>'],
            ['title' => 'Terms & Conditions', 'slug' => 'terms-conditions', 'content' => '<p>Terms and conditions...</p>'],
            ['title' => 'Privacy Policy', 'slug' => 'privacy-policy', 'content' => '<p>Privacy policies...</p>'],
            ['title' => 'Return & Refund Policy', 'slug' => 'return-refund-policy', 'content' => '<p>Return and refund details...</p>', 'policy_date' => date('Y-m-d')],
            ['title' => 'Shipping Policy', 'slug' => 'shipping-policy', 'content' => '<p>Shipping details...</p>']
        ];

        foreach ($pages as $page) {
            Page::firstOrCreate(['slug' => $page['slug']], $page);
        }
    }
}
