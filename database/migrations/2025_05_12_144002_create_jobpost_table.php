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
        Schema::create('jobpost', function (Blueprint $table) {
            $table->increments('JobPostId');
            $table->string('Title');
            $table->text('Category');
            $table->integer('MaximumBudget')->default(1);
            $table->integer('MinimumBudget')->default(0);
            $table->date('DeadLine');
            $table->string('Status', 20)->default('Available');
            $table->text('Attachments')->nullable();
            $table->string('Location');
            $table->text('Description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobpost');
    }
};
