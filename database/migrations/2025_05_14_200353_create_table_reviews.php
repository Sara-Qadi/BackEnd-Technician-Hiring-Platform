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
        Schema::create('reviews', function (Blueprint $table) {
            $table->increments('ReviewId');

            $table->unsignedInteger('ApplicationId'); // refers to appliesfor.appid
            $table->unsignedInteger('ReviewerId');    // user who wrote the review

            $table->tinyInteger('Rating')->default(0); // from 1 to 5
            $table->text('ReviewComment')->nullable();

            $table->timestamps();

            // Foreign keys
            $table->foreign('ApplicationId')->references('AppId')->on('appliesfor')->onDelete('cascade');
            $table->foreign('ReviewerId')->references('UserId')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};