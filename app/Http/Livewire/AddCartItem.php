<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Gloudemans\Shoppingcart\Facades\Cart;

class AddCartItem extends Component
{
    public $qty = 1;
    public $product;
    public $quantity;

    public function mount()
    {
        $this->quantity = $this->product->quantity;
    }

    public function addItem()
    {
        Cart::add([
            'id' => $this->product->id,
            'name' => $this->product->name,
            'qty' => $this->qty,
            'price' => $this->product->price,
            'weight' => 550,
        ]);
    }

    public function decrement()
    {
        $this->qty--;
    }
    public function increment()
    {
        $this->qty++;
    }

    public function render()
    {
        return view('livewire.add-cart-item');
    }

    
}
