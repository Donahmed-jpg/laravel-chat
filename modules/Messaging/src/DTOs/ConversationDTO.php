<?php

namespace Modules\Messaging\DTOs;

use App\Shared\DTOs\MessageDTO;
use App\Shared\ValueObjects\ConversationId;

/**
 * Represents a conversation as a pure data shape.
 *
 * Lives in the Messaging module — not the Shared Kernel —
 * because no other module needs the full conversation shape.
 * Other modules only ever need ConversationId (a value object)
 * which is already in the Shared Kernel.
 *
 * This DTO is what crosses the boundary between:
 *   - Messaging's domain layer (models)
 *   - Messaging's application layer (actions)
 *   - Messaging's presentation layer (controllers → Inertia)
 *
 * It never crosses a MODULE boundary — only a LAYER boundary.
 */
final class ConversationDTO
{
    public function __construct(
        public readonly ConversationId $id,
        public readonly string         $type,
        public readonly ?string        $name,
        // Participant user IDs — Messaging only stores IDs
        // The controller enriches these with UserDTOs via AuthContract
        // if the UI needs names/avatars
        /** @var int[] */
        public readonly array          $participantIds,
        public readonly ?MessageDTO    $lastMessage,
        public readonly \DateTimeImmutable $updatedAt,
    ) {}

    public function toArray(): array
    {
        return [
            'id'              => $this->id->value(),
            'type'            => $this->type,
            'name'            => $this->name,
            'participant_ids' => $this->participantIds,
            'last_message'    => $this->lastMessage?->toArray(),
            'updated_at'      => $this->updatedAt->format(\DateTimeInterface::ATOM),
        ];
    }
}