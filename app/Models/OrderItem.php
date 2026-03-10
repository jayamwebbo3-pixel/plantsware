<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'product_id', 'product_name', 'product_image', 'price', 'quantity', 'total', 'options'];
    protected $fillable = ['order_id', 'product_id', 'combo_pack_id', 'product_name', 'product_image', 'price', 'quantity', 'total'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function comboPack()
    {
        return $this->belongsTo(ComboPack::class);
    }
}
