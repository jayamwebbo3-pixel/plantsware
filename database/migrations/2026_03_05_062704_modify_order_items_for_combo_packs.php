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
        Schema::disableForeignKeyConstraints();
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign('order_items_product_id_foreign');
            $table->dropIndex('order_items_product_id_foreign');
            $table->unsignedBigInteger('product_id')->nullable()->change();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreignId('combo_pack_id')->nullable()->constrained('combo_packs')->onDelete('cascade');
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['combo_pack_id']);
            $table->dropColumn('combo_pack_id');
            $table->dropForeign(['product_id']);
            $table->unsignedBigInteger('product_id')->nullable(false)->change();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
        Schema::enableForeignKeyConstraints();
    }
};
