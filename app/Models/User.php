<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * User Model
 *
 * Represents an authenticated user in the system.
 * Each user:
 * - Belongs to one Role
 * - Has one Profile
 * - Has many Recipes
 * - Has many Comments
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Relationship: A User belongs to one Role.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Relationship: A User can have many Recipes.
     */
    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }

    /**
     * Relationship: A User can have many Comments.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Relationship: A User has one Profile.
     */
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function isCreator(): bool
    {
        return $this->role && $this->role->name === 'creator';
    }

    public function isUser(): bool
    {
        return $this->role && $this->role->name === 'user';
    }

}
