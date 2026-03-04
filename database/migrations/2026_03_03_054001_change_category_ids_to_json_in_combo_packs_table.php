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
            // Drop foreign keys first if they exist (depending on DB driver, dropForeign might be needed)
            $table->dropForeign(['category_id']);
            $table->dropForeign(['subcategory_id']);

            // Change to text to store array strings
            $table->text('category_id')->nullable()->change();
            $table->text('subcategory_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('combo_packs', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable()->change();
            $table->unsignedBigInteger('subcategory_id')->nullable()->change();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('subcategory_id')->references('id')->on('subcategories')->onDelete('set null');
        });
    }
};
