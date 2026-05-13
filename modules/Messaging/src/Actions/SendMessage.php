<?php

namespace Modules\Messaging\Actions;

use App\Shared\DTOs\MessageDTO;
use App\Shared\Exceptions\NotFoundException;
use App\Shared\Exceptions\UnauthorizedException;
// use App\Shared\ValueObjects\ConversationId;
use Modules\Messaging\DTOs\SendMessageDTO;
use Modules\Messaging\Repositories\Contracts\ConversationRepositoryContract;
use Modules\Messaging\Repositories\Contracts\MessageRepositoryContract;

class SendMessage
{
    public function __construct(
        private readonly ConversationRepositoryContract $conversations,
        private readonly MessageRepositoryContract      $messages,
    ) {}

    /**
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    public function execute(SendMessageDTO $dto): MessageDTO
    {
        // Verify the conversation exists
        $conversation = $this->conversations->findById($dto->conversationId);

        if ($conversation === null) {
            throw NotFoundException::for(
                'Conversation',
                $dto->conversationId->value()
            );
        }

        // Business rule: only participants can send messages
        if (! $conversation->hasParticipant($dto->senderId)) {
            throw UnauthorizedException::for('send message to this conversation');
        }

        // Business rule: empty messages are not allowed
        if (trim($dto->body) === '') {
            throw new \InvalidArgumentException('Message body cannot be empty.');
        }

        $message = $this->messages->create(
            conversationId: $dto->conversationId,
            senderId:       $dto->senderId->value(),
            body:           $dto->body,
        );

        // Touch the conversation's updated_at so it
        // sorts to the top of the conversations list
        $conversation->touch();

        // Return a DTO — never the raw Eloquent model
        return $message->toDTO();
    }
}