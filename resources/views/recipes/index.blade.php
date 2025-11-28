@php
    use Illuminate\Support\Str;
@endphp

<x-app-layout>
    <div class="py-6 max-w-6xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($recipes as $recipe)
            <div class="bg-white rounded-xl shadow p-4">
                <h2 class="text-xl font-semibold">{{ $recipe->title }}</h2>
                <p class="text-sm text-gray-600">{{ $recipe->category->name ?? 'Uncategorised' }}</p>
                <p class="mt-2 text-gray-700 line-clamp-3">{{ Str::limit($recipe->description, 100) }}</p>
                <p class="text-xs mt-2 text-gray-500">By {{ $recipe->user->name ?? 'Unknown User' }}</p>
            </div>
        @endforeach
    </div>
</x-app-layout>
