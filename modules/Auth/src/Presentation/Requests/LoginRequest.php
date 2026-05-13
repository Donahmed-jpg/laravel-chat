<?php

namespace Modules\Auth\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Auth\Application\DTOs\LoginUserCommand;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['boolean'],
        ];
    }

    public function toCommand(): LoginUserCommand
    {
        return new LoginUserCommand(
            email:    $this->validated('email'),
            password: $this->validated('password'),
            remember: $this->boolean('remember'),
        );
    }
}