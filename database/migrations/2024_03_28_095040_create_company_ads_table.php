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
            $table->string('acService')->nullable();
            $table->text('images')->nullable();
            $table->string('businessName')->nullable();
            $table->string('businessWebsite')->nullable();
            $table->string('businessLocation')->nullable();
            $table->string('businessPhoneNumber')->nullable();
            $table->string('businessEmail')->nullable();
            $table->string('businessDescription')->nullable();
            $table->string('companyId')->nullable();
            $table->string('userId')->nullable();
            $table->string('status')->nullable();
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
