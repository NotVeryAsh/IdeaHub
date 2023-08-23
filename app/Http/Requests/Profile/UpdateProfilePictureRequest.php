<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfilePictureRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule','array','string>
     */
    public function rules(): array
    {
        return [
            'profile_picture' => [
                'required',
                'image',
                'mimes:jpg,png,jpeg,gif,webp',
                'max:5120',
                'dimensions:max_width=800,max_height=800',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'profile_picture.required' => 'Profile picture is required.',
            'profile_picture.max' => 'Profile picture must be 5MB or less.',
            'profile_picture.image' => 'Profile picture must be an image.',
            'profile_picture.mimes' => 'Profile picture must be a JPEG, JPG, PNG, WEBP or GIF.',
            'profile_picture.dimensions' => 'Profile picture must be 800x800 or less.',
        ];
    }
}
