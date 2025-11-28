<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Category Factory
 *
 * Generates sample recipe categories such as 'Bulking', 'Cutting', and 'Balanced'.
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Bulking', 'Cutting', 'Balanced']),
        ];
    }
}
