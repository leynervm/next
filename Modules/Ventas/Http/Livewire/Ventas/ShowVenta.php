<?php

namespace Modules\Ventas\Http\Livewire\Ventas;

use App\Helpers\FormatoPersonalizado;
use App\Models\Cajamovimiento;
use App\Models\Concept;
use App\Models\Cuota;
use App\Models\Methodpayment;
use App\Models\Opencaja;
use App\Models\Sucursal;
use App\Rules\ValidateNumericEquals;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Modules\Facturacion\Entities\Comprobante;
use Modules\Ventas\Entities\Venta;
use Nwidart\Modules\Facades\Module;

class ShowVenta extends Component
{

    public $open = false;
    public $opencuotas = false;
    public $venta, $sucursal, $cuota, $methodpayment, $typepayment;
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
                'required',
                'integer',
                'min:1',
                'exists:ventas,id'
            ],
            'sucursal.id' => [
                'required',
                'integer',
                'min:1',
                'exists:sucursals,id'
            ],
            'methodpaymentventa_id' => [
                'nullable',
                Rule::requiredIf($this->typepayment->paycuotas == 0),
                'integer',
                'min:1',
                'exists:methodpayments,id'
            ],
            'cuentaventa_id' => [
                'nullable',
                // Rule::requiredIf($this->typepayment->paycuotas == 0),
                'integer',
                'min:1',
                'exists:cuentas,id'
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

    public function mount(Venta $venta, Concept $concept, Methodpayment $methodpayment, Opencaja $opencaja, Sucursal $sucursal)
    {
        $this->venta = $venta;
        $this->sucursal = $sucursal;
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
                'integer',
                'min:1',
                'exists:cuentas,id'
            ],
            'detalle' => ['nullable'],
        ]);

        DB::beginTransaction();
        try {
            $this->cuota->cajamovimiento()->create([
                'date' => now('America/Lima'),
                'amount' => number_format($this->cuota->amount, 2, '.', ''),
                'referencia' => 'VT-' . $this->venta->id,
                'detalle' => trim($this->detalle),
                'moneda_id' => $this->venta->moneda_id,
                'methodpayment_id' => $this->methodpayment_id,
                'typemovement' => Cajamovimiento::INGRESO,
                'cuenta_id' => $this->cuenta_id,
                'concept_id' => $this->concept->id,
                'opencaja_id' => $this->opencaja->id,
                'sucursal_id' => $this->sucursal->id,
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

        $cuotas = $venta->cuotas()->withWhereHas('cajamovimiento')->count();
        // dd( $cuotas);
        if ($cuotas > 0) {
            $mensaje = response()->json([
                'title' => 'No se puede eliminar comprobante ' . $venta->comprobante->seriecompleta,
                'text' => "La venta contiene cuotas de pago realizadas, eliminarlo causaría un conflicto en la base de datos, eliminelos de manera manual e intentelo nuevamente."
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
        } else {

            DB::beginTransaction();

            try {

                if (Module::isEnabled('Facturacion')) {
                    $sendsunat = $venta->comprobante->seriecomprobante->typecomprobante->sendsunat ?? 0;

                    if ($sendsunat) {
                        if ($venta->comprobante->codesunat == "0") {

                            if (Carbon::parse($venta->date)->diffInDays(Carbon::now()) > 7) {
                                $mensaje = response()->json([
                                    'title' => 'No se puede generar NOTA DE CRÉDITO para el comprobante seleccionado ' . $venta->comprobante->seriecompleta,
                                    'text' => "Fecha de anulación excedió los 7 dias válidos para emisión de NOTA DE CRÉDITO."
                                ])->getData();
                                $this->dispatchBrowserEvent('validation', $mensaje);
                                return false;
                            }

                            $codecomprobante = $venta->comprobante->seriecomprobante->typecomprobante->code;
                            $serienotacredito = $venta->sucursal->seriecomprobantes()
                                ->withWhereHas('typecomprobante', function ($query) {
                                    $query->where('code', '07');
                                })->where('code', $codecomprobante)->first();

                            if ($serienotacredito) {
                                // dd($serienotacredito);
                                $numeracion = $serienotacredito->contador + 1;
                                $comprobante = Comprobante::create([
                                    'seriecompleta' => $serienotacredito->serie . '-' . $numeracion,
                                    'date' => now('America/Lima'),
                                    'expire' => Carbon::now('America/Lima')->addDay(),
                                    'direccion' => $venta->comprobante->direccion,
                                    'exonerado' => $venta->comprobante->exonerado,
                                    'gravado' => $venta->comprobante->gravado,
                                    'descuento' => $venta->comprobante->descuento,
                                    'otros' => $venta->comprobante->igv,
                                    'igv' => $venta->comprobante->igv,
                                    'subtotal' => $venta->comprobante->subtotal,
                                    'total' => $venta->comprobante->total,
                                    'paymentactual' => $venta->comprobante->paymentactual,
                                    'percent' => $venta->comprobante->percent,
                                    'referencia' => $venta->comprobante->seriecompleta,
                                    'leyenda' => $venta->comprobante->leyenda,
                                    'client_id' => $venta->comprobante->client_id,
                                    'typepayment_id' => $venta->comprobante->typepayment_id,
                                    'seriecomprobante_id' => $serienotacredito->id,
                                    'moneda_id' => $venta->comprobante->moneda_id,
                                    'sucursal_id' => $venta->comprobante->sucursal->id,
                                    'user_id' => Auth::user()->id,
                                    'facturable_id' => $venta->id,
                                    'facturable_type' => Comprobante::class,
                                ]);

                                if (count($venta->cuotas) > 0) {
                                    foreach ($venta->cuotas as $item) {
                                        $comprobante->cuotas()->create([
                                            'cuota' => $item->cuota,
                                            'amount' => $item->amount,
                                            'expiredate' => $item->expiredate,
                                            'user_id' => Auth::user()->id,
                                        ]);
                                    }
                                }

                                if (count($venta->comprobante->facturableitems) > 0) {
                                    foreach ($venta->comprobante->facturableitems as $item) {
                                        $comprobante->facturableitems()->create([
                                            'item' => $item->item,
                                            'descripcion' => $item->descripcion,
                                            'cantidad' => $item->cantidad,
                                            'price' => $item->price,
                                            'percent' => $item->percent,
                                            'igv' => $item->igv,
                                            'subtotaligv' => $item->subtotaligv,
                                            'subtotal' => $item->subtotal,
                                            'total' => $item->total,
                                            'code' => $item->code,
                                            'unit' => $item->unit,
                                            'codetypeprice' => $item->codetypeprice,
                                            'afectacion' => $item->afectacion,
                                            'codeafectacion' => $item->codeafectacion,
                                            'nameafectacion' => $item->nameafectacion,
                                            'typeafectacion' => $item->typeafectacion,
                                            'abreviatureafectacion' => $item->abreviatureafectacion
                                        ]);
                                    }
                                }

                                $serienotacredito->contador = $numeracion;
                                $serienotacredito->save();
                                $venta->comprobante->delete();

                            } else {
                                DB::rollBack();
                                $mensaje = response()->json([
                                    'title' => 'No se puede generar NOTA DE CRÉDITO para el comprobante seleccionado ' . $venta->comprobante->seriecompleta,
                                    'text' => "Asignar series para generar NOTA DE CRÉDITO en caso de anulación de comprobantes, contáctese con su administrador e intentelo nuevamente."
                                ])->getData();
                                $this->dispatchBrowserEvent('validation', $mensaje);
                                return false;
                            }
                        }
                    }
                }

                if ($venta->cajamovimiento) {
                    $venta->cajamovimiento->delete();
                }

                $venta->cuotas()->each(function ($cuota) {
                    if ($cuota->cajamovimiento) {
                        $cuota->cajamovimiento->delete();
                    }
                    // $cuota->forceDelete();
                    $cuota->delete();
                });

                $venta->tvitems()->each(function ($item) {
                    $stockPivot = $item->producto->almacens()
                        ->where('almacen_id', $item->almacen_id)->first();

                    $item->producto->almacens()->updateExistingPivot($item->almacen_id, [
                        'cantidad' => $stockPivot->pivot->cantidad + $item->cantidad,
                    ]);

                    $item->itemseries()->each(function ($itemserie) {
                        $itemserie->serie->dateout = null;
                        $itemserie->serie->status = 0;
                        $itemserie->serie->save();
                        // $itemserie->forceDelete();
                        $itemserie->delete();
                    });
                    // $item->forceDelete();
                    $item->delete();
                });
                // $venta->forceDelete();
                $venta->delete();
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
            'cajamovimiento_id' => null,
            'amount' => '0.00',
        ];
    }

    public function updatecuotas()
    {

        $arrayamountcuotas = array_column($this->cuotas, 'amount');
        $this->resetValidation(['cuotas']);
        $this->amountcuotas = number_format(array_sum($arrayamountcuotas), 4, '.', '');
        $totalAmount = number_format($this->venta->total - $this->venta->paymentactual, 2, '.', '');

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
