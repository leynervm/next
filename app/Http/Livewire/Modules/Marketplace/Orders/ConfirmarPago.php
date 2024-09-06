<?php

namespace App\Http\Livewire\Modules\Marketplace\Orders;

use App\Enums\MovimientosEnum;
use App\Enums\StatusPayWebEnum;
use App\Models\Cajamovimiento;
use App\Models\Concept;
use App\Models\Methodpayment;
use App\Models\Moneda;
use App\Models\Monthbox;
use App\Models\Openbox;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Modules\Marketplace\Entities\Order;

class ConfirmarPago extends Component
{

    use AuthorizesRequests;

    public $order;
    public $open = false;
    public $paymentactual = 0;
    public $pendiente = 0;
    public $openbox, $monthbox, $methodpayment_id, $concept_id, $detalle, $moneda_id;

    public function mount(Order $order)
    {
        $this->moneda_id = $order->moneda_id;
        $this->openbox = Openbox::mybox(auth()->user()->sucursal_id)->first();
        $this->monthbox = Monthbox::usando(auth()->user()->sucursal_id)->first();
    }

    public function render()
    {
        $monedas = Moneda::orderBy('id', 'asc')->get();
        $methodpayments = Methodpayment::orderBy('name', 'asc')->get();

        return view('livewire.modules.marketplace.orders.confirmar-pago', compact('methodpayments', 'monedas'));
    }

    public function updatingOpen()
    {
        $this->authorize('admin.marketplace.orders.confirmpay');
        if (!$this->open) {
            $this->reset(['moneda_id']);
            $this->resetValidation();
            $this->pendiente = formatDecimalOrInteger($this->order->total - $this->order->cajamovimientos()->sum('amount'));
            $this->paymentactual = formatDecimalOrInteger($this->pendiente, 2);
            $this->methodpayment_id = Methodpayment::default()->first()->id ?? null;
            $this->moneda_id = $this->order->moneda_id;
            $this->open = true;
        }
    }

    public function savepayment()
    {
        $this->authorize('admin.marketplace.orders.confirmpay');

        if (!$this->monthbox || !$this->monthbox->isUsing()) {
            $this->dispatchBrowserEvent('validation', getMessageMonthbox());
            return false;
        }

        if (!$this->openbox || !$this->openbox->isActivo()) {
            $this->dispatchBrowserEvent('validation', getMessageOpencaja());
            return false;
        }

        $this->concept_id = Concept::payManualOrder()->first()->id ?? null;

        $this->validate([
            'paymentactual' => ['required', 'numeric', 'gt:0', 'decimal:0,4', 'lte:' . $this->pendiente, 'regex:/^\d{0,8}(\.\d{0,4})?$/',],
            'openbox.id' => ['required', 'integer', 'min:1', 'exists:openboxes,id'],
            'openbox.id' => ['required', 'integer', 'min:1', 'exists:openboxes,id'],
            'monthbox.id' => ['required', 'integer', 'min:1', 'exists:monthboxes,id'],
            'moneda_id' => ['required', 'integer', 'min:1', 'exists:monedas,id'],
            'concept_id' => ['required', 'integer', 'min:1', 'exists:concepts,id'],
            'methodpayment_id' => ['required', 'integer', 'min:1', 'exists:methodpayments,id'],
            'detalle' => ['nullable'],
        ]);

        $sumatoria_pagos = formatDecimalOrInteger($this->order->cajamovimientos()->sum('amount') + $this->paymentactual, 2, '.', '');
        if ($sumatoria_pagos > formatDecimalOrInteger($this->order->total)) {
            $mensaje =  response()->json([
                'title' => "MONTO A PAGAR SUPERA AL TOTAL DE LA ORDEN",
                'text' => null
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }

        try {

            DB::beginTransaction();
            $payment = $this->order->savePayment(
                auth()->user()->sucursal_id,
                number_format($this->paymentactual, 2, '.', ''),
                number_format($this->paymentactual, 2, '.', ''),
                null,
                $this->moneda_id,
                $this->methodpayment_id,
                MovimientosEnum::INGRESO->value,
                $this->concept_id,
                $this->openbox->id,
                $this->monthbox->id,
                $this->order->seriecompleta,
                trim($this->detalle)
            );

            if ($sumatoria_pagos == formatDecimalOrInteger($this->order->total)) {
                $this->order->status = StatusPayWebEnum::PAGO_CONFIRMADO;
                $this->order->save();
                $this->order->refresh();
            }

            DB::commit();
            $this->resetValidation();
            $this->reset(['open', 'methodpayment_id', 'detalle', 'paymentactual', 'moneda_id']);
            $this->order->refresh();
            $this->emit('render');
            $this->dispatchBrowserEvent('toast', toastJSON('PAGO REGISTRADO CORRECTAMENTE'));
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deletepay(Cajamovimiento $cajamovimiento)
    {

        $this->authorize('admin.marketplace.orders.confirmpay');
        DB::beginTransaction();
        try {
            $cajamovimiento->delete();
            DB::commit();
            $sumatoria_pagos = formatDecimalOrInteger($this->order->cajamovimientos()->sum('amount') ?? 0);

            if ($sumatoria_pagos < $this->order->total) {
                $this->order->status = StatusPayWebEnum::PENDIENTE;
                $this->order->save();
            }
            $this->order->refresh();
            $this->emit('render');
            $this->dispatchBrowserEvent('deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
