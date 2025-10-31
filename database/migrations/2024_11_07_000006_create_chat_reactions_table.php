<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use TTBooking\SupportChat\Models\Message;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $sqlite = DB::connection()->getDriverName() === 'sqlite';

        /** @var class-string<Model> $userModel */
        $userModel = config('support-chat.user_model');

        Schema::create('chat_reactions', function (Blueprint $table) use ($sqlite, $userModel) {
            $table->id();
            $table->foreignNanoid('message_id', (new Message)->nanoidSize())->constrained('chat_messages')->cascadeOnDelete();
            $table->foreignIdFor($userModel, 'user_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('user_id');
            $table->char('emoji', 1)->collation($sqlite ? 'binary' : 'utf8mb4_bin');
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
