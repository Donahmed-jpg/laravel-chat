<?php

namespace Modules\Auth\Domain\Exceptions;

use App\Shared\Exceptions\DomainException;

final class EmailAlreadyTakenException extends DomainException
{
    public static function for(string $email): self
    {
        return new self(
            message: "The email address [{$email}] is already registered.",
            context: ['email' => $email],
        );
    }
}