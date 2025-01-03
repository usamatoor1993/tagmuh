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
        Schema::create('company_ads', function (Blueprint $table) {
            $table->id();
            $table->string('ac_service')->nullable();
            $table->text('images')->nullable();
            $table->string('business_name')->nullable();
            $table->string('business_website')->nullable();
            $table->string('business_location')->nullable();
            $table->string('business_phone_number')->nullable();
            $table->string('business_email')->nullable();
            $table->string('business_description')->nullable();
            // $table->string('companyId')->nullable();
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            // $table->string('userId')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('price')->nullable();
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
        Schema::dropIfExists('company_ads');
    }
};
