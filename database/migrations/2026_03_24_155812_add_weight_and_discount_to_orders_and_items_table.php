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
        Schema::table('orders', function (Blueprint $blueprint) {
            $blueprint->decimal('total_weight', 10, 2)->default(0)->after('total');
            $blueprint->decimal('total_discount', 10, 2)->default(0)->after('total_weight');
        });

        Schema::table('order_items', function (Blueprint $blueprint) {
            $blueprint->decimal('weight', 10, 2)->default(0)->after('quantity');
            $blueprint->decimal('discount', 10, 2)->default(0)->after('weight');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $blueprint) {
            $blueprint->dropColumn(['total_weight', 'total_discount']);
        });

        Schema::table('order_items', function (Blueprint $blueprint) {
            $blueprint->dropColumn(['weight', 'discount']);
        });
    }
};
