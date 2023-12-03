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
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('scientific_name')->unique();
            $table->string('trade_name')->unique();
            $table->string('company_name');
            $table->string('photo')->nullable();
            $table->string('categories_name')->references('name')->on('categories')->onDelete('cascade');
            $table->string('form')->nullable();
            $table->integer('quantity');
            $table->integer('price');
            $table->date('expiration_at'); // we did it as time to add it as date
            $table->longText('details')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
