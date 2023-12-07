<?php

namespace Tests\Feature\Profile;

use App\Models\User;
use Tests\TestCase;

class ProfilePictureDisplayTest extends TestCase
{
    public function test_profile_picture_shows_first_characters_of_first_and_last_names()
    {
        $user = User::factory()->create([
            'first_name' => 'Xavier',
            'last_name' => 'Zachary',
        ]);

        $this->actingAs($user);

        $response = $this->get('/profile');

        // assert that the profile picture is showing these two characters
        $response->assertSeeText('XZ');
    }

    public function test_profile_picture_shows_first_characters_of_username()
    {
        $user = User::factory()->create([
            'username' => 'QX',
            'first_name' => null,
            'last_name' => null,
        ]);

        $this->actingAs($user);

        $response = $this->get('/profile');

        // assert that the profile picture is showing these two characters
        $response->assertSeeText('QX');
    }

    public function test_empty_profile_picture_is_displayed_when_user_is_not_logged_in()
    {
        $response = $this->get('/');

        $response->assertSeeInOrder(['Default Profile Picture']);
    }
}
