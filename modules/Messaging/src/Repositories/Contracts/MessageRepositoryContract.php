<?php

namespace Modules\Messaging\Repositories\Contracts;

use App\Shared\ValueObjects\ConversationId;
use Modules\Messaging\Models\Message;

interface MessageRepositoryContract
{
    /**
     * @return Message[]
     */
    public function findForConversation(
        ConversationId $conversationId,
        int $limit = 50,
        ?int $beforeId = null,  // for pagination — "messages before ID X"
    ): array;

    public function create(
        ConversationId $conversationId,
        int $senderId,
        string $body,
    ): Message;

    public function findById(int $id): ?Message;
}