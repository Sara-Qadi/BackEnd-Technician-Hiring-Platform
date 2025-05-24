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
        Schema::create('jobposts', function (Blueprint $table) {
            //$table->increments('jobpost_id');
            $table->id('jobpost_id');
            $table->string('title');
            $table->string('category');
            $table->integer('maximum_budget')->default(100);
            $table->integer('minimum_budget')->default(0);
            $table->date('deadline');
            $table->string('status', 20)->default('pending');
            $table->text('attachments')->nullable();
            $table->string('location');
            $table->text('description')->nullable();
            $table->timestamps();
            //$table->unsignedInteger('user_id');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            //$table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobposts');
    }
};
