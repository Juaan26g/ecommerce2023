<?php


namespace App\Http\Livewire\Admin;

use App\Models\Product;
use Livewire\{Component, WithPagination};
use Illuminate\Database\Query\Builder;

class ShowProducts2 extends Component
{
    use WithPagination;

    public $columns = ['Nombre','Categoria','Estado','Precio','Marca','NVendidos','Stock','Fecha de creacion'];
    public $shownColumns = [];
    public $search, $category, $price, $brand;
    public $reset;
    public $pagination = 10;
    public $order = null;
    public $direction = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortable($order)
    {
        if ($order !== $this->order) {
            $this->direction = null;
        }
        switch ($this->direction) {
            case null:
                $this->direction = 'asc';
                break;
            case 'asc':
                $this->direction = 'desc';
                break;
            case 'desc':
                $this->direction = null;
                break;
        }

        $this->order = $order;
    }


    public function Column($column)
    {
        return in_array($column, $this->shownColumns);
    }
    public function mount()
    {
        $this->shownColumns = $this->columns;
    }
    public function resetFilter()
    {
        $this->reset(['search', 'category', 'price', 'brand','reset']);
        $this->resetPage();
    }
    public function render()
    {
        $products = Product::query()->search($this->search)
        ->categoryFilter($this->category)
        ->brandFilter($this->brand);
        
        
        
        if ($this->price) {
            $products = $products->where('price', 'LIKE', "%{$this->price}%");
        }
        if ($this->order && $this->direction) {
            $products = $products->orderBy($this->order, $this->direction);
        }
        


        $products = $products->paginate($this->pagination);


        return view('livewire.admin.show-products2', compact('products'))->layout('layouts.admin');
    }
}
