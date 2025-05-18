<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('receiver_id');
            $table->unsignedBigInteger('sender_id');
            $table->text('message_content');

            $table->foreign('receiver_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('sender_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};