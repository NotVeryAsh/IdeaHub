<?php

namespace App\Rules;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PassesUsernameUpdateTimeConstraint implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $user = auth()->user();

        // Username is not being updated
        if ($user->username === $value) {
            return;
        }

        // Get last time username was updated
        $lastUpdated = Carbon::parse($user->username_updated_at);

        // Check if username has been updated in the last 6 hours
        if ($lastUpdated > now()->subHours(6)) {
            $difference = now()->diffInHours($lastUpdated->addHours(6));
            $fail("You may update your username again in $difference hours.");
        }
    }
}
