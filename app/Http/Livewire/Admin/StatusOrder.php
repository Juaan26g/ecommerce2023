<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;

class StatusOrder extends Component
{
    public function render()
    {
        $items = json_decode($this->order->content);
        $envio = json_decode($this->order->envio);
        
        return view('livewire.admin.status-order', compact('items', 'envio'));
    }
}
