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
            $table->increments('review_id');
            $table->timestamps();
            $table->tinyInteger('rating')->default(0);
            $table->text('review_comment')->nullable();
            $table->unsignedBigInteger('review_by');
            $table->unsignedBigInteger('review_to');
            $table->unsignedBigInteger('jobpost_id');
            $table->foreign('review_by')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('review_to')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('jobpost_id')->references('jobpost_id')->on('jobposts')->onDelete('cascade');
            $table->unique(['review_by', 'review_to', 'jobpost_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['review_by']);
            $table->dropForeign(['review_to']);
            $table->dropForeign(['jobpost_id']);
            $table->dropUnique(['review_by', 'review_to', 'jobpost_id']);
        });

        Schema::dropIfExists('reviews');
    }
};
