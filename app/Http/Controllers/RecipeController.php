<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Notifications\CommentNotification;



class RecipeController extends Controller
{
    // Show all recipes
    public function index(Request $request)
    {
        $categorySlug = $request->query('category');

        $recipes = Recipe::query()
            ->when($categorySlug, function ($query, $categorySlug) {

                // Map URL slugs → category names stored in DB
                $map = [
                    'cutting'       => 'Cutting',
                    'balanced'      => 'Balanced',
                    'high-protein'  => 'High Protein',
                    'bulking'       => 'Bulking',
                ];

                if (isset($map[$categorySlug])) {
                    $query->whereHas('category', function ($q) use ($map, $categorySlug) {
                        $q->where('name', $map[$categorySlug]);
                    });
                }
            })
            ->paginate(12);

        return view('recipes.index', compact('recipes'));
    }


    public function show(Recipe $recipe)
    {
        $recipe->load([
            'category',
            'user',
            'ingredients' => fn ($q) => $q->withPivot(['amount', 'calories', 'protein', 'carbs', 'fats'])
        ]);

        $otherRecipes = Recipe::where('id', '!=', $recipe->id)->latest()->get();

        return view('recipes.show', compact('recipe', 'otherRecipes'));
    }

    public function addComment(Request $request, Recipe $recipe)
    {
        
        $validated = $request->validate([
            'content' => 'required|min:2'
        ]);
        

        $comment = $recipe->comments()->create([
            'user_id' => auth::id(),       // correct
            'body' => $validated['content'], // avoids protected property warning
        ]);
        // Notify the recipe owner (except self-comments)
    if ($recipe->user_id !== auth::id()) {
    $recipe->user->notify(new CommentNotification($comment));
}

        return back()->with('success', 'Comment added!');
    }

    public function search(Request $request)
    {
        $query = $request->input('q');

        $recipes = Recipe::query()
            ->when($query, function ($queryBuilder) use ($query) {
                $queryBuilder
                    ->where('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhereHas('ingredients', function ($qIngredient) use ($query) {
                        $qIngredient->where('name', 'like', "%{$query}%");
                    })
                    ->orWhereHas('category', function ($qCategory) use ($query) {
                        $qCategory->where('name', 'like', "%{$query}%");
                    });
            })
            ->paginate(12);

        return view('recipes.search-results', compact('recipes', 'query'));
    }

    public function destroy(Recipe $recipe)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            abort(403);
        }

        // Creator can delete any recipe
        if ($user->isCreator() === false && $recipe->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        // Delete stored image if exists
        if ($recipe->image && Storage::disk('public')->exists($recipe->image)) {
            Storage::disk('public')->delete($recipe->image);
        }

        $recipe->delete();

        return redirect()->route('recipes.index')
            ->with('success', 'Recipe deleted successfully.');
    }






}
