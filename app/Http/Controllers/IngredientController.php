<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingredient;

class IngredientController extends Controller
{
    /**
     * Search ingredients for autocomplete / Livewire search box.
     *
     * Example: GET /api/ingredients/search?q=rice
     */
    public function search(Request $request)
    {
        // Validate input
        $request->validate([
            'q' => 'nullable|string|max:100'
        ]);

        $query = $request->get('q', '');

        // Basic search: find ingredients whose name contains the query
        $ingredients = Ingredient::where('name', 'LIKE', "%{$query}%")
            ->orderBy('name')
            ->limit(15)
            ->get([
                'id',
                'name',
                'calories_per_100g',
                'protein_per_100g',
                'carbs_per_100g',
                'fats_per_100g',
                'brand',
                'barcode',
                'image_url'
            ]);

        return response()->json($ingredients);
    }

    /**
     * Create a custom ingredient (for when user adds their own).
     *
     * Example POST /api/ingredients
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'               => 'required|string|max:255',
            'brand'              => 'nullable|string|max:255',
            'barcode'            => 'nullable|string|max:50',
            'calories_per_100g'  => 'required|numeric|min:0',
            'protein_per_100g'   => 'required|numeric|min:0',
            'carbs_per_100g'     => 'required|numeric|min:0',
            'fats_per_100g'      => 'required|numeric|min:0',
            'image_url'          => 'nullable|string|max:500',
        ]);

        $ingredient = Ingredient::create([
            'name'               => $request->name,
            'brand'              => $request->brand,
            'barcode'            => $request->barcode,
            'calories_per_100g'  => $request->calories_per_100g,
            'protein_per_100g'   => $request->protein_per_100g,
            'carbs_per_100g'     => $request->carbs_per_100g,
            'fats_per_100g'      => $request->fats_per_100g,
            'image_url'          => $request->image_url,
        ]);

        return response()->json($ingredient, 201);
    }
}
