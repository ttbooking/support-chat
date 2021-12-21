<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
            //$table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedInteger('sender_id');
            $table->foreignId('parent_id')->nullable()->constrained('messages')->cascadeOnDelete();
            $table->unsignedTinyInteger('type')->default(0)->index();
            $table->text('content')->fulltext();
            $table->unsignedTinyInteger('state')->default(0);
            $table->unsignedTinyInteger('flags')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->softDeletes();
            $table->foreign('sender_id')->references('id')->on('p2_users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
