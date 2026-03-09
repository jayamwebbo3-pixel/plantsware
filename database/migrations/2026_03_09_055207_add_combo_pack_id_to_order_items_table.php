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
        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'combo_pack_id')) {
                $table->foreignId('combo_pack_id')->nullable()->after('product_id')->constrained('combo_packs')->onDelete('cascade');
            }

            // Ensure product_id is nullable too since it might be a combo-only order
            if (Schema::hasColumn('order_items', 'product_id')) {
                $table->unsignedBigInteger('product_id')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (Schema::hasColumn('order_items', 'combo_pack_id')) {
                $table->dropForeign(['combo_pack_id']);
                $table->dropColumn('combo_pack_id');
            }
        });
    }
};
