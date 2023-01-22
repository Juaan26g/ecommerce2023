<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;

class CategoryFilter extends Component
{
    public $category, $subcategoria, $marca;

    use WithPagination;

    public function limpiar()

    {
        $this->reset(['subcategoria', 'marca']);
    }

    public function render()
    {
      
        $products = $this->category->products()->where('status', 2)->paginate(20);

        return view('livewire.category-filter', compact('products'));
    }
}
