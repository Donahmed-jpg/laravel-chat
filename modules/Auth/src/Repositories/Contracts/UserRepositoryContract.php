<?php

namespace Modules\Auth\Repositories\Contracts;


use App\Shared\ValueObjects\UserId;
use Modules\Auth\Models\User;

/**
 * Defines what persistence operations the Auth module needs.
 * 
 * The Action classes depend on THIS interface — not on Eloquent.
 * This means we can swap Eloquent for anything else without
 * touching a single Action or Controller.
 */

interface UserRepositoryContract
{
    public function findById(UserId $id): ?User;
    
    public function findByEmail(string $email): ?User;

    public function existsByEmail(string $email): bool;

    public function create(array $attributes): User;
}