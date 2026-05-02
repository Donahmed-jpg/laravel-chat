<?php

namespace App\Shared\Exceptions;

final class UnauthorizedException extends DomainException
{
    public static function for(string $action): self
    {
        return new self(
            message: "You are not authorized to perform [{$action}].",
            context: ['action' => $action],
        );
    }
}