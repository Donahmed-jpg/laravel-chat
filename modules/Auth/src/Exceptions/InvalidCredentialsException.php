<?php

namespace Modules\Auth\Exceptions;

use App\Shared\Exceptions\DomainException;

final class InvalidCredentialsException extends DomainException
{
    public static function make(): self
    {
        return new self(
            // Intentionally vague — never tell the user WHICH field is wrong
            message: 'The provided credentials are incorrect.',
        );
    }
}