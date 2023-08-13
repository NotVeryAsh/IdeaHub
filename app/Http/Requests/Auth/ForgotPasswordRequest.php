<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest
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
                'exists:users,email',
            ],
        ];
    }

    public function messages(): array
    {

        return [
            'email.required' => 'Email is required.',
            'email.email' => 'Email is invalid.',
            'email.exists' => 'Email not found.',
        ];
    }
}
