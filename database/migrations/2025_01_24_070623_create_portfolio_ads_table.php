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
        Schema::create('portfolio_ads', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('portfolio_id');
            $table->foreign('portfolio_id')->references('id')->on('portfolios')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->text('images')->nullable();
            $table->text('description')->nullable();
            $table->string('price')->nullable();
            $table->string('total_quantity')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portfolio_ads');
    }
};
