<?php

namespace Modules\Ventas\Http\Livewire\Ventas;

use App\Models\Carshoop;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ShowCarrito extends Component
{
    public function render()
    {
        $carrito = Carshoop::where('user_id', Auth::user()->id)->get();
        return view('ventas::livewire.ventas.show-carrito', compact('carrito'));
    }

    public function deleteitemcart(Carshoop $carshoop)
    {

        DB::beginTransaction();

        try {

            $stock = $carshoop->producto->almacens->find($carshoop->almacen_id)->pivot->cantidad;

            if ($carshoop->carshoopseries()->exists()) {
                foreach ($carshoop->carshoopseries as $itemserie) {
                    $itemserie->serie()->update([
                        'status' => 0,
                        'dateout' => null
                    ]);
                }
                $carshoop->carshoopseries()->delete();
            }

            $carshoop->producto->almacens()->updateExistingPivot($carshoop->almacen_id, [
                'updated_at' => now('America/Lima'),
                'cantidad' => $stock + $carshoop->cantidad,
                'user_id' => Auth::user()->id
            ]);

            $carshoop->delete();
            DB::commit();
            // $this->total = Carshoop::where('user_id', Auth::user()->id)->sum('total');
            if ($this->typepayment->paycuotas) {
                $this->calcular_cuotas();
            }
            $this->dispatchBrowserEvent('deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
