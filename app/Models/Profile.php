<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

/**
 * Profile Model
 *
 * Represents a user's extended profile (e.g. bio, goal, avatar).
 * Each user has exactly one profile.
 */
class Profile extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'bio', 'goal', 'avatar'];

    /**
     * Relationship: A Profile belongs to one User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
