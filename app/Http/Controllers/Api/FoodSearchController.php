<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\OpenFoodFactsService;

class FoodSearchController extends Controller
{
    public function index(Request $request, OpenFoodFactsService $service)
    {
        $request->validate([
            'q' => 'required|min:2',
        ]);

        info('CONTROLLER HIT with q=' . $request->q);

        return $service->search($request->q);
    }
}
