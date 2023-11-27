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
        Schema::create('favorite', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id') ->references('id')->on('users')->onDelete('cascade');
            $table->integer('medicine_id')->references('id')->on('medicines')->onDelete('cascade');
            $table->boolean('is_favorite')->default(false);
            $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorite');
    }
};
