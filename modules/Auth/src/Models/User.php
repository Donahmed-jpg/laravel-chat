<?php

namespace Modules\Auth\Models;

use App\Shared\DTOs\UserDTO;
use App\Shared\ValueObjects\UserId;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class User extends Model implements AuthenticatableContract
{
    use Authenticatable;
    use HasFactory;
    use Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed'
        ];
    }

    // ─────────────────────────────────────────────
    // Domain behaviour — business rules on the entity
    // ─────────────────────────────────────────────

    /**
     * Domain rule: a user is considered "verified" only when
     * email_verified_at is set. This encapsulates the rule
     * so no other layer needs to know HOW verification works.
     */
    public function isVerified(): bool
    {
        return $this->email_verified_at !== null;
    }

    /**
     * Produces a DTO — the only shape that leaves this module.
     * Nothing outside Auth\Models\User ever gets a raw User model.
     */
    public function toDTO(): UserDTO
    {
        return new UserDTO(
            id:        new UserId($this->id),
            name:      $this->name,
            email:     $this->email,
            createdAt: new \DateTimeImmutable(
                $this->created_at->toDateTimeString()
            ),
        );
    }
}