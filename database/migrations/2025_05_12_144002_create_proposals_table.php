<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('proposals', function (Blueprint $table) {
            $table->id(); // primary key, auto-increment
            $table->decimal('price', 10, 2)->default(0);    
            $table->string('status_agreed')->default('pending');
            $table->text('description_proposal')->nullable();
            $table->unsignedInteger('tech_id');
            $table->unsignedInteger('jobpost_id');
            //$table->foreign('tech_id')->references('id')->on('jobposts')->onDelete('cascade');
            //$table->foreign('jobpost_id')->references('id')->on('users')->onDelete('cascade'); 
            $table->foreign('tech_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('jobpost_id')->references('jobpost_id')->on('jobposts')->onDelete('cascade');           
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('proposals');
    }
};