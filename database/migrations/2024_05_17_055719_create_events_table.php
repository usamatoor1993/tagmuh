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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('image')->nullable();
            $table->text('description')->nullable();
            $table->time('time')->nullable();
            $table->date('date')->nullable();
            $table->string('event_by')->nullable();
            $table->text('interested')->nullable();
            $table->text('going')->nullable();
            $table->string('email')->nullable();
            $table->string('ticket')->nullable();
            $table->text('location')->nullable();
            // $table->string('userid')->nullable();
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
        Schema::dropIfExists('events');
    }
};
