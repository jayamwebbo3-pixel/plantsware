<?php

namespace Tests\Feature;

use App\Models\Coupon;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CouponTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_can_create_a_coupon()
    {
        $coupon = Coupon::create([
            'coupon_code' => 'SAVE10',
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'max_discount' => 100,
            'minimum_order_amount' => 500,
            'status' => true,
            'is_public' => true,
        ]);

        $this->assertDatabaseHas('coupons', [
            'coupon_code' => 'SAVE10',
            'discount_type' => 'percentage',
            'discount_value' => 10,
        ]);
    }

    /** @test */
    public function it_can_calculate_percentage_discount()
    {
        $coupon = Coupon::create([
            'coupon_code' => 'SAVE20',
            'discount_type' => 'percentage',
            'discount_value' => 20,
            'max_discount' => 100,
            'minimum_order_amount' => 300,
            'status' => true,
            'is_public' => true,
        ]);

        // Scenario 1: Below minimum order
        $this->assertFalse($coupon->isValidForCart(200));

        // Scenario 2: Above minimum order, discount calculation (20% of 400 is 80)
        $this->assertTrue($coupon->isValidForCart(400));
        $this->assertEquals(80, $coupon->calculateDiscount(400));

        // Scenario 3: Cap at max discount (20% of 1000 is 200, cap is 100)
        $this->assertEquals(100, $coupon->calculateDiscount(1000));
    }

    /** @test */
    public function it_can_calculate_fixed_discount()
    {
        $coupon = Coupon::create([
            'coupon_code' => 'FLAT150',
            'discount_type' => 'fixed',
            'discount_value' => 150,
            'minimum_order_amount' => 500,
            'status' => true,
            'is_public' => true,
        ]);

        $this->assertTrue($coupon->isValidForCart(600));
        $this->assertEquals(150, $coupon->calculateDiscount(600));
    }

    /** @test */
    public function it_validates_user_specific_targeting()
    {
        $coupon = Coupon::create([
            'coupon_code' => 'USERONLY',
            'discount_type' => 'fixed',
            'discount_value' => 50,
            'status' => true,
            'is_public' => false,
        ]);

        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // Assign to user1
        $coupon->users()->attach($user1->id);

        $this->assertTrue($coupon->isValidForUser($user1));
        $this->assertFalse($coupon->isValidForUser($user2));
    }

    /** @test */
    public function it_can_fetch_coupon_details_report()
    {
        $admin = \App\Models\AdminUser::create([
            'name' => 'Admin User',
            'username' => 'admin_test',
            'password' => 'password123',
        ]);

        $coupon = Coupon::create([
            'coupon_code' => 'REPORTTEST',
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'status' => true,
            'is_public' => false,
        ]);

        $user = User::factory()->create([
            'name' => 'Target Customer',
            'email' => 'target@example.com',
            'phone' => '1234567890',
        ]);

        $coupon->users()->attach($user->id);

        // Fetch report as admin
        $response = $this->actingAs($admin, 'admin')
            ->getJson(route('admin.coupons.report', $coupon->id));

        $response->assertStatus(200);
        $response->assertJsonPath('coupon.coupon_code', 'REPORTTEST');
        $response->assertJsonPath('coupon.audience_text', 'Restricted (Targeted)');
        $response->assertJsonCount(1, 'users');
        $response->assertJsonPath('users.0.name', 'Target Customer');
        $response->assertJsonPath('users.0.has_used', false);
    }

    /** @test */
    public function it_fetches_all_users_for_public_coupon_report()
    {
        $admin = \App\Models\AdminUser::create([
            'name' => 'Admin User',
            'username' => 'admin_test2',
            'password' => 'password123',
        ]);

        $coupon = Coupon::create([
            'coupon_code' => 'PUBLICTEST',
            'discount_type' => 'fixed',
            'discount_value' => 100,
            'status' => true,
            'is_public' => true,
        ]);

        $user1 = User::factory()->create(['name' => 'Customer A']);
        $user2 = User::factory()->create(['name' => 'Customer B']);

        // Fetch report as admin
        $response = $this->actingAs($admin, 'admin')
            ->getJson(route('admin.coupons.report', $coupon->id));

        $response->assertStatus(200);
        $response->assertJsonPath('coupon.coupon_code', 'PUBLICTEST');
        $response->assertJsonPath('coupon.audience_text', 'All Customers - Current & Past 5 Months');
        
        // Assert that both users are listed in the report
        $users = $response->json('users');
        $names = collect($users)->pluck('name')->toArray();
        $this->assertContains('Customer A', $names);
        $this->assertContains('Customer B', $names);
    }
}

