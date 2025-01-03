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
            $table->string('product_name')->nullable();
            $table->string('total_product')->nullable();
            $table->bigInteger('price')->nullable();
            $table->text('description')->nullable();
            // $table->string('companyAdId')->nullable();
            $table->unsignedBigInteger('company_ad_id');
            $table->foreign('company_ad_id')->references('id')->on('company_ads')->onDelete('cascade');
            // $table->string('companyId')->nullable();
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            // $table->string('userId')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
