<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'wallet_id',
        'type',
        'amount',
        'currency',
        'description',
    ];

    protected $casts = [
        'type' => \App\Enums\TransactionType::class,
        'amount' => 'decimal:4',
    ];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}
