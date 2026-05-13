<?php

namespace Modules\Auth\Application\UseCases;

use Modules\Auth\Application\DTOs\RegisterUserCommand;
use Modules\Auth\Application\DTOs\UserResponse;
use Modules\Auth\Domain\Entities\User;
use Modules\Auth\Domain\Exceptions\EmailAlreadyTakenException;
use Modules\Auth\Domain\Contracts\UserRepository;
use Modules\Auth\Domain\Contracts\PasswordHasher;

/**
 * Zero framework imports.
 * Depends only on Domain interfaces.
 * Fully testable with plain PHP mocks.
 */
class RegisterUser
{
    public function __construct(
        private readonly UserRepository $users,
        private readonly PasswordHasher $hasher,
    ) {}

    public function execute(RegisterUserCommand $command): UserResponse
    {
        if ($this->users->existsByEmail($command->email)) {
            throw EmailAlreadyTakenException::for($command->email);
        }

        // Named constructor expresses intent
        // No magic zero ID, no framework calls
        $user = User::register(
            name:           $command->name,
            email:          $command->email,
            hashedPassword: $this->hasher->hash($command->password),
        );

        $savedUser = $this->users->save($user);

        return UserResponse::fromEntity($savedUser);
    }
}