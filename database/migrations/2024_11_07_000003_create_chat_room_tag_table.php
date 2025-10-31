<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use TTBooking\SupportChat\Models\Room;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chat_room_tag', function (Blueprint $table) {
            $table->foreignNanoid('room_id', (new Room)->nanoidSize())->constrained('chat_rooms')->cascadeOnDelete();
            $table->string('tag_name', 32);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->primary(['room_id', 'tag_name']);
            $table->foreign('tag_name')->references('name')->on('chat_room_tags')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_room_tag');
    }
};
