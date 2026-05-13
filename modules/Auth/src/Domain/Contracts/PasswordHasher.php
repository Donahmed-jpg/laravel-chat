<?php

namespace Modules\Auth\Domain\Contracts;

interface PasswordHasher
{
    public function hash(string $plainPassword): string;
}