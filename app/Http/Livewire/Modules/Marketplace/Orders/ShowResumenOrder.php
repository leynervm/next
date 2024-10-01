<?php

namespace App\Http\Livewire\Modules\Marketplace\Orders;

use App\Models\Almacen;
use App\Models\Itemserie;
use App\Models\Kardex;
use App\Models\Serie;
use App\Models\Tvitem;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Modules\Marketplace\Entities\Order;
use Modules\Marketplace\Entities\Tracking;
use Modules\Marketplace\Entities\Trackingstate;

class ShowResumenOrder extends Component
{

    use AuthorizesRequests;

    protected $listeners = ['render'];

    public Order $order;
    public $tvitem;
    public $open = false;
    public $almacen_id;
    public $almacens = [];
    public $almacen = [];
    public $serie_id = '';
    public $trackingstate_id = '';

    public function mount()
    {
        $this->tvitem = new Tvitem();
    }

    public function render()
    {
        $trackingstates = Trackingstate::whereNotIn('id', $this->order->trackings()->pluck('trackingstate_id'))->get();
        return view('livewire.modules.marketplace.orders.show-resumen-order', compact('trackingstates'));
    }

    public function updatestock(Tvitem $tvitem)
    {
        $this->authorize('admin.marketplace.orders.discountstock');
        $this->resetValidation();
        $this->resetExcept(['order', 'tvitem']);
        $this->tvitem = $tvitem;
        $this->getAlmacens();
        $this->open = true;
    }

    public function getAlmacens()
    {
        foreach ($this->tvitem->producto->almacens as $item) {
            $almacen = [
                'id' => $item->id,
                'name' => $item->name,
                'cantidad' => 0,
                'pivot' =>  [
                    'cantidad' => $item->pivot->cantidad
                ],
            ];
            $this->almacens[$item->id] = $almacen;
        }
    }

    public function discountserie($almacen_id, $serie_id)
    {
        $this->authorize('admin.marketplace.orders.discountstock');
        $this->almacens[$almacen_id]['serie_id'] = $serie_id;
        $this->validate([
            "almacens.$almacen_id.serie_id" => [
                'required',
                'integer',
                'min:1',
                'exists:series,id'
            ]
        ]);

        DB::beginTransaction();
        try {
            $serie = Serie::find($serie_id);
            if ($serie->isDisponible()) {
                if (($this->tvitem->kardexes()->sum('cantidad') + 1) > $this->tvitem->cantidad) {
                    $mensaje =  response()->json([
                        'title' => 'CANTIDAD A DESCONTAR NO PUEDE SER MAYOR AL STOCK ADQUIRIDO !',
                        'text' => null
                    ])->getData();
                    $this->dispatchBrowserEvent('validation', $mensaje);
                    return false;
                }

                $almacenStock = $this->tvitem->producto->almacens()->find(($almacen_id));

                if (!$almacenStock) {
                    $mensaje = response()->json([
                        'title' => 'ALMACÉN NO SE ENCUENTRA DISPONIBLE',
                        'text' => null
                    ])->getData();
                    $this->dispatchBrowserEvent('validation', $mensaje);
                    return false;
                }

                if (($almacenStock->pivot->cantidad - 1) < 0) {
                    $mensaje =  response()->json([
                        'title' => 'CANTIDAD A DESCONTAR EN ALMACÉN SUPERA EL STOCK DISPONIBLE !',
                        'text' => null
                    ])->getData();
                    $this->dispatchBrowserEvent('validation', $mensaje);
                    return false;
                }

                $serie->status = Serie::SALIDA;
                $serie->save();
                $this->tvitem->itemseries()->create([
                    'serie_id' => $serie_id,
                    'status' => 0,
                    'date' => now('America/Lima'),
                    'user_id' => auth()->user()->id
                ]);
                $kardex = $this->tvitem->kardexes()->where('almacen_id', $almacen_id)->first();

                if ($kardex) {
                    $kardex->cantidad = $kardex->cantidad + 1;
                    // $kardex->oldstock = $almacenStock->pivot->cantidad;
                    $kardex->newstock = $almacenStock->pivot->cantidad - 1;
                    $kardex->save();
                } else {
                    $kardex = $this->tvitem->saveKardex(
                        $this->tvitem->producto_id,
                        $almacen_id,
                        $almacenStock->pivot->cantidad,
                        $almacenStock->pivot->cantidad - 1,
                        1,
                        Almacen::SALIDA_ALMACEN,
                        Kardex::SALIDA_VENTA_WEB,
                        $this->order->purchase_number,
                    );
                }

                $kardex->producto->almacens()->updateExistingPivot($almacen_id, [
                    'cantidad' => $almacenStock->pivot->cantidad - 1,
                ]);
                DB::commit();
                $this->dispatchBrowserEvent('updated');
                $this->tvitem->refresh();
                $this->order->refresh();
                $this->getAlmacens();
                $this->resetValidation();
                $this->reset(['serie_id']);
                if (($this->tvitem->kardexes()->sum('cantidad')) == $this->tvitem->cantidad) {
                    $this->reset(['open']);
                }
            } else {
                $mensaje =  response()->json([
                    'title' => 'SERIE YA NO SE ENCUENTRA DISPONIBLE !',
                    'text' => null
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            // $mensaje =  response()->json([
            //     'title' => $e->getMessage(),
            //     'text' => null,
            // ])->getData();
            // $this->dispatchBrowserEvent('validation', $mensaje);
            // return false;
            throw $e;
        } catch (\Throwable $e) {
            // DB::rollBack();
            // $mensaje =  response()->json([
            //     'title' => $e->getMessage(),
            //     'text' => null,
            // ])->getData();
            // $this->dispatchBrowserEvent('validation', $mensaje);
            // return false;
            throw $e;
        }
    }

    public function discountstock()
    {
        $this->authorize('admin.marketplace.orders.discountstock');
        $this->validate([
            "almacens" => ['required', 'array', 'min:1'],
            "almacens.*.cantidad" => ['nullable', 'numeric', 'min:0', 'decimal:0,2'],
        ]);

        $catidades = array_column($this->almacens, 'cantidad');
        $totalstock = array_sum($catidades) ?? 0;
        if ($totalstock <= 0) {
            $mensaje =  response()->json([
                'title' => 'EL STOCK A DESCONTAR DEBE SER MAYOR QUE CERO !',
                'text' => null
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }

        if (($this->tvitem->kardexes()->sum('cantidad') + $totalstock) > $this->tvitem->cantidad) {
            $mensaje =  response()->json([
                'title' => 'CANTIDAD A DESCONTAR NO PUEDE SER MAYOR AL STOCK ADQUIRIDO !',
                'text' => null
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }

        DB::beginTransaction();
        try {
            foreach ($this->almacens as $item) {
                if ($item['cantidad'] > 0) {

                    $almacenStock = $this->tvitem->producto->almacens()->find($item['id']);
                    if (!$almacenStock) {
                        $mensaje = response()->json([
                            'title' => 'ALMACÉN NO SE ENCUENTRA DISPONIBLE',
                            'text' => null
                        ])->getData();
                        $this->dispatchBrowserEvent('validation', $mensaje);
                        return false;
                    }

                    if (($almacenStock->pivot->cantidad - (float) $item['cantidad']) < 0) {
                        $mensaje =  response()->json([
                            'title' => 'CANTIDAD A DESCONTAR EN ALMACÉN SUPERA EL STOCK DISPONIBLE !',
                            'text' => null
                        ])->getData();
                        $this->dispatchBrowserEvent('validation', $mensaje);
                        return false;
                    }

                    $kardex = $this->tvitem->kardexes()->where('almacen_id', $item['id'])->first();

                    if ($kardex) {
                        $kardex->cantidad = $kardex->cantidad + (float) $item['cantidad'];
                        // $kardex->oldstock = $almacenStock->pivot->cantidad;
                        $kardex->newstock = $almacenStock->pivot->cantidad - (float) $item['cantidad'];
                        $kardex->save();
                    } else {
                        $kardex = $this->tvitem->saveKardex(
                            $this->tvitem->producto_id,
                            $item['id'],
                            $almacenStock->pivot->cantidad,
                            $almacenStock->pivot->cantidad - (float) $item['cantidad'],
                            1,
                            Almacen::SALIDA_ALMACEN,
                            Kardex::SALIDA_VENTA_WEB,
                            $this->order->purchase_number,
                        );
                    }

                    $kardex->producto->almacens()->updateExistingPivot($item['id'], [
                        'cantidad' => $almacenStock->pivot->cantidad - (float) $item['cantidad'],
                    ]);
                }
            }

            DB::commit();
            $this->dispatchBrowserEvent('updated');
            $this->tvitem->refresh();
            $this->order->refresh();
            $this->getAlmacens();
            $this->resetValidation();
            if (($this->tvitem->kardexes()->sum('cantidad')) == $this->tvitem->cantidad) {
                $this->reset(['open']);
            }
            $this->reset(['serie_id']);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deletestock(Kardex $kardex)
    {
        $this->authorize('admin.marketplace.orders.deletestock');
        try {
            $almacenStock = $kardex->producto->almacens()->find($kardex->almacen_id);
            if (!$almacenStock) {
                $mensaje = response()->json([
                    'title' => 'ALMACÉN NO SE ENCUENTRA DISPONIBLE',
                    'text' => null
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
            }

            $kardex->producto->almacens()->updateExistingPivot($kardex->almacen_id, [
                'cantidad' => $almacenStock->pivot->cantidad + (float) $kardex->cantidad,
            ]);
            DB::commit();
            $kardex->delete();
            $this->dispatchBrowserEvent('deleted');
            $this->order->refresh();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteitemseriestock(Itemserie $itemserie)
    {
        $this->authorize('admin.marketplace.orders.deletestock');
        DB::beginTransaction();
        try {
            $kardex = $itemserie->tvitem->kardexes()->where('almacen_id', $itemserie->serie->almacen_id)->first();
            if (!$kardex) {
                $mensaje = response()->json([
                    'title' => 'KARDEX DE SALIDA DEL PRODUCTO NO SE ENCUENTRA DISPONIBLE',
                    'text' => null
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
            }

            $almacenStock = $kardex->producto->almacens()->find($kardex->almacen_id);
            if (!$almacenStock) {
                $mensaje = response()->json([
                    'title' => 'ALMACÉN NO SE ENCUENTRA DISPONIBLE',
                    'text' => null
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
            }

            $itemserie->serie->status = Serie::DISPONIBLE;
            $itemserie->serie->save();
            $itemserie->delete();
            $kardex->producto->almacens()->updateExistingPivot($kardex->almacen_id, [
                'cantidad' => $almacenStock->pivot->cantidad + 1,
            ]);
            if (($kardex->cantidad - 1) == 0) {
                $kardex->delete();
            } else {
                $kardex->cantidad = $kardex->cantidad - 1;
                $kardex->newstock = $kardex->newstock + 1;
                $kardex->save();
            }
            DB::commit();
            $this->dispatchBrowserEvent('deleted');
            $this->order->refresh();
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
        $this->reset(['trackingstate_id']);
        $this->resetValidation();
        $this->order->refresh();
        $this->dispatchBrowserEvent('toast', toastJSON('Tracking actualizado correctamente'));
    }

    public function delete(Tracking $tracking)
    {
        $this->authorize('admin.marketplace.trackings.delete');
        $tracking->delete();
        $this->dispatchBrowserEvent('toast', toastJSON('Tracking actualizado correctamente'));
        $this->order->refresh();
    }
}
