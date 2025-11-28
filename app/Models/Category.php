<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Category Model
 *
 * Represents a category of recipes (e.g. Bulking, Cutting, Balanced).
 * One category can contain many recipes.
 */
class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Relationship: One Category has many Recipes.
     */
    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }
}
