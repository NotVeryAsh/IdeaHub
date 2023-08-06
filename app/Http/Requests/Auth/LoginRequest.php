<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use App\Rules\UserIdentifierExists;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'identifier' => [
                'required',
                'string',
                'max:255',
                // Check if a user exists with this email or username
                new UserIdentifierExists(),
            ],
            'password' => [
                'required',
                'string',
                'max:60',
            ],
            // Validation for a remember checkbox
            'remember' => [
                'nullable',
                'string',
                'in:on',
            ],
        ];
    }

    public function messages(): array
    {

        return [
            'identifier.required' => 'Email or username is required.',
            'identifier.string' => 'Email or username is incorrect.',
            'identifier.max' => 'Email or username is incorrect.',
            'password.required' => 'Password is required.',
            'password.string' => 'Password is incorrect. Try again or click "Forgot Password" to reset your password.',
            'password.max' => 'Password is incorrect. Try again or click "Forgot Password" to reset your password.',
            'remember.in' => 'The remember checkbox must be checked or not.',
            'remember.string' => 'The remember checkbox must be checked or not.',
        ];
    }
}
