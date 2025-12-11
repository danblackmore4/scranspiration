<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    public function show(User $user)
    {
        // Load posts (recipes) and comments
        $recipes = $user->recipes()->latest()->get();

        // Show all comments they've made
        $comments = $user->comments()->with('recipe')->latest()->get();

        return view('users.profile', compact('user', 'recipes', 'comments'));
    }
}
