<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Size;

class AddCartItemSize extends Component
{
    public $product;
    public $sizes;
    public $size_id = '';
    public $colors = [];
    public function mount()
    {
        $this->sizes = $this->product->sizes;
    }
    public function updatedSizeId($value)
    {
        $size = Size::find($value);
        $this->colors = $size->colors;
    }

    public function render()
    {
        return view('livewire.add-cart-item-size');
    }
}
