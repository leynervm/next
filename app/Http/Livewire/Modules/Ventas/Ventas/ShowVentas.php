<?php

namespace App\Http\Livewire\Modules\Ventas\Ventas;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Facturacion\Entities\Comprobante;
use Modules\Ventas\Entities\Venta;
use Nwidart\Modules\Facades\Module;

class ShowVentas extends Component
{

    use WithPagination, AuthorizesRequests;

    public $search = '';
    public $date = '';
    public $dateto = '';
    public $searchuser = '';
    public $deletes = false;

    protected $queryString = [
        'search' => ['except' => '', 'as' => 'buscar'],
        'date' => ['except' => '', 'as' => 'fecha'],
        'dateto' => ['except' => '', 'as' => 'hasta'],
        'searchuser' => ['except' => '', 'as' => 'usuario'],
        'deletes' => ['except' => false, 'as' => 'eliminados'],
    ];

    public function render()
    {
        $users = User::whereHas('ventas', function ($query) {
            $query->where('sucursal_id', auth()->user()->sucursal_id);
        })->orderBy('name', 'asc')->get();
        $ventas = Venta::with(['sucursal', 'user', 'client', 'typepayment', 'cajamovimientos', 'cuotas', 'moneda', 'seriecomprobante.typecomprobante'])
            ->where('sucursal_id', auth()->user()->sucursal_id);

        if (trim($this->search) !== '') {
            $ventas->whereHas('client', function ($query) {
                $query->where('document', 'ilike', '%' . $this->search . '%')
                    ->orWhere('name', 'ilike', '%' . $this->search . '%');
            })->orWhere('seriecompleta', 'ilike', ['%' . $this->search . '%']);
            // ->orWhereRaw("CONCAT(code, '-', id) ILIKE ?", ['%' . $this->search . '%']);
        }

        if ($this->date) {
            if ($this->dateto) {
                $ventas->whereDateBetween('date', $this->date, $this->dateto);
            } else {
                $ventas->whereDate('date', $this->date);
            }
        }

        if ($this->searchuser !== '') {
            $ventas->where('user_id', $this->searchuser);
        }

        if ($this->deletes) {
            $this->authorize('admin.ventas.deletes');
            $ventas->onlyTrashed();
        }

        $ventas = $ventas->orderBy('date', 'desc')->paginate();

        return view('livewire.modules.ventas.ventas.show-ventas', compact('ventas', 'users'));
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedDate()
    {
        $this->resetPage();
    }

    public function updatedDateto()
    {
        $this->resetPage();
    }

    public function updatedSearchuser()
    {
        $this->resetPage();
    }

    public function updatedDeletes()
    {
        $this->authorize('admin.ventas.deletes');
        $this->resetPage();
    }

    public function delete(Venta $venta)
    {
        $this->authorize('admin.ventas.delete');
        $cuotas = $venta->cuotas()->withWhereHas('cajamovimientos')->count();

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

                            if ($venta->sucursal->empresa->isProduccion()) {
                                $serienotacredito->contador = $numeracion;
                            } else {
                                $serienotacredito->contadorprueba = $numeracion;
                            }
                            $serienotacredito->save();
                            $venta->comprobante->delete();
                        } else {
                            DB::rollBack();
                            $mensaje = response()->json([
                                'title' => 'NO SE PUEDE GENERAR NOTA DE CRÉDITO DEL COMPROBANTE ' . $venta->code,
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

            if (count($venta->cajamovimientos) > 0) {
                $venta->cajamovimientos->each(function ($cajamovimiento) {
                    $cajamovimiento->delete();
                });
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
}
