<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\Recipe;
use App\Models\Ingredient;
use App\Services\OpenFoodFactsService;


new class extends Component {

    public string $title = '';
    public string $description = '';
    public int $servings = 1;
    public string $instructions = '';
    public int $category_id = 1;

    public string $search = '';
    public array $searchResults = [];

    /**
     * ingredients[] structure:
     * [
     *   'name' => string,
     *   'brand' => string|null,
     *   'barcode' => string|null,
     *   'calories_100g' => float,
     *   'protein_100g' => float,
     *   'carbs_100g' => float,
     *   'fats_100g' => float,
     *   'grams' => number,
     * ]
     */
    public array $ingredients = [];

    // Search OpenFoodFacts API via own backend route
    public function updatedSearch()
{
    if (strlen($this->search) < 2) {
        $this->searchResults = [];
        return;
    }

    info('Search triggered: ' . $this->search);

    /** @var OpenFoodFactsService $service */
    $service = app(OpenFoodFactsService::class);

    $this->searchResults = $service->search($this->search);

    info('Results count: ' . count($this->searchResults));
}


    // Add ingredient from the search results
    public function addIngredient($index)
    {
        $item = $this->searchResults[$index] ?? null;
        if (!$item) return;

        $this->ingredients[] = [
            'name' => $item['name'],
            'brand' => $item['brand'] ?? null,
            'barcode' => $item['id'],

            'calories_100g' => $item['nutrients']['calories_100g'],
            'protein_100g'  => $item['nutrients']['protein_100g'],
            'carbs_100g'    => $item['nutrients']['carbs_100g'],
            'fats_100g'     => $item['nutrients']['fats_100g'],

            'grams' => 100, // default editable amount
        ];

        $this->search = '';
        $this->searchResults = [];
    }

    // Remove ingredient
    public function removeIngredient($index)
    {
        unset($this->ingredients[$index]);
        $this->ingredients = array_values($this->ingredients);
    }

    // Save recipe, ingredients, and pivot macro data
    public function save()
    {
        $recipe = Recipe::create([
            'user_id' => Auth::id(),
            'title' => $this->title,
            'description' => $this->description,
            'servings' => $this->servings,
            'instructions' => $this->instructions,
            'category_id' => $this->category_id,
        ]);

        $totalCalories = 0;
        $totalProtein = 0;
        $totalCarbs = 0;
        $totalFats = 0;

        foreach ($this->ingredients as $item) {

            // Create / Find the ingredient in the DB
            $ingredient = Ingredient::firstOrCreate(
                ['name' => $item['name']],
                [
                    'brand' => $item['brand'],
                    'barcode' => $item['barcode'],
                    'calories_per_100g' => $item['calories_100g'],
                    'protein_per_100g'  => $item['protein_100g'],
                    'carbs_per_100g'    => $item['carbs_100g'],
                    'fats_per_100g'     => $item['fats_100g'],
                ]
            );

            // Calculate macros for the chosen amount
            $factor = $item['grams'] / 100;

            $cal = $item['calories_100g'] * $factor;
            $pro = $item['protein_100g']  * $factor;
            $car = $item['carbs_100g']    * $factor;
            $fat = $item['fats_100g']     * $factor;

            $totalCalories += $cal;
            $totalProtein  += $pro;
            $totalCarbs    += $car;
            $totalFats     += $fat;

            // Attach pivot row
            $recipe->ingredients()->attach($ingredient->id, [
                'amount' => $item['grams'],
                'calories' => $cal,
                'protein' => $pro,
                'carbs' => $car,
                'fats' => $fat,
            ]);
        }

        // Update recipe totals
        $recipe->update([
            'calories' => round($totalCalories),
            'protein'  => round($totalProtein),
            'carbs'    => round($totalCarbs),
            'fats'     => round($totalFats),
        ]);

        return redirect('/')->with('success', 'Recipe created!');
    }
};

?>

<div class="max-w-2xl mx-auto py-10">

    <h1 class="text-3xl font-bold mb-6">Add a New Recipe</h1>

    <form wire:submit.prevent="save">

        <!-- Title -->
        <div class="mb-4">
            <label class="block font-semibold">Title</label>
            <input type="text" wire:model="title" class="w-full border rounded p-2">
        </div>

        <!-- Description -->
        <div class="mb-4">
            <label class="block font-semibold">Description</label>
            <textarea wire:model="description" class="w-full border rounded p-2"></textarea>
        </div>

        <!-- Servings -->
        <div class="mb-4">
            <label class="block font-semibold">Servings</label>
            <input type="number" wire:model="servings" class="w-full border rounded p-2">
        </div>

        <!-- Instructions -->
        <div class="mb-4">
            <label class="block font-semibold">Instructions</label>
            <textarea wire:model="instructions" class="w-full border rounded p-2"></textarea>
        </div>

        <!-- Ingredient Search -->
        <h2 class="text-xl font-semibold mt-6 mb-2">Ingredients</h2>

        <div class="mb-4">
            <label class="block font-semibold">Search Ingredient</label>
            <input type="text" wire:model.live="search" class="w-full border rounded p-2">
        </div>

        <!-- Search Results -->
        @if(!empty($searchResults))
            <div class="border rounded bg-white shadow mb-4 max-h-60 overflow-y-auto">
                @foreach ($searchResults as $index => $result)
                    <button 
                        type="button"
                        wire:click="addIngredient({{ $index }})"
                        class="block w-full text-left p-2 border-b hover:bg-gray-100">
                        
                        <div class="font-semibold">{{ $result['name'] }}</div>
                        <div class="text-sm text-gray-500">
                            {{ $result['brand'] ?? 'Unknown brand' }}
                        </div>
                    </button>
                @endforeach
            </div>
        @endif


        <!-- Selected Ingredients -->
        <div class="mt-4">
            <h3 class="font-semibold">Selected Ingredients:</h3>

            @foreach ($ingredients as $index => $ingredient)
                <div class="flex items-center justify-between p-2 border-b">

                    <div>
                        <span class="font-semibold">{{ $ingredient['name'] }}</span>
                        <div class="text-sm text-gray-500">{{ $ingredient['brand'] }}</div>
                    </div>

                    <input type="number"
                           wire:model="ingredients.{{ $index }}.grams"
                           class="w-20 border rounded p-1">

                    <button type="button"
                            wire:click="removeIngredient({{ $index }})"
                            class="text-red-600 font-semibold">
                        Remove
                    </button>
                </div>
            @endforeach
        </div>

        <!-- Submit -->
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded mt-6">
            Save Recipe
        </button>

    </form>

</div>
