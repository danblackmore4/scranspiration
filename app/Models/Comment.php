<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Comment Model
 *
 * Represents a comment made by a user on a recipe.
 * A comment belongs to one user and one recipe.
 */
class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'body',
        'user_id',
        'recipe_id',
        'content',
    ];

    /**
     * Relationship: A comment belongs to the user who wrote it.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: A comment belongs to a specific recipe.
     */
    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }
}
