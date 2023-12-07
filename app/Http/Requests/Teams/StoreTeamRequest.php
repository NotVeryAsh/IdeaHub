<?php

namespace App\Http\Requests\Teams;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeamRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:50',
            ],
        ];
    }

    public function messages(): array
    {

        return [
            'name.required' => 'Team Name is required.',
            'name.string' => 'Team Name is invalid.',
            'name.max' => 'Team Name must not be greater than 50 characters.',

        ];
    }
}
