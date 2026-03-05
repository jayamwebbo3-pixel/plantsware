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
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('delivered_at')->nullable()->after('updated_at');
            $table->timestamp('return_requested_at')->nullable()->after('delivered_at');
            $table->text('return_reason')->nullable()->after('return_requested_at');
            $table->text('return_rejection_reason')->nullable()->after('return_reason');
        });
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled', 'returned', 'return_requested', 'return_rejected', 'completed') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled', 'returned') DEFAULT 'pending'");
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['delivered_at', 'return_requested_at', 'return_reason', 'return_rejection_reason']);
        });
    }
};
