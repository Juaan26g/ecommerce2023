<?php


namespace App\Http\Livewire\Admin;

use App\Models\Product;
use Livewire\{Component, WithPagination};

class ShowProducts2 extends Component
{
    use WithPagination;
    public $search;
    public $pagination = 10;

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function render()
    {
        $products = Product::where('name', 'LIKE', "%{$this->search}%")->paginate($this->pagination);

        return view('livewire.admin.show-products2', compact('products'))->layout('layouts.admin');
    }
}
