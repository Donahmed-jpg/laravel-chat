<?php


namespace Modules\Auth\Repositories;

use App\Shared\ValueObjects\UserId;
use Modules\Auth\Models\User;
use Modules\Auth\Repositories\Contracts\UserRepositoryContract;
use Override;

/**
 * All Eloquent queries for users live here — nowhere else.
 * 
 * If tomorrow we switch to a different ORM or add a caching layer,
 * we create a new class implementing UserRepositoryContract.
 * Zero Actions, zero Controllers change.
 */


class EloquentUserRepository implements UserRepositoryContract
{
    
    public function findById(UserId $id): ?User
    {
        return User::find($id->value());
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function existsByEmail(string $email): bool
    {
        throw new \Exception('Not implemented');
    }

    public function create(array $attributes): User
    {
        return User::create($attributes);
    }
}