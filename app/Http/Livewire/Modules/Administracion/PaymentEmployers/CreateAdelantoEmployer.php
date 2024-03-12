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
        $this->openbox =  Openbox::mybox(auth()->user()->employer->sucursal_id)->first();
        $this->monthbox = Monthbox::usando(auth()->user()->employer->sucursal_id)->first();
        $this->concept_id = Concept::adelantoemployer()->first()->id ?? null;
        $this->moneda_id = Moneda::default()->first()->id ?? null;
        $this->methodpayment_id = Methodpayment::default()->first()->id ?? null;
        $this->adelantomaximo = mi_empresa()->montoadelanto > 0 ? mi_empresa()->montoadelanto : null;
    }

    public function render()
    {
        $employers = Employer::with('areawork')->where('sucursal_id', auth()->user()->sucursal_id)
            ->orderBy('name', 'asc')->get();
        $methodpayments = Methodpayment::orderBy('name', 'asc')->get();
        $monedas = Moneda::orderBy('code', 'asc')->get();

        $sumatorias = Cajamovimiento::with('moneda')->withWhereHas('sucursal', function ($query) {
            $query->withTrashed()->where('id', auth()->user()->sucursal_id);
        })->selectRaw('moneda_id, typemovement, SUM(amount) as total')->groupBy('moneda_id')
            ->where('openbox_id', $this->openbox->id)->where('monthbox_id', $this->monthbox->id)
            ->groupBy('typemovement')->orderBy('total', 'desc')->get();

        $diferencias = Cajamovimiento::with('moneda')->withWhereHas('sucursal', function ($query) {
            $query->withTrashed()->where('id', auth()->user()->sucursal_id);
        })->selectRaw("moneda_id, SUM(CASE WHEN typemovement = 'INGRESO' THEN amount ELSE -amount END) as diferencia")
            ->where('openbox_id', $this->openbox->id)->where('monthbox_id', $this->monthbox->id)
            ->groupBy('moneda_id')->orderBy('diferencia', 'desc')->get();

        return view('livewire.modules.administracion.payment-employers.create-adelanto-employer', compact('employers', 'methodpayments', 'monedas', 'sumatorias', 'diferencias'));
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

        if (!$this->monthbox->isUsing()) {
            $mensaje =  response()->json([
                'title' => 'APERTURAR NUEVA CAJA MENSUAL !',
                'text' => "No se encontraron cajas mensuales aperturadas para registrar movimiento."
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }

        if (!$this->openbox->isActivo()) {
            $this->dispatchBrowserEvent('validation', getMessageOpencaja());
            return false;
        }

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

            $methodpayment = Methodpayment::find($this->methodpayment_id)->type;
            $moneda = Moneda::find($this->moneda_id);
            $saldocaja = Cajamovimiento::withWhereHas('methodpayment', function ($query) use ($methodpayment) {
                $query->where('type', $methodpayment);
            })->where('sucursal_id', $this->employer->sucursal_id)
                ->where('openbox_id', $this->openbox->id)->where('monthbox_id', $this->monthbox->id)
                ->where('moneda_id', $this->moneda_id)
                ->selectRaw("COALESCE(SUM(CASE WHEN typemovement = 'INGRESO' THEN amount ELSE -amount END), 0) as diferencia")
                ->first()->diferencia ?? 0;
            $saldocaja = $saldocaja < 0 ? 0 : $saldocaja;
            $forma = $methodpayment == Methodpayment::EFECTIVO ? 'EFECTIVO' : 'TRANSFERENCIAS';
            $amountsaldo = $moneda->code == 'PEN' ? $saldocaja + $this->openbox->aperturarestante : $saldocaja;

            if (($amountsaldo - $this->amount) < 0) {
                $mensaje =  response()->json([
                    'title' => 'SALDO DE CAJA INSUFICIENTE PARA REALIZAR PAGO DEL TRABAJADOR !',
                    'text' => "Monto de egreso en moneda seleccionada supera el saldo disponible en caja, mediante $forma."
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
            }

            $descontar = $saldocaja - $this->amount;
            if ($descontar < 0) {
                $this->openbox->aperturarestante = $this->openbox->aperturarestante + ($descontar);
                $this->openbox->save();
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
