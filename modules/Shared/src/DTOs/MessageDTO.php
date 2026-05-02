<?php

namespace App\Shared\DTOs;

use App\Shared\ValueObjects\UserId;
use App\Shared\ValueObjects\ConversationId;

final class MessageDTO
{
    public function __construct(
        public readonly int $id,
        public readonly ConversationId $conversationId,
        public readonly UserId $senderId,
        public readonly string $body,
        public readonly \DateTimeImmutable $sentAt,
    ) {}

    public static function from(array $data): self
    {
        return new self(
            id:             $data['id'],
            conversationId: new ConversationId($data['conversation_id']),
            senderId:       new UserId($data['sender_id']),
            body:           $data['body'],
            sentAt:         new \DateTimeImmutable($data['sent_at'] ?? 'now'),
        );
    }

    public function toArray(): array
    {
        return [
            'id'              => $this->id,
            'conversation_id' => $this->conversationId->value(),
            'sender_id'       => $this->senderId->value(),
            'body'            => $this->body,
            'sent_at'         => $this->sentAt->format(\DateTimeInterface::ATOM),
        ];
    }
}