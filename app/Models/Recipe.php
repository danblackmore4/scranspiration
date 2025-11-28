<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Recipe Model
 *
 * Represents a gym meal recipe created by a user.
 * Belongs to one category and one user.
 * Has many comments and many ingredients.
 */
class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'category_id',
        'user_id',
        'calories',
        'protein',
        'carbs',
        'fats',
        'servings',
        'instructions',
    ];

    public function totalCalories()
    {
        return $this->ingredients->sum('pivot.calories');
    }

    public function totalProtein()
    {
        return $this->ingredients->sum('pivot.protein');
    }

    public function totalCarbs()
    {
        return $this->ingredients->sum('pivot.carbs');
    }

    public function totalFats()
    {
        return $this->ingredients->sum('pivot.fats');
    }

    /**
     * Relationship: A Recipe belongs to the User who created it.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: A Recipe belongs to one Category.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relationship: A Recipe has many Comments.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Relationship: A Recipe belongs to many Ingredients.
     */
    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class);
    }
}
