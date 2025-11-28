<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Recipe;
use App\Models\User;
use App\Models\Category;

class RecipeSeeder extends Seeder
{
    public function run(): void
    {
        $recipes = [
            // I will insert the 30 recipes here in the final version
        ];

        foreach ($recipes as $data) {
            Recipe::create([
                'title' => $data['title'],
                'description' => $data['description'],
                'servings' => $data['servings'],
                'calories' => $data['calories'],
                'protein' => $data['protein'],
                'carbs' => $data['carbs'],
                'fats' => $data['fats'],
                'instructions' => $data['instructions'],

                // TEMP for now — replace once we align your categories + users
                'user_id' => User::inRandomOrder()->first()->id,
                'category_id' => Category::inRandomOrder()->first()->id,
            ]);
        }
    }
}
