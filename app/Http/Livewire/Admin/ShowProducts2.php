<?php

namespace App\Http\Livewire\Admin;

use App\Models\Product;
use Livewire\{Component, WithPagination};

class ShowProducts2 extends Component
{
    use WithPagination;
    public $search;

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function render()
    {
        $products = Product::where('name', 'LIKE', "%{$this->search}%")->paginate(10);

        return view('livewire.admin.show-products', compact('products'))->layout('layouts.admin');
    }
}
