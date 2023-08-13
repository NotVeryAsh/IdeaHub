<?php

namespace Auth;

use App\Models\User;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    public function test_user_can_logout()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/auth/login');
    }

    public function test_user_is_redirected_to_login_page_if_not_logged_in()
    {
        $response = $this->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/auth/login');
    }
}
