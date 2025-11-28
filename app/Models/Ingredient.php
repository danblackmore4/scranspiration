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

    protected $fillable = ['name'];

    /**
     * Relationship: An Ingredient belongs to many Recipes.
     */
    public function recipes()
    {
        return $this->belongsToMany(Recipe::class);
    }
}
