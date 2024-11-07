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
        Schema::create('chat_reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignNanoid('message_id', 7)->constrained('chat_messages')->cascadeOnDelete();
            //$table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('user_id');
            $table->char('emoji', 1)->collation('utf8mb4_bin');
            $table->timestamp('created_at')->useCurrent();
            $table->index(['message_id', 'user_id']);
            $table->unique(['message_id', 'user_id', 'emoji']);
            $table->foreign('user_id')->references('id')->on('p2_users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_reactions');
    }
};
