<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class PassesRecaptcha implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Make request to recaptcha API
        $request = Http::asForm()
            ->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => config('services.recaptcha.secret'),
                'response' => $value,
            ]);

        // Get json response from api
        $response = $request->json();

        // Check if response is valid
        $fails = $response['success'] === false ||
            $response['score'] < 0.5 ||
            $response['action'] !== request('recaptcha_action') ||
            $response['hostname'] !== request()->getHost();

        if ($fails) {
            $fail('Recaptcha failed.');
        }
    }
}
