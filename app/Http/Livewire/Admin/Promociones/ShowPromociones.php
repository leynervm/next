<?php

namespace App\Http\Livewire\Admin\Promociones;

use App\Models\Pricetype;
use App\Models\Promocion;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class ShowPromociones extends Component
{

    use WithPagination;
    use AuthorizesRequests;

    protected $queryString = [
        'estado' => ['except' => '',],
    ];
    protected $listeners = ['render'];
    public $pricetype_id;
    public $estado = Promocion::ACTIVO;

    public function mount()
    {
        if (mi_empresa()->usarLista()) {
            $pricetypes = Pricetype::orderBy('id', 'asc');
            if ($pricetypes->exists()) {
                if ($pricetypes->default()->exists()) {
                    $this->pricetype_id = $pricetypes->default()->first()->id;
                } else {
                    $this->pricetype_id = Pricetype::orderBy('id', 'asc')->first()->id;
                }
            }
        }
    }

    public function render()
    {
        $pricetypes = Pricetype::orderBy('id', 'asc')->get();
        $promociones = Promocion::with(['producto.images', 'itempromos']);
        if (trim($this->estado) != '') {
            $promociones->where('status', $this->estado);
        }
        $promociones = $promociones->orderBy('id', 'desc')->paginate();
        return view('livewire.admin.promociones.show-promociones', compact('promociones', 'pricetypes'));
    }

    public function updatedDisponibles($value)
    {
        $this->resetPage();
    }

    public function delete(Promocion $promocion)
    {
        $this->authorize('admin.promociones.delete');
        // $producto = Producto::find(2);
        // $descuento = $producto->promocions()->descuentos()->disponibles()->get();
        // dd($descuento);

        // $promocion->status = 1;
        // $promocion->save;
        if ($promocion->itempromos()->exists()) {
            $promocion->itempromos()->delete();
        }
        $promocion->delete();
        $this->dispatchBrowserEvent('toast', toastJSON('Promoción eliminado correctamente'));
    }

    public function finalizarpromocion(Promocion $promocion)
    {
        $this->authorize('admin.promociones.edit');
        $promocion->status = Promocion::FINALIZADO;
        $promocion->save();
        $this->dispatchBrowserEvent('toast', toastJSON('Promoción finalizado correctamente'));
    }

    public function disablepromocion(Promocion $promocion)
    {
        $this->authorize('admin.promociones.edit');
        $promocion->status = $promocion->status == '1' ? 0 : 1;
        if ($promocion->status == Promocion::ACTIVO) {

            if (!is_null($promocion->expiredate)) {
                if (Carbon::now('America/Lima')->gt(Carbon::parse($promocion->expiredate)->format('d-m-Y'))) {
                    $mensaje = response()->json([
                        'title' => 'No se puede activar una promoción con fecha expirada !',
                        'text' => "La fecha de finalización de promoción ha expirado, no se puede activar la promoción."
                    ])->getData();
                    $this->dispatchBrowserEvent('validation', $mensaje);
                    return false;
                }
            }

            $existotherpromociones = Promocion::disponibles()->where('producto_id', $promocion->producto_id)->exists();
            if ($existotherpromociones) {
                $mensaje = response()->json([
                    'title' => 'Promocion contiene productos que ya se encuentran en promociones activas !',
                    'text' => "No puede tener distintas promociones activas de un solo producto."
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
            }

            $existproductoitemcombos = Promocion::disponibles()
                ->withWhereHas('itempromos', function ($query) use ($promocion) {
                    $query->where('producto_id', $promocion->producto_id);
                })->exists();

            if ($existproductoitemcombos) {
                $mensaje = response()->json([
                    'title' => 'EL producto promocionado se encuentra dentro de un combo activo promocionado !',
                    'text' => "EL producto a promocionar no puede estar vinculado a una promoción activa."
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
            }
        }
        $promocion->save();
        $this->dispatchBrowserEvent('updated');
    }
}
