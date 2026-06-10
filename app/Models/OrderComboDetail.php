<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderComboDetail extends Model
{
    protected $table = 'order_combo_details';
    
    protected $fillable = [
        'order_id',
        'custom_combo_id',
        'product_total',
        'discount_percentage',
        'discount_amount',
        'discounted_total',
        'gst_amount',
        'shipping_amount',
        'final_amount'
    ];
    
    protected $casts = [
        'product_total' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'discounted_total' => 'decimal:2',
        'gst_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'final_amount' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
