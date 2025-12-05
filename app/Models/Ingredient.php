<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Ingredient Model
 *
 * Represents an ingredient used in a recipe.
 * Ingredients and recipes share a many-to-many relationship.
 */
class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'brand',
        'barcode',
        'image_url',
        'calories_per_100g',
        'protein_per_100g',
        'carbs_per_100g',
        'fats_per_100g',
    ];

    /**
     * Relationship: An Ingredient belongs to many Recipes.
     */
    public function recipes()
    {
        return $this->belongsToMany(Recipe::class)
                    ->withPivot('amount', 'calories', 'protein', 'carbs', 'fats')
                    ->withTimestamps();
    }
}
