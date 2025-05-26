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
        Schema::create('appliesfor', function (Blueprint $table) {
            $table->integer('proposal_id');
            $table->integer('UserId');
            $table->integer('JobpostId');
    

            $table->primary(['JobpostId', 'UserId']);
            $table->unique(['proposal_id', 'UserId'], 'proposal_id');
            $table->unique(['proposal_id', 'JobpostId'], 'proposalid_2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appliesfor');
    }
};
