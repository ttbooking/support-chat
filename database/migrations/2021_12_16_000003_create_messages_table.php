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
            $table->nanoid()->primary();
            $table->foreignNanoid('room_id')->constrained()->cascadeOnDelete();
            //$table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedInteger('sender_id')->index();
            //$table->foreignNanoid('parent_id')->nullable()->constrained('messages')->cascadeOnDelete();
            $table->nanoid('parent_id')->nullable()->index();
            $table->unsignedTinyInteger('type')->default(0)->index();
            $table->text('content')->fulltext();
            $table->unsignedTinyInteger('state')->default(0);
            $table->unsignedTinyInteger('flags')->default(0);
            $table->timestamp('created_at', 6)->useCurrent()->index();
            $table->timestamp('updated_at', 6)->useCurrent()->useCurrentOnUpdate();
            $table->softDeletes('deleted_at', 6);
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->foreign('sender_id')->references('id')->on('p2_users')->cascadeOnDelete();
            $table->foreign('parent_id')->references('id')->on('messages')->cascadeOnDelete();
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
