<?php

namespace App\Http\Livewire;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Order;
use Livewire\Component;

class PaymentOrder extends Component
{
    use AuthorizesRequests;
    public $order;
    protected $listeners = ['payOrder'];
    public function mount(Order $order)
    {
        $this->order = $order;
    }

    public function payOrder()
    {
        $this->order->status = 2;
        $this->order->save();
        return redirect()->route('orders.show', $this->order);
    }
    public function render()
    {
        $items = json_decode($this->order->content);
        return view('livewire.payment-order', compact('items'));
    }
}
