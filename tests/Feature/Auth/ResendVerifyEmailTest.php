<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Tests\TestCase;

class ResendVerifyEmailTest extends TestCase
{
    public function test_can_resend_verification_email()
    {
        self::fakeSuccessfulRecaptchaResponse();

        Notification::fake();

        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->post('/auth/verify-email/resend', [
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('message', 'Verification link sent!');
        Notification::assertSentTo($user, VerifyEmail::class);
    }

    public function test_can_not_resend_verification_email_when_already_verified()
    {
        self::fakeSuccessfulRecaptchaResponse();

        Notification::fake();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/auth/verify-email/resend', [
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/dashboard');
        $response->assertSessionHas('status', 'Email already verified');
        Notification::assertNotSentTo($user, VerifyEmail::class);
    }

    public function test_throttle_middleware_works_for_email_verification_resend()
    {
        self::fakeSuccessfulRecaptchaResponse();

        Notification::fake();

        $user = User::factory()->unverified()->create();

        // Send request 7 times since 6 is the max allowed before throttling
        for ($i = 0; $i < 7; $i++) {
            $response = $this->actingAs($user)->post('/auth/verify-email/resend', [
                'recaptcha_response' => Str::random(40),
                'recaptcha_action' => 'test',
            ]);
        }

        $response->assertStatus(429);
    }

    public function test_recaptcha_action_is_required()
    {
        self::fakeSuccessfulRecaptchaResponse();

        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->post('/auth/verify-email/resend', [
            'recaptcha_response' => Str::random(40),
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'recaptcha_action' => 'Recaptcha action is required.',
        ]);
    }

    public function test_recaptcha_action_must_be_string()
    {
        self::fakeSuccessfulRecaptchaResponse();

        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->post('/auth/verify-email/resend', [
            'recaptcha_action' => 1,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'recaptcha_action' => 'Recaptcha action is invalid.',
        ]);
    }

    public function test_recaptcha_response_is_required()
    {
        self::fakeUnsuccessfulRecaptchaResponse();

        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->post('/auth/verify-email/resend', [
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'recaptcha_response' => 'Recaptcha response is required.',
        ]);
    }

    public function test_recaptcha_response_must_be_string()
    {
        self::fakeSuccessfulRecaptchaResponse();

        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->post('/auth/verify-email/resend', [
            'recaptcha_response' => 1,
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'recaptcha_response' => 'Recaptcha response is invalid.',
        ]);
    }

    public function test_recaptcha_response_must_pass()
    {
        self::fakeUnsuccessfulRecaptchaResponse();

        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->post('/auth/verify-email/resend', [
            'recaptcha_response' => Str::random(40),
            'recaptcha_action' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'recaptcha_response' => 'Recaptcha failed.',
        ]);
    }
}
