<?php

namespace App\Livewire\Recipes;

use Livewire\Component;
use App\Models\Recipe;

class Index extends Component
{
    public $recipes = [];
    public $activeRecipe = null;

    public function mount()
    {
        $this->recipes = Recipe::with(['category', 'user'])->latest()->get();
    }

    public function openRecipe($id)
    {
        $this->activeRecipe = Recipe::with([
            'category',
            'user',
            'ingredients' => fn ($q) => $q->withPivot(['amount','calories','protein','carbs','fats']),
        ])->find($id);

        $this->dispatch('scrollToRecipe');
    }

    public function closeRecipe()
    {
        $this->activeRecipe = null;
    }

    public function render()
    {
        return view('livewire.recipes.index');
    }
}
