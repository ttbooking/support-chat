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
        Schema::create('room_user', function (Blueprint $table) {
            $table->foreignNanoid('room_id')->constrained()->cascadeOnDelete();
            //$table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('user_id');
            $table->unique(['room_id', 'user_id']);
            $table->foreign('user_id')->references('id')->on('p2_users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_user');
    }
};
