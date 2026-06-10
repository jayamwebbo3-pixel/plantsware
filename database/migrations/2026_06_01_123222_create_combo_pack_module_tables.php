<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add combo_pack_eligible to products table
        Schema::table('products', function (Blueprint $table) {
            $table->enum('combo_pack_eligible', ['Yes', 'No'])->default('No')->after('image');
        });

        // 2. Create combo_pack_settings table
        Schema::create('combo_pack_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_enabled')->default(false);
            $table->integer('max_products')->default(3);
            $table->timestamps();
        });

        // 3. Create combo_pack_discount_slabs table
        Schema::create('combo_pack_discount_slabs', function (Blueprint $table) {
            $table->id();
            $table->decimal('min_amount', 10, 2);
            $table->decimal('max_amount', 10, 2);
            $table->decimal('discount_percentage', 5, 2);
            $table->boolean('status')->default(true); // Active/Inactive
            $table->timestamps();
        });

        // 4. Create order_combo_details table
        Schema::create('order_combo_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('custom_combo_id')->nullable(); // Unique identifier for the custom combo in options
            $table->decimal('product_total', 10, 2);
            $table->decimal('discount_percentage', 5, 2);
            $table->decimal('discount_amount', 10, 2);
            $table->decimal('discounted_total', 10, 2);
            $table->decimal('gst_amount', 10, 2);
            $table->decimal('shipping_amount', 10, 2);
            $table->decimal('final_amount', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_combo_details');
        Schema::dropIfExists('combo_pack_discount_slabs');
        Schema::dropIfExists('combo_pack_settings');
        
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('combo_pack_eligible');
        });
    }
};
