<?php

namespace Modules\Auth\Domain\Entities;

use App\Shared\ValueObjects\UserId;

final class User
{
    public function __construct(
        // Nullable — null means not yet persisted
        // Once saved the repository returns the entity with a real UserId
        private readonly ?UserId             $id,
        private readonly string              $name,
        private readonly string              $email,
        private readonly string              $hashedPassword,
        private readonly ?\DateTimeImmutable $verifiedAt,
        private readonly \DateTimeImmutable  $createdAt,
    ) {}

    public function id(): UserId
    {
        if ($this->id === null) {
            throw new \LogicException(
                'Cannot access ID of a user that has not been persisted yet.'
            );
        }

        return $this->id;
    }

    public function name(): string { return $this->name; }
    public function email(): string { return $this->email; }
    public function hashedPassword(): string { return $this->hashedPassword; }
    public function createdAt(): \DateTimeImmutable { return $this->createdAt; }

    public function isVerified(): bool
    {
        return $this->verifiedAt !== null;
    }

    public function isPersisted(): bool
    {
        return $this->id !== null;
    }

    public function equals(self $other): bool
    {
        return $this->id()->equals($other->id());
    }

    /**
     * Named constructor — expresses intent clearly.
     * "I am creating a new user that does not exist in the database yet."
     */
    public static function register(
        string $name,
        string $email,
        string $hashedPassword,
    ): self {
        return new self(
            id:             null,
            name:           $name,
            email:          $email,
            hashedPassword: $hashedPassword,
            verifiedAt:     null,
            createdAt:      new \DateTimeImmutable(),
        );
    }
}