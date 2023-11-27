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
        Schema::create('medicine_order', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('medicine_id')->references('id')->on('medicines')->onDelete('cascade');
            $table->integer('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->integer('quantity'); //edited
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicine_order');
    }
};
