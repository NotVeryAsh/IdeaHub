<?php

namespace App\Http\Requests\Teams;

use Illuminate\Foundation\Http\FormRequest;

class ListTeamMembersRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'page' => [
                'nullable',
                'integer',
            ],
            'per_page' => [
                'nullable',
                'integer',
            ],
            'search_term' => [
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'page.integer' => 'Page must be an integer.',
            'per_page.integer' => 'Per page must be an integer.',
            'search_term.string' => 'Search term must be a string.',
            'search_term.max' => 'Search term must not be greater than 255 characters.',

        ];
    }
}
