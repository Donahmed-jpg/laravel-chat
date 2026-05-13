<?php

namespace Modules\Auth\Application\UseCases;

use Modules\Auth\Application\Contracts\SessionManager;
use Modules\Auth\Application\DTOs\LoginUserCommand;
use Modules\Auth\Application\DTOs\UserResponse;
use Modules\Auth\Domain\Exceptions\InvalidCredentialsException;
use Modules\Auth\Domain\Contracts\UserRepository;
use Modules\Auth\Domain\Contracts\PasswordChecker;

/**
 * Zero framework imports.
 * No Auth facade. No session management.
 * Session is handled by the controller after this returns.
 *
 * This use case has one job:
 * verify the credentials are correct and return the user.
 * Starting a session is a Presentation concern.
 */
class LoginUser
{
    public function __construct(
        private readonly UserRepository $users,
        private readonly PasswordChecker $checker,
        private readonly SessionManager $session,
    ) {}

    public function execute(LoginUserCommand $command): UserResponse
    {
        $user = $this->users->findByEmail($command->email);

        // Intentionally same exception whether user not found
        // or password wrong — never reveal which one failed
        if ($user === null) {
            throw InvalidCredentialsException::make();
        }

        if (! $this->checker->check($command->password, $user->hashedPassword())) {
            throw InvalidCredentialsException::make();
        }

        //
        $this->session->startSession($user->id(), $command->remember);
        return UserResponse::fromEntity($user);
    }


}