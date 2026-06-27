<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'coupon_code',
        'discount_type',
        'discount_value',
        'max_discount',
        'minimum_order_amount',
        'valid_from',
        'valid_to',
        'is_public',
        'status',
    ];

    protected $casts = [
        'valid_from' => 'date',
        'valid_to' => 'date',
        'is_public' => 'boolean',
        'status' => 'boolean',
        'discount_value' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'minimum_order_amount' => 'decimal:2',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'coupon_users', 'coupon_id', 'user_id');
    }

    public function usages()
    {
        return $this->hasMany(CouponUsage::class);
    }

    public function isValidForCart($orderAmount)
    {
        if ($this->minimum_order_amount && $orderAmount < $this->minimum_order_amount) {
            return false;
        }
        return true;
    }

    public function calculateDiscount($orderAmount)
    {
        if ($this->discount_type === 'percentage') {
            $discount = ($orderAmount * $this->discount_value) / 100;
            if ($this->max_discount && $discount > $this->max_discount) {
                return (float) $this->max_discount;
            }
            return (float) $discount;
        }
        return (float) min($this->discount_value, $orderAmount);
    }

    public function isValidForUser($user)
    {
        if (!$user) {
            return false;
        }
        if ($this->is_public) {
            return $user->created_at >= now()->subMonths(5)->startOfMonth();
        }
        return $this->users()->where('users.id', $user->id)->exists();
    }
}
