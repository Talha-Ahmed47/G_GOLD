<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoldPrice extends Model
{
    protected $fillable = [
        'price_per_gram',
        'currency',
        'fetched_at',
    ];

    protected $casts = [
        'price_per_gram' => 'decimal:4',
        'fetched_at' => 'datetime',
    ];
}
