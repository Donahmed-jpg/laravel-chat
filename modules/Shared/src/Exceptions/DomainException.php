<?php

namespace App\Shared\Exceptions;

use RuntimeException;

/**
 * Base for all domain-level exceptions.
 * Catch this to handle any business rule violation.
 */
abstract class DomainException extends RuntimeException
{
    public function __construct(
        string $message,
        private readonly array $context = [],
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, 0, $previous);
    }

    public function context(): array
    {
        return $this->context;
    }
}