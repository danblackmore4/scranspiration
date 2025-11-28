<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Ingredient Factory
 *
 * Generates a list of common gym-related food ingredients.
 */
class IngredientFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement([
                'Chicken Breast',
                'Oats',
                'Rice',
                'Peanut Butter',
                'Broccoli',
                'Eggs',
                'Spinach',
            ]),
        ];
    }
}
