<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Tests\TestCase;

class VerifyEmailTest extends TestCase
{
    public function test_can_verify_email()
    {
        Notification::fake();

        $user = User::factory()->unverified()->create();

        // create verify email
        $mailable = new VerifyEmail();
        $mail = $mailable->toMail($user);

        // get verify email url
        $url = $mail->actionUrl;

        // make a response to the url as the user
        $response = $this->actingAs($user)->get($url);

        $response->assertStatus(302);
        $response->assertRedirect('/dashboard');
    }

    public function test_can_not_verify_email_when_already_verified()
    {
        Notification::fake();

        $user = User::factory()->create();

        // create verify email
        $mailable = new VerifyEmail();
        $mail = $mailable->toMail($user);

        // get verify email url
        $url = $mail->actionUrl;

        // make a response to the url as the user
        $response = $this->actingAs($user)->get($url);
        $response->assertStatus(302);
        $response->assertRedirect('/dashboard');
        $response->assertSessionHas('message', 'Email already verified!');
    }

    public function test_can_not_verify_email_when_invalid_signature()
    {
        Notification::fake();

        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get('/auth/verify-email/invalid/invalid');

        $response->assertStatus(403);
    }

    public function test_can_not_verify_email_when_signature_expired()
    {
        Notification::fake();

        $user = User::factory()->unverified()->create();

        // create verify email
        $mailable = new VerifyEmail();
        $mail = $mailable->toMail($user);

        // get verify email url
        $url = $mail->actionUrl;

        // make a response to the url as the user
        $response = $this->actingAs($user)->get($url);

        $url = Str::replaceLast('expires=', 'expires=1', $url);

        $response = $this->actingAs($user)->get($url);

        $response->assertStatus(403);
    }

    public function test_can_not_verify_email_for_invalid_user()
    {
        Notification::fake();

        $user = User::factory()->unverified()->create();

        // create verify email
        $mailable = new VerifyEmail();
        $mail = $mailable->toMail($user);

        // get verify email url
        $url = $mail->actionUrl;

        // make request to url as invalid user
        $invalidUserId = $user->id + 1;

        $url = Str::replaceLast("$user->id", "$invalidUserId", $url);

        $response = $this->actingAs($user)->get($url);

        $response->assertStatus(403);
    }

    public function test_user_is_redirected_to_email_notice_page_when_not_verified()
    {
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(302);
        $response->assertRedirect('/auth/verify-email');
    }
}
