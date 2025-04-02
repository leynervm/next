<?php

namespace App\Http\Livewire\Modules\Marketplace\Orders;

use App\Mail\TrackingOrderMailable;
use App\Models\Almacen;
use App\Models\Carshoopitem;
use App\Models\Itemserie;
use App\Models\Kardex;
use App\Models\Producto;
use App\Models\Serie;
use App\Models\Tvitem;
use App\Rules\ValidateStock;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Modules\Marketplace\Entities\Order;
use Modules\Marketplace\Entities\Tracking;
use Modules\Marketplace\Entities\Trackingstate;
use Illuminate\Support\Facades\Mail;

class ShowResumenOrder extends Component
{

    use AuthorizesRequests;

    protected $listeners = ['render'];

    public $order;
    public $tvitem;
    public $open = false;
    public $sendemail = false;
    public $trackingstate_id = '';

    public $almacens = [], $almacenitem = [];

    public function mount(Order $order)
    {
        $this->order = $order;
        $this->tvitem = new Tvitem();
    }

    public function render()
    {
        $trackingstates = Trackingstate::whereNotIn('id', $this->order->trackings()->pluck('trackingstate_id'))->get();
        return view('livewire.modules.marketplace.orders.show-resumen-order', compact('trackingstates'));
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
            $this->order->refresh();
            $this->tvitem->refresh();
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
            $this->order->refresh();
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
            $this->order->refresh();
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
            $this->order->refresh();
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
            $this->order->refresh();
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
            $this->order->refresh();
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

    // TRACKING
    public function save()
    {
        $this->authorize('admin.marketplace.trackings.create');

        if (!$this->order->isPagoconfirmado()) {
            $mensaje = response()->json([
                'title' => 'NO SE PUEDE AGREGAR TRACKINGS,PAGO DE ORDEN PENDIENTE !',
                'text' => null,
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }
        $this->validate([
            'trackingstate_id' => [
                'required',
                'integer',
                'min:1',
                'exists:trackingstates,id',
                Rule::unique('trackings', 'trackingstate_id')->where('order_id', $this->order->id)
            ]
        ]);

        $this->order->trackings()->create([
            'date' => now('America/Lima'),
            'trackingstate_id' => $this->trackingstate_id,
            'user_id' => auth()->user()->id
        ]);
        $this->resetValidation();
        $this->order->refresh();
        $this->dispatchBrowserEvent('toast', toastJSON('Tracking actualizado correctamente'));
        if ($this->sendemail) {
            $mail = Mail::to($this->order->user->email)->queue(new TrackingOrderMailable($this->order));
        }
        $this->reset(['trackingstate_id', 'sendemail']);
    }

    public function delete(Tracking $tracking)
    {
        $this->authorize('admin.marketplace.trackings.delete');
        $tracking->delete();
        $this->dispatchBrowserEvent('toast', toastJSON('Tracking actualizado correctamente'));
        $this->order->refresh();
    }
}
