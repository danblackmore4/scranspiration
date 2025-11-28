<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Role Model
 *
 * Represents a user role (e.g. Admin, User).
 * One role can be assigned to many users.
 */
class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Relationship: One Role has many Users.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
