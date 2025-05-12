<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void{Schema::create('messages', function (Blueprint $table) {
        $table->unsignedBigInteger('ReciverId');
        $table->unsignedBigInteger('SenderId')->index('fk_sender');
        $table->text('MessageContent') ;
        $table->primary(['ReciverId', 'SenderId']);
        $table->foreign('ReciverId')->references('ReciverId')->on('users')->onDelete('cascade');
        $table->foreign('SenderId')->references('SenderId')->on('users')->onDelete('cascade');
    });
}

       public function down(): void{Schema::dropIfExists('messages');}
};