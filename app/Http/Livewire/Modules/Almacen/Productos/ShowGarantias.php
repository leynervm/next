<?php

namespace App\Http\Livewire\Modules\Almacen\Productos;

use App\Helpers\GetPrice;
use App\Models\Garantiaproducto;
use App\Models\Pricetype;
use App\Models\Producto;
use App\Models\Typegarantia;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ShowGarantias extends Component
{

    public $producto, $typegarantia, $pricetype;
    public $typegarantia_id, $time;
    public $open = false;
    public $newprice, $priceold, $pricemanual;
    public $titulo, $descripcion;

    protected $listeners = ['delete'];

    public function mount(Producto $producto)
    {
        $this->producto = $producto;
        $this->typegarantia = new Typegarantia();
        $this->pricetype = new Pricetype();
    }

    public function render()
    {

        $typegarantias = Typegarantia::orderBy('name', 'asc')->get();
        $pricetypes = Pricetype::orderBy('ganancia', 'desc')->orderBy('name', 'asc')->get();

        return view('livewire.modules.almacen.productos.show-garantias', compact('typegarantias', 'pricetypes'));
    }

    public function updatedTypegarantiaId($value)
    {
        $this->reset(['typegarantia', 'time']);
        if ($value) {
            $this->typegarantia = Typegarantia::find($value);
            $this->time = $this->typegarantia->time;
        }
    }

    public function save()
    {
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
        $garantiaproducto->deleteOrFail();
        $this->producto->refresh();
        $this->dispatchBrowserEvent('deleted');
    }

    public function cambiarprecioventa(Pricetype $pricetype)
    {
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
        if ($this->pricemanual) {
            $this->producto->pricetypes()->detach($this->pricetype->id);
            $this->resetValidation();
            $this->reset(['pricemanual']);
            $this->dispatchBrowserEvent('deleted');
        }
    }

    public function savedetalle()
    {
        $this->validate([
            'producto.id' => ['required', 'integer', 'min:1', 'exists:productos,id'],
            'titulo' => ['required', 'string', 'min:3'],
            'descripcion' => ['required', 'string', 'min:3']
        ]);

        $this->producto->detalleproductos()->create([
            'title' => $this->titulo,
            'descripcion' => $this->descripcion,
            'created_at' => now('America/Lima')
        ]);

        $this->reset(['titulo', 'descripcion']);
        $this->resetValidation();
        $this->emit("resetCKEditor");
        $this->producto->refresh();
        $this->dispatchBrowserEvent('created');
    }
}
