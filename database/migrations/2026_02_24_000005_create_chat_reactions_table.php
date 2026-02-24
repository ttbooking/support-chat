<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use TTBooking\SupportChat\Models\Message;
use TTBooking\SupportChat\SupportChat;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $sqlite = DB::connection()->getDriverName() === 'sqlite';

        Schema::create('chat_reactions', function (Blueprint $table) use ($sqlite) {
            $table->id();
            $table->foreignNanoid('message_id', (new Message)->nanoidSize())->constrained('chat_messages')->cascadeOnDelete();
            $table->foreignIdFor(SupportChat::userModel(), 'user_id')->constrained()->cascadeOnDelete();
            $table->char('emoji', 10)->collation($sqlite ? 'binary' : 'utf8mb4_bin');
            $table->timestamp('created_at')->useCurrent();
            $table->index(['message_id', 'user_id']);
            $table->unique(['message_id', 'user_id', 'emoji']);
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
