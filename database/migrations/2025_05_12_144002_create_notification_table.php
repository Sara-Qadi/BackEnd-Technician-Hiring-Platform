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
   Schema::create('notification', function (Blueprint $table) {
    $table->increments('NotificationId');
    $table->unsignedInteger('UserId');
    $table->string('ReadStatus', 100);
    $table->string('Type', 50);
    $table->text('Message');

    $table->foreign('UserId')->references('UserId')->on('users')->onDelete('cascade');
});
}
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification');
    }
};
