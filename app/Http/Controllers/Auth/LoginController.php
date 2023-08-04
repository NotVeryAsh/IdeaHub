<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Developer Notes -
    //
    // I wasn't sure about how to handle authentication effectively since
    // we are trying to authenticate the user with both a username and an email
    //
    // In a usual login form request, you would be able to validate an email
    // with the 'email' rule, but this isn't possible since we could be passing
    // a username instead.
    //
    // On top of that, the authorize method in the form request will always return
    // a 403 instead of a 401, which rules out the possibility of using Rule::exists()
    // to check if a user exists with either that email or username and returning a 401
    // unauthorized error code.
    //
    // This would also be a less efficient approach since we would be running more
    // sql queries with Rule::exists, which will only eventually be run again in the
    // Auth::attempt() method.
    //
    // And so I am going to go with a less clean but more efficient approach and checking
    // if the passed identifier is a valid email, and if so, we will attempt authentication
    // with the email, otherwise we will attempt authentication with the username
    //
    // ... in the controller ... *shivers*

    /**
     * @return void
     */
    public function authenticate(LoginRequest $request)
    {
        Auth::attempt();
    }
}
