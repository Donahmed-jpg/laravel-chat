<?php

namespace Modules\Auth\Domain\Exceptions;

use App\Shared\Exceptions\DomainException;

final class InvalidCredentialsException extends DomainException
{
    public static function make(): self
    {
        return new self(
            // never reveal which field is wrong
            message: 'The provided credentials are incorrect.',
        );
    }
}