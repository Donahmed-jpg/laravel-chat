<?php

namespace Modules\Auth\src\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Auth\DTOs\RegisterDTO;

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
            // Note: NO 'unique' rule here.
            // Uniqueness is a BUSINESS RULE → it lives in RegisterUser action.
            // HTTP requests validate format, not business constraints.
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    /**
     * Convenience: transform validated HTTP data into a typed DTO.
     * The controller calls this instead of $request->validated().
     */
    public function toDTO(): RegisterDTO
    {
        return RegisterDTO::fromArray($this->validated());
    }
}