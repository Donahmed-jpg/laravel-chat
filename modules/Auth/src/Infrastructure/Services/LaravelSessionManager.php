<?php

namespace Modules\Auth\Infrastructure\Services;

use App\Shared\ValueObjects\UserId;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Auth;
use Modules\Auth\Application\Contracts\SessionManager;
use Modules\Auth\Infrastructure\Repositories\EloquentUserRepository;

/**
 * Both LaravelSessionManager and EloquentUserRepository
 * are Infrastructure — they are allowed to know about each other.
 * No layer boundaries are crossed here.
 */
class LaravelSessionManager implements SessionManager
{
    public function __construct(
        private readonly EloquentUserRepository $repository,
        private readonly Store                  $session,
    ) {}

    public function startSession(UserId $userId, bool $remember): void
    {
        // Hits the in-memory cache if findByEmail() already ran
        // this request — zero extra database query
        $record = $this->repository->findCachedEloquentById($userId);

        if ($record === null) {
            throw new \RuntimeException(
                "Cannot start session — user [{$userId->value()}] not found."
            );
        }

        // $this->wipeSession();

        Auth::login($record, $remember);
        // $this->regenerate();

        $this->regenerateToken();
    }

    public function endSession(): void
    {
        Auth::logout();
    }

    public function regenerate(): void
    {
        $this->session->regenerate();
    }

    public function wipeSession(): void
    {
        $this->session->invalidate();
    }

    public function regenerateToken(): void
    {
        $this->session->regenerateToken();
    }
}