<?php

namespace Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_logout()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/auth/logout');

        $this->assertGuest();
        $response->assertRedirectToRoute('home');
    }

    public function test_user_is_redirected_to_login_page_if_not_logged_in()
    {
        $response = $this->post('/auth/logout');

        $this->assertGuest();
        $response->assertRedirectToRoute('home');
    }
}
