<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EmailNotInTeam implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Get the user with the email in the request
        $user = User::query()->where('email', $value)->first();

        // If user doesn't exist
        if (! $user) {
            return;
        }

        // Get the team route binding
        $team = request()->route('team');

        // Check if the creator is attempting to invite themselves
        if ($user->is($team->creator)) {
            $fail('You cannot invite yourself.');
        }

        // Check if the user is part of this team
        if ($user->teams->contains($team)) {
            $fail('This user is already a member of this team.');
        }
    }
}
