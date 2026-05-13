<?php

namespace Modules\Messaging\Models;

use App\Shared\DTOs\MessageDTO;
use App\Shared\ValueObjects\ConversationId;
use App\Shared\ValueObjects\UserId;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use SoftDeletes;

    protected $table = 'messages';

    protected $fillable = [
        'conversation_id',
        'sender_id',
        'body',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime'
        ];
    }

    // ── Relationships ──────────────────────────────────────────

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    // No relationship to User — Messaging does not own users
    // sender_id is just an integer here
    // Call AuthContract::findUser(new UserId($this->sender_id))
    // if you need the full user data

    // ── Domain behaviour ───────────────────────────────────────

    public function isFromSender(UserId $userId): bool
    {
        return $this->sender_id === $userId->value();
    }

    /**
     * The only shape of Message data that leaves this module.
     */

    public function toDTO(): MessageDTO
    {
        return new MessageDTO(
            id:             $this->id,
            conversationId: new ConversationId($this->conversation_id),
            senderId:       new UserId($this->sender_id),
            body:           $this->body,
            sentAt:         new DateTimeImmutable(
                                $this->created_at->toDateTimeString()
                            )
        );
    }
}