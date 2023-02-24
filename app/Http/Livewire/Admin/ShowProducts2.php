<?php


namespace App\Http\Livewire\Admin;

use App\Models\Product;
use Livewire\{Component, WithPagination};

class ShowProducts2 extends Component
{
    use WithPagination;

    public $columns = ['Nombre','Categoria','Estado','Precio','Marca','NVendidos','Stock','Fecha de creacion'];
    public $shownColumns = [];
    public $search;
    public $pagination = 10;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function Column($column)
    {
        return in_array($column, $this->shownColumns);
    }
    public function mount()
    {
        $this->shownColumns = $this->columns;
    }
    public function render()
    {
        $products = Product::where('name', 'LIKE', "%{$this->search}%")->paginate($this->pagination);

        return view('livewire.admin.show-products2', compact('products'))->layout('layouts.admin');
    }
}
