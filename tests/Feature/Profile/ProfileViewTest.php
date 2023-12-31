<?php

namespace Profile;

use App\Models\User;
use Tests\TestCase;

class ProfileViewTest extends TestCase
{
    public function test_viewing_profile_page_requires_authentication()
    {
        $response = $this->get('/profile');
        $response->assertRedirectToRoute('login');
    }

    public function test_profile_view_is_returned()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/profile');
        $response->assertViewIs('profile.index');
    }

    public function test_profile_view_contains_correct_data_when_viewing_as_self()
    {
        $user = User::factory()->create([
            'username' => 'johndoe',
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        $this->actingAs($user);

        $response = $this->get('/profile');
        $response->assertSeeInOrder([
            'Viewing your profile',
            'johndoe',
            'John',
            'Doe',
        ]);
    }

    public function test_profile_view_contains_correct_data_when_viewing_as_other_person()
    {
        User::factory()->create([
            'username' => 'johndoe',
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/profile/johndoe');
        $response->assertSeeInOrder([
            'johndoe',
            'John',
            'Doe',
        ]);
    }
}
