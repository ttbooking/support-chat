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
        Schema::create('messages', function (Blueprint $table) {
            $table->char('id', 21)->primary();
            $table->foreignUlid('room_id', 21)->constrained()->cascadeOnDelete();
            //$table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedInteger('sender_id');
            $table->foreignUlid('parent_id', 21)->nullable()->constrained('messages')->cascadeOnDelete();
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
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
