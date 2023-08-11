<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ResendVerifyEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_resend_verification_email()
    {
        Notification::fake();

        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->post('/auth/verify-email/resend');

        $response->assertStatus(302);
        $response->assertRedirect('/auth/verify-email');
        $response->assertSessionHas('message', 'Verification link sent!');
        Notification::assertSentTo($user, VerifyEmail::class);
    }

    public function test_can_not_resend_verification_email_when_already_verified()
    {
        Notification::fake();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/auth/verify-email/resend');

        $response->assertStatus(302);
        $response->assertRedirect('/dashboard');
        $response->assertSessionHas('message', 'Email already verified');
        Notification::assertNotSentTo($user, VerifyEmail::class);
    }

    public function test_throttle_middleware_works_for_email_verification_resend()
    {
        Notification::fake();

        $user = User::factory()->unverified()->create();

        // Send request 7 times since 6 is the max allowed before throttling
        for ($i = 0; $i < 7; $i++) {
            $response = $this->actingAs($user)->post('/auth/verify-email/resend');
        }

        $response->assertStatus(429);
        Notification::assertSentToTimes($user, VerifyEmail::class, 6);
    }
}
