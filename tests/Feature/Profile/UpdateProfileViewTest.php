<?php

namespace Profile;

use App\Models\User;
use Tests\TestCase;

class UpdateProfileViewTest extends TestCase
{
    public function test_viewing_update_profile_page_requires_authentication()
    {
        $response = $this->get('/profile/edit');
        $response->assertRedirectToRoute('login');
    }

    public function test_update_profile_view_is_returned()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/profile/edit');
        $response->assertViewIs('profile.edit');
    }

    public function test_update_profile_view_contains_correct_data()
    {
        $user = User::factory()->create([
            'username' => 'johndoe',
            'email' => 'test@test.com',
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);
        $this->actingAs($user);

        $response = $this->get('/profile/edit');
        $response->assertSeeInOrder([
            'Edit Profile',
            'test@test.com',
            'johndoe',
            'John',
            'Doe',
        ]);
    }
}
