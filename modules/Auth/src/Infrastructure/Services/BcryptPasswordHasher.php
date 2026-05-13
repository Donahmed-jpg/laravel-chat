<?php

namespace Modules\Auth\Infrastructure\Services;

use Illuminate\Support\Facades\Hash;
use Modules\Auth\Domain\Contracts\PasswordHasher;

/**
 * Laravel's bcrypt implementation of the domain's PasswordHasher.
 * Laravel lives here — not in the domain, not in the use case.
 */


class BcryptPasswordHasher implements PasswordHasher
{
    
    public function hash(string $plainPassword): string
    {
        return Hash::make($plainPassword);
    }
}