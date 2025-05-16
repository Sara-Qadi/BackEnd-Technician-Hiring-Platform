<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

  public function up(): void{Schema::create('report', function (Blueprint $table) {
    $table->increments('ReportId');
    $table->unsignedBigInteger('UserId');
    $table->unsignedBigInteger('JobPostId');
    $table->text('reason');
    $table->string('ReportType')->nullable();

        $table->foreign('UserId')->references('UserId')->on('users')->onDelete('cascade');
            $table->foreign('JobPostId')->references('JobPostId')->on('jobpost')->onDelete('cascade');
        });
    }

  public function down(): void{Schema::dropIfExists('report');}
};
