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
        Schema::create('chat_room_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name', 32)->index();
            $table->string('type', 32)->default('')->index();
            $table->timestamp('created_at')->useCurrent();
            $table->unique(['name', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_room_tags');
    }
};
