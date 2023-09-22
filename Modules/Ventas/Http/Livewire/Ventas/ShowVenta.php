<?php

namespace Modules\Ventas\Http\Livewire\Ventas;

use App\Models\Caja;
use App\Models\Cajamovimiento;
use App\Models\Concept;
use App\Models\Methodpayment;
use App\Models\Opencaja;
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
    public $opencuotas = false;

    public $methodpayment_id, $detalle, $caja_id, $concept_id;
    public $cuotas = [];

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

    protected $messages = [
        'cuotas.*.id.required' => 'Id de cuota requerido',
        'cuotas.*.date.required' => 'Fecha de pago de cuota requerido',
        'cuotas.*.date.after_or_equal' => 'Fecha de pago debe ser mayor igual a la actual',
        'cuotas.*.amount.required' => 'Monto de cuota requerido',
    ];

    public function mount(Venta $venta)
    {
        $this->venta = $venta;
        $this->cuota = new Cuota();
        $this->methodpayment_id = Methodpayment::defaultMethodpayment()->first()->id ?? null;
        $this->concept_id = Concept::defaultConceptPaycuota()->first()->id ?? null;
        $this->caja_id = Opencaja::CajasAbiertas()->CajasUser()->first()->id ?? null;
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

    public function editcuotas()
    {

        $this->resetValidation(['cuotas']);
        $this->reset(['cuotas']);

        if (count($this->venta->cuotas)) {
            foreach ($this->venta->cuotas as $cuota) {
                $this->cuotas[] = [
                    'id' => $cuota->id,
                    'cuota' => $cuota->cuota,
                    'date' => $cuota->expiredate,
                    'cajamovimiento' => $cuota->cajamovimiento_id,
                    'amount' => $cuota->amount,
                ];
            }
        }
        $this->opencuotas = true;
    }

    public function updatecuotas()
    {

        $this->resetValidation(['cuotas']);
        $amountCuotas = number_format(0, 2);
        $totalAmount = number_format($this->venta->total, 2, '.', '');

        DB::beginTransaction();

        foreach ($this->cuotas as $item => $cuota) {

            $amountCuotas = number_format($amountCuotas + ($cuota["amount"] == "" ? 0 : $cuota["amount"]), 2, '.', '');

            $validateDate = $this->validate([
                "cuotas.$item.id" => ['required', 'integer', 'min:1', 'exists:cuotas,id'],
                "cuotas.$item.cuota" => ['required', 'integer', 'min:1'],
                "cuotas.$item.date" => ['required', 'date', is_null($cuota["cajamovimiento"]) ? 'after_or_equal:today' : 'before_or_equal:today'],
                "cuotas.$item.amount" => ['required', 'numeric', 'min:1', 'decimal:0,2'],
                "cuotas.$item.cajamovimiento" => ['nullable'],
            ]);

            if (is_null($cuota["cajamovimiento"])) {
                $this->venta->cuotas[$item]->expiredate = $cuota["date"];
                $this->venta->cuotas[$item]->amount = $cuota["amount"];
                $this->venta->cuotas[$item]->save();
            }
        }

        if ($totalAmount !== $amountCuotas) {
            $this->addError('cuotas', "Monto total de cuotas($amountCuotas) no coincide con monto total de venta($totalAmount)");
            DB::rollBack();
            return false;
        }

        DB::commit();
        $this->resetValidation(['cuotas']);
        $this->reset(['cuotas', 'opencuotas']);
        $this->dispatchBrowserEvent('updated');
    }

    public function hydrate()
    {
        $this->dispatchBrowserEvent('render-showventa-select2');
    }
}
