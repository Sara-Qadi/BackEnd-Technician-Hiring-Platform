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
            $table->integer('ProposalId');
            $table->integer('UserId');
            $table->integer('JobpostId');
            $table->integer('Rating')->nullable()->default(0);
            $table->text('ReviewComment')->nullable();

            $table->primary(['JobpostId', 'UserId']);
            $table->unique(['ProposalId', 'UserId'], 'proposalid');
            $table->unique(['ProposalId', 'JobpostId'], 'proposalid_2');
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
