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
        Schema::create('company_ad_reviews', function (Blueprint $table) {
            $table->id();
            $table->string('stars')->default(0);
            // $table->string('companyAdId')->nullable();
            $table->unsignedBigInteger('company_ad_id');
            $table->foreign('company_ad_id')->references('id')->on('company_ads')->onDelete('cascade');
            // $table->string('userId')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->longText('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_ad_reviews');
    }
};
