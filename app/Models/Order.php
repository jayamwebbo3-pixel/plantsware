<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'shipping_address',
        'subtotal',
        'shipping',
        'tax',
        'total',
        'status',
        'payment_status',
        'payment_method',
        'shipped_at',
        'delivered_at',
        'return_requested_at',
        'return_reason',
        'return_rejection_reason',
        'return_images'
    ];

    protected $casts = [
        'shipping_address' => 'array',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'return_requested_at' => 'datetime',
        'return_images' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusBadgeClass()
    {
        return [
            'pending' => 'bg-warning text-dark',
            'confirmed' => 'bg-info text-dark',
            'processing' => 'bg-info text-dark',
            'shipped' => 'bg-primary',
            'delivered' => 'bg-success',
            'cancelled' => 'bg-danger',
            'returned' => 'bg-dark',
            'return_requested' => 'bg-warning text-dark',
            'return_rejected' => 'bg-danger',
            'completed' => 'bg-success',
        ][$this->status] ?? 'bg-secondary';
    }

    /**
     * Automatically update orders with 'shipped' status to 'delivered' after 2 days.
     */
    public static function autoUpdateShippedToDelivered()
    {
        return self::where('status', 'shipped')
            ->where(function($q) {
                $q->where('shipped_at', '<=', now()->subDays(2))
                  ->orWhere(function($sq) {
                      $sq->whereNull('shipped_at')
                        ->where('updated_at', '<=', now()->subDays(2));
                  });
            })
            ->update([
                'status' => 'delivered',
                'delivered_at' => now(),
                'updated_at' => now(),
            ]);
    }
}
