<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Category;

/**
 * Recipe Factory
 *
 * Generates recipes that include nutritional information and relationships
 * to users (creators) and categories (e.g. Bulking, Cutting).
 */
class RecipeFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(4),
            'calories' => fake()->numberBetween(200, 900),
            'protein' => fake()->numberBetween(10, 80),
            'carbs' => fake()->numberBetween(20, 150),
            'fats' => fake()->numberBetween(5, 50),
        ];
    }
}
