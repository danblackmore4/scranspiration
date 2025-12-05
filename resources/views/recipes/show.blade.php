<x-app-layout>
    <div class="max-w-6xl mx-auto mt-8">

        <a href="{{ route('recipes.index') }}" class="text-blue-600 underline">&larr; Back to recipes</a>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-6">

            <div>
                @if ($recipe->image)
                    <img src="{{ asset('storage/' . $recipe->image) }}"
                         class="w-full h-80 object-cover rounded-xl shadow">
                @endif
            </div>

            <div>

                <h1 class="text-3xl font-bold mb-3">{{ $recipe->title }}</h1>

                <p class="text-gray-700 mb-4">{{ $recipe->description }}</p>

                <h2 class="font-semibold text-xl mb-2">Macros</h2>
                <ul class="mb-6">
                    <li>Calories: {{ $recipe->calories }}</li>
                    <li>Protein: {{ $recipe->protein }} g</li>
                    <li>Carbs: {{ $recipe->carbs }} g</li>
                    <li>Fats: {{ $recipe->fats }} g</li>
                </ul>

                <h2 class="font-semibold text-xl mb-2">Ingredients</h2>
                @foreach ($recipe->ingredients as $ingredient)
                    <div class="mb-2">
                        <span class="font-semibold">{{ $ingredient->name }}</span>
                        — {{ $ingredient->pivot->amount }} g
                    </div>
                @endforeach

                <h2 class="font-semibold text-xl mt-6 mb-2">Instructions</h2>
                <p class="whitespace-pre-line">{{ $recipe->instructions }}</p>

                <h2 class="font-semibold text-xl mt-6">Servings</h2>
                <p>{{ $recipe->servings }}</p>

            </div>
        </div>

        <h2 class="text-2xl font-bold mt-12 mb-4">More Recipes</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($otherRecipes as $r)
                <a href="{{ route('recipes.show', $r->id) }}"
                   class="block bg-white rounded-xl shadow p-4">
                    <h3 class="text-lg font-semibold">{{ $r->title }}</h3>
                    <p class="text-sm text-gray-600">{{ $r->category->name ?? '' }}</p>
                </a>
            @endforeach
        </div>

    </div>
</x-app-layout>
