<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('room_user', function (Blueprint $table) {
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
            //$table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('user_id');
            $table->unique(['room_id', 'user_id']);
            $table->foreign('user_id')->references('id')->on('p2_users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('room_user');
    }
}
