<?php

namespace Modules\Messaging\Repositories;

use App\Shared\ValueObjects\ConversationId;
use App\Shared\ValueObjects\UserId;
use Modules\Messaging\Models\Conversation;
use Modules\Messaging\Repositories\Contracts\ConversationRepositoryContract;

class EloquentConversationRepository implements ConversationRepositoryContract
{
    public function findById(ConversationId $id): ?Conversation
    {
        return Conversation::find($id->value());
    }

    public function findDirectBetween(UserId $userA, UserId $userB): ?Conversation
    {
        // Find conversations where BOTH users are participants
        // and the type is direct
        return Conversation::where('type', 'direct')
            ->whereHas('participantIds', function ($q) use ($userA) {
                $q->where('user_id', $userA->value());
            })
            ->whereHas('participantIds', function ($q) use ($userB) {
                $q->where('user_id', $userB->value());
            })
            ->first();
    }

    public function findForUser(UserId $userId): array
    {
        return Conversation::whereHas('participantIds', function ($q) use ($userId) {
                $q->where('user_id', $userId->value());
            })
            ->with('lastMessage')
            ->orderByDesc('updated_at')
            ->get()
            ->all();
    }

    // public function findForUser(UserId $userId): array
    // {
    //     return Conversation::whereHas('participantIds', function ($q) use ($userId) {
    //             $q->where('user_id', $userId->value());
    //         })
    //         ->with(['lastMessage', 'participantIds'])
    //         ->orderByDesc('updated_at')
    //         ->get()
    //         ->map(fn (Conversation $c) => $c->toDTO())  // ← convert here
    //         ->all();
    // }

    public function create(string $type, ?string $name = null): Conversation
    {
        return Conversation::create([
            'type' => $type,
            'name' => $name,
        ]);
    }

    public function addParticipant(ConversationId $id, UserId $userId): void
    {
        Conversation::findOrFail($id->value())
            ->participantIds()
            ->attach($userId->value());
    }
}