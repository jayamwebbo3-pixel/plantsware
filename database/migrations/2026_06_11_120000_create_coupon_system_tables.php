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
        // 1. Add total_purchase_value to users table
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('total_purchase_value', 12, 2)->default(0.00)->after('email');
        });

        // Recalculate purchase values for any existing users
        try {
            if (class_exists(\App\Models\User::class)) {
                \App\Models\User::chunk(100, function ($users) {
                    foreach ($users as $user) {
                        $user->updatePurchaseValue();
                    }
                });
            }
        } catch (\Exception $e) {
            // Ignore failure if models or tables are not fully ready during migrations
        }


        // 2. Create coupons table
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('coupon_code')->unique();
            $table->string('discount_type'); // 'fixed' or 'percentage'
            $table->decimal('discount_value', 10, 2);
            $table->decimal('max_discount', 10, 2)->nullable();
            $table->decimal('minimum_order_amount', 10, 2)->default(0.00);
            $table->date('valid_from')->nullable();
            $table->date('valid_to')->nullable();
            $table->boolean('is_public')->default(true);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 3. Create coupon_users junction table
        Schema::create('coupon_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained('coupons')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // 4. Create coupon_usage table
        Schema::create('coupon_usage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained('coupons')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->decimal('discount_amount', 10, 2);
            $table->timestamp('used_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupon_usage');
        Schema::dropIfExists('coupon_users');
        Schema::dropIfExists('coupons');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('total_purchase_value');
        });
    }
};
