<?php

namespace App\Http\Livewire\Modules\Administracion\PaymentEmployers;

use App\Enums\MovimientosEnum;
use App\Models\Cajamovimiento;
use App\Models\Concept;
use App\Models\Employer;
use App\Models\Employerpayment;
use App\Models\Methodpayment;
use App\Models\Moneda;
use App\Models\Monthbox;
use App\Models\Openbox;
use App\Rules\ValidateBoxPayEqual;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ShowPaymentEmployers extends Component
{

    use WithPagination, AuthorizesRequests;

    public $employer;
    public $employerpayment;
    public $open = false;
    protected $listeners = ['render'];

    public $amount, $methodpayment_id, $concept_id, $openbox, $monthbox, $moneda_id, $detalle;
    public $amountmax = 0;
    public $searchmonth = '';

    protected $queryString = [
        'searchmonth' => ['except' => '', 'as' => 'mes'],
    ];

    protected function rules()
    {
        return [
            'amount' => [
                'required', 'numeric', 'gt:0', 'decimal:0,4', 'max:' . $this->amountmax,
            ],
            'methodpayment_id' => ['required', 'integer', 'min:1', 'exists:methodpayments,id'],
            'concept_id' => ['required', 'integer', 'min:1', 'exists:concepts,id'],
            'openbox.id' => ['required', 'integer', 'min:1', 'exists:openboxes,id'],
            'monthbox.id' => [
                'required', 'integer', 'min:1', 'exists:monthboxes,id',
                new ValidateBoxPayEqual($this->employerpayment->id)
            ],
            'moneda_id' => ['required', 'integer', 'min:1', 'exists:monedas,id'],
            'detalle' => ['nullable', 'string'],
        ];
    }

    public function mount(Employer $employer)
    {
        $this->employerpayment = new Employerpayment();
        $this->employer = $employer;
        $this->openbox =  Openbox::mybox(auth()->user()->sucursal_id)->first();
        $this->monthbox = Monthbox::usando(auth()->user()->sucursal_id)->first();
        $this->concept_id = Concept::payemployer()->first()->id ?? null;
        $this->moneda_id = Moneda::default()->first()->id ?? null;
        $this->methodpayment_id = Methodpayment::default()->first()->id ?? null;
    }


    public function render()
    {
        $payments = $this->employer->employerpayments()->with('cajamovimientos');
        $methodpayments = Methodpayment::orderBy('name', 'asc')->get();
        $months = Employerpayment::select('month')->where('employer_id', $this->employer->id)->groupBy('month')->get();

        if (trim($this->searchmonth) != '') {
            $payments->where('month', $this->searchmonth);
        }
        $payments = $payments->paginate();
        return view('livewire.modules.administracion.payment-employers.show-payment-employers', compact('payments', 'methodpayments', 'months'));
    }

    public function pay(Employerpayment $employerpayment)
    {
        $this->authorize('admin.administracion.employers.payments.create');
        $this->employerpayment = $employerpayment;
        $this->resetValidation();
        $this->reset(['amount', 'detalle',]);
        $this->amount = number_format($this->employer->sueldo + $employerpayment->bonus - ($employerpayment->amount + $employerpayment->descuentos + $employerpayment->adelantos), 2, '.', ', ');
        $this->open = true;
    }

    public function save()
    {
        $this->authorize('admin.administracion.employers.payments.create');
        if (!$this->monthbox || !$this->monthbox->isUsing()) {
            $this->dispatchBrowserEvent('validation', getMessageMonthbox());
            return false;
        }

        if (!$this->openbox || !$this->openbox->isActivo()) {
            $this->dispatchBrowserEvent('validation', getMessageOpencaja());
            return false;
        }

        $this->amountmax = ($this->employer->sueldo + $this->employerpayment->bonus) - ($this->employerpayment->amount + $this->employerpayment->adelantos + $this->employerpayment->descuentos);
        $validateData = $this->validate();
        // dd($validateData, $this->amountmax);
        try {

            DB::beginTransaction();
            $methodpayment = Methodpayment::find($this->methodpayment_id);
            $saldocaja = Cajamovimiento::withWhereHas('methodpayment', function ($query) use ($methodpayment) {
                $query->where('type', $methodpayment->type);
            })->where('sucursal_id', $this->employerpayment->employer->sucursal_id)
                ->where('openbox_id', $this->openbox->id)->where('monthbox_id', $this->monthbox->id)
                ->where('moneda_id', $this->moneda_id)
                ->selectRaw("COALESCE(SUM(CASE WHEN typemovement = '" . MovimientosEnum::INGRESO->value . "' THEN amount ELSE -amount END), 0) as diferencia")
                ->first()->diferencia ?? 0;
            $forma = $methodpayment->isEfectivo() ? 'EFECTIVO' : 'TRANSFERENCIA';

            if (($saldocaja - $this->amount) < 0) {
                $mensaje =  response()->json([
                    'title' => 'SALDO DE CAJA INSUFICIENTE PARA REALIZAR PAGO DEL PERSONAL !',
                    'text' => "Monto de egreso en moneda seleccionada supera el saldo disponible en caja, mediante $forma."
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
            }

            $this->employerpayment->savePayment(
                $this->employer->sucursal_id,
                $this->amount,
                $this->amount,
                null,
                $this->moneda_id,
                $this->methodpayment_id,
                MovimientosEnum::EGRESO->value,
                $this->concept_id,
                $this->openbox->id,
                $this->monthbox->id,
                $this->employer->name,
                $this->detalle,
            );

            $this->employerpayment->amount = $this->employerpayment->amount + $this->amount;
            $this->employerpayment->save();
            DB::commit();
            $this->reset(['amount', 'detalle', 'open']);
            $this->resetValidation();
            $this->dispatchBrowserEvent('created');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(Employerpayment $employerpayment)
    {
        $this->authorize('admin.administracion.employers.payments.delete');
        try {
            DB::beginTransaction();
            if ($employerpayment->cajamovimientos()->exists()) {
                $employerpayment->cajamovimientos->forceDelete();
            }
            $employerpayment->delete();
            DB::commit();
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
