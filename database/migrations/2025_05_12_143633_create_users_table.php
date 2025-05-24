<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('user_id');

            $table->string('user_name', 50);
            $table->string('email', 191)->unique();
            $table->string('password', 191);
            $table->string('phone', 25);
            $table->string('country', 50);
            $table->timestamps(); // to add 2 columns: created_at, updated_at
            $table->unsignedInteger('role_id');
           
            $table->foreign('role_id')->references('role_id')->on('roles')->onDelete('cascade');

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
