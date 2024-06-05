<?php

namespace App\Http\Livewire\Modules\Marketplace\Orders;

use App\Enums\StatusPayWebEnum;
use Livewire\Component;
use Modules\Marketplace\Entities\Order;

class ConfirmarPago extends Component
{

    public Order $order;

    public function render()
    {
        return view('livewire.modules.marketplace.orders.confirmar-pago');
    }

    public function confirmarpago()
    {
        $this->order->status = StatusPayWebEnum::PAGO_CONFIRMADO;
        $this->order->save();
        $this->order->refresh();
        $this->dispatchBrowserEvent('toast', toastJSON('Pago confirmado correctamente'));
        $this->dispatchBrowserEvent('reload');
    }

    public function anularpago()
    {
        $this->order->status = StatusPayWebEnum::CONFIRMAR_PAGO;
        $this->order->save();
        $this->order->refresh();
        $this->dispatchBrowserEvent('toast', toastJSON('Pago anulado correctamente'));
        $this->dispatchBrowserEvent('reload');
    }
}
