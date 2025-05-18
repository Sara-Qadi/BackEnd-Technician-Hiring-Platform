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
            $table->text('description_proposal')->nullable();
            $table->unsignedInteger('submission_id')->nullable();
            $table->foreign('submission_id')->references('id')->on('submissions')->onDelete('cascade');
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('proposals');
    }
};