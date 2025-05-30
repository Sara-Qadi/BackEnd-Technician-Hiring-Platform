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
  Schema::create('profiles', function (Blueprint $table) {
    $table->unsignedBigInteger('user_id')->primary();
$table->string('photo')->nullable();

   $table->string('cv')->nullable();

    $table->integer('rating')->default(0);


    $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');

});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
