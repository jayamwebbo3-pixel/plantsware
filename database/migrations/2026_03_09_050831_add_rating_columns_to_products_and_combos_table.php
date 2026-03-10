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
        if (!Schema::hasColumn('products', 'avg_rating')) {
            Schema::table('products', function (Blueprint $table) {
                $table->decimal('avg_rating', 2, 1)->default(0.0)->after('price');
                $table->integer('total_reviews')->default(0)->after('avg_rating');
            });
        }

        if (!Schema::hasColumn('combo_packs', 'avg_rating')) {
            Schema::table('combo_packs', function (Blueprint $table) {
                $table->decimal('avg_rating', 2, 1)->default(0.0)->after('offer_price');
                $table->integer('total_reviews')->default(0)->after('avg_rating');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['avg_rating', 'total_reviews']);
        });

        Schema::table('combo_packs', function (Blueprint $table) {
            $table->dropColumn(['avg_rating', 'total_reviews']);
        });
    }
};
