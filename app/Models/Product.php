<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'metal_type',
        'weight_oz',
        'purity',
        'premium_percentage',
        'image_url',
    ];

    protected $casts = [
        'weight_oz' => 'decimal:4',
        'purity' => 'decimal:4',
        'premium_percentage' => 'decimal:2',
    ];
}
