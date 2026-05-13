<?php

namespace Modules\Auth\Infrastructure\Repositories;

use App\Shared\ValueObjects\UserId;
use Modules\Auth\Domain\Contracts\UserRepository;
use Modules\Auth\Domain\Entities\User;
use Modules\Auth\Infrastructure\Models\EloquentUser;

class EloquentUserRepository implements UserRepository
{
    /**
     * In-memory cache of Eloquent records fetched this request.
     * Keyed by email and by id.
     *
     * This is a request-scoped cache — the repository is resolved
     * fresh by Laravel's container on every HTTP request, so this
     * array never persists between requests.
     *
     * When the use case calls findByEmail(), we cache the record.
     * When LaravelSessionManager calls findEloquentById(), it gets
     * the cached record — zero extra query.
     *
     * @var array<string, EloquentUser>
     */
    private array $cache = [];

    public function findById(UserId $id): ?User
    {
        $key = 'id_' . $id->value();

        if (! isset($this->cache[$key])) {
            $record = EloquentUser::find($id->value());

            if ($record === null) {
                return null;
            }

            $this->cache($record);
        }

        return $this->toEntity($this->cache[$key]);
    }

    public function findByEmail(string $email): ?User
    {
        $key = 'email_' . $email;

        if (! isset($this->cache[$key])) {
            $record = EloquentUser::where('email', $email)->first();

            if ($record === null) {
                return null;
            }

            $this->cache($record);
        }

        return $this->toEntity($this->cache[$key]);
    }

    public function existsByEmail(string $email): bool
    {
        return EloquentUser::where('email', $email)->exists();
    }

    public function save(User $user): User
    {
        $record           = new EloquentUser();
        $record->name     = $user->name();
        $record->email    = $user->email();
        $record->password = $user->hashedPassword();

        $record->save();

        $this->cache($record);

        return $this->toEntity($record);
    }

    /**
     * Used exclusively by LaravelSessionManager.
     * Returns the cached Eloquent record — no extra query
     * if findByEmail() was already called this request.
     *
     * This method exists in the repository because infrastructure
     * is the only layer that works with Eloquent records.
     */
    public function findCachedEloquentById(UserId $id): ?EloquentUser
    {
        $key = 'id_' . $id->value();

        if (isset($this->cache[$key])) {
            return $this->cache[$key];
        }

        $record = EloquentUser::find($id->value());

        if ($record) {
            $this->cache($record);
        }

        return $record;
    }

    // ── Private ───────────────────────────────────────────────────

    /**
     * Stores the record under both its id and email keys
     * so it can be retrieved either way without a query.
     */
    private function cache(EloquentUser $record): void
    {
        $this->cache['id_' . $record->id]       = $record;
        $this->cache['email_' . $record->email] = $record;
    }

    private function toEntity(EloquentUser $record): User
    {
        return new User(
            id:             new UserId($record->id),
            name:           $record->name,
            email:          $record->email,
            hashedPassword: $record->password,
            verifiedAt:     $record->email_verified_at
                                ? new \DateTimeImmutable(
                                    $record->email_verified_at->toDateTimeString()
                                  )
                                : null,
            createdAt:      new \DateTimeImmutable(
                                $record->created_at->toDateTimeString()
                            ),
        );
    }
}