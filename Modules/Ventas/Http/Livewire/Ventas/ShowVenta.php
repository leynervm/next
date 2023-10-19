<?php

namespace Modules\Ventas\Http\Livewire\Ventas;

use App\Models\Concept;
use App\Models\Cuota;
use App\Models\Methodpayment;
use App\Models\Opencaja;
use App\Rules\ValidateNumericEquals;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Modules\Ventas\Entities\Venta;

class ShowVenta extends Component
{

    public $open = false;
    public $opencuotas = false;
    public $venta, $cuota, $methodpayment, $typepayment;
    public $opencaja, $concept;
    public $methodpayment_id, $methodpaymentventa_id, $detalle, $cuentaventa_id, $cuenta_id;
    public $cuentas = [];
    public $cuotas = [];
    public $amountcuotas = 0;

    protected $listeners = ['delete', 'deletepaycuota', 'deletecuota'];

    protected function rules()
    {
        return [
            'venta.id' => [
                'required', 'integer', 'min:1', 'exists:ventas,id'
            ],
            'methodpaymentventa_id' => [
                'nullable',
                Rule::requiredIf($this->typepayment->paycuotas == 0),
                'integer', 'min:1', 'exists:methodpayments,id'
            ],
            'cuentaventa_id' => [
                'nullable',
                Rule::requiredIf($this->typepayment->paycuotas == 0),
                'integer', 'min:1', 'exists:cuentas,id'
            ],
            'detalle' => ['nullable'],
        ];
    }

    protected $messages = [
        'cuotas.*.id.required' => 'Id de cuota requerido',
        'cuotas.*.date.required' => 'Fecha de pago de cuota requerido',
        'cuotas.*.date.after_or_equal' => 'Fecha de pago debe ser mayor igual a la actual',
        'cuotas.*.amount.required' => 'Monto de cuota requerido',
    ];

    public function mount(Venta $venta, Concept $concept, Methodpayment $methodpayment, Opencaja $opencaja)
    {
        $this->venta = $venta;
        $this->concept = $concept;
        $this->opencaja = $opencaja;
        $this->methodpayment_id = $methodpayment->id ?? null;
        $this->typepayment = $venta->typepayment;
        $this->cuota = new Cuota();
        if ($this->venta->cajamovimiento) {
            $this->methodpayment = $venta->cajamovimiento->methodpayment;
            $this->methodpaymentventa_id = $venta->cajamovimiento->methodpayment_id ?? null;
            $this->detalle = $venta->cajamovimiento->detalle;
            $this->cuentaventa_id = $venta->cajamovimiento->cuenta_id;
            $this->cuentas = $this->methodpayment->cuentas;
        }
    }

    public function render()
    {
        $methodpayments = Methodpayment::orderBy('name', 'asc')->get();
        return view('ventas::livewire.ventas.show-venta', compact('methodpayments'));
    }

    public function update()
    {
        $this->validate();
        $this->venta->cajamovimiento->methodpayment_id = $this->methodpaymentventa_id;
        $this->venta->cajamovimiento->cuenta_id = $this->cuentaventa_id;
        $this->venta->cajamovimiento->save();
        $this->dispatchBrowserEvent('updated');
    }

    public function pay(Cuota $cuota)
    {
        $this->reset(['cuentas', 'cuenta_id']);
        $this->resetValidation();
        $this->cuota = $cuota;

        $this->methodpayment = Methodpayment::DefaultMethodpayment()->first() ?? new Methodpayment();
        $this->cuentas = $this->methodpayment->cuentas ?? [];
        if (count($this->cuentas) == 1) {
            $this->cuenta_id = $this->methodpayment->cuentas->first()->id;
        }
        $this->open = true;
    }

    public function savepayment()
    {

        $this->validate([
            'cuota.id' => ['required', 'integer', 'min:1', 'exists:cuotas,id'],
            'opencaja.id' => ['required', 'integer', 'min:1', 'exists:opencajas,id'],
            'concept.id' => ['required', 'integer', 'min:1', 'exists:concepts,id'],
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
                'referencia' => $this->venta->comprobante->seriecompleta,
                'detalle' => trim($this->detalle),
                'moneda_id' => $this->venta->moneda_id,
                'methodpayment_id' => $this->methodpayment_id,
                'typemovement' => '+',
                'cuenta_id' => $this->cuenta_id,
                'concept_id' => $this->concept->id,
                'opencaja_id' => $this->opencaja->id,
                'user_id' => Auth::user()->id,
            ]);

            DB::commit();
            $this->resetValidation();
            $this->reset(['open', 'methodpayment_id', 'cuenta_id', 'cuentas']);
            $this->venta->refresh();
            $this->dispatchBrowserEvent('created');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }


    public function updatedMethodpaymentventaId($value)
    {

        $this->reset(['cuentas', 'cuentaventa_id']);
        $this->methodpaymentventa_id = !empty(trim($value)) ? trim($value) : null;
        if ($this->methodpaymentventa_id) {
            $this->methodpayment = Methodpayment::findOrFail($value);
            $this->cuentas = $this->methodpayment->cuentas;
            if ($this->methodpayment->cuentas->count() == 1) {
                $this->cuentaventa_id = $this->methodpayment->cuentas->first()->id;
            }
        }
    }

    public function updatedMethodpaymentId($value)
    {

        $this->reset(['cuentas', 'cuenta_id']);
        $this->methodpaymentventa_id = !empty(trim($value)) ? trim($value) : null;
        if ($this->methodpaymentventa_id) {
            $this->methodpayment = Methodpayment::findOrFail($value);
            $this->cuentas = $this->methodpayment->cuentas;
            if ($this->methodpayment->cuentas->count() == 1) {
                $this->cuenta_id = $this->methodpayment->cuentas->first()->id;
            }
        }
    }

    public function deletepaycuota(Cuota $cuota)
    {

        DB::beginTransaction();
        try {
            $cuota->cajamovimiento->delete();
            DB::commit();
            $this->venta->refresh();
            $this->dispatchBrowserEvent('deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(Venta $venta)
    {

        DB::beginTransaction();

        try {
            if ($venta->cajamovimiento) {
                $venta->cajamovimiento->delete();
            }

            $venta->cuotas()->each(function ($cuota) {
                $cuota->cajamovimiento->delete();
                $cuota->forceDelete();
            });

            $venta->tvitems()->each(function ($item) {
                $stockPivot = $item->producto->almacens()
                    ->where('almacen_id', $item->almacen_id)->first();

                $item->producto->almacens()->updateExistingPivot($item->almacen_id, [
                    'cantidad' =>  $stockPivot->pivot->cantidad + $item->cantidad,
                ]);

                $item->itemseries()->each(function ($itemserie) {
                    $itemserie->serie->dateout = null;
                    $itemserie->serie->status = 0;
                    $itemserie->serie->save();
                    $itemserie->forceDelete();
                });
                // $item->series()->forceDelete();
                $item->forceDelete();
            });

            if ($venta->comprobante->codesunat == 0) {
                dd("#GENERAR NOTA CREDITO");
            }
            else {
                $venta->comprobante->delete = 1;
                $venta->comprobante->save();
            }
            $venta->forceDelete();

            DB::commit();
            $this->resetValidation();
            $this->dispatchBrowserEvent('deleted');
            return redirect()->route('admin.ventas');
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
            $this->venta->refresh();
            $this->dispatchBrowserEvent('deleted');
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
        $totalAmount = number_format($this->venta->total, 2, '.', '');

        $this->validate([
            'venta.id' => ['required', 'integer', 'min:1', 'exists:ventas,id'],
            'cuotas' => ['required', 'array', 'min:1'],
            'cuotas.*.id' => ['nullable', 'integer', 'min:1', 'exists:cuotas,id'],
            'cuotas.*.cuota' => ['required', 'integer', 'min:1'],
            'cuotas.*.date' => ['required', 'date'],
            'cuotas.*.amount' => ['required', 'numeric', 'min:1', 'decimal:0,4'],
            'cuotas.*.cajamovimiento_id' => ['nullable', 'integer', 'min:1', 'exists:cajamovimientos,id'],
            'amountcuotas' => ['required', 'numeric', 'min:1', 'decimal:0,4', new ValidateNumericEquals($totalAmount)]
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
                    $this->venta->cuotas()->create([
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
            $this->venta->refresh();
            $this->dispatchBrowserEvent('updated');
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
        $this->dispatchBrowserEvent('render-show-venta');
    }
}
