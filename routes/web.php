<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use Livewire\Volt\Volt;
use App\Models\Recipe;

// Public homepage
Route::get('/', function () {
    $recipes = Recipe::with(['category', 'user'])->latest()->get();
    return view('recipes.index', compact('recipes'));
});

// Auth protected routes
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Volt::route('/recipes/create', 'recipes.create')->name('recipes.create');
});

require __DIR__ . '/auth.php';
