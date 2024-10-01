<?php

namespace App\Http\Livewire\Modules\Marketplace\Orders;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Marketplace\Entities\Order;

class ShowOrders extends Component
{

    use WithPagination;
    use AuthorizesRequests;

    public $search = '', $date = '', $dateto = '', $pago = '';

    protected $queryString = [
        'search' => ['except' => '', 'as' => 'buscar'],
        'date' => ['except' => '', 'as' => 'fecha'],
        'dateto' => ['except' => '', 'as' => 'hasta'],
        'pago' => ['except' => '', 'as' => 'estado-pago'],
        // 'deletes' => ['except' => false, 'as' => 'eliminados'],
    ];

    public function render()
    {

        $orders = Order::with(['direccion', 'shipmenttype', 'moneda', 'client', 'transaccion'])
            ->with(['trackings' => function ($query) {
                $query->orderBy('id', 'desc')->orderBy('date', 'desc');
            }]);

        if (trim($this->search) !== '') {
            $orders->whereHas('user', function ($query) {
                $query->where('document', 'ilike', '%' . $this->search . '%')
                    ->orWhere('name', 'ilike', '%' . $this->search . '%')
                    ->orWhere('email', 'ilike', '%' . $this->search . '%');
            })->orWhere('purchase_number', 'ilike', ['%' . $this->search . '%']);
        }

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

        return view('livewire.modules.marketplace.orders.show-orders', compact('orders'));
    }

    public function updatedDate($value)
    {
        $this->resetPage();
        if (empty($value)) {
            $this->reset(['dateto']);
        }
    }

    public function updatedDateto()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPago()
    {
        $this->resetPage();
    }
}
