<?php

namespace App\Shared\Contracts;

use App\Shared\DTOs\MessageDTO;
use App\Shared\ValueObjects\UserId;
use App\Shared\ValueObjects\ConversationId;

/**
 * What the Messaging module exposes to the rest of the application.
 * Presence module uses this to know which conversations a user is in.
 */
interface MessagingContract
{
    /**
     * Get all conversation IDs for a given user.
     *
     * @return ConversationId[]
     */
    public function getConversationsForUser(UserId $userId): array;

    /**
     * Get the last message in a conversation.
     */
    public function getLastMessage(ConversationId $conversationId): ?MessageDTO;
}