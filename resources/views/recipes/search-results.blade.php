<x-app-layout>
    <div class="max-w-6xl mx-auto mt-8">
        <h1 class="text-2xl font-bold mb-4">
            Search results for: "{{ $query }}"
        </h1>

        @if ($recipes->count() === 0)
            <p class="text-gray-600">No recipes found.</p>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($recipes as $recipe)
                <x-recipe-card :recipe="$recipe" />
            @endforeach
        </div>

        <div class="mt-6">
            {{ $recipes->links() }}
        </div>
    </div>
</x-app-layout>
