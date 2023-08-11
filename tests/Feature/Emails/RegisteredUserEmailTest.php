<?php

namespace Emails;

use App\Mail\RegisteredUser;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegisteredUserEmailTest extends TestCase
{
    public function test_registered_user_is_sent_to_correct_email_address()
    {
        Mail::fake();

        $user = User::factory()->create([
            'email' => 'test@test.com',
        ]);

        $mailable = new RegisteredUser($user);
        $mailable->to($user);
        $mailable->assertTo($user->email);
    }

    public function test_registered_user_is_sent_with_correct_subject()
    {
        Mail::fake();

        $user = User::factory()->create([
            'email' => 'test@test.com',
        ]);

        $mailable = new RegisteredUser($user);
        $mailable->assertHasSubject("Welcome to Idea Hub, $user->username!");
    }

    public function test_registered_user_is_sent_with_correct_markdown_content()
    {
        Mail::fake();

        $user = User::factory()->create([
            'email' => 'test@test.com',
            'username' => 'test',
        ]);

        $mailable = new RegisteredUser($user);
        $mailable->assertSeeInOrderInHtml([
            "Welcome to Idea Hub, $user->username!",
            route('dashboard'),
            'Go To Your Dashboard',
        ]);
    }

    public function test_registered_user_email_has_from_correct_address()
    {
        Mail::fake();

        $user = User::factory()->create([
            'email' => 'test@test.com',
        ]);

        $mailable = new RegisteredUser($user);
        $mailable->assertFrom('info@idea-hub.net', 'Idea Hub');
    }
}
