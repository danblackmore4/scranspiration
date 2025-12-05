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

        <!-- Image File Input -->
        <div class="mb-4">
            <label class="block font-semibold">Recipe Image</label>
            <input type="file" wire:model="image" class="w-full border rounded p-2">
        </div>

        @error('image')
            <div class="text-red-600 text-sm">{{ $message }}</div>
        @enderror

        @if ($image)
            <div class="mt-2">
                <img src="{{ $image->temporaryUrl() }}" class="w-48 rounded shadow">
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