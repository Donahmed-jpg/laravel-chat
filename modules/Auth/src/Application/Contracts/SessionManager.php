<?php

namespace Modules\Auth\Application\Contracts;

use App\Shared\ValueObjects\UserId;

/**
 * Abstracts session lifecycle from the application layer.
 *
 * Defined here in Application so both Presentation (controller)
 * and Infrastructure (implementation) depend on this contract.
 *
 * The use case does NOT use this — it only verifies credentials.
 * The controller uses this AFTER the use case returns successfully.
 */
interface SessionManager
{
    public function startSession(UserId $user, bool $remember): void;

    public function endSession(): void;

    public function regenerate(): void;

    public function wipeSession(): void;

    public function regenerateToken(): void;
}