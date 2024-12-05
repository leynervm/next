<?php

namespace App\Http\Livewire\Modules\Marketplace\Usersweb;

use Livewire\Component;
use Livewire\WithPagination;
use Modules\Marketplace\Entities\Order;
use Modules\Marketplace\Entities\Trackingstate;

class ShowOrders extends Component
{

    use WithPagination;

    public $date = '';
    public $dateto = '';
    public $pago = '';
    public $status = '';

    protected $queryString = [
        'date' => ['except' => '', 'as' => 'fecha'],
        'dateto' => ['except' => '', 'as' => 'hasta'],
        'pago' => ['except' => '', 'as' => 'estado-pago'],
        'status' => ['except' => '', 'as' => 'estado-pedido'],
    ];

    public function render()
    {
        $orders = Order::with(['direccion', 'moneda', 'client', 'shipmenttype', 'user'])->where('user_id', auth()->user()->id);
        // if (trim($this->status) !== '') {
        // }

        if (trim($this->date) !== '') {
            if ($this->dateto) {
                $orders->whereDateBetween('date', $this->date, $this->dateto);
            } else {
                $orders->whereDate('date', $this->date);
            }
        }

        if (trim($this->pago) !== '') {
            $orders->where('status', $this->pago);
        }

        $orders = $orders->orderBy('date', 'desc')->paginate();
        return view('livewire.modules.marketplace.usersweb.show-orders', compact('orders'));
    }
}
