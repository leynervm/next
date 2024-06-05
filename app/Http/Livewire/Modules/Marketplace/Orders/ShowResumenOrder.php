<?php

namespace App\Http\Livewire\Modules\Marketplace\Orders;

use App\Models\Almacen;
use App\Models\Kardex;
use App\Models\Tvitem;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Modules\Marketplace\Entities\Order;

class ShowResumenOrder extends Component
{

    public Order $order;
    public $tvitem;
    public $open = false;
    public $almacen_id;
    public $almacens = [];

    public function mount()
    {
        $this->tvitem = new Tvitem();
    }

    public function render()
    {
        return view('livewire.modules.marketplace.orders.show-resumen-order');
    }

    public function descontarstock(Tvitem $tvitem)
    {
        // dd($tvitem->producto->almacens);
        // $this->authorize('admin.almacen.productos.almacen');
        $this->resetValidation();
        $this->resetExcept(['order', 'tvitem']);
        $this->tvitem = $tvitem;
        $this->almacens = $tvitem->producto->almacens;
        $this->open = true;
    }

    public function save()
    {
        $this->validate([
            'tvitem.cantidad' => ['required', 'numeric', 'min:1', 'decimal:0,4'],
            'tvitem.id' => ['required', 'integer', 'min:1', 'exists:tvitems,id'],
            'almacen_id' => ['required', 'integer', 'min:1', 'exists:almacens,id'],
        ]);

        DB::beginTransaction();
        try {
            $stock = $this->tvitem->producto->almacens()->find($this->almacen_id);
            if (!$stock) {
                $mensaje = response()->json([
                    'title' => 'ALMACÉN NO DISPONIBLE',
                    'text' => "Almacén seleccionado no se encuentra vinculado al producto."
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
            }

            if ($stock->pivot->cantidad <= 0) {
                $mensaje = response()->json([
                    'title' => 'STOCK NO DISPONIBLE',
                    'text' => "Stock del producto en almacén no se encuentra disponible.<br>" .
                        formatDecimalOrInteger($stock->pivot->cantidad) . " UND disponibles."
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
            }

            if (($stock->pivot->cantidad - $this->tvitem->cantidad) < 0) {
                $mensaje = response()->json([
                    'title' => 'STOCK NO DISPONIBLE',
                    'text' => "Cantidad de salida supera al stock disponible en almacén seleccionado.<br>" .
                        formatDecimalOrInteger($stock->pivot->cantidad) . " UND disponibles."
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
            }

            $this->tvitem->saveKardex(
                $this->tvitem->producto_id,
                $this->almacen_id,
                $stock->pivot->cantidad,
                $stock->pivot->cantidad - $this->tvitem->cantidad,
                $this->tvitem->cantidad,
                Almacen::SALIDA_ALMACEN,
                Kardex::SALIDA_VENTA_WEB,
                null,
            );

            $this->tvitem->producto->almacens()->updateExistingPivot($this->almacen_id, [
                'cantidad' => $stock->pivot->cantidad - $this->tvitem->cantidad,
            ]);

            DB::commit();
            $this->dispatchBrowserEvent('updated');
            $this->resetValidation();
            $this->resetExcept(['order', 'tvitem']);
            $this->order->refresh();
            // $this->dispatchBrowserEvent('reload');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
