<?php

namespace Modules\Messaging\Services;

use App\Shared\Contracts\MessagingContract;
use App\Shared\DTOs\MessageDTO;
use App\Shared\ValueObjects\ConversationId;
use App\Shared\ValueObjects\UserId;
use Modules\Messaging\Repositories\Contracts\ConversationRepositoryContract;
use Modules\Messaging\Repositories\Contracts\MessageRepositoryContract;

/**
 * Implements the Shared Kernel's MessagingContract.
 *
 * This is what other modules (Presence etc.) get when they
 * inject MessagingContract. They never see this class directly.
 */
class MessagingService implements MessagingContract
{
    public function __construct(
        private readonly ConversationRepositoryContract $conversations,
        private readonly MessageRepositoryContract      $messages,
    ) {}

    public function getConversationsForUser(UserId $userId): array
    {
        $conversations = $this->conversations->findForUser($userId);

        return array_map(
            fn ($c) => $c->id,
            $conversations
        );
    }

    public function getLastMessage(ConversationId $conversationId): ?MessageDTO
    {
        $conversations = $this->conversations->findById($conversationId);

        return $conversations?->lastMessage?->toDTO();
    }
}