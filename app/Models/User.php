<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'name',
        'email',
        'total_purchase_value',
        'password',
        'google_id',
        'phone',
        'address',
        'city',
        'state',
        'pincode',
        'otp',
        'otp_expires_at',
        'email_verified_at',
    ];

    /**
     * Hidden attributes
     */
    protected $hidden = [
        'password',
        'remember_token',
        'otp',
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'otp_expires_at' => 'datetime',
        'total_purchase_value' => 'decimal:2',

        // Keep ONLY if address column is JSON
        'address' => 'array',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    |
    */

    public function addresses()
    {
        return $this->hasMany(UserAddress::class);
    }

    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function assignedCoupons()
    {
        return $this->belongsToMany(Coupon::class, 'coupon_users', 'user_id', 'coupon_id');
    }

    public function couponUsages()
    {
        return $this->hasMany(CouponUsage::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    public function hasInWishlist(Product $product): bool
    {
        return $this->wishlist()
            ->where('product_id', $product->id)
            ->exists();
    }

    public function updatePurchaseValue()
    {
        $total = $this->orders()
            ->whereIn('status', ['confirmed', 'processing', 'shipped', 'delivered', 'completed'])
            ->where('payment_status', 'paid')
            ->sum('total');
        $this->update(['total_purchase_value' => $total]);
    }
}
