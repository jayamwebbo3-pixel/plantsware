<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = ['title', 'slug', 'content', 'image', 'extra_content', 'policy_date'];

    protected $casts = [
        'extra_content' => 'array',
        'policy_date' => 'date',
    ];
}
