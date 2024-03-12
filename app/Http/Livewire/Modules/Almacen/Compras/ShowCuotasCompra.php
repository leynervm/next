<?php

namespace App\Http\Livewire\Modules\Almacen\Compras;

use App\Enums\MovimientosEnum;
use App\Models\Cajamovimiento;
use App\Models\Concept;
use App\Models\Cuota;
use App\Models\Methodpayment;
use App\Models\Monthbox;
use App\Models\Openbox;
use App\Models\Typepayment;
use App\Rules\ValidateNumericEquals;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Almacen\Entities\Compra;

class ShowCuotasCompra extends Component
{

    use AuthorizesRequests;

    use WithPagination;
    public $opencuotas = false;
    public $openpaycuota = false;
    public $compra, $cuota, $concept_id, $openbox, $monthbox;
    public $countcuotas = 1;
    public $amountcuotas = 0;

    public $methodpayment_id, $detalle;
    public $cuotas = [];

    public function mount(Compra $compra)
    {
        $this->compra = $compra;
        $this->openbox = Openbox::mybox(auth()->user()->sucursal_id)->first();
        $this->monthbox = Monthbox::usando($this->compra->sucursal_id)->first();
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
            $this->authorize('admin.almacen.compras.pagos');
            $this->reset(['cuota']);
            $this->cuota = new Cuota();
        }
    }

    public function calcularcuotas()
    {

        $this->authorize('admin.almacen.compras.create');
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

        $this->authorize('admin.almacen.compras.create');
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
                "moneda_id" => $this->compra->moneda_id,
                "sucursal_id" => $this->compra->sucursal_id,
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

        $this->authorize('admin.almacen.compras.create');
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
        $this->authorize('admin.almacen.compras.create');
        if (count($this->cuotas) > 0) {
            if (!empty(end($this->cuotas)['date'])) {
                $date = Carbon::parse(end($this->cuotas)['date'])->addMonth()->format('Y-m-d');
            } else {
                $date = Carbon::now('America/Lima')->format('Y-m-d');
            }
        } else {
            $date = Carbon::now('America/Lima')->format('Y-m-d');
        }

        $this->cuotas[] = [
            'id' => null,
            'cuota' => count($this->cuotas) + 1,
            'date' => $date,
            'cajamovimiento_id' => null,
            'amount' => '0.00',
        ];
    }

    public function updatecuotas()
    {

        $this->authorize('admin.almacen.compras.create');
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
                        "moneda_id" => $this->compra->moneda_id,
                        "sucursal_id" => $this->compra->sucursal_id,
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
        $this->authorize('admin.almacen.compras.pagos');
        $this->resetValidation();
        $this->reset(['methodpayment_id', 'detalle']);
        $this->cuota = $cuota;
        $this->openpaycuota = true;
    }

    public function savepayment()
    {

        $this->authorize('admin.almacen.compras.pagos');
        if ($this->compra->sucursal_id <> auth()->user()->sucursal_id) {
            $mensaje =  response()->json([
                'title' => 'SUCURSAL DE COMPRA DIFERENTE A SUCURSAL DE APERTURA DE CAJA !',
                'text' => "No se pueden realizar movimientos en caja de una sucursal diferente a caja aperturada."
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
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

        $this->concept_id = Concept::PaycuotaCompra()->first()->id ?? null;
        $this->validate([
            'cuota.id' => ['required', 'integer', 'min:1', 'exists:cuotas,id'],
            'openbox.id' => ['required', 'integer', 'min:1', 'exists:openboxes,id'],
            'monthbox.id' => ['required', 'integer', 'min:1', 'exists:monthboxes,id'],
            'concept_id' => ['required', 'integer', 'min:1', 'exists:concepts,id'],
            'methodpayment_id' => ['required', 'integer', 'min:1', 'exists:methodpayments,id'],
            'detalle' => ['nullable'],
        ]);

        DB::beginTransaction();
        try {

            $methodpayment = Methodpayment::find($this->methodpayment_id)->type;
            $saldocaja = Cajamovimiento::withWhereHas('methodpayment', function ($query) use ($methodpayment) {
                $query->where('type', $methodpayment);
            })->where('sucursal_id', $this->compra->sucursal_id)
                ->where('openbox_id', $this->openbox->id)->where('monthbox_id', $this->monthbox->id)
                ->where('moneda_id', $this->compra->moneda_id)
                ->selectRaw("COALESCE(SUM(CASE WHEN typemovement = 'INGRESO' THEN amount ELSE -amount END), 0) as diferencia")
                ->first()->diferencia ?? 0;
            $saldocaja = $saldocaja < 0 ? 0 : $saldocaja;
            $forma = $methodpayment == Methodpayment::EFECTIVO ? 'EFECTIVO' : 'TRANSFERENCIAS';
            $amountsaldo = $this->compra->moneda->code == 'PEN' ? $saldocaja + $this->openbox->aperturarestante : $saldocaja;

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

            $this->cuota->savePayment(
                $this->compra->sucursal_id,
                number_format($this->cuota->amount, 3, '.', ''),
                $this->compra->moneda_id,
                $this->methodpayment_id,
                MovimientosEnum::EGRESO->value,
                $this->concept_id,
                $this->openbox->id,
                $this->monthbox->id,
                $this->compra->referencia,
                trim($this->detalle)
            );
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

        $this->authorize('admin.almacen.compras.pagos');
        DB::beginTransaction();
        try {
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

        $this->authorize('admin.almacen.compras.create');
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
