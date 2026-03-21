<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'door_number',
        'street',
        'district',
        'state',
        'city',
        'post_code',
        'phone_number',
        'alternative_number',
        'is_default',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
