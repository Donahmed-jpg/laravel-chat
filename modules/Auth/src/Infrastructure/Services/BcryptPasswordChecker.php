<?php

namespace Modules\Auth\Infrastructure\Services;

use Illuminate\Support\Facades\Hash;
use Modules\Auth\Domain\Contracts\PasswordChecker;

class BcryptPasswordChecker implements PasswordChecker
{
    public function check(string $plainPassword, string $hashedPassword): bool
    {
        return Hash::check($plainPassword, $hashedPassword);
    }
}