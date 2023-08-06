<?php

namespace Auth;

use App\Models\User;
use Tests\TestCase;

class ShowVerifyEmailNoticeTest extends TestCase
{
    public function test_can_see_email_notice_page_if_user_unverified()
    {
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get('/auth/verify-email');

        $response->assertStatus(200);
        $response->assertViewIs('auth.verify-email-notice');
        $response->assertSeeInOrder([
            'Email Verification Sent! Check your email for a verification link.',
            '/auth/verify-email/resend',
            'Resend Verification Email',
        ]);
    }

    public function test_can_not_resend_verification_email_when_already_verified()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/auth/verify-email');

        $response->assertStatus(302);
        $response->assertRedirect('/dashboard');
        $response->assertSessionHas('message', 'Email already verified');
    }

    public function test_user_is_redirected_to_login_page_if_unauthenticated()
    {
        $response = $this->get('/auth/verify-email');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }
}
