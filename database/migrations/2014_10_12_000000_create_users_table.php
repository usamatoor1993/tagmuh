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
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('business_name')->nullable();
            $table->string('email')->nullable();
            $table->string('business_email')->nullable();
            $table->string('phone_number')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->text('image')->nullable();
            $table->text('cover_photo')->nullable();
            $table->text('location')->nullable();
            $table->text('business_location')->nullable();
            $table->string('category')->nullable();
            $table->string('card_issue_date')->nullable();
            $table->string('card_expire_date')->nullable();
            $table->string('user_type')->nullable();
            $table->text('description')->nullable();
            $table->text('id_card')->nullable();
            $table->text('business_license')->nullable();
            $table->text('business_model')->nullable();
            $table->string('rating')->nullable();
            $table->text('timings')->nullable(); 
            $table->integer('status')->default(0);
            $table->integer('category_verified')->default(0);
            $table->integer('is_verified')->default(0);
            // $table->text('likes')->nullable();
            // $table->text('dislikes')->nullable();
            
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
