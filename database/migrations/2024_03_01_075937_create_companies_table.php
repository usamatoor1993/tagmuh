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
            $table->string('user_id')->nullable();
            $table->string('name')->unique()->nullable();
            $table->string('email')->unique()->nullable();
            $table->text('address')->nullable();
            $table->text('store_hours')->nullable();
            $table->text('category')->nullable();
            $table->text('reels')->nullable();
            $table->text('webLink')->nullable();
            $table->text('profilePhoto')->nullable();
            $table->text('coverPhoto')->nullable();
            $table->text('isVerified')->default(0);
            $table->text('likes')->nullable();
            $table->text('dislikes')->nullable();
            $table->string('rating')->default(0);
            $table->string('isSelected')->default(0);

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
