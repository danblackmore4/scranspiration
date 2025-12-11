<?php

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\CommentController;
use App\Livewire\Recipes\CreateRecipe;
use Livewire\Volt\Volt;

use App\Livewire\Recipes\Index;

// Public homepage using Volt component
Route::get('/', [RecipeController::class, 'index'])->name('recipes.index');


// Your recipe routes
Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes.index');
Route::get('/recipes/{recipe}', [RecipeController::class, 'show'])->name('recipes.show');
Route::post('/recipes/{recipe}/comment', [RecipeController::class, 'addComment'])->name('recipes.comment');
Route::delete('/recipes/{recipe}', [RecipeController::class, 'destroy'])->name('recipes.destroy');

// Comments

Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

Route::get('/search', [RecipeController::class, 'search'])->name('recipes.search');

// User profiles
Route::get('/user/{user}', [ProfileController::class, 'show'])->name('user.profile');

Route::get('/recipes/{recipe}', [RecipeController::class, 'show'])
    ->where('recipe', '[0-9]+')
    ->name('recipes.show');


Volt::route('/test', 'test');

Route::get('/force-logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('force.logout')->middleware('auth');


Route::post('/notification/{id}/read', function ($id) {
    /** @var \App\Models\User $user */
    $user = Auth::user();

    if (!$user) {
        abort(401);
    }

    $notification = $user->notifications()->find($id);

    if ($notification) {
        $notification->markAsRead();
    }

    return back();
})->middleware('auth')->name('notifications.read');



// Auth protected routes
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/recipes/create', CreateRecipe::class)->name('recipes.create');

});

require __DIR__ . '/auth.php';
