<?php

namespace Modules\Almacen\Http\Livewire\Compras;

use App\Models\Concept;
use App\Models\Cuota;
use App\Models\Empresa;
use App\Models\Methodpayment;
use App\Models\Moneda;
use App\Models\Opencaja;
use App\Models\Proveedor;
use App\Models\Typepayment;
use App\Rules\ValidateNumericEquals;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Modules\Almacen\Entities\Compra;

class ShowCompra extends Component
{

    public $openprice = false;
    public $opencuotas = false;
    public $openpaycuota = false;

    public $compra, $moneda, $empresa, $cuota;
    public $typepayment, $methodpayment, $opencaja;
    public $countcuotas = 1;
    public $amountcuotas = 0;
    public $cuotas = [];
    public $cuentas = [];

    public $methodpayment_id, $detalle, $concept_id, $cuenta_id;

    protected $listeners = ['refresh', 'delete', 'deletepaycuota', 'deletecuota'];

    protected function rules()
    {
        return [
            'compra.proveedor_id' => ['required', 'integer', 'min:1', 'exists:proveedors,id'],
            'compra.date' => ['required', 'date'],
            'compra.moneda_id' => ['required', 'integer', 'min:1', 'exists:monedas,id'],
            'compra.referencia' => ['required', 'string', 'min:3'],
            'compra.tipocambio' => [
                'nullable', Rule::requiredIf($this->moneda->code == 'USD'),
            ],
            'compra.guia' => ['nullable', 'string', 'min:3'],
            'compra.gravado' => ['required', 'numeric', 'min:0', 'decimal:0,4'],
            'compra.exonerado' => ['required', 'numeric', 'min:0', 'decimal:0,4'],
            'compra.igv' => ['required', 'numeric', 'min:0', 'decimal:0,4'],
            'compra.otros' => ['required', 'numeric', 'min:0', 'decimal:0,4'],
            'compra.total' => ['required', 'numeric', 'gt:0', 'decimal:0,4'],
            'compra.typepayment_id' => ['required', 'integer', 'min:1', 'exists:typepayments,id'],
            'compra.detalle' => ['nullable', 'string'],
            'compra.counter' => ['required', 'numeric', 'min:0', 'decimal:0,2'],
        ];
    }

    public function mount(Compra $compra, Empresa $empresa, Opencaja $opencaja)
    {
        $this->compra = $compra;
        $this->opencaja = $opencaja;
        $this->moneda = Moneda::find($compra->moneda_id) ?? null;
        $this->cuota = new Cuota();
        $this->empresa = $empresa;
        $this->typepayment = $compra->typepayment;
    }

    public function render()
    {
        $proveedores = Proveedor::orderBy('name', 'asc')->get();
        $typepayments = Typepayment::orderBy('name', 'asc')->get();
        $methodpayments = Methodpayment::orderBy('name', 'asc')->get();
        return view('almacen::livewire.compras.show-compra', compact('proveedores', 'typepayments', 'methodpayments'));
    }

    public function refresh()
    {
        $this->compra->refresh();
    }

    public function updatedCompraMonedaId($value)
    {
        $this->moneda = Moneda::find($value) ?? null;
    }

    public function updatedCompraGravado($value)
    {
        $this->compra->gravado = number_format(trim($value) == "" ? 0 : $value, 4, '.', '');
        $this->calculartotal();
    }

    public function updatedCompraExonerado($value)
    {
        $this->compra->exonerado = number_format(trim($value) == "" ? 0 : $value, 4, '.', '');
        $this->calculartotal();
    }

    public function updatedCompraIgv($value)
    {
        $this->compra->igv = number_format(trim($value) == "" ? 0 : $value, 4, '.', '');
        $this->calculartotal();
    }

    public function updatedCompraOtros($value)
    {
        $this->compra->otros = number_format(trim($value) == "" ? 0 : $value, 4, '.', '');
        $this->calculartotal();
    }

    public function calculartotal()
    {
        $this->compra->total = number_format($this->compra->gravado +  $this->compra->igv + $this->compra->exonerado + $this->compra->otros, 4, '.', '');
    }

    public function update()
    {
        $validateData = $this->validate();
        $this->calculartotal();
        DB::beginTransaction();
        try {
            $this->compra->save();

            if ($this->compra->cajamovimiento) {
                $this->compra->cajamovimiento->amount = $this->compra->total;
                $this->compra->cajamovimiento->save();
            }
            DB::commit();
            $this->compra->refresh();
            $this->resetValidation();
            $this->dispatchBrowserEvent('updated');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
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
                $result =  number_format($this->compra->total - $sumaCuotas, 4, '.', '');
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
            'amountcuotas' => ['required', 'numeric', 'min:1', 'decimal:0,4', new ValidateNumericEquals($this->compra->total)]
        ]);

        $responseCuotas = response()->json($this->cuotas)->getData();
        DB::beginTransaction();

        foreach ($responseCuotas as $item) {
            $this->compra->cuotas()->create([
                "cuota" => $item->cuota,
                "expiredate" => $item->date,
                "amount" => $item->amount,
                "user_id" => Auth::user()->id,
            ]);
        }

        DB::commit();
        $this->compra->refresh();
        $this->resetValidation(['cuotas', 'amountcuotas', 'countcuotas']);
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
            'cajamovimiento_id' =>  null,
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
                        "user_id" => Auth::user()->id
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

    public function pay(Cuota $cuota)
    {
        $this->resetValidation();
        $this->reset(['cuentas', 'cuenta_id', 'methodpayment_id', 'detalle']);
        $this->cuota = $cuota;
        // $this->methodpayment_id  = Methodpayment::DefaultMethodpayment()->first()->id ?? null;
        $this->openpaycuota = true;
    }

    public function savepayment()
    {

        $this->concept_id = Concept::DefaultConceptPaycuota()->first()->id ?? null;

        $this->validate([
            'cuota.id' => ['required', 'integer', 'min:1', 'exists:cuotas,id'],
            'opencaja.id' => ['required', 'integer', 'min:1', 'exists:opencajas,id'],
            'concept_id' => ['required', 'integer', 'min:1', 'exists:concepts,id'],
            'methodpayment_id' => ['required', 'integer', 'min:1', 'exists:methodpayments,id'],
            'cuenta_id' => [
                'nullable',
                Rule::requiredIf(count($this->cuentas) > 1),
                'integer', 'min:1', 'exists:cuentas,id'

            ],
            'detalle' => ['nullable'],
        ]);

        DB::beginTransaction();
        try {
            $this->cuota->cajamovimiento()->create([
                'date' => now('America/Lima'),
                'amount' => number_format($this->cuota->amount, 2, '.', ''),
                'referencia' => $this->compra->referencia,
                'detalle' => trim($this->detalle),
                'moneda_id' => $this->compra->moneda_id,
                'methodpayment_id' => $this->methodpayment_id,
                'typemovement' => '-',
                'cuenta_id' => $this->cuenta_id,
                'concept_id' => $this->concept_id,
                'opencaja_id' => $this->opencaja->id,
                'user_id' => Auth::user()->id,
            ]);

            DB::commit();
            $this->resetValidation();
            $this->reset(['openpaycuota', 'methodpayment_id', 'concept_id', 'cuenta_id']);
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

    public function updatedMethodpaymentId($value)
    {
        $this->reset(['cuentas', 'cuenta_id']);
        if ($value) {
            $methodpayment = Methodpayment::find($value);
            $this->cuentas = $methodpayment->cuentas;
            if (count($this->cuentas) == 1) {
                $this->cuenta_id = $methodpayment->cuentas()->first()->id ?? null;
            }
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

    public function delete()
    {

        if (count($this->compra->compraitems)) {

            $seriesouts = $this->compra->compraitems()
                ->WhereHas('series', function ($query) {
                    $query->whereNotNull('dateout');
                })
                ->count();

            if ($seriesouts) {
                $message = response()->json([
                    'title' => 'No se puede eliminar el registro seleccionado !',
                    'text' => 'Existen series relacionados en salidas, eliminarlo causará conflictos en los datos'
                ])->getData();
                $this->dispatchBrowserEvent('validation', $message);
                return false;
            }
        }

        // dd("Proceder a eliminar...");
        DB::beginTransaction();
        try {
            $this->compra->compraitems()->each(function ($item) {
                $productoAlmacen = $item->producto->almacens()
                    ->where('almacen_id', $item->almacen_id)->first();

                $item->producto->almacens()->updateExistingPivot($item->almacen_id, [
                    'cantidad' =>  $productoAlmacen->pivot->cantidad - $item->cantidad,
                ]);

                $item->producto->pricebuy = $item->oldpricebuy;
                $item->producto->pricesale = $item->oldpricesale;
                $item->producto->save();
                $item->series()->forceDelete();
                $item->forceDelete();
            });

            if ($this->compra->cajamovimiento) {
                $this->compra->cajamovimiento()->delete();
            }
            $this->compra->delete();
            DB::commit();
            $this->dispatchBrowserEvent('deleted');
            return redirect()->route('admin.almacen.compras');
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
