<x-app-layout>

    <div class="max-w-6xl mx-auto mt-8">

        {{-- USER HEADER --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold">{{ $user->name }}</h1>

            @if($user->profile)
                <p class="text-gray-700 mt-2">{{ $user->profile->bio }}</p>
                <p class="text-gray-600 text-sm">{{ $user->profile->goal }}</p>
            @endif
        </div>

        {{-- USER POSTS --}}
        <h2 class="text-2xl font-bold mb-4">Recipes by {{ $user->name }}</h2>

        @if ($recipes->isEmpty())
            <p class="text-gray-600">This user has not posted any recipes yet.</p>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
            @foreach ($recipes as $recipe)
                <x-recipe-card :recipe="$recipe" />
            @endforeach
        </div>

        {{-- USER COMMENTS --}}
        <h2 class="text-2xl font-bold mb-4">Comments by {{ $user->name }}</h2>

        @forelse ($comments as $comment)
            <div class="border p-4 rounded-lg mb-4 bg-gray-50 shadow-sm">

                <p class="text-gray-800">{{ $comment->body }}</p>

                <div class="text-xs text-gray-500 mt-2">
                    On recipe:
                    <a href="{{ route('recipes.show', $comment->recipe->id) }}"
                       class="text-blue-600 underline">
                        {{ $comment->recipe->title }}
                    </a>
                    — {{ $comment->created_at->diffForHumans() }}
                </div>

            </div>
        @empty
            <p class="text-gray-600">This user hasn’t posted any comments yet.</p>
        @endforelse

    </div>

</x-app-layout>
