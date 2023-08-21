<?php

namespace App\Http\Requests\Profile;

use App\Rules\PassesUsernameUpdateTimeConstraint;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule','array','string>
     */
    public function rules(): array
    {
        return [
            'email' => [
                'sometimes',
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->user()->id),
            ],
            'username' => [
                'sometimes',
                'required',
                'max:20',
                'min:3',
                Rule::unique('users', 'username')->ignore($this->user()->id),
                'alpha_dash',
                new PassesUsernameUpdateTimeConstraint(),
            ],
            'password' => [
                'nullable',
                'min:8',
                'max:60',
                'confirmed',
            ],
            'first_name' => [
                'nullable',
                'string',
                'max:35',
            ],
            'last_name' => [
                'nullable',
                'string',
                'max:35',
            ],
            'profile_picture' => [
                'image',
                'mimes:jpg,png,jpeg,gif,webp',
                'max:5120',
                'dimensions:max_width=800,max_height=800',
            ]
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
            'first_name.string' => 'First name is invalid.',
            'first_name.max' => 'First name must not be greater than 35 characters.',
            'last_name.string' => 'Last name is invalid.',
            'last_name.max' => 'Last name must not be greater than 35 characters.',
            'profile_picture.max' => 'Profile picture must be 5MB or less.',
            'profile_picture.image' => 'Profile picture must be an image.',
            'profile_picture.mimes' => 'Profile picture must be a JPEG, JPG, PNG, WEBP or GIF.',
            'profile_picture.dimensions' => 'Profile picture must be 800x800 or less.',
        ];
    }
}
