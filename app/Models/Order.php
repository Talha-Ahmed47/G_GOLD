<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'metal',
        'type',
        'quantity',
        'price_per_unit',
        'total_price',
        'status',
    ];

    protected $casts = [
        'type' => \App\Enums\OrderType::class,
        'status' => \App\Enums\OrderStatus::class,
        'quantity' => 'decimal:4',
        'price_per_unit' => 'decimal:4',
        'total_price' => 'decimal:4',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
