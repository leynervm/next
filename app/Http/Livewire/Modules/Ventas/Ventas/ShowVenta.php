<?php

namespace App\Http\Livewire\Modules\Ventas\Ventas;

use App\Enums\MovimientosEnum;
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
    public $istransferencia = false;
    public $venta, $cuota, $monthbox, $openbox, $moneda_id, $concept,
        $methodpayment_id, $detalle, $tipocambio;
    public $cuotas = [];
    public $amountcuotas = 0;
    public $countcuotas = 1;
    public $tvitem = [];
    public $pendiente =  0;
    public $paymentactual = 0;
    public $totalamount = 0;
    public $amountincrement = 0;
    public $amountpendiente =  0;

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

    public function deleteserie(Itemserie $itemserie)
    {
        $this->authorize('admin.ventas.create');
        DB::beginTransaction();
        try {
            $itemserie->serie->status = 0;
            $itemserie->serie->dateout = null;
            $itemserie->serie->save();
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
