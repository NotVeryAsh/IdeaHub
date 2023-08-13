<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_can_be_viewed_without_authentication()
    {
        $response = $this->get('/home');
        $response->assertStatus(200);
    }

    public function test_home_view_is_returned()
    {
        $response = $this->get('/home');
        $response->assertViewIs('home');
    }

    public function test_home_view_contains_correct_data()
    {
        $response = $this->get('/home');
        $response->assertSee('Welcome to Idea Hub');
    }

    public function test_can_see_home_view_when_authenticated()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/home');
        $response->assertStatus(200);
    }
}
