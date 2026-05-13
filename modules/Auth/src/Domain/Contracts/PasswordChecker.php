<?php

namespace Modules\Auth\Domain\Contracts;

/**
 * Domain service interface — verifying a password is a domain concern.
 * The domain knows it needs to verify passwords.
 * It does not know WHICH algorithm does the verification.
 */
interface PasswordChecker
{
    public function check(string $plainPassword, string $hashedPassword): bool;
}