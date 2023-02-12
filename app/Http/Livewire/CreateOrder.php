<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\{Department, City, District, Order};
use Gloudemans\Shoppingcart\Facades\Cart;

class CreateOrder extends Component
{

    public $departments, $cities = [], $districts = [];
    public $department_id = '', $city_id = '', $district_id = '';
    public $address, $reference;
    public $contact, $phone, $shipping_cost;
    public $envio_type = 1;

    public $rules = [
        'contact' => 'required',
        'phone' => 'required',
        'envio_type' => 'required'
    ];

    public function create_order()
    {
        $rules = $this->rules;
        if ($this->envio_type == 2) {
            $rules['department_id'] = 'required';
            $rules['city_id'] = 'required';
            $rules['district_id'] = 'required';
            $rules['address'] = 'required';
            $rules['reference'] = 'required';
        }
        $this->validate($rules);

        $order = new Order();

        $order->user_id = auth()->user()->id;
        $order->contact = $this->contact;
        $order->phone = $this->phone;
        $order->envio_type = $this->envio_type;
        $order->shipping_cost = 0;
        $order->total = $this->shipping_cost + Cart::subtotal();
        $order->content = Cart::content();

        if ($this->envio_type == 2) {
            $order->shipping_cost = $this->shipping_cost;
            $order->department_id = $this->department_id;
            $order->city_id = $this->city_id;
            $order->district_id = $this->district_id;
            $order->address = $this->address;
            $order->reference = $this->reference;
        }

        $order->save();

        foreach (Cart::content() as $item) {
            discount($item);
            }

        Cart::destroy();
        $paymentR = route('orders.payment', $order);
        return $this->redirect($paymentR);
    }
    public function mount()
    {
        $this->departments = Department::all();
    }

    public function render()
    {
        return view('livewire.create-order');
    }
}
