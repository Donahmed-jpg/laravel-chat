<?php

namespace Modules\Auth\Actions;

use App\Shared\DTOs\UserDTO;
use Modules\Auth\DTOs\RegisterDTO;
use Modules\Auth\Exceptions\EmailAlreadyTakenException;
use Modules\Auth\Repositories\Contracts\UserRepositoryContract;

/**
 * USE CASE: Register a new user.
 * 
 * Responsibilities:
 *   1. Enforce the uniqueness business rule
 *   2. Delegate persistence to the repository
 *   3. Return a DTO (never a Model)
 * 
 * This class does NOT know about:
 *   - HTTP (no Request/Response)
 *   - Inertia (no redirects)
 *   - Sessions (no auth()->login())
 *   - Queues (events dispatched separately)
 */

class RegisterUser
{
    public function __construct(
        private readonly UserRepositoryContract $users,
    )
    {}

    /**
     * @throws EmailAlreadyTakenException
     */

    public function execute(RegisterDTO $dto): UserDTO
    {
        // Business rule: email must be unique

        if($this->users->existsByEmail($dto->email)) {
            throw EmailAlreadyTakenException::for($dto->email);
        }

        $user = $this->users->create([
            'name'     => $dto->name,
            'email'    => $dto->email,
            'password' => $dto->password, // Model casts this to hashed
        ]);

        return $user->toDTO();
    }
}