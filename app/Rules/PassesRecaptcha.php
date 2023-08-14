<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use ReCaptcha\ReCaptcha;

class PassesRecaptcha implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $recaptcha = new ReCaptcha(config('recaptcha.server_secret'));
        $resp = $recaptcha->setExpectedHostname(config('recaptcha.expected_host'))
            ->setExpectedAction(request()->input('action'))
            ->setScoreThreshold(0.5)
            ->verify($value, request()->ip());

        if ($errors = $resp->getErrorCodes()) {
            $fail(implode(', ', $errors));
        }
    }
}
