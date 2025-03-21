<?php

namespace App\Http\Livewire\Modules\Ventas\Ventas;

use App\Enums\MovimientosEnum;
use App\Models\Almacen;
use App\Models\Cajamovimiento;
use App\Models\Carshoop;
use App\Models\Carshoopitem;
use App\Models\Concept;
use App\Models\Cuota;
use App\Models\Itemserie;
use App\Models\Kardex;
use App\Models\Methodpayment;
use App\Models\Moneda;
use App\Models\Monthbox;
use App\Models\Openbox;
use App\Models\Serie;
use App\Models\Tvitem;
use App\Rules\ValidateNumericEquals;
use App\Rules\ValidateStock;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Modules\Ventas\Entities\Venta;

class ShowVenta extends Component
{

    use AuthorizesRequests;

    public $open = false, $openpay = false, $opencuotas = false;
    public $istransferencia = false;
    public $venta, $cuota, $monthbox, $openbox, $moneda_id, $concept,
        $methodpayment_id, $detalle, $tipocambio;
    public $amountcuotas = 0, $countcuotas = 1;
    public $tvitem, $almacens = [], $cuotas = [], $almacenitem = [];
    public $pendiente =  0, $paymentactual = 0, $totalamount = 0;
    public $amountincrement = 0, $amountpendiente =  0;
    public $serie_id;

    protected function rules()
    {
        return [
            'totalamount' => ['required', 'numeric', 'decimal:0,4', 'min:0', 'gt:0'],
            'tipocambio' => [
                'nullable',
                Rule::requiredIf($this->venta->moneda_id != $this->moneda_id),
                'numeric',
                'decimal:0,4',
                'min:0',
                'gt:0'
            ],
            'moneda_id' => ['required', 'integer', 'min:1', 'exists:monedas,id'],
            'cuota.id' => ['required', 'integer', 'min:1', 'exists:cuotas,id'],
            'monthbox.id' => ['required', 'integer', 'min:1', 'exists:monthboxes,id'],
            'openbox.id' => ['required', 'integer', 'min:1', 'exists:openboxes,id'],
            'concept.id' => ['required', 'integer', 'min:1', 'exists:concepts,id'],
            'methodpayment_id' => ['required', 'integer', 'min:1', 'exists:methodpayments,id'],
            'detalle' => [Rule::requiredIf($this->istransferencia)],
        ];
    }

    public function mount(Venta $venta)
    {
        $this->cuota = new Cuota();
        $this->tvitem = new Tvitem();
        $this->openbox = Openbox::mybox($venta->sucursal_id)->first();
        $this->monthbox = Monthbox::usando($venta->sucursal_id)->first();
        $this->venta = $venta;

        if ($venta->increment > 0) {
            $saldopagar = number_format($venta->total - $venta->cajamovimientos()->sum('amount') ?? 0, 3, '.', '');
            $total = number_format($saldopagar / (1 + ($venta->increment / 100)), 3, '.', '');
            $this->amountincrement = number_format($total * $venta->increment / 100, 2, '.', '');
        }
    }

    public function render()
    {
        $monedas = $this->venta->sucursal->empresa->usarDolar() ? Moneda::orderBy('id', 'asc')->get() : Moneda::default()->get();
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
        $this->amountpendiente = $cuota->amount - $cuota->cajamovimientos()->sum('amount');
        $this->paymentactual = (float) $this->amountpendiente;
        $this->tipocambio = $this->venta->sucursal->empresa->tipocambio ?? 0;
        $this->moneda_id = $cuota->moneda_id;
        $this->methodpayment_id = Methodpayment::default()->first()->id ?? null;
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

        if ($this->methodpayment_id) {
            $this->istransferencia = Methodpayment::find($this->methodpayment_id)->isTransferencia();
        }

        $this->tipocambio = empty($this->tipocambio) ? null : (float) $this->tipocambio;
        $this->totalamount = $this->paymentactual;
        if ($this->venta->moneda_id != $this->moneda_id) {
            if ($this->paymentactual > 0 && $this->tipocambio > 0) {
                $monedaConvertir = $this->venta->moneda->isDolar() ? 'PEN' : 'USD';
                $this->totalamount = convertMoneda($this->paymentactual, $monedaConvertir, $this->tipocambio, 2);
            }
        }
        $this->concept = Concept::paycuota()->first();
        $this->validate();
        if (($this->cuota->cajamovimientos()->sum('amount') + $this->paymentactual) > $this->cuota->amount) {
            $mensaje =  response()->json([
                'title' => "MONTO TOTAL DE PAGO NO DEBE SUPERAR AL TOTAL DE LA CUOTA !",
                'text' => null
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }

        DB::beginTransaction();

        try {
            $this->cuota->savePayment(
                $this->venta->sucursal_id,
                $this->paymentactual,
                $this->totalamount,
                ($this->venta->moneda_id != $this->moneda_id) ? $this->tipocambio : null,
                $this->moneda_id,
                $this->methodpayment_id,
                MovimientosEnum::INGRESO->value,
                $this->concept->id,
                $this->openbox->id,
                $this->monthbox->id,
                $this->venta->seriecompleta,
                !empty($this->detalle) ? trim($this->detalle) : null,
            );

            $this->venta->paymentactual += $this->paymentactual;
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

    public function deletecuota(Cuota $cuota)
    {
        $this->authorize('admin.ventas.create');
        DB::beginTransaction();
        try {
            $cuota->delete();
            $this->reset(['cuotas', 'cuota', 'countcuotas']);
            $this->cuota = new Cuota();
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
                    'cajamovimientos' => $cuota->cajamovimientos->toArray(),
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
            'cajamovimientos' => [],
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
        $amountcuotas = number_format($this->venta->total - ($this->venta->gratuito + $this->venta->igvgratuito), 3, '.', '');

        $data = $this->validate([
            'venta.id' => ['required', 'integer', 'min:1', 'exists:ventas,id'],
            'cuotas' => ['required', 'array', 'min:1'],
            'cuotas.*.id' => ['nullable', 'integer', 'min:1', 'exists:cuotas,id'],
            'cuotas.*.cuota' => ['required', 'integer', 'min:1'],
            'cuotas.*.date' => ['required', 'date'],
            'cuotas.*.amount' => ['required', 'min:0', 'gt:0', 'numeric', 'decimal:0,3'],
            'cuotas.*.cajamovimiento_id' => ['nullable', 'integer', 'min:1', 'exists:cajamovimientos,id'],
            'amountcuotas' => [
                'required',
                'numeric',
                'min:0',
                'gt:0',
                'decimal:0,3',
                new ValidateNumericEquals($amountcuotas)
            ]
        ]);

        $responseCuotas = response()->json($this->cuotas)->getData();
        DB::beginTransaction();

        try {

            foreach ($responseCuotas as $key => $item) {
                if (count($item->cajamovimientos) == 0) {
                    if (Carbon::parse($item->date)->isBefore(Carbon::now()->format('Y-m-d'))) {
                        $this->addError("cuotas.$key.date", 'La fecha debe ser mayor a la fecha actual.');
                        return false;
                    }
                }
                if ($item->id) {
                    if (count($item->cajamovimientos) == 0) {
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
                    'required',
                    'integer',
                    'min:1',
                    'exists:tvitems,id'
                ],
                "tvitem.$tvitem->id.serie" => [
                    'required',
                    'string',
                    'min:4',
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
        $this->tipocambio = $this->venta->sucursal->empresa->tipocambio ?? 0;
        $this->pendiente = $this->venta->total - $this->venta->cajamovimientos()->sum('amount');
        $this->paymentactual = (float) $this->pendiente;
        $this->methodpayment_id = Methodpayment::default()->first()->id ?? null;
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

        $this->tipocambio = empty($this->tipocambio) ? null : (float) $this->tipocambio;
        $this->paymentactual = empty($this->paymentactual) ? 0 : (float) $this->paymentactual;
        $this->totalamount = $this->paymentactual;

        if ($this->venta->moneda_id != $this->moneda_id) {
            if ($this->paymentactual > 0 && $this->tipocambio > 0) {
                $monedaConvertir = $this->venta->moneda->isDolar() ? 'PEN' : 'USD';
                $this->totalamount = convertMoneda($this->paymentactual, $monedaConvertir, $this->tipocambio, 2);
            } else {
                $this->totalamount = 0;
            }
        }

        if ($this->methodpayment_id) {
            $this->istransferencia = Methodpayment::find($this->methodpayment_id)->isTransferencia();
        }

        $this->concept = Concept::ventas()->first();

        $this->validate([
            'paymentactual' => ['required', 'numeric', 'decimal:0,4', 'min:0', 'gt:0', 'regex:/^\d{0,8}(\.\d{0,3})?$/'],
            'totalamount' => ['required', 'numeric', 'decimal:0,4', 'min:0', 'gt:0', 'regex:/^\d{0,8}(\.\d{0,3})?$/'],
            'tipocambio' => [
                'nullable',
                Rule::requiredIf($this->venta->moneda_id != $this->moneda_id),
                'numeric',
                'decimal:0,4',
                'min:0',
                'gt:0'
            ],
            'monthbox.id' => ['required', 'integer', 'min:1', 'exists:monthboxes,id'],
            'openbox.id' => ['required', 'integer', 'min:1', 'exists:openboxes,id'],
            'concept.id' => ['required', 'integer', 'min:1', 'exists:concepts,id'],
            'methodpayment_id' => ['required', 'integer', 'min:1', 'exists:methodpayments,id'],
            'moneda_id' => ['required', 'integer', 'min:1', 'exists:monedas,id'],
            'detalle' => [Rule::requiredIf($this->istransferencia)],
        ]);

        if (($this->venta->cajamovimientos()->sum('amount') + $this->paymentactual) > $this->venta->total) {
            $mensaje =  response()->json([
                'title' => 'MONTO PARCIAL SUPERA AL TOTAL DE LA VENTA !',
                'text' => null
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }

        DB::beginTransaction();
        try {
            $this->venta->savePayment(
                $this->venta->sucursal_id,
                $this->paymentactual,
                $this->totalamount,
                ($this->venta->moneda_id != $this->moneda_id) ? $this->tipocambio : null,
                $this->moneda_id,
                $this->methodpayment_id,
                MovimientosEnum::INGRESO->value,
                $this->concept->id,
                $this->openbox->id,
                $this->monthbox->id,
                $this->venta->seriecompleta,
                !empty($this->detalle) ? trim($this->detalle) : null,
            );
            $this->venta->paymentactual += $this->paymentactual;
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

    public function confirmkardexstock($key)
    {
        $validateData = $this->validate([
            "almacens.$key.id" => ['required', 'integer', 'min:1', 'exists:almacens,id'],
            'tvitem.producto_id' => ['required', 'integer', 'min:1', 'exists:productos,id'],
            "almacens.$key.cantidad" => $this->tvitem->producto->isRequiredserie() ?
                ['nullable'] : [
                    'required',
                    'integer',
                    'gt:0',
                    new ValidateStock($this->tvitem->producto_id, $this->almacens[$key]['id'], $this->almacens[$key]['cantidad']),
                    'lte:' . $this->tvitem->cantidad - $this->tvitem->kardexes->sum('cantidad'),
                ],
            "almacens.$key.serie_id" => $this->tvitem->producto->isRequiredserie() ?
                [
                    Rule::requiredIf($this->tvitem->producto->isRequiredserie()),
                    'integer',
                    'min:1',
                    'exists:series,id',
                    new ValidateStock($this->tvitem->producto_id, $this->almacens[$key]['id'], 1),
                ] : ['nullable'],
        ], [], [
            "tvitem.producto_id" => 'producto',
            "almacens.$key.id" => 'almacen',
            "almacens.$key.cantidad" => 'cantidad',
            "almacens.$key.serie_id" => 'serie',
        ]);

        DB::beginTransaction();
        try {
            $date = now('America/Lima');
            $serie_id = $this->tvitem->producto->isRequiredserie() && !empty($this->almacens[$key]['serie_id']) ? $this->almacens[$key]['serie_id'] : null;
            $cantidad = $this->tvitem->producto->isRequiredserie() ? 1 : $this->almacens[$key]['cantidad'];
            $stock = $this->tvitem->producto->almacens()->find($key)->pivot->cantidad;

            if (!empty($serie_id)) {
                $serie = Serie::find($serie_id);
                if ($this->tvitem->itemseries()->where('serie_id', $serie_id)->exists()) {
                    $mensaje =  response()->json([
                        'title' => "SERIE $serie->serie YA SE ENCUENTRA AGREGADO !",
                        'text' => null
                    ])->getData();
                    $this->dispatchBrowserEvent('validation', $mensaje);
                    return false;
                }

                if ($this->tvitem->isDiscountStock() || $this->tvitem->isReservedStock()) {
                    if (!$serie->isDisponible()) {
                        $mensaje =  response()->json([
                            'title' => "SERIE $serie->serie NO SE ENCUENTRA DISPONIBLE !",
                            'text' => null
                        ])->getData();
                        $this->dispatchBrowserEvent('validation', $mensaje);
                        return false;
                    }
                    $this->tvitem->registrarSalidaSerie($serie_id);
                } else {
                    $this->tvitem->itemseries()->create([
                        'date' =>  $date,
                        'serie_id' => $serie_id,
                        'user_id' => auth()->user()->id
                    ]);
                }
            }

            $kardex = $this->tvitem->updateOrCreateKardex($key, $stock, $cantidad);
            $kardex->detalle = Kardex::SALIDA_VENTA;
            $kardex->save();
            if ($this->tvitem->isDiscountStock() || $this->tvitem->isReservedStock()) {
                $this->tvitem->producto->descontarStockProducto($key, $cantidad);
            }
            DB::commit();
            $this->dispatchBrowserEvent('toast', toastJSON('STOCK ACTUALIZADO CORRECTAMENTE'));
            $this->venta->refresh();
            $this->tvitem->refresh();
            // if ($this->tvitem->kardexes->sum('cantidad') == $this->tvitem->cantidad) {
            //     $this->openstock = false;
            // }
            foreach ($this->tvitem->producto->almacens as $item) {
                $this->almacens[$item->id]['tvitem_id'] = $this->tvitem->id;
                $this->almacens[$item->id]['id'] = $item->id;
                $this->almacens[$item->id]['serie_id'] = null;
                $this->almacens[$item->id]['cantidad'] = $this->tvitem->producto->isRequiredserie() ? 1 : 0;
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deletekardex(Tvitem $tvitem, Kardex $kardex)
    {
        DB::beginTransaction();
        try {
            $tvitem->load(['producto.almacens']);
            if ($tvitem->isDiscountStock() || $tvitem->isReservedStock()) {
                // $stock = $tvitem->producto->almacens()->find($kardex->almacen_id)->pivot->cantidad;
                // $tvitem->producto->almacens()->updateExistingPivot($kardex->almacen_id, [
                //     'cantidad' => $stock + $kardex->cantidad,
                // ]);
                $tvitem->producto->incrementarStockProducto($kardex->almacen_id, $kardex->cantidad);
            }
            $kardex->delete();
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

    public function deletekardexcarshoop(Carshoopitem $carchoopitem, Kardex $kardex)
    {
        DB::beginTransaction();
        try {
            $carchoopitem->load(['tvitem', 'producto.almacens']);
            if ($carchoopitem->tvitem->isDiscountStock() || $carchoopitem->tvitem->isReservedStock()) {
                $carchoopitem->producto->incrementarStockProducto($kardex->almacen_id, 1);
            }
            $kardex->delete();
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

    public function deleteitemserie(Itemserie $itemserie)
    {
        DB::beginTransaction();
        try {
            $itemserie->load(['seriable.kardexes', 'serie'  => function ($query) {
                $query->with(['producto.almacens']);
            }]);
            $tvitem = $itemserie->seriable;
            $almacen_id = $itemserie->serie->almacen_id;
            $kardex = $tvitem->kardexes->where('almacen_id', $almacen_id)->first();

            if ($tvitem->isDiscountStock() || $tvitem->isReservedStock()) {
                $tvitem->updateSerieDisponible($itemserie->serie);
                if ($kardex) {
                    $itemserie->serie->producto->incrementarStockProducto($almacen_id, 1);
                }
            }

            if ($kardex) {
                $kardex->cantidad = $kardex->cantidad - 1;
                $kardex->newstock = $kardex->newstock - 1;
                if ($kardex->cantidad == 0) {
                    $kardex->delete();
                } else {
                    $kardex->save();
                }
            }
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

    public function deleteitemserieitem(Itemserie $itemserie)
    {
        DB::beginTransaction();
        try {
            $itemserie->load(['seriable.kardexes', 'serie'  => function ($query) {
                $query->with(['producto.almacens']);
            }]);
            $carshoopitem = $itemserie->seriable;
            $almacen_id = $itemserie->serie->almacen_id;
            $kardex = $carshoopitem->kardexes->where('almacen_id', $almacen_id)->first();
            // dd($carshoopitem->tvitem->isDiscountStock());
            if ($carshoopitem->tvitem->isDiscountStock() || $carshoopitem->tvitem->isReservedStock()) {
                $carshoopitem->tvitem->updateSerieDisponible($itemserie->serie);
                if ($kardex) {
                    $itemserie->serie->producto->incrementarStockProducto($almacen_id, 1);
                }
            }

            if ($kardex) {
                $kardex->cantidad = $kardex->cantidad - 1;
                $kardex->newstock = $kardex->newstock - 1;
                if ($kardex->cantidad == 0) {
                    $kardex->delete();
                } else {
                    $kardex->save();
                }
            }
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

    public function openmodalcarshoops(Tvitem $tvitem)
    {

        $this->reset(['almacens', 'almacenitem']);
        $this->resetValidation();

        $tvitem->load(['itemseries' => function ($query) {
            $query->with(['serie.almacen']);
        }, 'kardexes.almacen', 'producto' => function ($query) {
            $query->with(['almacens', 'unit', 'seriesdisponibles']);
        }, 'carshoopitems' => function ($query) {
            $query->with(['kardexes.almacen', 'itempromo', 'itemseries' => function ($query) {
                $query->with(['serie.almacen']);
            }, 'producto' => function ($subq) {
                $subq->with(['almacens', 'unit', 'marca', 'category', 'seriesdisponibles']);
            }]);
        }]);
        $this->tvitem = $tvitem;
        foreach ($tvitem->producto->almacens as $item) {
            $this->almacens[$item->id]['tvitem_id'] = $tvitem->id;
            $this->almacens[$item->id]['id'] = $item->id;
            $this->almacens[$item->id]['serie_id'] = '';
            $this->almacens[$item->id]['cantidad'] = $tvitem->producto->isRequiredserie() ? 1 : 0;
        }

        foreach ($tvitem->carshoopitems as $item) {
            foreach ($item->producto->almacens as $almacen) {
                $this->almacenitem[$item->id]['almacens'][$almacen->id]['id'] = $almacen->id;
                $this->almacenitem[$item->id]['almacens'][$almacen->id]['serie_id'] = '';
                $this->almacenitem[$item->id]['almacens'][$almacen->id]['cantidad'] = $item->producto->isRequiredserie() ? 1 : 0;
            }
        }
    }

    public function confirmkardexstockitem($key, Carshoopitem $carshoopitem)
    {

        $carshoopitem->load(['tvitem', 'kardexes.almacen', 'itempromo', 'producto' => function ($query) {
            $query->with(['unit', 'almacens', 'series' => function ($subq) {
                $subq->disponibles();
            }]);
        }, 'itemseries' => function ($query) {
            $query->with(['serie.almacen']);
        }]);

        // dd($this->almacenitem);
        $validateData = $this->validate([
            "almacenitem.$carshoopitem->id.almacens.$key.id" => ['required', 'integer', 'min:1', 'exists:almacens,id'],
            // 'carshoopitem.producto_id' => ['required', 'integer', 'min:1', 'exists:productos,id'],
            "almacenitem.$carshoopitem->id.almacens.$key.cantidad" => $carshoopitem->producto->isRequiredserie() ?
                ['nullable'] : [
                    'required',
                    'integer',
                    'gt:0',
                    new ValidateStock($carshoopitem->producto_id, $this->almacenitem[$carshoopitem->id]['almacens'][$key]['id'], $this->almacenitem[$carshoopitem->id]['almacens'][$key]['cantidad']),
                    'lte:' . $carshoopitem->cantidad - $carshoopitem->kardexes->sum('cantidad'),
                ],
            "almacenitem.$carshoopitem->id.almacens.$key.serie_id" => $carshoopitem->producto->isRequiredserie() ?
                [
                    Rule::requiredIf($carshoopitem->producto->isRequiredserie()),
                    'integer',
                    'min:1',
                    'exists:series,id',
                    new ValidateStock($carshoopitem->producto_id, $this->almacenitem[$carshoopitem->id]['almacens'][$key]['id'], 1),
                ] : ['nullable'],
        ], [], [
            "almacenitem.$carshoopitem->id.almacens.$key.id" => 'almacen',
            "almacenitem.$carshoopitem->id.almacens.$key.cantidad" => 'cantidad',
            "almacenitem.$carshoopitem->id.almacens.$key.serie_id" => 'serie',
        ]);

        DB::beginTransaction();
        try {
            $serie_id = $carshoopitem->producto->isRequiredserie() && !empty($this->almacenitem[$carshoopitem->id]['almacens'][$key]['serie_id']) ? $this->almacenitem[$carshoopitem->id]['almacens'][$key]['serie_id'] : null;
            $cantidad = $carshoopitem->producto->isRequiredserie() ? 1 : $this->almacenitem[$carshoopitem->id]['almacens'][$key]['cantidad'];
            $stock = $carshoopitem->producto->almacens()->find($key)->pivot->cantidad;

            if (!empty($serie_id)) {
                $serie = Serie::find($serie_id);

                if ($carshoopitem->itemseries()->where('serie_id', $serie_id)->exists()) {
                    $mensaje =  response()->json([
                        'title' => "SERIE $serie->serie YA SE ENCUENTRA AGREGADO !",
                        'text' => null
                    ])->getData();
                    $this->dispatchBrowserEvent('validation', $mensaje);
                    return false;
                }

                if ($carshoopitem->tvitem->isDiscountStock() || $carshoopitem->tvitem->isReservedStock()) {
                    if (!$serie->isDisponible()) {
                        $mensaje =  response()->json([
                            'title' => "SERIE $serie->serie NO SE ENCUENTRA DISPONIBLE !",
                            'text' => null
                        ])->getData();
                        $this->dispatchBrowserEvent('validation', $mensaje);
                        return false;
                    }
                    $carshoopitem->registrarSalidaSerie($serie_id);
                } else {
                    $carshoopitem->itemseries()->create([
                        'date' => now('America/Lima'),
                        'serie_id' => $serie_id,
                        'user_id' => auth()->user()->id
                    ]);
                }
            }

            $kardex = $carshoopitem->updateOrCreateKardex($key, $stock, $cantidad);
            $kardex->detalle = Kardex::SALIDA_VENTA;
            $kardex->save();

            if ($carshoopitem->tvitem->isDiscountStock() || $carshoopitem->tvitem->isReservedStock()) {
                $carshoopitem->producto->descontarStockProducto($key, $cantidad);
            }
            DB::commit();
            $this->dispatchBrowserEvent('toast', toastJSON('STOCK ACTUALIZADO CORRECTAMENTE'));
            $this->venta->refresh();
            foreach ($carshoopitem->tvitem->carshoopitems as $item) {
                foreach ($item->producto->almacens as $almacen) {
                    $this->almacenitem[$item->id]['almacens'][$almacen->id]['id'] = $almacen->id;
                    $this->almacenitem[$item->id]['almacens'][$almacen->id]['serie_id'] = '';
                    $this->almacenitem[$item->id]['almacens'][$almacen->id]['cantidad'] = $item->producto->isRequiredserie() ? 1 : 0;
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    // function enviarsunat()
    // {

    //     if ($this->venta->comprobante && !$this->venta->comprobante->isSendSunat()) {
    //         $response = $this->venta->comprobante->enviarComprobante();

    //         if ($response->success) {
    //             if (empty($response->mensaje)) {
    //                 $mensaje = response()->json([
    //                     'title' => $response->title,
    //                     'icon' => 'success'
    //                 ]);
    //                 $this->dispatchBrowserEvent('toast', $mensaje->getData());
    //             } else {
    //                 $mensaje = response()->json([
    //                     'title' => $response->title,
    //                     'text' => $response->mensaje,
    //                 ]);
    //                 $this->dispatchBrowserEvent('validation', $mensaje->getData());
    //             }
    //         } else {
    //             $mensaje = response()->json([
    //                 'title' => $response->title,
    //                 'text' => $response->mensaje,
    //             ]);
    //             $this->dispatchBrowserEvent('validation', $mensaje->getData());
    //         }
    //     } else {
    //         $mensaje = response()->json([
    //             'title' => 'COMPROBANTE ELECTRÓNICO ' . $this->venta->comprobante->seriecompleta . ' YA FUÉ EMITIDO A SUNAT.',
    //             'text' => null,
    //         ]);
    //         $this->dispatchBrowserEvent('validation', $mensaje->getData());
    //     }
    // }
}
