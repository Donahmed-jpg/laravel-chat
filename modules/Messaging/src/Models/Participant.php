<?php

namespace Modules\Messaging\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Represents a conversation participant record.
 *
 * This model exists purely so the Messaging module can work
 * with participant user IDs without importing Auth\Models\User.
 *
 * It maps to the conversation_participants pivot table.
 * It does NOT have a relationship back to Auth\Models\User.
 * When we need full user data, we go through AuthContract.
 */

class Participant extends Model
{
    protected $table = 'conversation_participants';

    protected $fillable = [
        'conversation_id',
        'user_id',
        'last_read_at',
    ];

    protected function casts(): array
    {
        return [
            'last_read_at' => 'datetime'
        ];
    }
}