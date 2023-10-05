<?php

namespace App\Http\Requests\Teams;

use App\Rules\EmailNotInTeam;
use Illuminate\Foundation\Http\FormRequest;

class StoreTeamInvitationRequest extends FormRequest
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
                new EmailNotInTeam(),
            ],
        ];
    }

    public function messages(): array
    {

        return [
            'email.required' => 'Email is required.',
            'email.email' => 'Email must be a valid email address.',
            'email.max' => 'Email must not be greater than 255 characters.',
        ];
    }
}
