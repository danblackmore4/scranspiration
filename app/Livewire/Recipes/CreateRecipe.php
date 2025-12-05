<?php

namespace App\Livewire\Recipes;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Recipe;
use App\Models\Ingredient;
use App\Services\OpenFoodFactsService;
use Illuminate\Support\Facades\Auth;

class CreateRecipe extends Component
{
    use WithFileUploads;

    public string $title = '';
    public string $description = '';
    public int $servings = 1;
    public string $instructions = '';
    public int $category_id = 1;

    public string $search = '';
    public array $searchResults = [];
    public array $ingredients = [];
    public $image;

    /**
     * Search OpenFoodFacts via your service.
     */
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

    public function addIngredient($index)
    {
        $item = $this->searchResults[$index] ?? null;
        if (!$item) return;

        $this->ingredients[] = [
            'name'          => $item['name'],
            'brand'         => $item['brand'] ?? null,
            'barcode'       => $item['id'],
            'calories_100g' => $item['nutrients']['calories_100g'],
            'protein_100g'  => $item['nutrients']['protein_100g'],
            'carbs_100g'    => $item['nutrients']['carbs_100g'],
            'fats_100g'     => $item['nutrients']['fats_100g'],
            'grams'         => 100,
        ];

        $this->search = '';
        $this->searchResults = [];
    }

    public function removeIngredient($index)
    {
        unset($this->ingredients[$index]);
        $this->ingredients = array_values($this->ingredients);
    }

    public function save()
    {
        // (optional) validation – you can add back your rules here if you like
        // $this->validate([...]);

        $imagePath = null;

        if ($this->image) {
            $imagePath = $this->image->store('recipe_images', 'public');
        }


        $recipe = Recipe::create([
            'user_id'      => Auth::id(),
            'title'        => $this->title,
            'description'  => $this->description,
            'servings'     => $this->servings,
            'instructions' => $this->instructions,
            'category_id'  => $this->category_id,
            'image'        => $imagePath,
        ]);

        $totalCalories = 0;
        $totalProtein  = 0;
        $totalCarbs    = 0;
        $totalFats     = 0;

        foreach ($this->ingredients as $item) {
            $ingredient = Ingredient::firstOrCreate(
                ['name' => $item['name']],
                [
                    'brand'              => $item['brand'],
                    'barcode'            => $item['barcode'],
                    'calories_per_100g'  => $item['calories_100g'],
                    'protein_per_100g'   => $item['protein_100g'],
                    'carbs_per_100g'     => $item['carbs_100g'],
                    'fats_per_100g'      => $item['fats_100g'],
                ]
            );

            $factor = $item['grams'] / 100;

            $cal = $item['calories_100g'] * $factor;
            $pro = $item['protein_100g']  * $factor;
            $car = $item['carbs_100g']    * $factor;
            $fat = $item['fats_100g']     * $factor;

            $totalCalories += $cal;
            $totalProtein  += $pro;
            $totalCarbs    += $car;
            $totalFats     += $fat;

            $recipe->ingredients()->attach($ingredient->id, [
                'amount'   => $item['grams'],
                'calories' => $cal,
                'protein'  => $pro,
                'carbs'    => $car,
                'fats'     => $fat,
            ]);
        }

        $recipe->update([
            'calories' => round($totalCalories),
            'protein'  => round($totalProtein),
            'carbs'    => round($totalCarbs),
            'fats'     => round($totalFats),
        ]);

        return redirect('/')->with('success', 'Recipe created!');
    }

    public function render()
    {
        return view('livewire.recipes.create');
    }
}
