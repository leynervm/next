<?php

namespace Modules\Soporte\Http\Livewire\Orders;

use Livewire\Component;
use Modules\Soporte\Entities\Order;

class LatestOrders extends Component
{

    public $orderAdd = [];
    public $latestOrders = [];

    protected $listeners = ['newOrder'];

    // public function mount()
    // {
    // $this->orders = new Order();
    // }

    public function render()
    {
        return view('soporte::livewire.orders.latest-orders');
    }

    public function newOrder($orderId)
    {
        if (!empty($orderId)) {

            $this->orderAdd[] = $orderId;
            $this->latestOrders = Order::whereIn('id', $this->orderAdd)->get();
        }
    }

    // public function hydrate()
    // {
    // $this->orders->push
    // dd("Nuevo evento");
    // }
}
