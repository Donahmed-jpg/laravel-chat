<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();

            // Type allows us to extend to group chat later
            // without a schema change — just a new type value
            $table->enum('type', ['direct', 'group'])->default('direct');

            // Human readable name — null for direct (we derive it
            // from participant names), required for group
            $table->string('name')->nullable();

            $table->timestamps();
        });

        // Pivot table — who is in which conversation
        // Separated from conversations so we can support
        // group chat without changing the conversations table
        Schema::create('conversation_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')
                ->constrained()
                ->cascadeOnDelete();
            
            // No FK constraint — Messaging does not own users
            // Integrity enforced at application level via AuthContract
            // in the CreateConversation action
            $table->unsignedBigInteger('user_id');


            // When this participant last read the conversation
            // Used for unread message counts later
            $table->timestamp('last_read_at')->nullable();

            $table->timestamps();

            // A user can only be in a conversation once
            $table->unique(['conversation_id', 'user_id']);
            // Index for the common query: "all conversations for user X"
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversation_participants');
        Schema::dropIfExists('conversations');
    }
};