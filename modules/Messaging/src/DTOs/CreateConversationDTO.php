<?php

namespace Modules\Messaging\DTOs;

use App\Shared\ValueObjects\UserId;

final class CreateConversationDTO
{
    public function __construct(
        public readonly UserId $initiatorId,
        public readonly UserId $participantId,   // for direct chat
        public readonly string $type = 'direct',
        public readonly ?string $name = null,    // for group chat later
    ) {}

    public static function forDirect(UserId $initiator, UserId $participant): self
    {
        return new self(
            initiatorId:   $initiator,
            participantId: $participant,
            type:          'direct',
        );
    }
}