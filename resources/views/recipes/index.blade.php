<x-app-layout>
    <div class="max-w-6xl mx-auto mt-8">

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

            @foreach ($recipes as $recipe)
                <a href="{{ route('recipes.show', $recipe->id) }}" class="block">

                    {{-- Unified card wrapper: SAME for all recipes --}}
                    <div class="bg-white rounded-xl shadow overflow-hidden flex flex-col h-80">

                        {{-- Optional image section (fixed height) --}}
                        @if ($recipe->image)
                            <div class="h-40 w-full">
                                <img src="{{ asset('storage/' . $recipe->image) }}"
                                     class="w-full h-full object-cover">
                            </div>
                        @endif

                        {{-- Text content --}}
                        <div class="p-4 flex flex-col flex-1">
                            <h2 class="text-xl font-semibold">
                                {{ $recipe->title }}
                            </h2>

                            <p class="text-sm text-gray-600">
                                {{ $recipe->category->name ?? 'Uncategorised' }}
                            </p>

                            <p class="mt-2 text-gray-700 line-clamp-3 flex-grow">
                                {{ $recipe->description }}
                            </p>

                            <p class="text-xs mt-2 text-gray-500 mt-auto">
                                By {{ $recipe->user->name ?? 'Unknown user' }}
                            </p>
                        </div>

                    </div>

                </a>
            @endforeach

        </div>
    </div>
</x-app-layout>
