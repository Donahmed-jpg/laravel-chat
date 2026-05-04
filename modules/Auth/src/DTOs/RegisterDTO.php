<?php

namespace Modules\Auth\DTOs;

/**
 * Carries registration intent from the HTTP layer into the use case.
 * 
 * The Action receives this — not a Request, not an array.
 * This makes the Action completely testable without HTTP.
 */

final class RegisterDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password
    )
    { }

    public static function fromArray(array $data): self
    {
        return new self(
            name:       $data['name'],
            email:      $data['email'],
            password:   $data['password']
        );
    }
}