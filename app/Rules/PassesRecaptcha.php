<?php

namespace App\Rules;

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
        $recaptcha = new ReCaptcha(config('services.recaptcha.secret'));
        $resp = $recaptcha->setExpectedHostname(config('app.url'))
            ->setExpectedAction(request()->input('recaptcha_action'))
            ->setScoreThreshold(0.5)
            ->verify($value, request()->ip());

        if ($errors = $resp->getErrorCodes()) {
            $fail(implode(', ', $errors));
        }
    }
}
