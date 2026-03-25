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
        Schema::create('shipping_rates', function (Blueprint $table) {
            $table->id();
            $table->string('state_name');
            $table->integer('base_weight')->default(250); // in grams
            $table->decimal('base_cost', 10, 2)->default(0.00);
            $table->integer('additional_weight_unit')->default(250); // in grams
            $table->decimal('additional_cost_per_unit', 10, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_rates');
    }
};
