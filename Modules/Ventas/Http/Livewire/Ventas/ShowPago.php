<?php

namespace Modules\Ventas\Http\Livewire\Ventas;

use App\Models\Concept;
use App\Models\Methodpayment;
use App\Models\Opencaja;
use Carbon\Carbon;
use Livewire\Component;
use Modules\Facturacion\Entities\Typecomprobante;
use Modules\Facturacion\Entities\Typepayment;

class ShowPago extends Component
{

    public $typepayment;
    public $methodpayment;

    public $cuotas = [];
    public $accounts = [];
    public $totalIncrement = 0;

    public $caja_id, $typecomprobante_id, $typepayment_id,
        $methodpayment_id, $detallepago, $concept_id, $cuenta_id;

    public function mount()
    {
        $this->typepayment = Typepayment::defaultTypepayment()->first();
        $this->typepayment_id = Typepayment::defaultTypepayment()->first()->id ?? null;
        $this->methodpayment_id = Methodpayment::defaultMethodPayment()->first()->id ?? null;
        $this->typecomprobante_id = Typecomprobante::defaultTypecomprobante()->first()->id ?? null;
        $this->concept_id = Concept::defaultConceptVentas()->first()->id ?? null;
    }


    public function render()
    {
        $typecomprobantes = Typecomprobante::orderBy('code', 'asc')->get();
        $typepayments = Typepayment::orderBy('name', 'asc')->get();
        $methodpayments = Methodpayment::orderBy('name', 'asc')->get();
        $opencajas = Opencaja::CajasAbiertas()->CajasUser()->orderBy('startdate', 'desc')->get();

        return view('ventas::livewire.ventas.show-pago', compact('typecomprobantes', 'typepayments', 'opencajas', 'methodpayments'));
    }


    public function updatedMethodpaymentId($value)
    {

        $this->reset(['accounts', 'cuenta_id']);
        $this->methodpayment_id = !empty(trim($value)) ? trim($value) : null;
        if ($this->methodpayment_id) {
            $this->methodpayment = Methodpayment::findOrFail($value);
            $this->accounts = $this->methodpayment->cuentas;
            if ($this->methodpayment->cuentas->count() == 1) {
                $this->cuenta_id = $this->methodpayment->cuentas->first()->id;
            }
        }
    }

    public function updatedTypepaymentId($value)
    {

        $this->reset(['typepayment', 'increment', 'countcuotas', 'cuotas', 'methodpayment_id', 'accounts', 'cuenta_id']);
        $this->typepayment_id = !empty(trim($value)) ? trim($value) : null;
        $this->typepayment = Typepayment::findOrFail($value);
        if ($this->typepayment->paycuotas) {
            $this->calcular_cuotas();
        }


        // if ($this->client->pricetype_id) {
        //     $this->pricetype_id = $this->client->pricetype_id;
        // }
    }

    public function calcular_cuotas()
    {
        $this->reset(['cuotas']);
        $this->resetValidation(['increment', 'countcuotas']);

        if ((!empty(trim($this->countcuotas))) || $this->countcuotas > 0) {

            $totalAmount = number_format($this->total, 2, '.', '');

            if ((!empty(trim($this->increment))) || $this->increment > 0) {
                $incremento = ($totalAmount * $this->increment) / 100;
                $totalAmount = number_format($totalAmount + $incremento, 2, '.', '');
                $this->totalIncrement = number_format($totalAmount, 2, '.', '');
            }
            // $this->total = $totalAmount;

            $amountCuota = number_format($totalAmount / $this->countcuotas, 2, '.', '');
            $date = Carbon::now('America/Lima')->format('Y-m-d');

            $sumaCuotas = 0.00;
            for ($i = 1; $i <= $this->countcuotas; $i++) {
                $cuota = "00000" . $i;
                $sumaCuotas = number_format($sumaCuotas + $amountCuota, 2, '.', '');

                if ($i == $this->countcuotas) {
                    $result =  number_format($totalAmount - $sumaCuotas, 2, '.', '');
                    $amountCuota = number_format($amountCuota + ($result), 2, '.', '');
                }

                $this->cuotas[] = [
                    // 'cuota' => "Cuota" . substr($cuota, -3),
                    'cuota' => $i,
                    'date' => $date,
                    'amount' => $amountCuota,
                    'suma' => $sumaCuotas,
                ];
                $date = Carbon::parse($date)->addMonth()->format('Y-m-d');
            }
        } else {
            $this->addError('countcuotas', 'Ingrese cantidad válida de cuotas');
        }
    }
}
