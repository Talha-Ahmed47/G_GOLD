<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminMaterial extends Model
{
    protected $fillable = [
        'metal',
        'buy_price',
        'amount',
    ];
}
