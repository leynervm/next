<?php

namespace App\Http\Livewire\Modules\Marketplace\Orders;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Modules\Marketplace\Entities\Order;
use Modules\Marketplace\Entities\Tracking;
use Modules\Marketplace\Entities\Trackingstate;

class ShowTrackings extends Component
{

    use AuthorizesRequests;


    public Order $order;

    public $open = false;
    public $trackingstate_id;


    public function render()
    {
        $trackingstates = Trackingstate::whereNotIn('id', $this->order->trackings()->pluck('trackingstate_id'))->get();
        return view('livewire.modules.marketplace.orders.show-trackings', compact('trackingstates'));
    }

    public function save()
    {
        $this->authorize('admin.marketplace.trackings.create');

        if (!$this->order->isPagoconfirmado()) {
            $mensaje = response()->json([
                'title' => 'PAGO DEL PEDIDO PENDIENTE POR CONFIRMAR !',
                'text' => 'No se puede agregar estados de seguimiento al pedido mientras el pago no está confirmado, primero debe confirmar el pago.',
                'type' => 'warning'
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }
        $this->validate([
            'trackingstate_id' => [
                'required', 'integer', 'min:1', 'exists:trackingstates,id',
                Rule::unique('trackings', 'trackingstate_id')->where('order_id', $this->order->id)
            ]
        ]);

        $this->order->trackings()->create([
            'date' => now('America/Lima'),
            'trackingstate_id' => $this->trackingstate_id,
            'user_id' => auth()->user()->id
        ]);
        $this->reset(['trackingstate_id']);
        $this->resetValidation();
        $this->order->refresh();
        $this->dispatchBrowserEvent('toast', toastJSON('Tracking actualizado correctamente'));
    }

    public function delete(Tracking $tracking)
    {
        $this->authorize('admin.marketplace.trackings.delete');
        $tracking->delete();
        $this->dispatchBrowserEvent('toast', toastJSON('Tracking actualizado correctamente'));
        $this->order->refresh();
    }
}
