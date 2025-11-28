<?php
use Livewire\Volt\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\Recipe;

new class extends Component {
    

    public string $title = '';
    public string $description = '';
    public int $servings = 1;
    public string $instructions = '';
    public int $category_id = 1;

    public string $search = '';
    public array $searchResults = [];
    public array $ingredients = [];

    public function updatedSearch()
    {
        if (strlen($this->search) < 2) {
            $this->searchResults = [];
            return;
        }

        $results = Http::get('https://api.calorieninjas.com/v1/nutrition', [
            'query' => $this->search,
        ])->json();

        $this->searchResults = $results['items'] ?? [];
    }

    public function addIngredient($name)
    {
        $this->ingredients[] = [
            'name' => $name,
            'grams' => 100,
        ];
    }

    public function removeIngredient($index)
    {
        unset($this->ingredients[$index]);
        $this->ingredients = array_values($this->ingredients);
    }

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

        foreach ($this->ingredients as $ingredient) {
            $recipe->ingredients()->create($ingredient);
        }

        return redirect('/')->with('success', 'Recipe created!');
    }
};

?>

<div class="max-w-2xl mx-auto py-10">

    <h1 class="text-3xl font-bold mb-6">Add a New Recipe</h1>

    <form wire:submit.prevent="save">

        <div class="mb-4">
            <label class="block font-semibold">Title</label>
            <input type="text" wire:model="title" class="w-full border rounded p-2">
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Description</label>
            <textarea wire:model="description" class="w-full border rounded p-2"></textarea>
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Servings</label>
            <input type="number" wire:model="servings" class="w-full border rounded p-2">
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Instructions</label>
            <textarea wire:model="instructions" class="w-full border rounded p-2"></textarea>
        </div>

        <h2 class="text-xl font-semibold mt-6 mb-2">Ingredients</h2>

        <div class="mb-4">
            <label class="block font-semibold">Search Ingredient</label>
            <input type="text" wire:model.live="search" class="w-full border rounded p-2">
        </div>

        <div>
            @foreach ($searchResults as $result)
                <button type="button" wire:click="addIngredient('{{ $result['name'] }}')"
                        class="block w-full text-left p-2 border-b hover:bg-gray-100">
                    {{ $result['name'] }}
                </button>
            @endforeach
        </div>

        <div class="mt-4">
            <h3 class="font-semibold">Selected Ingredients:</h3>
            @foreach ($ingredients as $index => $ingredient)
                <div class="flex items-center justify-between p-2 border-b">
                    <span>{{ $ingredient['name'] }}</span>

                    <input type="number" wire:model="ingredients.{{ $index }}.grams"
                           class="w-20 border rounded p-1">

                    <button type="button" wire:click="removeIngredient({{ $index }})"
                            class="text-red-600 font-semibold">
                        Remove
                    </button>
                </div>
            @endforeach
        </div>

        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded mt-6">
            Save Recipe
        </button>

    </form>
</div>
