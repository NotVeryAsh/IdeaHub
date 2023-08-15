<?php

use App\Models\User;
use Tests\TestCase;

class DashboardViewTest extends TestCase
{
    public function test_viewing_dashboard_requires_authentication()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirectToRoute('login');
    }

    public function test_dashboard_view_is_returned()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/dashboard');
        $response->assertViewIs('dashboard');
    }

    public function test_dashboard_view_contains_correct_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/dashboard');
        $response->assertSee("Hey, $user->username");
    }
}
