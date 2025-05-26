<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

  public function up(): void{Schema::create('report', function (Blueprint $table) {

    $table->increments('report_id');
    $table->unsignedBigInteger('user_id');
    $table->unsignedBigInteger('jobpost_id')->nullable();
    $table->text('reason');
    $table->string('report_type')->nullable();
    $table->timestamps();
    $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
    $table->foreign('jobpost_id')->references('jobpost_id')->on('jobposts')->onDelete('cascade');
  });
  }

  

  public function down(): void{Schema::dropIfExists('report');}
};
