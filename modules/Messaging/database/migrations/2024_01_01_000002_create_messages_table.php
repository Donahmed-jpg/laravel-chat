<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')
                ->constrained()
                ->cascadeOnDelete();

            // We store sender_id directly — not a FK to users
            // because the Messaging module does not own the users table.
            // Auth module owns users. Messaging only stores the ID.
            // Cross-module FKs create tight coupling at the DB level.
            $table->unsignedBigInteger('sender_id');

            $table->text('body');

            // Soft deletes — "delete message" hides it, not destroys it
            $table->softDeletes();

            $table->timestamps();

            // Most common query: "give me messages for conversation X
            // ordered by time" — this index makes it fast
            $table->index(['conversation_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};