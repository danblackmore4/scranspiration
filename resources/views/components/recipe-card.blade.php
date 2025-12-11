@props(['recipe'])

<div class="h-80">
    <div class="bg-white rounded-xl shadow overflow-hidden flex flex-col h-full">

        {{-- Fixed image area so every card has the same top height --}}
        <a href="{{ route('recipes.show', $recipe->id) }}" class="block h-40 w-full bg-gray-200">
            @if ($recipe->image)
                <img
                    src="{{ asset('storage/' . $recipe->image) }}"
                    class="w-full h-full object-cover"
                    alt="{{ $recipe->title }}"
                >
            @endif
        </a>

        {{-- Text content --}}
        <div class="p-4 flex flex-col flex-1">
            <a href="{{ route('recipes.show', $recipe->id) }}" class="block">
                <h2 class="text-xl font-semibold">
                    {{ $recipe->title }}
                </h2>
            </a>

            <p class="text-sm text-gray-600">
                {{ $recipe->category->name ?? 'Uncategorised' }}
            </p>

            <p class="mt-2 text-gray-700 line-clamp-3 flex-grow">
                {{ $recipe->description }}
            </p>

            <p class="text-xs mt-2 text-gray-500 mt-auto whitespace-nowrap">
                By
                <a
                    href="{{ route('user.profile', $recipe->user) }}"
                    class="inline text-black underline"
                >
                    {{ $recipe->user->name }}
                </a>
            </p>
        </div>

    </div>
</div>
