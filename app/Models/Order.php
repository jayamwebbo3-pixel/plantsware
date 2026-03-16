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
        'delivered_at',
        'return_requested_at',
        'return_reason',
        'return_rejection_reason',
        'return_images'
    ];

    protected $casts = [
        'shipping_address' => 'array',
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
}
