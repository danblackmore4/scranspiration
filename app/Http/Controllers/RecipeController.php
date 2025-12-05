<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    // Show all recipes
    public function index()
    {
        $recipes = Recipe::with('user', 'category')->latest()->get();
        return view('recipes.index', compact('recipes'));
    }

    public function show(Recipe $recipe)
    {
        $recipe->load([
            'category',
            'user',
            'ingredients' => fn ($q) => $q->withPivot(['amount', 'calories', 'protein', 'carbs', 'fats'])
        ]);

        $otherRecipes = Recipe::where('id', '!=', $recipe->id)->latest()->get();

        return view('recipes.show', compact('recipe', 'otherRecipes'));
    }
}
