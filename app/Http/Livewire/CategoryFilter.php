<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
class CategoryFilter extends Component
{
    public $category;

    use WithPagination;
    public function render()
    {
        $products = $this->category->products()->where('status', 2)->paginate(20);

        return view('livewire.category-filter', compact('products'));
    }
}
