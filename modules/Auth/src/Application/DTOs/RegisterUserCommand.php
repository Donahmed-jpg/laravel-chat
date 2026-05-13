<?php

namespace Modules\Auth\Application\DTOs;

/**
 * Input DTO — carries registration intent into the use case.
 *
 * Created by: Presentation layer (RegisterRequest)
 * Consumed by: RegisterUser use case
 * Direction: inward only — never returned, never crosses module boundary
 *
 * Named "Command" because it expresses intent:
 * "I want to register a user with these values."
 */
final class RegisterUserCommand
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
    ) {}
}