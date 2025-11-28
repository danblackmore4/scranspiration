<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Role Factory
 *
 * Generates user roles such as 'admin' and 'user'.
 */
class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['admin', 'user']),
        ];
    }
}
