<?php

namespace App\Shared\Contracts;

use App\Shared\DTOs\UserDTO;
use App\Shared\ValueObjects\UserId;

/**
 * What the Auth module exposes to the rest of the application.
 * 
 * Other modules depend on THIS, not on Auth internals.
 * The Auth module implements this. Others inject this interface.
 */
interface AuthContract
{
    /**
     * Find a user by their ID.
     * Returns a DTO (not an Eloquent model) intentionally.
     */
    public function findUser(UserId $id): ?UserDTO;

    /**
     * Get the currently authenticated user.
     */
    public function currentUser(): ?UserDTO;

    /**
     * Check if a user ID exists in the system.
     */
    public function userExists(UserId $id): bool;
}