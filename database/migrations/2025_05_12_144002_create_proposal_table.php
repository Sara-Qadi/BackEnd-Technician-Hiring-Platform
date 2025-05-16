<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('proposals', function (Blueprint $table) {
            $table->increments('proposal_id'); // primary key, auto-increment
            $table->integer('price')->default(0);
            $table->text('description_proposal')->nullable();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('proposals');
    }
};