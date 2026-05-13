<?php

namespace Modules\Auth\Application\DTOs;

use App\Shared\ValueObjects\UserId;
use DateTimeImmutable;
use Modules\Auth\Domain\Entities\User;

/**
 * Output DTO — carries user data out of the use case.
 *
 * Created by: Application layer (mapped from User entity)
 * Consumed by: Presentation layer (controller → Inertia)
 * Direction: outward only — never passed into a use case
 *
 * Notice this is MODULE-INTERNAL — it lives in Application/DTOs/
 * not in the Shared Kernel. No other module needs this exact shape.
 *
 * The Shared Kernel has UserResponse for cross-module use.
 * This one is specifically for Auth's own presentation needs
 * and can carry Auth-specific fields the Shared version does not.
 */
final class UserResponse
{
    public function __construct(
        public readonly UserId    $id,
        public readonly string $name,
        public readonly string $email,
        public readonly bool   $isVerified,
        public readonly DateTimeImmutable $createdAt,
    ) {}

    /**
     * Named constructor — maps directly from the domain entity.
     * The entity never leaves the domain layer.
     * This DTO is what travels upward instead.
     */
    public static function fromEntity(User $user): self
    {
        return new self(
            id:         $user->id(),
            name:       $user->name(),
            email:      $user->email(),
            isVerified: $user->isVerified(),
            createdAt:  $user->createdAt(),
        );
    }

    public function toArray(): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'email'       => $this->email,
            'is_verified' => $this->isVerified,
            'created_at'  => $this->createdAt,
        ];
    }
}