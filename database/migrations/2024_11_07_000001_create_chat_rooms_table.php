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

        Schema::create('chat_rooms', function (Blueprint $table) use ($userModel) {
            $table->nanoid(length: 7)->primary();
            $table->string('name')->nullable()->index();
            $table->foreignIdFor($userModel, 'created_by')->constrained()->cascadeOnDelete();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_rooms');
    }
};
