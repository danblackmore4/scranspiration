<x-app-layout>
    <div class="max-w-6xl mx-auto mt-8">

        <a href="{{ route('recipes.index') }}" class="text-black-800 underline">&larr; Back to recipes</a>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-6">

            <!-- LEFT COLUMN -->
            <div>
                @if ($recipe->image)
                    <img src="{{ asset('storage/' . $recipe->image) }}"
                        class="w-full h-80 object-cover rounded-xl shadow">
                @endif

                <!-- COMMENTS UNDER IMAGE -->
                <div class="mt-8">
                    <h2 class="text-2xl font-bold mb-4">Comments</h2>

                    {{-- Scrollable list of comments (this part scrolls) --}}
                    <div
                        class="space-y-3 pr-2 mb-4 border rounded-lg"
                        style="max-height: 160px; overflow-y: auto;"
                    >
                        @forelse ($recipe->comments as $comment)
                            <div class="relative p-3 bg-gray-50 shadow-sm rounded">
                                <a href="{{ route('user.profile', $comment->user) }}" 
                                    class="font-semibold text-sm text-black-600 underline">
                                    {{ $comment->user->name }}
                                    </a>
                                <div class="text-gray-700 text-sm">{{ $comment->body }}</div>
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ $comment->created_at->diffForHumans() }}
                                </div>

                                @auth
                                    @if (auth()->user()->isCreator() || $comment->user_id === auth()->id())
                                        <form action="{{ route('comments.destroy', $comment) }}"
                                            method="POST"
                                            onsubmit="return confirm('Delete this comment?');"
                                            class="absolute top-2 right-2">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-500 text-xs underline">Delete</button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                        @empty
                            <p class="text-gray-600">No comments yet.</p>
                        @endforelse
                    </div>


                    {{-- Form stays outside the scroll box --}}
                    @auth
                        <form action="{{ route('recipes.comment', $recipe->id) }}" method="POST">
                            @csrf
                            <textarea
                                name="content"
                                rows="3"
                                class="w-full border rounded p-2 mb-2"
                                placeholder="Leave a comment..."></textarea>

                            <button class="px-4 py-2 bg-blue-600 text-white rounded">
                                Post Comment
                            </button>
                        </form>
                    @else
                        <p class="mt-4 text-gray-600">
                            <a href="/login" class="text-blue-600 underline">Log in</a> to post a comment.
                        </p>
                    @endauth
                </div>
            </div>

            

            <div class="self-start">

                @auth
                    @if (auth()->user()->isCreator() || $recipe->user_id === auth()->id())
                        <form action="{{ route('recipes.destroy', $recipe) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this recipe?');">
                            @csrf
                            @method('DELETE')

                            <button class="mt-4 px-4 py-2 bg-red-600 text-white rounded">
                                Delete Recipe
                            </button>
                        </form>
                    @endif
                @endauth


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
