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
            'path' => config('filesystems.default_profile_pictures_path').'/'.Str::random(30).'.jpg',
        ];
    }

    public function configure(): static
    {
        return $this->afterMaking(function (DefaultProfilePicture $profilePicture) {

            // Fake create profile picture based on the path
            $image = fake()->image(null, 100, 100);

            // Store profile picture in storage
            Storage::put($profilePicture->path, $image);
        });
    }
}
