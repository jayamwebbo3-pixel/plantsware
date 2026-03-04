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
            $table->dropColumn(['sort_order', 'is_featured']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('combo_packs', function (Blueprint $table) {
            $table->integer('sort_order')->default(0)->after('meta_keywords');
            $table->boolean('is_featured')->default(false)->after('short_description');
        });
    }
};
