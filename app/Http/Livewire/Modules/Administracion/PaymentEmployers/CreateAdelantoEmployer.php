<?php

namespace App\Http\Livewire\Modules\Administracion\PaymentEmployers;

use App\Enums\MovimientosEnum;
use App\Models\Cajamovimiento;
use App\Models\Concept;
use App\Models\Employer;
use App\Models\Methodpayment;
use App\Models\Moneda;
use App\Models\Monthbox;
use App\Models\Openbox;
use App\Rules\ValidateEmployerpayment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateAdelantoEmployer extends Component
{

    use AuthorizesRequests;

    public $open = false;
    public $employer;
    public $openbox, $monthbox, $employer_id, $amount, $concept_id, $moneda_id, $methodpayment_id, $detalle;
    public $amountadelantos = 0;
    public $adelantomaximo;
    public $adelantos = [];

    protected function rules()
    {
        return [
            'amount' => ['required', 'numeric', 'min:0', 'gt:0', 'decimal:0,4'],
            'employer_id' => ['required', 'integer', 'min:1', 'exists:employers,id'],
            'methodpayment_id' => ['required', 'integer', 'min:1', 'exists:methodpayments,id'],
            'concept_id' => ['required', 'integer', 'min:1', 'exists:concepts,id'],
            'openbox.id' => ['required', 'integer', 'min:1', 'exists:openboxes,id'],
            'monthbox.id' => ['required', 'integer', 'min:1', 'exists:monthboxes,id'],
            'moneda_id' => ['required', 'integer', 'min:1', 'exists:monedas,id'],
            'detalle' => ['nullable', 'string'],
        ];
    }

    public function mount()
    {
        $this->employer = new Employer();
        $this->openbox =  Openbox::mybox(auth()->user()->sucursal_id)->first();
        $this->monthbox = Monthbox::usando(auth()->user()->sucursal_id)->first();
        $this->concept_id = Concept::adelantoemployer()->first()->id ?? null;
        $this->moneda_id = Moneda::default()->first()->id ?? null;
        $this->methodpayment_id = Methodpayment::default()->first()->id ?? null;
    }

    public function render()
    {
        $employers = Employer::with('areawork')->where('sucursal_id', auth()->user()->sucursal_id)
            ->orderBy('name', 'asc')->get();
        $methodpayments = Methodpayment::orderBy('name', 'asc')->get();
        $monedas = Moneda::orderBy('code', 'asc')->get();

        if ($this->monthbox) {
            $diferencias = Cajamovimiento::with('moneda')->withWhereHas('sucursal', function ($query) {
                $query->withTrashed()->where('id', auth()->user()->sucursal_id);
            })->selectRaw("moneda_id, SUM(CASE WHEN typemovement = 'INGRESO' THEN amount ELSE -amount END) as diferencia")
                ->where('openbox_id', $this->openbox->id)->where('monthbox_id', $this->monthbox->id)
                ->groupBy('moneda_id')->orderBy('diferencia', 'desc')->get();
        } else {
            $diferencias = [];
        }

        return view('livewire.modules.administracion.payment-employers.create-adelanto-employer', compact('employers', 'methodpayments', 'monedas', 'diferencias'));
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->authorize('admin.administracion.employers.adelantos.create');
            $this->reset(['employer', 'employer_id', 'adelantos', 'detalle', 'amount']);
            $this->employer = new Employer();
            $this->resetValidation();
            $this->methodpayment_id = Methodpayment::default()->first()->id ?? null;
        }
    }

    public function updatedEmployerId($value)
    {
        if ($value) {
            $this->employer = Employer::find($value);
            $this->adelantos = $this->employer->cajamovimientos()->where('monthbox_id', $this->monthbox->id)->get();
            // $this->amount = number_format($this->employer->sueldo - $this->adelantos->sum() ?? 0, 2, '.', '');
        }
    }

    public function save()
    {

        $this->authorize('admin.administracion.employers.adelantos.create');
        if ($this->employer->id) {
            if ($this->employer->sucursal_id <> auth()->user()->sucursal_id) {
                $mensaje =  response()->json([
                    'title' => 'SUCURSAL DEL PERSONAL DIFERENTE A SUCURSAL DE APERTURA DE CAJA !',
                    'text' => "No se pueden realizar movimientos en caja de una sucursal diferente a caja aperturada."
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
            }
        }

        if (!$this->monthbox || !$this->monthbox->isUsing()) {
            $this->dispatchBrowserEvent('validation', getMessageMonthbox());
            return false;
        }

        if (!$this->openbox || !$this->openbox->isActivo()) {
            $this->dispatchBrowserEvent('validation', getMessageOpencaja());
            return false;
        }

        $mi_empresa = mi_empresa();
        $this->adelantomaximo = $mi_empresa->montoadelanto > 0 ? $mi_empresa->montoadelanto : null;
        $adelantos = $this->employer->cajamovimientos()
            ->where('monthbox_id', $this->monthbox->id)->sum('amount');
        if ($this->adelantomaximo) {
            if (($adelantos + $this->amount) > $this->adelantomaximo) {
                $mensaje =  response()->json([
                    'title' => 'MONTO TOTAL DE ADELANTOS SUPERA EL LÍMITE PERMITIDO !',
                    'text' => "No se pueden registrar adelantos que superen un total máximo de $this->adelantomaximo durante el mes."
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
            }
        } else {
            if (($adelantos + $this->amount) > $this->employer->sueldo) {
                $mensaje =  response()->json([
                    'title' => 'MONTO TOTAL DE ADELANTOS SUPERA EL SUELDO DEL PERSONAL !',
                    'text' => "No se pueden registrar adelantos que superen el total del sueldo del personal " . $this->employer->sueldo . " durante el mes."
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
            }
        }

        $this->concept_id = Concept::adelantoemployer()->first()->id ?? null;
        $this->validate();
        DB::beginTransaction();

        try {

            $methodpayment = Methodpayment::find($this->methodpayment_id);
            $saldocaja = Cajamovimiento::withWhereHas('methodpayment', function ($query) use ($methodpayment) {
                $query->where('type', $methodpayment->type);
            })->where('sucursal_id', $this->employer->sucursal_id)
                ->where('openbox_id', $this->openbox->id)->where('monthbox_id', $this->monthbox->id)
                ->where('moneda_id', $this->moneda_id)
                ->selectRaw("COALESCE(SUM(CASE WHEN typemovement = '" . MovimientosEnum::INGRESO->value . "' THEN amount ELSE -amount END), 0) as diferencia")
                ->first()->diferencia ?? 0;
            $forma = $methodpayment->isEfectivo() ? 'EFECTIVO' : 'TRANSFERENCIA';

            if (($saldocaja - $this->amount) < 0) {
                $mensaje =  response()->json([
                    'title' => 'SALDO INSUFICIENTE PARA REALIZAR ADELANTO DE PAGO DEL PERSONAL !',
                    'text' => "Monto de egreso en moneda seleccionada supera el saldo disponible en caja, mediante $forma."
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
            }

            $this->employer->savePayment(
                $this->employer->sucursal_id,
                $this->amount,
                $this->moneda_id,
                $this->methodpayment_id,
                MovimientosEnum::EGRESO->value,
                $this->concept_id,
                $this->openbox->id,
                $this->monthbox->id,
                'PERSONAL: ' . $this->employer->name,
                $this->detalle,
            );

            DB::commit();
            $this->reset(['employer', 'employer_id', 'adelantos', 'detalle', 'open', 'amount']);
            $this->employer = new Employer();
            $this->resetValidation();
            $this->dispatchBrowserEvent('created');
            $this->emitTo('admin.cajamovimientos.show-cajamovimientos', 'render');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
