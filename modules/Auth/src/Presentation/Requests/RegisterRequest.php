<?php

namespace Modules\Auth\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Auth\Application\DTOs\RegisterUserCommand;

/**
 * Handles HTTP-level validation only.
 *
 * Rules here answer: "is this valid HTTP input?"
 * Not: "does this satisfy business rules?"
 *
 * Business rules (email uniqueness etc.) live in the use case.
 * Format rules (required, string, email) live here.
 *
 * toCommand() is the only bridge between Presentation and Application.
 * The controller never calls $request->validated() directly.
 */
class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'min:2', 'max:100'],
            'email'    => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function toCommand(): RegisterUserCommand
    {
        return new RegisterUserCommand(
            name:     $this->validated('name'),
            email:    $this->validated('email'),
            password: $this->validated('password'),
        );
    }
}