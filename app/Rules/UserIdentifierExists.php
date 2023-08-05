<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UserIdentifierExists implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $identifier = request()->input('identifier');

        // Custom rule to check if user exists since Rule::exists doesn't support orWhere clauses.
        $user = User::query()->where('email', $identifier)
            ->orWhere('username', $identifier)
            ->first();

        if (! $user) {
            $fail('Email or username is incorrect.');
        }
    }
}
