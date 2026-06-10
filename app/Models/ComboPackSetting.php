<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComboPackSetting extends Model
{
    protected $table = 'combo_pack_settings';
    
    protected $fillable = ['is_enabled', 'max_products'];
    
    protected $casts = [
        'is_enabled' => 'boolean',
        'max_products' => 'integer',
    ];
}
