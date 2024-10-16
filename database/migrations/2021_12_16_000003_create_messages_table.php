<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use TTBooking\SupportChat\Enums\MessageState;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->nanoid(length: 7)->primary();
            $table->foreignNanoid('room_id', 7)->constrained()->cascadeOnDelete();
            //$table->foreignId('sent_by')->constrained('users')->cascadeOnDelete();
            $table->unsignedInteger('sent_by')->index();
            //$table->foreignNanoid('reply_to')->nullable()->constrained('messages')->cascadeOnDelete();
            $table->nanoid('reply_to', 7)->nullable()->index();
            $table->text('content')->fulltext();
            $table->json('meta')->nullable();
            $table->enum('state', array_column(MessageState::cases(), 'value'))->default(MessageState::Saved->value)
                ->collation('utf8mb4_bin');
            $table->unsignedTinyInteger('flags')->default(0);
            $table->timestamp('created_at', 6)->useCurrent()->index();
            $table->timestamp('updated_at', 6)->useCurrent()->useCurrentOnUpdate();
            $table->softDeletes('deleted_at', 6);
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->foreign('sent_by')->references('id')->on('p2_users')->cascadeOnDelete();
            $table->foreign('reply_to')->references('id')->on('messages')->cascadeOnDelete();
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
