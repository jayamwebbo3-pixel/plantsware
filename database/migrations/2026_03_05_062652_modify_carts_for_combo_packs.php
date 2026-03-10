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
        Schema::table('carts', function (Blueprint $table) {
            $table->dropUnique('carts_user_id_session_id_product_id_unique');
            $table->dropIndex('carts_product_id_foreign');
            $table->unsignedBigInteger('product_id')->nullable()->change();
            $table->foreignId('combo_pack_id')->nullable()->constrained('combo_packs')->onDelete('cascade');
            $table->unique(['user_id', 'session_id', 'product_id', 'combo_pack_id'], 'carts_unique_items_idx');
            $table->index('product_id', 'carts_product_id_foreign');
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('carts', function (Blueprint $table) {
            $table->dropUnique('carts_unique_items_idx');
            $table->dropForeign(['combo_pack_id']);
            $table->dropColumn('combo_pack_id');
            $table->dropForeign(['product_id']);
            $table->unsignedBigInteger('product_id')->nullable(false)->change();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->unique(['user_id', 'session_id', 'product_id']);
        });
        Schema::enableForeignKeyConstraints();
    }
};
