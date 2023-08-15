<?php

namespace App\Http\Requests\Auth;

use App\Rules\PassesRecaptcha;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'username' => [
                'required',
                'max:20',
                'min:3',
                'unique:users,username',
                'alpha_dash',
            ],
            'password' => [
                'required',
                'min:8',
                'max:60',
                'confirmed',
            ],
            // Validation for a remember checkbox
            'remember' => [
                'nullable',
                'string',
                'in:on',
            ],
            'recaptcha_action' => [
                'required',
                'string',
            ],
            'recaptcha_response' => [
                'required',
                'string',
                new PassesRecaptcha(),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email is required.',
            'email.email' => 'Email must be a valid email address.',
            'email.max' => 'Email must not be greater than 255 characters.',
            'email.unique' => 'Email has already been taken.',
            'username.required' => 'Username is required.',
            'username.max' => 'Username must not be greater than 20 characters.',
            'username.min' => 'Username must be at least 3 characters.',
            'username.unique' => 'Username has already been taken.',
            'username.alpha_dash' => 'Username must only contain letters, numbers, dashes, and underscores.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.max' => 'Password must not be greater than 60 characters.',
            'password.confirmed' => 'Passwords do not match.',
            'remember.in' => 'Remember checkbox must be checked or not.',
            'remember.string' => 'Remember checkbox must be checked or not.',
            'recaptcha_action.required' => 'Recaptcha action is required.',
            'recaptcha_action.string' => 'Recaptcha action is invalid.',
            'recaptcha_response.required' => 'Recaptcha response is required.',
            'recaptcha_response.string' => 'Recaptcha response is invalid.',
        ];
    }
}
