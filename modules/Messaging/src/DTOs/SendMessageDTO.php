<?php

namespace Modules\Messaging\DTOs;

use App\Shared\ValueObjects\ConversationId;
use App\Shared\ValueObjects\UserId;

final class SendMessageDTO
{
    public function __construct(
        public readonly ConversationId $conversationId,
        public readonly UserId         $senderId,
        public readonly string         $body,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            conversationId: new ConversationId($data['conversation_id']),
            senderId:       new UserId($data['sender_id']),
            body:           trim($data['body']),
        );
    }
}