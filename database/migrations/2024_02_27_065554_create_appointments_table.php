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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('companyId')->nullable();
            $table->string('userId')->nullable();
            $table->string('serviceId')->nullable();
            $table->string('userServiceId')->nullable();
            $table->string('service')->nullable();
            $table->string('tokenAmount')->nullable();
            $table->string('remaingAmount')->nullable();
            $table->string('phoneNumber')->nullable();
            $table->string('serviceType')->nullable();
            $table->string('catId')->nullable();
            $table->string('durationStart')->nullable();
            $table->string('durationEnd')->nullable();
            $table->string('canceledBy')->nullable();
            $table->string('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
