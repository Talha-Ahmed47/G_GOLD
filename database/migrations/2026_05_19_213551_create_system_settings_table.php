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
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Seed default values immediately
        \App\Models\SystemSetting::create([
            'key' => 'shop_location',
            'value' => 'Aurum Gold Store, 123 Luxury Lane, Midtown Manhattan, NY'
        ]);

        \App\Models\SystemSetting::create([
            'key' => 'storage_limit_kg',
            'value' => '5.0'
        ]);

        \App\Models\SystemSetting::create([
            'key' => 'extra_storage_price_per_kg',
            'value' => '15.00'
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
