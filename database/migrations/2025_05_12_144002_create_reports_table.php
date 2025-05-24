<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

  public function up(): void{Schema::create('report', function (Blueprint $table) {
    $table->increments('ReportId');
    $table->unsignedBigInteger('UserId');
    $table->unsignedBigInteger('jobpost_id');
    $table->text('reason');
    $table->string('ReportType')->nullable();

        $table->foreign('UserId')->references('UserId')->on('users')->onDelete('cascade');
            $table->foreign('jobpost_id')->references('jobpost_id')->on('jobpost')->onDelete('cascade');
        });
    }

  public function down(): void{Schema::dropIfExists('report');}
};
