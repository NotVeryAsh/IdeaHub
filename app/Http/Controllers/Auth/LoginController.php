<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\UserIdentifierExists;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Validation\Validator;

class LoginController extends Controller
{
    // TODO Turn this function into a stateless-friendly endpoint ie. meaningful status codes and json data

    public function authenticate(Request $request): Response|RedirectResponse
    {
        // Check if the data in the request is valid.
        $validator = $this->getValidator($request);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        // Get validated data
        $validated = $validator->safe()->only(['identifier', 'password']);
        $identifier = $validated['identifier'];
        $password = $validated['password'];

        // Check if identifier is an email or a username
        $fieldType = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Attempt to authenticate the user with the identifier (username or email) and password
        $authIsSuccessful = Auth::attempt([$fieldType => $identifier, 'password' => $password]);

        // Password is incorrect - we have already established that a user with the identifier exists
        if (! $authIsSuccessful) {

            // Return back with password error message
            return redirect()->back()->withErrors([
                'password' => 'Password is incorrect. Try again or click "Forgot Password" to reset your password.',
            ]);
        }

        // Regenerate the session to prevent session fixation attacks
        $request->session()->regenerate();

        // Redirect the user to the intended page, or the dashboard if there is no intended page.
        return redirect()->intended('dashboard');
    }

    private function getValidator(Request $request): Validator
    {
        // Get rules and messages
        $rules = $this->getValidationRules($request);
        $messages = $this->getValidationMessages();

        // Make a validator instance with the requested data as well as the rules and messages
        return ValidatorFacade::make($request->all(), $rules, $messages);
    }

    private function getValidationRules(): array
    {
        return [
            'identifier' => [
                'required',
                'string',
                'max:255',
                new UserIdentifierExists(),
            ],
            'password' => [
                'required',
                'string',
                'max:60',
            ],
        ];
    }

    private function getValidationMessages(): array
    {
        return [
            'identifier.required' => 'Email or username is required.',
            'identifier.string' => 'Email or username is incorrect.',
            'identifier.max' => 'Email or username is incorrect.',
            'password.required' => 'Password is required.',
            'password.string' => 'Password is incorrect. Try again or click "Forgot Password" to reset your password.',
            'password.max' => 'Password is incorrect. Try again or click "Forgot Password" to reset your password.',
        ];
    }
}
