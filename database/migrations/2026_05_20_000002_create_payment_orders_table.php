<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tracks pending/completed gateway payment intents.
 * Prevents double-crediting on duplicate callbacks and provides a full audit trail.
 * Separate from `transactions` which records confirmed wallet movements.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Gateway identifier: 'jazzcash' | 'stripe'
            $table->string('gateway', 20)->index();

            // The reference number we generate (pp_TxnRefNo / Stripe PaymentIntent ID)
            $table->string('reference')->unique();

            // Amount in the app's currency (PKR for JazzCash, USD for Stripe)
            $table->decimal('amount', 15, 2);
            $table->string('currency', 10)->default('PKR');

            // Status lifecycle: pending → completed | failed
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending')->index();

            // Raw gateway response payload for debugging
            $table->json('gateway_response')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_orders');
    }
};
