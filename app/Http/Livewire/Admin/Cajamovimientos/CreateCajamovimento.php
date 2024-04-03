<?php

namespace App\Http\Livewire\Admin\Cajamovimientos;

use App\Enums\DefaultConceptsEnum;
use App\Enums\MovimientosEnum;
use App\Models\Cajamovimiento;
use App\Models\Concept;
use App\Models\Methodpayment;
use App\Models\Moneda;
use App\Models\Monthbox;
use App\Models\Openbox;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateCajamovimento extends Component
{

    use AuthorizesRequests;

    public $open = false;
    public $amount, $referencia, $detalle, $moneda_id,
        $methodpayment_id, $concept_id, $openbox_id,
        $monthbox_id, $sucursal_id, $openbox, $monthbox;

    public function rules()
    {
        return [
            'amount' => ['required', 'numeric', 'min:0', 'gt:0', 'decimal:0,4',],
            'openbox.id' => ['required', 'integer', 'min:1', 'exists:openboxes,id'],
            'monthbox.id' => ['required', 'integer', 'min:1', 'exists:monthboxes,id'],
            'concept_id' => ['required', 'integer', 'min:1', 'exists:concepts,id'],
            'moneda_id' => ['required', 'integer', 'min:1', 'exists:monedas,id'],
            'methodpayment_id' => ['required', 'integer', 'min:1', 'exists:methodpayments,id'],
            'detalle' => ['nullable'],
        ];
    }

    public function mount()
    {
        $this->openbox = Openbox::mybox(auth()->user()->sucursal_id)->first();
        $this->monthbox = Monthbox::usando(auth()->user()->sucursal_id)->first();
    }

    public function render()
    {
        $methodpayments = Methodpayment::orderBy('name', 'asc')->get();
        $concepts = Concept::where('default', DefaultConceptsEnum::DEFAULT)->get();
        $monedas = Moneda::orderBy('id', 'asc')->get();

        if ($this->monthbox) {
            $sumatorias = Cajamovimiento::with('moneda')->withWhereHas('sucursal', function ($query) {
                $query->withTrashed()->where('id', auth()->user()->sucursal_id);
            })->selectRaw('moneda_id, typemovement, SUM(amount) as total')->groupBy('moneda_id')
                ->where('openbox_id', $this->openbox->id)->where('monthbox_id', $this->monthbox->id)
                ->groupBy('typemovement')->orderBy('total', 'desc')->get();
        } else {
            $sumatorias = [];
        }

        if ($this->monthbox) {
            $diferencias = Cajamovimiento::with('moneda')->withWhereHas('sucursal', function ($query) {
                $query->withTrashed()->where('id', auth()->user()->sucursal_id);
            })->selectRaw("moneda_id, SUM(CASE WHEN typemovement = 'INGRESO' THEN amount ELSE -amount END) as diferencia")
                ->where('openbox_id', $this->openbox->id)->where('monthbox_id', $this->monthbox->id)
                ->groupBy('moneda_id')->orderBy('diferencia', 'desc')->get();
        } else {
            $diferencias = [];
        }

        return view('livewire.admin.cajamovimientos.create-cajamovimento', compact('methodpayments', 'concepts', 'monedas', 'sumatorias', 'diferencias'));
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->authorize('admin.cajas.movimientos.create');
            $this->resetValidation();
            $this->resetExcept(['openbox', 'monthbox']);
        }
        $this->moneda_id = Moneda::default()->first()->id ?? null;
        $this->methodpayment_id = Methodpayment::default()->first()->id ?? null;
    }

    public function save()
    {

        $this->authorize('admin.cajas.movimientos.create');
        if (!$this->monthbox || !$this->monthbox->isUsing()) {
            $this->dispatchBrowserEvent('validation', getMessageMonthbox());
            return false;
        }

        if (!$this->openbox || !$this->openbox->isActivo()) {
            $this->dispatchBrowserEvent('validation', getMessageOpencaja());
            return false;
        }

        // $diferencia = CajaMovimiento::selectRaw('moneda, SUM(CASE WHEN tipomovimiento = "ingreso" THEN monto ELSE 0 END) - SUM(CASE WHEN tipomovimiento = "egreso" THEN monto ELSE 0 END) as diferencia')
        //     ->groupBy('moneda')
        //     ->get();

        $this->validate();
        DB::beginTransaction();
        try {

            $typemovement = Concept::find($this->concept_id);
            $methodpayment = Methodpayment::find($this->methodpayment_id);
            $saldocaja = Cajamovimiento::withWhereHas('methodpayment', function ($query) use ($methodpayment) {
                $query->where('type', $methodpayment->type);
            })->where('sucursal_id', auth()->user()->sucursal_id)
                ->where('openbox_id', $this->openbox->id)->where('monthbox_id', $this->monthbox->id)
                ->where('moneda_id', $this->moneda_id)
                ->selectRaw("COALESCE(SUM(CASE WHEN typemovement = '" . MovimientosEnum::INGRESO->value . "' THEN amount ELSE -amount END), 0) as diferencia")
                ->first()->diferencia ?? 0;

            if ($typemovement->isEgreso()) {
                $forma = $methodpayment->isEfectivo() ? 'EFECTIVO' : 'TRANSFERENCIA';

                if (($saldocaja - $this->amount) < 0) {
                    $mensaje =  response()->json([
                        'title' => 'SALDO DE CAJA INSUFICIENTE PARA REALIZAR OPERACIÓN MEDIANTE ' . $forma . ' !',
                        'text' => "Monto de egreso en moneda seleccionada supera el saldo disponible en caja, mediante $forma."
                    ])->getData();
                    $this->dispatchBrowserEvent('validation', $mensaje);
                    return false;
                }
            }

            auth()->user()->savePayment(
                auth()->user()->sucursal_id,
                number_format($this->amount, 3, '.', ''),
                $this->moneda_id,
                $this->methodpayment_id,
                $typemovement->typemovement->value,
                $this->concept_id,
                $this->openbox->id,
                $this->monthbox->id,
                'MOVIMIENTO MANUAL EN CAJA',
                trim($this->detalle)
            );
            DB::commit();
            $this->resetValidation();
            $this->resetExcept(['openbox', 'monthbox']);
            $this->emitTo('admin.cajamovimientos.show-cajamovimientos', 'render');
            $this->dispatchBrowserEvent('created');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
