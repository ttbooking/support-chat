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
        Schema::create('chat_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignNanoid('message_id', 7)->constrained('chat_messages')->cascadeOnDelete();
            $table->string('name')->index();
            $table->string('type')->nullable()->index();
            $table->unsignedBigInteger('size');
            $table->boolean('audio')->default(false);
            $table->float('duration')->nullable();
            $table->string('url')->nullable();
            $table->binary('preview')->nullable();
            $table->unique(['message_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_attachments');
    }
};
