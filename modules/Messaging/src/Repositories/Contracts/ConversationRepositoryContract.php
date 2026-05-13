<?php

namespace Modules\Messaging\Repositories\Contracts;

use App\Shared\ValueObjects\ConversationId;
use App\Shared\ValueObjects\UserId;
use Modules\Messaging\DTOs\ConversationDTO;
use Modules\Messaging\Models\Conversation;

interface ConversationRepositoryContract
{
    // Why does findById still return a Conversation model?
    // Because Actions need to call methods on the model 
    // like hasParticipant() and touch(). 
    // These are behavioural operations — 
    // they belong on the entity. Once we need to pass 
    // conversation data upward to the controller 
    // or across layers, we call toDTO() at that point. 
    // The model stays inside 
    // the domain layer. The DTO is what leaves it.

    public function findById(ConversationId $id): ?Conversation;

    /**
     * Find an existing direct conversation between exactly two users.
     * Returns null if none exists — caller decides whether to create one.
     */
    public function findDirectBetween(
        UserId $userA,
        UserId $userB
    ): ?Conversation;

    /**
     * Returns DTOs — not raw models.
     * The application layer never sees Eloquent here.
     *
     * @return ConversationDTO[]
     */
    public function findForUser(UserId $userId): array;

    public function create(string $type, ?string $name = null): Conversation;

    public function addParticipant(
        ConversationId $id,
        UserId $userId
    ): void;
}