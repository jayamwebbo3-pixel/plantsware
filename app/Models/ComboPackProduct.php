<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComboPackProduct extends Model
{
    protected $fillable = [
        'combo_pack_id',
        'product_ids',
    ];

    protected $casts = [
        'product_ids' => 'array',
    ];

    public function comboPack()
    {
        return $this->belongsTo(ComboPack::class);
    }
}
