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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('firstName')->nullable();
            $table->string('lastName')->nullable();
            $table->string('BName')->nullable();
            $table->string('email')->nullable();
            $table->string('Bemail')->nullable();
            $table->string('phoneNumber')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->text('image')->nullable();
            $table->text('coverPhoto')->nullable();
            $table->text('location')->nullable();
            $table->text('BLocation')->nullable();
            $table->string('category')->nullable();
            $table->string('cardIssueDate')->nullable();
            $table->string('cardExpireDate')->nullable();
            $table->string('userType')->nullable();
            $table->string('status')->default(0);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
