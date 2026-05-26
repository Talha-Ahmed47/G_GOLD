<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('admin_materials', function (Blueprint $table) {
            $table->id();
            $table->string('metal');
            $table->decimal('amount', 15, 4)->default(0);
            $table->decimal('buy_price', 15, 4)->default(0); // price per gram from API
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('admin_materials');
    }
};
