<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Category;
use App\Models\Recipe;
use App\Models\Comment;
use App\Models\Ingredient;
use App\Models\Profile;

/**
 * Database Seeder
 *
 * Seeds the application's database with sample data demonstrating:
 * - One-to-One (User ↔ Profile)
 * - One-to-Many (User ↔ Recipes, Category ↔ Recipes)
 * - Many-to-Many (Recipe ↔ Ingredients)
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Creates realistic, related data for all models.
     */
    public function run(): void
    {
        /**
         * Create Roles (e.g. admin, user)
         */
        $roles = Role::factory()->count(2)->create();

        /**
         * Create Categories (e.g. Bulking, Cutting, Balanced)
         */
        $categories = Category::factory()->count(3)->create();

        /**
         * Create Ingredients (for many-to-many relationship)
         */
        $ingredients = Ingredient::factory()->count(10)->create();

        /**
         * Create Users with Roles and Profiles
         */
        $users = User::factory(10)->create()->each(function ($user) use ($roles) {

            // Assign each user a random role (one-to-many)
            $user->role_id = $roles->random()->id;
            $user->save();

            // Create a unique profile for each user (one-to-one)
            Profile::factory()->create([
                'user_id' => $user->id,
            ]);
        });

        /**
         * Create Recipes, attach Ingredients, and add Comments
         */
        $users->each(function ($user) use ($categories, $ingredients) {

            // Each user creates three recipes (one-to-many)
            Recipe::factory(3)->create([
                'user_id' => $user->id,
                'category_id' => $categories->random()->id,
            ])->each(function ($recipe) use ($user, $ingredients) {

                // Attach 2–5 random ingredients (many-to-many)
                $recipe->ingredients()->attach(
                    $ingredients->random(rand(2, 5))->pluck('id')->toArray()
                );

                // Each recipe gets two comments from its creator
                Comment::factory(2)->create([
                    'user_id' => $user->id,
                    'recipe_id' => $recipe->id,
                ]);
            });
        });

        $this->call(RecipeSeeder::class);
    }
}
