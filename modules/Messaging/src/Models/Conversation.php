<?php

namespace Modules\Messaging\Models;

use App\Shared\ValueObjects\ConversationId;
use App\Shared\ValueObjects\UserId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Messaging\DTOs\ConversationDTO;

class Conversation extends Model
{
    protected $table = 'conversations';

    protected $fillable = [
        'type',
        'name',
    ];

    protected function casts(): array
    {
        return [
            'type' => 'string',
        ];
    }

    // ── Relationships ─────────────────────────────────────────

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function lastMessage(): HasOne
    {
        return $this->hasOne(Message::class)
            ->latestOfMany();
    }

    // We store participant user IDs in the pivot table
    // The Messaging module does not load User models —
    // it only works with IDs and fetches user data via AuthContract
    
    public function participantIds(): BelongsToMany
    {
        return $this->belongsToMany(
            // Self-referential trick: we point to a minimal pivot model
            // We do NOT point to Auth\Models\User — that would couple modules
            related:        Participant::class,
            table:          'conversation_participants',
            foreignPivotKey: 'conversation_id',
            relatedPivotKey: 'user_id',
        );

    }

    // ── Domain behaviour ──────────────────────────────────────

    /**
     * Domain rule: a direct conversation can only have 2 participants.
     * Checked before creation in the CreateConversation action.
     */
    public function isDirect(): bool
    {
        return $this->type === 'direct';
    }

    public function isGroup(): bool
    {
        return $this->type === 'group';
    }

    /**
     * Check if a given user is a participant in this conversation.
     * Used by SendMessage to enforce access control.
     */
    public function hasParticipant(UserId $userId): bool
    {
        return $this->participantIds()
            ->wherePivot('user_id', $userId->value())
            ->exists();
    }

    public function toDTO(): ConversationDTO
    {
        return new ConversationDTO(
            id:             new ConversationId($this->id),
            type:           $this->type,
            name:           $this->name,
            // Extract raw IDs from the pivot — no User model involved
            participantIds: $this->participantIds()
                                ->pluck('user_id')
                                ->toArray(),
            lastMessage:    $this->lastMessage?->toDTO(),
            updatedAt:      new \DateTimeImmutable(
                                $this->updated_at->toDateTimeString()
                            ),
        );
    }


}