<?php

use App\Models\User;
use Tests\TestCase;

class HomeTest extends TestCase
{
    public function test_home_can_be_viewed_without_authentication()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_home_view_is_returned()
    {
        $response = $this->get('/');
        $response->assertViewIs('home');
    }

    public function test_home_view_contains_correct_data()
    {
        $response = $this->get('/');
        $response->assertSee('Welcome to Idea Hub');
    }

    public function test_can_see_home_view_when_authenticated()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/');
        $response->assertStatus(200);
    }
}
