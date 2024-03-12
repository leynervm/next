<?php

namespace App\Http\Livewire\Admin\Promociones;

use App\Models\Pricetype;
use App\Models\Promocion;
use Livewire\Component;
use Livewire\WithPagination;

class ShowPromociones extends Component
{

    use WithPagination;

    public $pricetype_id;

    protected $listeners = ['render'];

    public function mount()
    {
        $pricetypes = Pricetype::default();
        if (count($pricetypes->get()) > 0) {
            $this->pricetype_id = $pricetypes->first()->id;
        } else {
            $this->pricetype_id = Pricetype::orderBy('id', 'asc')->first()->id;
        }
    }

    public function render()
    {
        $pricetypes = Pricetype::orderBy('id', 'asc')->get();
        $promociones = Promocion::with(['producto', 'itempromos'])->orderBy('id', 'desc')->paginate();
        return view('livewire.admin.promociones.show-promociones', compact('promociones', 'pricetypes'));
    }

    public function delete(Promocion $promocion)
    {
        // $producto = Producto::find(2);
        // $descuento = $producto->promocions()->descuentos()->disponibles()->get();
        // dd($descuento);
        $promocion->delete();
        $this->dispatchBrowserEvent('deleted');
    }

    public function desactivar(Promocion $promocion)
    {
        $promocion->status = $promocion->status == '1' ? 0 : 1;
        $promocion->save();
        $this->dispatchBrowserEvent('updated');
    }
}
