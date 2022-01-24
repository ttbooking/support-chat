<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessageFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id')->constrained()->cascadeOnDelete();
            $table->string('name')->index();
            $table->string('type')->index();
            $table->unsignedBigInteger('size');
            $table->boolean('audio')->default(false);
            $table->unsignedFloat('duration')->nullable();
            $table->string('url')->nullable();
            $table->binary('preview')->nullable();
            $table->unique(['message_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('message_files');
    }
}
