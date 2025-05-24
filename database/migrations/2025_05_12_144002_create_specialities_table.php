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
        Schema::create('specialities', function (Blueprint $table) {
            $table->integer('specialities_id');
            $table->integer('ProfileId')->index('profileid');

            $table->primary(['specialities_id', 'ProfileId']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('specialities');
    }
};
