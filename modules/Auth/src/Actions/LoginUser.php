<?php

namespace Modules\Auth\Actions;

use App\Shared\DTOs\UserDTO;
use Illuminate\Support\Facades\Auth;
use Modules\Auth\DTOs\LoginDTO;
use Modules\Auth\Exceptions\InvalidCredentialsException;
use Modules\Auth\Repositories\Contracts\UserRepositoryContract;

class LoginUser
{
    public function __construct(
        private readonly UserRepositoryContract $users,
    ) {}

    /**
     * @throws InvalidCredentialsException
     */
    public function execute(LoginDTO $dto): UserDTO
    {
        $credentials = [
            'email'    => $dto->email,
            'password' => $dto->password,
        ];

        if (! Auth::attempt($credentials, $dto->remember)) {
            throw InvalidCredentialsException::make();
        }

        // Auth::attempt() already verified + set the session
        // We just retrieve the authenticated user and return a DTO
        $user = $this->users->findByEmail($dto->email);

        return $user->toDTO();
    }
}