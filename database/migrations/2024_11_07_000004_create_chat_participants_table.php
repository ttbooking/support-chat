<?php

use Illuminate\Database\Eloquent\Model;
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
        /** @var class-string<Model> $userModel */
        $userModel = config('support-chat.user_model');

        Schema::create('chat_participants', function (Blueprint $table) use ($userModel) {
            $table->foreignNanoid('room_id', 7)->constrained('chat_rooms')->cascadeOnDelete();
            $table->foreignIdFor($userModel, 'user_id')->constrained()->cascadeOnDelete();
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
