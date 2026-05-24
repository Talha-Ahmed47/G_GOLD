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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('metal_type'); // gold, silver, platinum, palladium
            $table->decimal('weight_oz', 8, 4);
            $table->decimal('purity', 8, 4)->default(0.9999);
            $table->decimal('premium_percentage', 5, 2)->default(5.00); // 5% premium over spot
            $table->string('image_url');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
