<?php

return [

    // The Secret for the client side of Google's Recaptcha
    'client_secret' => env('RECAPTCHA_CLIENT_SECRET'),

    // The Secret for the server side of Google's Recaptcha
    'server_secret' => env('RECAPTCHA_SERVER_SECRET'),

    // The expected hostname for the request
    'expected_host' => env('APP_URL'),
];
