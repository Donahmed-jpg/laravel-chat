<?php

namespace Modules\Auth\Application\DTOs;

/**
 * Input DTO — carries login intent into the use case.
 */
final class LoginUserCommand
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
        public readonly bool   $remember = false,
    ) {}
}