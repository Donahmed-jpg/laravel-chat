<?php

namespace Modules\Messaging\Repositories;

use App\Shared\ValueObjects\ConversationId;
use Modules\Messaging\Models\Message;
use Modules\Messaging\Repositories\Contracts\MessageRepositoryContract;

class EloquentMessageRepository implements MessageRepositoryContract
{
    public function findForConversation(
        ConversationId $conversationId,
        int $limit = 50,
        ?int $beforeId = null,
    ): array {
        $query = Message::where('conversation_id', $conversationId->value())
            ->orderByDesc('created_at')
            ->limit($limit);

        if ($beforeId !== null) {
            $query->where('id', '<', $beforeId);
        }

        // Reverse so messages appear oldest → newest in the UI
        return $query->get()->reverse()->values()->all();
    }

    public function create(
        ConversationId $conversationId,
        int $senderId,
        string $body,
    ): Message {
        return Message::create([
            'conversation_id' => $conversationId->value(),
            'sender_id'       => $senderId,
            'body'            => $body,
        ]);
    }

    public function findById(int $id): ?Message
    {
        return Message::find($id);
    }
}