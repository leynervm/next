<?php

namespace App\Http\Livewire\Modules\Ventas\Ventas;

use App\Enums\MovimientosEnum;
use App\Helpers\Facturacion\createXML;
use App\Helpers\Facturacion\SendXML;
use App\Models\Cajamovimiento;
use App\Models\Concept;
use App\Models\Cuota;
use App\Models\Itemserie;
use App\Models\Methodpayment;
use App\Models\Moneda;
use App\Models\Monthbox;
use App\Models\Openbox;
use App\Models\Tvitem;
use App\Rules\ValidateNumericEquals;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Modules\Facturacion\Entities\Comprobante;
use Modules\Ventas\Entities\Venta;
use Nwidart\Modules\Facades\Module;

class ShowVenta extends Component
{

    use AuthorizesRequests;

    public $open = false;
    public $openpay = false;
    public $opencuotas = false;
    public $venta, $cuota, $monthbox, $openbox, $moneda_id, $concept,
        $methodpayment_id, $detalle, $tipocambio;
    public $cuotas = [];
    public $amountcuotas = 0;
    public $countcuotas = 0;
    public $tvitem = [];
    public $pendiente =  0;
    public $paymentactual = 0;
    public $totalamount = 0;
    public $amountincrement = 0;

    protected function rules()
    {
        return [
            'totalamount' => ['required', 'numeric', 'decimal:0,4', 'min:0', 'gt:0'],
            'tipocambio' => [
                'nullable', Rule::requiredIf($this->venta->moneda_id != $this->moneda_id),
                'numeric', 'decimal:0,4', 'min:0', 'gt:0'
            ],
            'moneda_id' => ['required', 'integer', 'min:1', 'exists:monedas,id'],
            'cuota.id' => ['required', 'integer', 'min:1', 'exists:cuotas,id'],
            'monthbox.id' => ['required', 'integer', 'min:1', 'exists:monthboxes,id'],
            'openbox.id' => ['required', 'integer', 'min:1', 'exists:openboxes,id'],
            'concept.id' => ['required', 'integer', 'min:1', 'exists:concepts,id'],
            'methodpayment_id' => ['required', 'integer', 'min:1', 'exists:methodpayments,id'],
            'detalle' => ['nullable', 'string'],
        ];
    }

    public function mount(Venta $venta)
    {
        $this->cuota = new Cuota();
        $this->openbox = Openbox::mybox($venta->sucursal_id)->first();
        $this->monthbox = Monthbox::usando($venta->sucursal_id)->first();
        $this->venta = $venta;

        if ($venta->increment > 0) {
            $saldopagar = number_format($venta->total - ($venta->gratuito + $venta->igvgratuito + $venta->cajamovimientos()->sum('amount') ?? 0), 3, '.', '');
            $total = number_format($saldopagar / (1 + ($venta->increment / 100)), 3, '.', '');
            $this->amountincrement = number_format($total * $venta->increment / 100, 2, '.', '');
        }
    }

    public function render()
    {
        $monedas = mi_empresa()->usarDolar() ? Moneda::orderBy('id', 'asc')->get() : Moneda::default()->get();
        $methodpayments = Methodpayment::orderBy('name', 'asc')->get();
        if ($this->monthbox && $this->openbox) {
            $diferencias = Cajamovimiento::with('moneda')->withWhereHas('sucursal', function ($query) {
                $query->where('id', auth()->user()->sucursal_id);
            })->selectRaw("moneda_id, SUM(CASE WHEN typemovement = 'INGRESO' THEN totalamount ELSE -totalamount END) as diferencia")
                ->where('openbox_id', $this->openbox->id)->where('monthbox_id', $this->monthbox->id)
                ->groupBy('moneda_id')->orderBy('diferencia', 'desc')->get();
        } else {
            $diferencias = [];
        }
        return view('livewire.modules.ventas.ventas.show-venta', compact('methodpayments', 'diferencias', 'monedas'));
    }

    public function pay(Cuota $cuota)
    {
        $this->authorize('admin.ventas.payments.edit');
        $this->resetValidation();
        $this->cuota = $cuota;
        $this->paymentactual = $cuota->amount;
        $this->tipocambio = mi_empresa()->tipocambio ?? 0;
        $this->moneda_id = $cuota->moneda_id;
        $this->methodpayment_id = Methodpayment::default()->first()->id ?? null;
        $this->concept = Concept::paycuota()->first();
        $this->open = true;
    }

    public function savepayment()
    {
        $this->authorize('admin.ventas.payments.edit');
        if (!$this->monthbox || !$this->monthbox->isUsing()) {
            $this->dispatchBrowserEvent('validation', getMessageMonthbox());
            return false;
        }

        if (!$this->openbox || !$this->openbox->isActivo()) {
            $this->dispatchBrowserEvent('validation', getMessageOpencaja());
            return false;
        }

        if ($this->cuota->moneda_id == $this->moneda_id) {
            $this->totalamount = $this->paymentactual;
        }

        $this->validate();
        DB::beginTransaction();

        try {
            $this->cuota->savePayment(
                $this->venta->sucursal_id,
                $this->cuota->amount,
                $this->totalamount,
                $this->cuota->moneda_id != $this->moneda_id ? $this->tipocambio : null,
                $this->moneda_id,
                $this->methodpayment_id,
                MovimientosEnum::INGRESO->value,
                $this->concept->id,
                $this->openbox->id,
                $this->monthbox->id,
                $this->venta->seriecompleta,
                !empty($this->detalle) ? trim($this->detalle) : null,
            );

            $this->venta->paymentactual += $this->cuota->amount;
            $this->venta->save();
            DB::commit();
            $this->resetValidation();
            $this->resetExcept(['cuota', 'openbox', 'monthbox', 'venta']);
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

    public function deletepaycuota(Cuota $cuota)
    {
        $this->authorize('admin.ventas.payments.edit');
        DB::beginTransaction();
        try {
            $this->venta->paymentactual -= $cuota->amount;
            $this->venta->save();
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
        $this->authorize('admin.ventas.delete');
        $cuotas = $venta->cuotas()->withWhereHas('cajamovimiento')->count();

        if ($cuotas > 0) {
            $mensaje = response()->json([
                'title' => 'No se puede eliminar comprobante ' . $venta->code,
                'text' => "La venta contiene cuotas de pago realizadas, eliminar pagos manualmente e inténtelo nuevamente."
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }

        DB::beginTransaction();

        try {

            if (Module::isEnabled('Facturacion')) {
                $sendsunat = $venta->seriecomprobante->typecomprobante->sendsunat ?? 0;

                if ($sendsunat) {
                    if ($venta->comprobante->isSendSunat()) {

                        if (Carbon::parse($venta->date)->diffInDays(Carbon::now()) > 7) {
                            $mensaje = response()->json([
                                'title' => 'No se puede generar NOTA DE CRÉDITO para el comprobante ' . $venta->code,
                                'text' => "Fecha de anulación excedió los 7 dias válidos para emisión de NOTA DE CRÉDITO."
                            ])->getData();
                            $this->dispatchBrowserEvent('validation', $mensaje);
                            return false;
                        }

                        $codeCPE = $venta->comprobante->seriecomprobante->typecomprobante->code;
                        $serienotacredito = $venta->sucursal->seriecomprobantes()
                            ->withWhereHas('typecomprobante', function ($query) use ($codeCPE) {
                                $query->where('code', '07')->where('referencia', $codeCPE);
                            })->first();

                        if ($serienotacredito) {
                            // dd($serienotacredito);
                            $numeracion = $venta->sucursal->empresa->isProduccion() ? $serienotacredito->contador + 1 : $serienotacredito->contadorprueba + 1;
                            $comprobante = Comprobante::create([
                                'seriecompleta' => $serienotacredito->serie . '-' . $numeracion,
                                'date' => now('America/Lima'),
                                'expire' => Carbon::now('America/Lima')->addDay(),
                                'direccion' => $venta->comprobante->direccion,
                                'exonerado' => $venta->comprobante->exonerado,
                                'gravado' => $venta->comprobante->gravado,
                                'gratuito' => $venta->comprobante->gratuito,
                                'inafecto' => $venta->comprobante->inafecto,
                                'descuento' => $venta->comprobante->descuento,
                                'otros' => $venta->comprobante->igv,
                                'igv' => $venta->comprobante->igv,
                                'igvgratuito' => $venta->comprobante->igvgratuito,
                                'subtotal' => $venta->comprobante->subtotal,
                                'total' => $venta->comprobante->total,
                                'paymentactual' => $venta->comprobante->paymentactual,
                                'percent' => $venta->comprobante->percent,
                                'referencia' => $venta->seriecompleta,
                                'leyenda' => $venta->comprobante->leyenda,
                                'sendmode' => $venta->comprobante->sendmode,
                                'client_id' => $venta->comprobante->client_id,
                                'typepayment_id' => $venta->comprobante->typepayment_id,
                                'seriecomprobante_id' => $serienotacredito->id,
                                'moneda_id' => $venta->comprobante->moneda_id,
                                'sucursal_id' => $venta->comprobante->sucursal_id,
                                'user_id' => auth()->user()->id,
                                'facturable_id' => $venta->id,
                                'facturable_type' => Comprobante::class,
                            ]);

                            if (count($venta->cuotas) > 0) {
                                foreach ($venta->cuotas as $item) {
                                    $comprobante->cuotas()->create([
                                        'cuota' => $item->cuota,
                                        'amount' => $item->amount,
                                        'expiredate' => $item->expiredate,
                                        'moneda_id' => $this->venta->moneda_id,
                                        "sucursal_id" => $this->venta->sucursal_id,
                                        'user_id' => auth()->user()->id,
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

                            if ($this->venta->sucursal->empresa->isProduccion()) {
                                $serienotacredito->contador = $numeracion;
                            } else {
                                $serienotacredito->contadorprueba = $numeracion;
                            }
                            $serienotacredito->save();
                            $venta->comprobante->delete();
                        } else {
                            DB::rollBack();
                            $mensaje = response()->json([
                                'title' => 'No se puede generar NOTA DE CRÉDITO para el comprobante ' . $venta->code,
                                'text' => "Asignar series para generar NOTA DE CRÉDITO en caso de anulación de comprobantes, contáctese con su administrador e intentelo nuevamente."
                            ])->getData();
                            $this->dispatchBrowserEvent('validation', $mensaje);
                            return false;
                        }
                    } else {
                        $venta->comprobante->delete();
                    }
                }
            }

            if ($venta->cajamovimiento) {
                $venta->cajamovimiento->delete();
            }

            $venta->cuotas()->delete();
            $venta->tvitems()->each(function ($tvitem) {
                // dd($tvitem, $tvitem->tvitemable);
                //SI ALTER STOCK REPONER ALMACEN SINO VERIFICAR KARDEX Y ELIMINAR ITEM

                if ($tvitem->isDiscountStock() || $tvitem->isReservedStock()) {
                    $stock = $tvitem->producto->almacens()->find($tvitem->almacen_id);
                    $tvitem->producto->almacens()->updateExistingPivot($tvitem->almacen_id, [
                        'cantidad' => $stock->pivot->cantidad + $tvitem->cantidad,
                    ]);

                    $tvitem->itemseries()->each(function ($itemserie) {
                        $itemserie->serie->dateout = null;
                        $itemserie->serie->status = 0;
                        $itemserie->serie->save();
                        $itemserie->delete();
                    });

                    if ($tvitem->kardex) {
                        if ($tvitem->kardex->promocion) {
                            $tvitem->kardex->promocion->outs = $tvitem->kardex->promocion->outs - $tvitem->cantidad;
                            $tvitem->kardex->promocion->save();
                        }
                    }
                }

                if ($tvitem->kardex) {
                    $tvitem->kardex->delete();
                }
                $tvitem->delete();
                // $tvitem->saveKardex(
                //     $tvitem->producto_id,
                //     $tvitem->almacen_id,
                //     $stockPivot->pivot->cantidad,
                //     $stockPivot->pivot->cantidad + $tvitem->cantidad,
                //     $tvitem->cantidad,
                //     '+',
                //     Kardex::REPOSICION_ANULACION,
                //     $tvitem->tvitemable->code
                // );
            });
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
        // }
    }

    public function deletecuota(Cuota $cuota)
    {
        $this->authorize('admin.ventas.create');
        DB::beginTransaction();
        try {
            $cuota->delete();
            $this->reset(['cuotas']);
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
        $this->authorize('admin.ventas.create');
        $this->resetValidation(['cuotas']);
        $this->reset(['cuotas']);

        if (count($this->venta->cuotas)) {
            foreach ($this->venta->cuotas as $cuota) {
                $this->cuotas[] = [
                    'id' => $cuota->id,
                    'cuota' => $cuota->cuota,
                    'date' => $cuota->expiredate,
                    'cajamovimiento_id' => $cuota->cajamovimiento->id ?? null,
                    'amount' => number_format($cuota->amount, 3, '.', ''),
                ];
            }
        }
        $this->opencuotas = true;
    }

    public function addnewcuota()
    {
        $this->authorize('admin.ventas.create');
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
        $this->authorize('admin.ventas.create');
        $arrayamountcuotas = array_column($this->cuotas, 'amount');
        $this->resetValidation(['cuotas']);
        $this->amountcuotas = number_format(array_sum($arrayamountcuotas), 3, '.', '');
        // $this->amountcuotas = number_format($this->venta->total - $this->venta->paymentactual, 3, '.', '');
        $amountcuotas = number_format($this->venta->total - $this->venta->paymentactual, 3, '.', '');

        $data = $this->validate([
            'venta.id' => ['required', 'integer', 'min:1', 'exists:ventas,id'],
            'cuotas' => ['required', 'array', 'min:1'],
            'cuotas.*.id' => ['nullable', 'integer', 'min:1', 'exists:cuotas,id'],
            'cuotas.*.cuota' => ['required', 'integer', 'min:1'],
            'cuotas.*.date' => ['required', 'date'],
            'cuotas.*.amount' => ['required', 'min:0', 'gt:0', 'numeric', 'decimal:0,3'],
            'cuotas.*.cajamovimiento_id' => ['nullable', 'integer', 'min:1', 'exists:cajamovimientos,id'],
            'amountcuotas' => [
                'required', 'numeric', 'min:0', 'gt:0', 'decimal:0,3',
                new ValidateNumericEquals($amountcuotas)
            ]
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
                        "moneda_id" => $this->venta->moneda_id,
                        "sucursal_id" => $this->venta->sucursal_id,
                        "user_id" => auth()->user()->id
                    ]);
                }
            }

            DB::commit();
            $this->resetValidation();
            $this->reset(['opencuotas', 'cuotas', 'countcuotas']);
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

    public function deleteserie(Itemserie $itemserie)
    {
        $this->authorize('admin.ventas.create');
        DB::beginTransaction();
        try {
            $itemserie->serie->status = 0;
            $itemserie->serie->dateout = null;
            $itemserie->serie->save();
            $itemserie->tvitem->requireserie = Tvitem::PENDING_SERIE;
            $itemserie->tvitem->save();
            $itemserie->delete();
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

    public function calcularcuotas()
    {
        $this->authorize('admin.ventas.create');
        $this->resetValidation(['cuotas']);
        $amountcuotas = number_format($this->venta->total - $this->venta->paymentactual, 3, '.', '');
        $amountCuota = number_format($amountcuotas / $this->countcuotas, 3, '.', '');

        if ((!empty(trim($this->countcuotas))) || $this->countcuotas > 0) {

            $date = Carbon::now('America/Lima')->addMonth()->format('Y-m-d');
            $sumaCuotas = 0.00;

            for ($i = 1; $i <= $this->countcuotas; $i++) {
                $sumaCuotas = number_format($sumaCuotas + $amountCuota, 3, '.', '');
                if ($i == $this->countcuotas) {
                    $result = number_format($amountcuotas - $sumaCuotas, 3, '.', '');
                    $amountCuota = number_format($amountCuota + ($result), 3, '.', '');
                }

                $this->cuotas[] = [
                    'id' => null,
                    'cuota' => $i,
                    'amount' => number_format($amountCuota, 3, '.', ''),
                    'date' => $date,
                    'cajamovimiento_id' => null,
                ];
                $date = Carbon::parse($date)->addMonth()->format('Y-m-d');
            }
        } else {
            $this->addError('countcuotas', 'Ingrese cantidad válida de cuotas');
        }
    }

    public function saveserie(Tvitem $tvitem)
    {

        $this->tvitem[$tvitem->id]["tvitem_id"] = $tvitem->id;

        DB::beginTransaction();
        try {
            $this->validate([
                "tvitem.$tvitem->id.tvitem_id" => [
                    'required', 'integer', 'min:1', 'exists:tvitems,id'
                ],
                "tvitem.$tvitem->id.serie" => [
                    'required', 'string', 'min:4',
                ],
            ]);

            $serie = trim(mb_strtoupper($this->tvitem[$tvitem->id]["serie"], "UTF-8"));
            $query = $tvitem->producto->series()->disponibles()
                ->where('almacen_id', $tvitem->almacen_id)
                ->whereRaw('UPPER(serie) = ?', $serie);

            if (!$query->exists()) {
                $this->addError("tvitem.$tvitem->id.serie", 'Serie no se encuentra disponible');
                return false;
            }
            $serieProducto = $query->first();
            $tvitem->itemseries()->create([
                'date' => now('America/Lima'),
                'serie_id' => $serieProducto->id,
                'user_id' => auth()->user()->id,
            ]);
            $serieProducto->status = 2;
            $serieProducto->dateout = now('America/Lima');
            $serieProducto->save();

            if (formatDecimalOrInteger($tvitem->cantidad) == $tvitem->itemseries->count()) {
                $tvitem->requireserie = 0;
                $tvitem->save();
            }
            DB::commit();
            $this->venta->refresh();
            $this->reset(['tvitem']);
            $this->dispatchBrowserEvent('created');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deletepay(Cajamovimiento $cajamovimiento)
    {
        // $this->authorize('admin.ventas.deletepayment');
        DB::beginTransaction();
        try {
            $this->venta->paymentactual = $this->venta->paymentactual - $cajamovimiento->amount;
            $this->venta->save();
            $cajamovimiento->delete();
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

    public function openmodal()
    {
        // $this->authorize('admin.almacen.compras.pagos');
        $this->resetValidation();
        $this->tipocambio = mi_empresa()->tipocambio ?? 0;
        $this->pendiente = $this->venta->total - $this->venta->cajamovimientos()->sum('amount');
        $this->paymentactual = number_format($this->pendiente, 3, '.', '');
        $this->methodpayment_id = Methodpayment::default()->first()->id ?? null;
        $this->concept = Concept::ventas()->first();
        $this->moneda_id = $this->venta->moneda_id;
        $this->openpay = true;
    }

    public function savepay()
    {
        // $this->authorize('admin.ventas.payments.edit');
        if (!$this->monthbox || !$this->monthbox->isUsing()) {
            $this->dispatchBrowserEvent('validation', getMessageMonthbox());
            return false;
        }

        if (!$this->openbox || !$this->openbox->isActivo()) {
            $this->dispatchBrowserEvent('validation', getMessageOpencaja());
            return false;
        }

        $parcialamount = $this->venta->cajamovimientos()->sum('amount') ?? 0;
        if (($parcialamount + $this->paymentactual) > $this->venta->total) {
            $mensaje =  response()->json([
                'title' => 'MONTO PARCIAL SUPERA AL TOTAL DE LA VENTA !',
                'text' => null
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }

        if ($this->venta->moneda_id == $this->moneda_id) {
            $this->totalamount = $this->paymentactual;
        }

        $this->validate([
            'paymentactual' => ['required', 'numeric', 'decimal:0,4', 'min:0', 'gt:0'],
            'totalamount' => ['required', 'numeric', 'decimal:0,4', 'min:0', 'gt:0'],
            'tipocambio' => [
                'nullable', Rule::requiredIf($this->venta->moneda_id != $this->moneda_id),
                'numeric', 'decimal:0,4', 'min:0', 'gt:0'
            ],
            'monthbox.id' => ['required', 'integer', 'min:1', 'exists:monthboxes,id'],
            'openbox.id' => ['required', 'integer', 'min:1', 'exists:openboxes,id'],
            'concept.id' => ['required', 'integer', 'min:1', 'exists:concepts,id'],
            'methodpayment_id' => ['required', 'integer', 'min:1', 'exists:methodpayments,id'],
            'moneda_id' => ['required', 'integer', 'min:1', 'exists:monedas,id'],
            'detalle' => ['nullable', 'string'],
        ]);

        DB::beginTransaction();
        try {
            $this->venta->savePayment(
                $this->venta->sucursal_id,
                $this->paymentactual,
                $this->totalamount,
                $this->venta->moneda_id != $this->moneda_id ? $this->tipocambio : null,
                $this->moneda_id,
                $this->methodpayment_id,
                MovimientosEnum::INGRESO->value,
                $this->concept->id,
                $this->openbox->id,
                $this->monthbox->id,
                $this->venta->seriecompleta,
                !empty($this->detalle) ? trim($this->detalle) : 'PAGO PARCIAL VENTA',
            );
            $this->venta->paymentactual = $this->venta->paymentactual + $this->paymentactual;
            $this->venta->save();
            DB::commit();
            $this->resetValidation();
            $this->resetExcept(['cuota', 'openbox', 'monthbox', 'venta']);
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


    function enviarsunat()
    {

        if ($this->venta->comprobante && !$this->venta->comprobante->isSendSunat()) {
            $response = $this->venta->comprobante->enviarComprobante();

            if ($response->success) {
                if (empty($response->mensaje)) {
                    $mensaje = response()->json([
                        'title' => $response->title,
                        'icon' => 'success'
                    ]);
                    $this->dispatchBrowserEvent('toast', $mensaje->getData());
                } else {
                    $mensaje = response()->json([
                        'title' => $response->title,
                        'text' => $response->mensaje,
                    ]);
                    $this->dispatchBrowserEvent('validation', $mensaje->getData());
                }
            } else {
                $mensaje = response()->json([
                    'title' => $response->title,
                    'text' => $response->mensaje,
                ]);
                $this->dispatchBrowserEvent('validation', $mensaje->getData());
            }
        } else {
            $mensaje = response()->json([
                'title' => 'COMPROBANTE ELECTRÓNICO ' . $this->venta->comprobante->seriecompleta . ' YA FUÉ EMITIDO A SUNAT.',
                'text' => null,
            ]);
            $this->dispatchBrowserEvent('validation', $mensaje->getData());
        }
    }
}
