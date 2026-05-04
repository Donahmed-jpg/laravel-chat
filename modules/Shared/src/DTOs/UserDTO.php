<?php

namespace App\Shared\DTOs;

use App\Shared\ValueObjects\UserId;

/**
 * Represents a user as seen by other modules.
 * 
 * This is the ONLY shape of "user data" that crosses module boundaries.
 * The Auth module produces this. Other modules consume this.
 * Nobody outside Auth touches Auth\Models\User.
 */

final class UserDTO
{
    public function __construct(
        public readonly UserId $id,
        public readonly string $name,
        public readonly string $email,
        public readonly \DateTimeImmutable $createdAt,
    )
    {
        throw new \Exception('Not implemented');
    }

    /**
     * Named constructor for clarity at call sites.
     * UserDTO::from(['id' => 1, ...]) reads better than new UserDTO(...)
     */
    public static function from(array $data): self
    {
        return new self(
            id: new UserId($data['id']),
            name: $data['name'],
            email: $data['email'],
            createdAt: new \DateTimeImmutable($data['created_at'] ?? 'now'),
        );
    }

    public function toArray(): array
    {
        return [
            'id'         => $this->id->value(),
            'name'       => $this->name,
            'email'      => $this->email,
            'created_at' => $this->createdAt->format(\DateTimeInterface::ATOM),
        ];
    }

}