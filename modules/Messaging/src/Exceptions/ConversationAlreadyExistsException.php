<?php

namespace Modules\Messaging\Exceptions;

use App\Shared\Exceptions\DomainException;

final class ConversationAlreadyExistsException extends DomainException
{
    public static function for(int $conversationId): self
    {
        return new self(
            message: "A conversation already exists with ID [{$conversationId}].",
            context: ['conversation_id' => $conversationId],
        );
    }
}