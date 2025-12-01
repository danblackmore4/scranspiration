<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\Api\FoodSearchController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| These routes return JSON only (not views).
| Perfect for search boxes, Livewire calls, and barcode scanning later.
|
*/
Route::get('/debug/test', function () {
    return ['api_loaded' => true];
});


Route::get('/ingredients/search', [IngredientController::class, 'search']);

Route::get('/foods/search', [FoodSearchController::class, 'index']);
