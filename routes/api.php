<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IngredientController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| These routes return JSON only (not views).
| Perfect for search boxes, Livewire calls, and barcode scanning later.
|
*/

Route::get('/ingredients/search', [IngredientController::class, 'search']);
