<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessageReactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_reactions', function (Blueprint $table) {
            $table->foreignId('message_id')->constrained()->cascadeOnDelete();
            //$table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('user_id');
            $table->char('emoji', 1);
            $table->index(['message_id', 'user_id']);
            $table->unique(['message_id', 'user_id', 'emoji']);
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
        Schema::dropIfExists('message_reactions');
    }
}
