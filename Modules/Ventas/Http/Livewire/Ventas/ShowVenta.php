<?php

namespace Modules\Ventas\Http\Livewire\Ventas;

use App\Models\Caja;
use App\Models\Cajamovimiento;
use App\Models\Concept;
use App\Models\Methodpayment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Modules\Ventas\Entities\Cuota;
use Modules\Ventas\Entities\Venta;

class ShowVenta extends Component
{

    public $venta;
    public $cuota;

    public $open = false;
    public $methodpayment_id, $detalle, $caja_id, $concept_id;

    protected function rules()
    {
        return [
            'cuota.id' => ['required', 'integer', 'exists:cuotas,id'],
            'caja_id' => ['required', 'integer', 'exists:cajas,id'],
            'concept_id' => ['required', 'integer', 'exists:concepts,id'],
            'methodpayment_id' => ['required', 'integer', 'exists:methodpayments,id'],
            'detalle' => ['nullable'],
        ];
    }

    public function mount(Venta $venta)
    {
        $this->venta = $venta;
        $this->cuota = new Cuota();
        $this->methodpayment_id = Methodpayment::defaultMethodpayment()->first()->id ?? null;
        $this->concept_id = Concept::defaultConceptPaycuota()->first()->id ?? null;
        $this->caja_id = Caja::defaultCaja()->first()->id ?? null;
    }

    public function render()
    {
        $methodpayments = Methodpayment::orderBy('name', 'asc')->get();
        return view('ventas::livewire.ventas.show-venta', compact('methodpayments'));
    }

    public function pay(Cuota $cuota)
    {
        $this->cuota = $cuota;
        $this->resetValidation();
        $this->open = true;
    }

    public function savepayment()
    {
        $this->validate();

        DB::beginTransaction();
        try {

            $cajamovimiento = Cajamovimiento::create([
                'date' => now('America/Lima'),
                'amount' => $this->cuota->amount,
                'referencia' => null,
                'detalle' => $this->detalle,
                'moneda_id' => $this->cuota->venta->moneda_id,
                'methodpayment_id' => $this->methodpayment_id,
                'typemovement' => '+',
                'concept_id' => $this->concept_id,
                'caja_id' => $this->caja_id,
                'user_id' => Auth::user()->id,
            ]);

            DB::commit();

            $this->cuota->update([
                'datepayment' => now('America/Lima'),
                'userpayment_id' => Auth::user()->id,
                'cajamovimiento_id' => $cajamovimiento->id
            ]);

            $this->dispatchBrowserEvent('created');
            $this->venta->refresh();
            $this->resetValidation();
            $this->reset(['open']);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function hydrate()
    {
        $this->dispatchBrowserEvent('render-showventa-select2');
    }
}
