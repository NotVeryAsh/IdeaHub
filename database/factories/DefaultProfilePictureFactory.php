<?php

namespace Database\Factories;

use App\Models\DefaultProfilePicture;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DefaultProfilePicture>
 */
class DefaultProfilePictureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'path' => 'images/default/profile_pictures/'.Str::random(30).'.jpg',
        ];
    }

    public function configure(): static
    {
        return $this->afterMaking(function (DefaultProfilePicture $profilePicture) {

            // Fake create an image
            $image = fake()->image(null, 100, 100);

            // Storage image in storage
            Storage::put($profilePicture->path, $image);
        });
    }
}
