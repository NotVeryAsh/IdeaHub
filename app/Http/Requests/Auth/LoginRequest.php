<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
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
                function ($attribute, $value, $fail) {
                    $user = User::query()->where('email', $value)
                        ->orWhere('username', $value)
                        ->first();

                    if (! $user) {
                        $fail('Email or username is incorrect.');
                    }
                },
            ],
            'password' => [
                'required',
                'string',
                'max:60',
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
        ];
    }
}