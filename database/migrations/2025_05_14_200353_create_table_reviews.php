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
            $table->tinyInteger('rating')->default(0); // from 1 to 5
            $table->text('review_comment')->nullable();
            $table->text('riview_to')->nullable();
            $table->text('riview_by')->nullable();


            
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