<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase, WithFaker;

    public static function fakeSuccessfulRecaptchaResponse(): void
    {
        // Fake google recaptcha response
        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' => Http::response([
                'success' => true,
                'errorCodes' => [],
                'hostname' => 'localhost',
                'challengeTs' => '2023-08-15T12:42:19Z',
                'apkPackageName' => '',
                'score' => 0.9,
                'action' => 'test',
            ]),
        ]);
    }

    public static function fakeUnsuccessfulRecaptchaResponse(): void
    {
        // Fake google recaptcha response
        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' => Http::response([
                'success' => false,
                'errorCodes' => [
                    'timeout-or-duplicate',
                    'hostname-mismatch',
                    'action-mismatch',
                    'score-threshold-not-met',
                ],
                'hostname' => '',
                'challengeTs' => '',
                'apkPackageName' => '',
                'score' => null,
                'action' => '',
            ]),
        ]);
    }
}
