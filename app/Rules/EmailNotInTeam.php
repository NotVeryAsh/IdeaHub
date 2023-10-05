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
        $user = User::query()->find($value);

        if (! $user) {
            return;
        }

        $team = request()->route('team');

        if ($user->teams->contains($team)) {
            $fail('This user is already a member of this team.');
        }
    }
}
