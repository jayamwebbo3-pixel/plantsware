<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('combo_packs', function (Blueprint $table) {
            $table->string('sku')->unique()->nullable()->after('is_combo_only');
            $table->integer('stock_quantity')->default(0)->after('sku');
            $table->text('short_description')->nullable()->after('stock_quantity');
            $table->boolean('is_featured')->default(false)->after('short_description');
            $table->string('meta_title')->nullable()->after('is_featured');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->string('meta_keywords')->nullable()->after('meta_description');
            $table->integer('sort_order')->default(0)->after('meta_keywords');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('combo_packs', function (Blueprint $table) {
            $table->dropColumn([
                'sku',
                'stock_quantity',
                'short_description',
                'is_featured',
                'meta_title',
                'meta_description',
                'meta_keywords',
                'sort_order'
            ]);
        });
    }
};
