<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;

class RegisterViewTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_can_see_page_if_user_is_unauthenticated()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertViewIs('auth.register');
        $response->assertSeeInOrder([
            'Register',
            '/auth/register',
        ]);
    }

    public function test_user_is_redirected_if_they_are_already_logged_in()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/register');

        $response->assertStatus(302);
        $response->assertRedirect('/dashboard');
    }

    public function test_recaptcha_data_is_present()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSeeInOrder([
            'recaptcha-protected-form',
            'data-sitekey',
            'data-action',
        ]);
    }
}
