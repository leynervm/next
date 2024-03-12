<?php

namespace App\Http\Livewire\Modules\Almacen\Productos;

use App\Helpers\GetPrice;
use App\Models\Garantiaproducto;
use App\Models\Pricetype;
use App\Models\Producto;
use App\Models\Typegarantia;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ShowGarantias extends Component
{

    use AuthorizesRequests;

    public $producto, $typegarantia, $pricetype;
    public $typegarantia_id, $time;
    public $open = false;
    public $newprice, $priceold, $pricemanual;
    public $descripcion = '';

    protected $listeners = ['delete'];

    public function mount(Producto $producto)
    {
        $this->producto = $producto;
        $this->typegarantia = new Typegarantia();
        $this->pricetype = new Pricetype();
        if ($producto->detalleproducto) {
            $this->descripcion =  $producto->detalleproducto->descripcion;
        }
    }

    public function render()
    {

        $typegarantias = Typegarantia::orderBy('name', 'asc')->get();
        $pricetypes = Pricetype::orderBy('id', 'asc')->orderBy('name', 'asc')->get();

        return view('livewire.modules.almacen.productos.show-garantias', compact('typegarantias', 'pricetypes'));
    }

    public function save()
    {
        $this->authorize('admin.almacen.productos.garantias.edit');
        $validatedata = $this->validate([
            'producto.id' => ['required', 'integer', 'min:1', 'exists:productos,id'],
            'typegarantia_id' => [
                'required', 'integer', 'min:1', 'exists:typegarantias,id',
                Rule::unique('garantiaproductos', 'typegarantia_id')
                    ->where('producto_id', $this->producto->id)
            ],
            'time' => ['required', 'numeric', 'min:1']
        ]);

        $this->producto->garantiaproductos()->create([
            'time' => $this->time,
            'typegarantia_id' => $this->typegarantia_id,
        ]);

        $this->resetValidation();
        $this->reset(['time', 'typegarantia_id']);
        $this->producto->refresh();
        $this->dispatchBrowserEvent('created');
    }

    public function delete(Garantiaproducto $garantiaproducto)
    {
        $this->authorize('admin.almacen.productos.garantias.edit');
        $garantiaproducto->delete();
        $this->producto->refresh();
        $this->dispatchBrowserEvent('deleted');
    }

    public function cambiarprecioventa(Pricetype $pricetype)
    {
        $this->authorize('admin.administracion.pricetypes.productos');
        $this->reset(['newprice', 'priceold']);
        $this->resetValidation();
        $this->pricetype = $pricetype;
        $prices = GetPrice::getPriceProducto($this->producto, $pricetype->id)->getData();
        $this->priceold = $prices->oldPrice;
        $this->newprice = $prices->pricemanual ?? $prices->pricesale;
        $this->pricemanual = $this->producto->pricetypes()->where('pricetype_id', $pricetype->id)->first();
        $this->open = true;
    }

    public function saveprecioventa()
    {

        $this->authorize('admin.administracion.pricetypes.productos');
        $this->validate([
            'pricetype.id' => ['required', 'integer', 'min:1', 'exists:pricetypes,id'],
            'producto.id' => ['required', 'integer', 'min:1', 'exists:productos,id'],
            'newprice' => ['required', 'decimal:0,2', 'min:0.1']
        ]);

        $this->producto->pricetypes()->syncWithoutDetaching([
            $this->pricetype->id => [
                'price' => $this->newprice,
            ]
        ]);

        $this->resetValidation();
        $this->reset(['open', 'newprice', 'priceold']);
        $this->dispatchBrowserEvent('updated');
    }

    public function deletepricemanual()
    {
        $this->authorize('admin.administracion.pricetypes.productos');
        if ($this->pricemanual) {
            $this->producto->pricetypes()->detach($this->pricetype->id);
            $this->resetValidation();
            $this->reset(['pricemanual']);
            $this->dispatchBrowserEvent('deleted');
        }
    }

    public function savedetalle()
    {
        $this->authorize('admin.almacen.productos.especificaciones');
        $validateData = $this->validate([
            'producto.id' => ['required', 'integer', 'min:1', 'exists:productos,id'],
            'descripcion' => ['required', 'string']
        ]);

        $this->producto->detalleproducto()->updateOrCreate(
            ['id' => $this->producto->detalleproducto->id ?? null],
            ['descripcion' => $this->descripcion]
        );
        // $this->reset(['descripcion']);
        $this->resetValidation();
        // $this->emit("resetCKEditor");
        $this->producto->refresh();
        $this->dispatchBrowserEvent('updated');
    }

    // public function deletedescripcion(Detalleproducto $detalleproducto)
    // {
    //     $detalleproducto->delete();
    //     $this->producto->refresh();
    //     $this->dispatchBrowserEvent('deleted');
    // }
}
