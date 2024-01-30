<?php

namespace App\Http\Livewire\Modules\Almacen\Compras;

use App\Models\Cajamovimiento;
use App\Models\Concept;
use App\Models\Cuota;
use App\Models\Methodpayment;
use App\Models\Opencaja;
use App\Models\Typepayment;
use App\Rules\ValidateNumericEquals;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Almacen\Entities\Compra;

class ShowCuotasCompra extends Component
{

    use WithPagination;
    public $opencuotas = false;
    public $openpaycuota = false;
    public $compra, $cuota, $concept, $opencaja;
    public $countcuotas = 1;
    public $amountcuotas = 0;

    public $methodpayment_id, $detalle;
    public $cuotas = [];

    public function mount(Compra $compra, Opencaja $opencaja)
    {
        $this->compra = $compra;
        $this->opencaja = $opencaja;
        $this->concept = new Concept();
        $this->cuota = new Cuota();
    }

    public function render()
    {
        $typepayments = Typepayment::orderBy('name', 'asc')->get();
        $methodpayments = Methodpayment::orderBy('name', 'asc')->get();

        return view('livewire.modules.almacen.compras.show-cuotas-compra', compact('typepayments', 'methodpayments'));
    }

    public function updatedOpenpaycuota()
    {
        if ($this->openpaycuota == false) {
            $this->reset(['cuota']);
            $this->cuota = new Cuota();
        }
    }

    public function calcularcuotas()
    {

        $this->reset(['cuotas']);
        $this->resetValidation(['countcuotas']);
        $this->validate([
            'countcuotas' => ['required', 'numeric', 'min:1']
        ]);

        // $totalAmount = number_format($this->compra->moneda->code == "USD" ? $this->compra->totalpayus : $this->compra->totalpay, 2, '.', '');
        $amountCuota = number_format($this->compra->total / $this->countcuotas, 4, '.', '');
        $date = Carbon::now('America/Lima')->format('Y-m-d');

        $sumaCuotas = 0.00;
        for ($i = 1; $i <= $this->countcuotas; $i++) {
            $sumaCuotas = number_format($sumaCuotas + $amountCuota, 4, '.', '');

            if ($i == $this->countcuotas) {
                $result = number_format($this->compra->total - $sumaCuotas, 4, '.', '');
                $amountCuota = number_format($amountCuota + ($result), 4, '.', '');
            }

            $this->cuotas[] = [
                'cuota' => $i,
                'date' => $date,
                'amount' => $amountCuota,
                'suma' => $sumaCuotas,
                'cajamovimiento_id' => null,
            ];
            $date = Carbon::parse($date)->addMonth()->format('Y-m-d');
        }
    }

    public function savecuotas()
    {

        $arrayamountcuotas = array_column($this->cuotas, 'amount');
        $this->resetValidation(['cuotas']);
        $this->amountcuotas = number_format(array_sum($arrayamountcuotas), 4, '.', '');

        $this->validate([
            'compra.id' => ['required', 'integer', 'min:1', 'exists:compras,id'],
            'countcuotas' => ['required', 'integer', 'min:1'],
            'cuotas' => ['required', 'array', 'min:1'],
            "cuotas.*.cuota" => ['required', 'integer', 'min:1'],
            "cuotas.*.date" => ['required', 'date', 'after_or_equal:today'],
            "cuotas.*.amount" => ['required', 'numeric', 'min:1', 'decimal:0,4'],
            'amountcuotas' => [
                'required', 'numeric', 'min:1', 'decimal:0,4',
                new ValidateNumericEquals($this->compra->total)
            ]
        ]);

        $responseCuotas = response()->json($this->cuotas)->getData();
        DB::beginTransaction();

        foreach ($responseCuotas as $item) {
            $this->compra->cuotas()->create([
                'cuota' => $item->cuota,
                'expiredate' => $item->date,
                'amount' => $item->amount,
                'user_id' => auth()->user()->id,
            ]);
        }

        DB::commit();
        $this->compra->refresh();
        $this->resetValidation();
        $this->reset(['cuotas', 'amountcuotas', 'countcuotas']);
        $this->dispatchBrowserEvent('updated');
    }

    public function editcuotas()
    {

        $this->resetValidation(['cuotas']);
        $this->reset(['cuotas']);

        if (count($this->compra->cuotas)) {
            foreach ($this->compra->cuotas as $cuota) {
                $this->cuotas[] = [
                    'id' => $cuota->id,
                    'cuota' => $cuota->cuota,
                    'date' => $cuota->expiredate,
                    'cajamovimiento_id' => $cuota->cajamovimiento->id ?? null,
                    'amount' => $cuota->amount,
                ];
            }
        }
        $this->opencuotas = true;
    }

    public function addnewcuota()
    {
        $this->cuotas[] = [
            'id' => null,
            'cuota' => count($this->cuotas) + 1,
            'date' => Carbon::now('America/Lima')->format('Y-m-d'),
            'cajamovimiento_id' => null,
            'amount' => '0.00',
        ];
    }

    public function updatecuotas()
    {

        $arrayamountcuotas = array_column($this->cuotas, 'amount');
        $this->resetValidation(['cuotas']);
        $this->amountcuotas = number_format(array_sum($arrayamountcuotas), 2, '.', '');

        $this->validate([
            'compra.id' => ['required', 'integer', 'min:1', 'exists:compras,id'],
            'cuotas' => ['required', 'array', 'min:1'],
            'cuotas.*.id' => ['nullable', 'integer', 'min:1', 'exists:cuotas,id'],
            'cuotas.*.cuota' => ['required', 'integer', 'min:1'],
            'cuotas.*.date' => ['required', 'date'],
            'cuotas.*.amount' => ['required', 'numeric', 'min:1', 'decimal:0,4'],
            'cuotas.*.cajamovimiento_id' => ['nullable', 'integer', 'min:1', 'exists:cajamovimientos,id'],
            'amountcuotas' => ['required', 'numeric', 'min:1', 'decimal:0,4', new ValidateNumericEquals($this->compra->total)]
        ]);

        $responseCuotas = response()->json($this->cuotas)->getData();
        DB::beginTransaction();

        try {
            foreach ($responseCuotas as $key => $item) {
                if (!$item->cajamovimiento_id) {
                    if (Carbon::parse($item->date)->isBefore(Carbon::now()->format('Y-m-d'))) {
                        $this->addError("cuotas.$key.date", 'La fecha debe ser mayor a la fecha actual.');
                        return false;
                    }
                }
                if ($item->id) {
                    if (!$item->cajamovimiento_id) {
                        $cuota = Cuota::find($item->id);
                        $cuota->expiredate = $item->date;
                        $cuota->amount = $item->amount;
                        $cuota->update();
                    }
                } else {
                    $this->compra->cuotas()->create([
                        "cuota" => $item->cuota,
                        "expiredate" => $item->date,
                        "amount" => $item->amount,
                        "user_id" => auth()->user()->id
                    ]);
                }
            }

            DB::commit();
            $this->resetValidation();
            $this->reset(['opencuotas', 'cuotas']);
            $this->compra->refresh();
            $this->dispatchBrowserEvent('updated');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function paycuota(Cuota $cuota)
    {
        $this->resetValidation();
        $this->reset(['methodpayment_id', 'detalle']);
        $this->concept = Concept::PaycuotaCompra()->first();
        $this->cuota = $cuota;
        $this->openpaycuota = true;
    }

    public function savepayment()
    {

        if (!verifyOpencaja($this->opencaja->id)) {
            $this->dispatchBrowserEvent('validation', getMessageOpencaja());
            return false;
        }

        $this->validate([
            'cuota.id' => ['required', 'integer', 'min:1', 'exists:cuotas,id'],
            'opencaja.id' => ['required', 'integer', 'min:1', 'exists:opencajas,id'],
            'concept.id' => ['required', 'integer', 'min:1', 'exists:concepts,id'],
            'methodpayment_id' => ['required', 'integer', 'min:1', 'exists:methodpayments,id'],
            'detalle' => ['nullable'],
        ]);

        DB::beginTransaction();
        try {
            $this->cuota->cajamovimiento()->create([
                'date' => now('America/Lima'),
                'amount' => number_format($this->cuota->amount, 3, '.', ''),
                'referencia' => $this->compra->referencia,
                'detalle' => trim($this->detalle),
                'moneda_id' => $this->compra->moneda_id,
                'methodpayment_id' => $this->methodpayment_id,
                'typemovement' => Cajamovimiento::EGRESO,
                'concept_id' => $this->concept->id,
                'opencaja_id' => $this->opencaja->id,
                'sucursal_id' => $this->compra->sucursal->id,
                'user_id' => auth()->user()->id,
            ]);

            DB::commit();
            $this->resetValidation();
            $this->reset(['openpaycuota', 'methodpayment_id', 'detalle']);
            $this->compra->refresh();
            $this->dispatchBrowserEvent('created');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deletepaycuota(Cuota $cuota)
    {

        DB::beginTransaction();
        try {
            // dd($cajamovimiento->cuota);
            $cuota->cajamovimiento->delete();
            DB::commit();
            $this->compra->refresh();
            $this->dispatchBrowserEvent('deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deletecuota(Cuota $cuota)
    {

        DB::beginTransaction();
        try {
            $cuota->delete();
            DB::commit();
            $this->compra->refresh();
            $this->dispatchBrowserEvent('deleted');
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
        $this->dispatchBrowserEvent('render-select2-editcompra');
    }
}
