<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use TTBooking\SupportChat\Enums\MessageState;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $sqlite = DB::connection()->getDriverName() === 'sqlite';

        Schema::create('chat_messages', function (Blueprint $table) use ($sqlite) {
            $table->nanoid(length: 7)->primary();
            $table->foreignNanoid('room_id', 7)->constrained('chat_rooms')->cascadeOnDelete();
            // $table->foreignId('sent_by')->constrained('users')->cascadeOnDelete();
            $table->unsignedInteger('sent_by')->index();
            // $table->foreignNanoid('reply_to')->nullable()->constrained('chat_messages')->cascadeOnDelete();
            $table->nanoid('reply_to', 7)->nullable()->index();
            $table->text('content')->unless($sqlite)->fulltext();
            $table->json('meta')->nullable();
            $table->enum('state', array_column(MessageState::cases(), 'value'))->default(MessageState::Saved->value)
                ->collation($sqlite ? 'binary' : 'utf8mb4_bin');
            $table->unsignedTinyInteger('flags')->default(0);
            $table->timestamp('created_at', 6)->useCurrent()->index();
            $table->timestamp('updated_at', 6)->useCurrent()->useCurrentOnUpdate();
            $table->softDeletes('deleted_at', 6);
        });

        Schema::table('chat_messages', function (Blueprint $table) {
            $table->foreign('sent_by')->references('id')->on('p2_users')->cascadeOnDelete();
            $table->foreign('reply_to')->references('id')->on('chat_messages')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};
