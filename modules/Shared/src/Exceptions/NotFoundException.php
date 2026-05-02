<?php

namespace App\Shared\Exceptions;

final class NotFoundException extends DomainException
{
    public static function for(string $entity, int|string $id): self
    {
        return new self(
            message: "{$entity} with ID [{$id}] was not found.",
            context: ['entity' => $entity, 'id' => $id],
        );
    }
}