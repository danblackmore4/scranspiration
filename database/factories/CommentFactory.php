<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Recipe;

/**
 * Comment Factory
 *
 * Generates realistic comments linked to users and recipes.
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'recipe_id' => Recipe::factory(),
            'body' => fake()->sentence(10),
        ];
    }
}
