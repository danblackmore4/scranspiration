<x-app-layout>
    {{-- CATEGORY FILTER BAR --}}
    <div class="flex justify-center gap-6 text-lg font-semibold mb-6">

        <a href="{{ route('recipes.index', ['category' => 'cutting']) }}"
        class="cursor-pointer {{ request('category') === 'cutting' ? 'text-black-600 underline' : 'text-gray-700' }}">
            CUTTING
        </a>

        <span class="text-gray-400">|</span>

        <a href="{{ route('recipes.index', ['category' => 'balanced']) }}"
        class="cursor-pointer {{ request('category') === 'balanced' ? 'text-black-600 underline' : 'text-gray-700' }}">
            BALANCED
        </a>

        <span class="text-gray-400">|</span>

        <a href="{{ route('recipes.index', ['category' => 'high-protein']) }}"
        class="cursor-pointer {{ request('category') === 'high-protein' ? 'text-black-600 underline' : 'text-gray-700' }}">
            HIGH PROTEIN
        </a>

        <span class="text-gray-400">|</span>

        <a href="{{ route('recipes.index', ['category' => 'bulking']) }}"
        class="cursor-pointer {{ request('category') === 'bulking' ? 'text-black-600 underline' : 'text-gray-700' }}">
            BULKING
        </a>

    </div>

    <div class="max-w-6xl mx-auto mt-8">

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

            @foreach ($recipes as $recipe)
                <x-recipe-card :recipe="$recipe" />
            @endforeach

        </div>
    </div>
</x-app-layout>
