<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecipeController;
use App\Livewire\Recipes\CreateRecipe;
use Livewire\Volt\Volt;

use App\Livewire\Recipes\Index;

// Public homepage using Volt component
Route::get('/', [RecipeController::class, 'index'])->name('recipes.index');


Route::get('/recipes/{recipe}', [RecipeController::class, 'show'])
    ->where('recipe', '[0-9]+')
    ->name('recipes.show');


Volt::route('/test', 'test');


// Auth protected routes
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/recipes/create', CreateRecipe::class)->name('recipes.create');

});

require __DIR__ . '/auth.php';
