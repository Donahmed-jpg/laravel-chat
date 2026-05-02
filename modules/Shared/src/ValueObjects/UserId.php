<?php

namespace App\Shared\ValueObjects;

use InvalidArgumentException;

final class UserId {
    
    public function __construct(
        private readonly int $value
    )
    {
        if ($value <= 0) {
            throw new InvalidArgumentException(
                "UserId must be a positive integer, [{$value}] given."
            );
        }
        
    }

    public function value(): int
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString()
    {
        return (string) $this->value;
    }
}