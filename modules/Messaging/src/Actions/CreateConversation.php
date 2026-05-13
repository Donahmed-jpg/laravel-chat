<?php

namespace Modules\Messaging\Actions;

use App\Shared\Contracts\AuthContract;
use App\Shared\Exceptions\NotFoundException;
use App\Shared\ValueObjects\ConversationId;
use Modules\Messaging\DTOs\ConversationDTO;
use Modules\Messaging\DTOs\CreateConversationDTO;
use Modules\Messaging\Exceptions\ConversationAlreadyExistsException;
use Modules\Messaging\Repositories\Contracts\ConversationRepositoryContract;

class CreateConversation
{
    public function __construct(
        private readonly ConversationRepositoryContract $conversations,
        private readonly AuthContract                   $auth,
    ) {}

    public function execute(CreateConversationDTO $dto): ConversationDTO
    //                                             ↑ returns DTO not model
    {
        // Verify both users exist via AuthContract
        if (! $this->auth->userExists($dto->initiatorId)) {
            throw NotFoundException::for('User', $dto->initiatorId->value());
        }

        if (! $this->auth->userExists($dto->participantId)) {
            throw NotFoundException::for('User', $dto->participantId->value());
        }

        // Business rule: cannot start a conversation with yourself
        if ($dto->initiatorId->equals($dto->participantId)) {
            throw new \InvalidArgumentException(
                'Cannot create a conversation with yourself.'
            );
        }

        // Business rule: direct conversation must not already exist
        if ($dto->type === 'direct') {
            $existing = $this->conversations->findDirectBetween(
                $dto->initiatorId,
                $dto->participantId,
            );

            if ($existing !== null) {
                throw ConversationAlreadyExistsException::for($existing->id);
            }
        }

        // Create and persist — model stays inside this layer
        $conversation = $this->conversations->create($dto->type, $dto->name);

        $conversationId = new ConversationId($conversation->id);

        $this->conversations->addParticipant($conversationId, $dto->initiatorId);
        $this->conversations->addParticipant($conversationId, $dto->participantId);

        // Convert to DTO before returning upward — model never leaves this layer
        return $conversation->toDTO();
        //                   ↑
        //                   domain layer converts itself to a DTO
        //                   controller receives pure data, not Eloquent
    }
}