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
}
