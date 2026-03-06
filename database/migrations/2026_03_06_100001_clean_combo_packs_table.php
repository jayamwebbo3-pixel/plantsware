<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('combo_packs', function (Blueprint $table) {
            // Drop columns only used for combo-only products
            $columnsToDrop = [];
            foreach (['is_combo_only', 'sku', 'short_description', 'meta_title', 'meta_description', 'meta_keywords', 'sort_order', 'is_featured'] as $col) {
                if (Schema::hasColumn('combo_packs', $col)) {
                    $columnsToDrop[] = $col;
                }
            }
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }

    public function down(): void
    {
        Schema::table('combo_packs', function (Blueprint $table) {
            $table->boolean('is_combo_only')->default(false)->after('is_active');
            $table->string('sku')->unique()->nullable();
            $table->text('short_description')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_featured')->default(false);
        });
    }
};
