<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use TTBooking\SupportChat\Models\Room;
use TTBooking\SupportChat\SupportChat;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chat_participants', function (Blueprint $table) {
            $table->foreignNanoid('room_id', (new Room)->nanoidSize())->constrained('chat_rooms')->cascadeOnDelete();
            $table->foreignIdFor(SupportChat::userModel(), 'user_id')->constrained()->cascadeOnDelete();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->primary(['room_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_participants');
    }
};
