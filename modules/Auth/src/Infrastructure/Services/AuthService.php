<?php

namespace Modules\Auth\Infrastructure\Services;

use App\Shared\Contracts\AuthContract;
use App\Shared\DTOs\UserResponse as SharedUserResponse;
use App\Shared\ValueObjects\UserId;
use Illuminate\Support\Facades\Auth;
use Modules\Auth\Domain\Contracts\UserRepository;

/**
 * Implements the Shared Kernel's AuthContract.
 *
 * Lives in Infrastructure because:
 *   - It uses Laravel's Auth facade (framework concern)
 *   - It depends on UserRepository (infrastructure binding)
 *
 * Other modules inject AuthContract → Laravel resolves this class.
 * Other modules never know this class exists.
 *
 * Notice it returns Shared Kernel's UserResponse — not Auth's
 * internal UserResponse. Two different output shapes for
 * two different audiences.
 */

class AuthService implements AuthContract
{
    public function __construct(
        private readonly UserRepository $users,
    )
    {}

    
    public function findUser(UserId $id): ?SharedUserResponse
    {
        $user = $this->users->findById($id);

        if ($user === null) {
            return null;
        }

        return new SharedUserResponse(
            id:         $user->id(),
            name:       $user->name(),
            email:      $user->email(),
            createdAt:  $user->createdAt(),
        );
    }

    
    public function currentUser(): ?SharedUserResponse
    {
        $eloquentUser = Auth::user();
        
        if (! $eloquentUser ){
            return null;
        }

        return new SharedUserResponse(
            id:        new UserId($eloquentUser->id),
            name:      $eloquentUser->name,
            email:     $eloquentUser->email,
            createdAt: $eloquentUser->created_at->toDateTimeImmutable(),
        );

    }

    public function userExists(UserId $id): bool
    {
        return $this->users->findById($id) !== null;
    }

}