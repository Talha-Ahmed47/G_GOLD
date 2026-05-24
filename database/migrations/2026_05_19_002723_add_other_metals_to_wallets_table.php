<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('wallets', function (Blueprint $table) {
            $table->decimal('silver_balance', 15, 4)->default(0)->after('gold_balance');
            $table->decimal('platinum_balance', 15, 4)->default(0)->after('silver_balance');
            $table->decimal('palladium_balance', 15, 4)->default(0)->after('platinum_balance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wallets', function (Blueprint $table) {
            $table->dropColumn(['silver_balance', 'platinum_balance', 'palladium_balance']);
        });
    }
};
