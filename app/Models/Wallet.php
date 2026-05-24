<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = [
        'user_id',
        'balance',
        'gold_balance',
        'silver_balance',
        'platinum_balance',
        'palladium_balance',
    ];

    protected $casts = [
        'balance' => 'decimal:4',
        'gold_balance' => 'decimal:4',
        'silver_balance' => 'decimal:4',
        'platinum_balance' => 'decimal:4',
        'palladium_balance' => 'decimal:4',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
