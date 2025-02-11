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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            // $table->string('user_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->text('address')->nullable();
            $table->text('store_hours')->nullable();
            $table->text('category')->nullable();
            $table->text('reels')->nullable();
            $table->text('web_link')->nullable();
            $table->text('profile_photo')->nullable();
            $table->text('cover_photo')->nullable();
            $table->tinyInteger('is_verified')->default(0);
            $table->text('likes')->nullable();
            $table->text('dislikes')->nullable();
            $table->string('rating')->default(0);
            $table->tinyInteger('is_selected')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
