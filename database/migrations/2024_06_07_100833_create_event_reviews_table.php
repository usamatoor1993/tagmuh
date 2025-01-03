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
        Schema::create('event_reviews', function (Blueprint $table) {
            $table->id();
            $table->string('stars')->default(0);
            // $table->string('eventId')->nullable();
            $table->unsignedBigInteger('event_id');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
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
        Schema::dropIfExists('event_reviews');
    }
};
