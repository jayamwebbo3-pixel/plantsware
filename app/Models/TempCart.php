<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempCart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'product_id',
        'combo_pack_id',
        'quantity',
        'status',
        'options',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function comboPack()
    {
        return $this->belongsTo(ComboPack::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
