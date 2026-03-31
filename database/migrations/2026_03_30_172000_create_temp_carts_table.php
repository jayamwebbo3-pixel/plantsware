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
        Schema::create('temp_carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('session_id')->nullable();
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('combo_pack_id')->nullable()->constrained('combo_packs')->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->string('status')->default('pending')->comment('pending, paid, expired');
            $table->json('options')->nullable();
            $table->timestamps();
            
            // Allow querying records more than 10 mins old efficiently
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temp_carts');
    }
};
