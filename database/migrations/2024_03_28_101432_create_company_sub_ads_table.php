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
        Schema::create('company_sub_ads', function (Blueprint $table) {
            $table->id();
            $table->text('images')->nullable();
            $table->string('productName')->nullable();
            $table->string('totalProduct')->nullable();
            $table->bigInteger('price')->nullable();
            $table->text('description')->nullable();
            $table->string('companyAdId')->nullable();
            $table->string('companyId')->nullable();
            $table->string('userId')->nullable();
            $table->string('status')->default(0);
            $table->string('rating')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_sub_ads');
    }
};
