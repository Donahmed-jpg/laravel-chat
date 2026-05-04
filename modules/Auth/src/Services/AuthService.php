<?php

namespace Modules\Auth\Services;

use App\Shared\Contracts\AuthContract;
use App\Shared\DTOs\UserDTO;
use App\Shared\ValueObjects\UserId;
use Illuminate\Support\Facades\Auth;
use Modules\Auth\Repositories\Contracts\UserRepositoryContract;

/**
 * Implements the Shared Kernel's AuthContract.
 * 
 * This is what OTHER modules get when they inject AuthContract.
 * They never see this class — they only see the interface.
 */
class AuthService implements AuthContract
{
    public function __construct(
        private readonly UserRepositoryContract $users,
    ) {}

    public function findUser(UserId $id): ?UserDTO
    {
        $user = $this->users->findById($id);

        return $user?->toDTO();
    }

    public function currentUser(): ?UserDTO
    {
        $user = Auth::user();

        return $user?->toDTO();
    }

    public function userExists(UserId $id): bool
    {
        return $this->users->findById($id) !== null;
    }
}