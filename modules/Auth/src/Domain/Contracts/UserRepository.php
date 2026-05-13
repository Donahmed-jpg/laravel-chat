<?php

namespace Modules\Auth\Domain\Contracts;

use App\Shared\ValueObjects\UserId;
use Modules\Auth\Domain\Entities\User;

/**
 * Defines what the Auth domain needs from persistence.
 *
 * This interface lives in the Domain layer — the innermost layer.
 * It defines the CONTRACT from the domain's perspective:
 * "I need to be able to do these things with users."
 *
 * It says nothing about HOW these operations are performed.
 * No Eloquent. No SQL. No Redis. Pure intent.
 *
 * The Infrastructure layer provides the concrete implementation.
 * The Domain layer never knows which implementation is running.
 */
interface UserRepository
{
    public function findById(UserId $id): ?User;

    public function findByEmail(string $email): ?User;

    public function existsByEmail(string $email): bool;

    /**
     * Persists a new user and returns it with its generated ID.
     */
    public function save(User $user): User;
}