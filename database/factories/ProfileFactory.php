<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * Profile Factory
 *
 * Generates a profile for each user, including their goal, bio, and avatar.
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(), // Creates a new user if not provided
            'bio' => fake()->sentence(),
            'goal' => fake()->randomElement(['Bulking', 'Cutting', 'Maintenance']),
            'avatar' => fake()->imageUrl(200, 200, 'people'),
        ];
    }
}
