<?php

namespace Modules\Almacen\Http\Livewire\Ofertas;

use App\Helpers\FormatoPersonalizado;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use App\Models\Almacen;
use App\Models\Oferta;
use App\Models\Producto;

class CreateOferta extends Component
{

    public $open = false;
    public $producto;
    public $max = 0;

    public $almacen_id, $producto_id, $descuento, $datestart, $dateexpire, $limit;

    protected function rules()
    {
        return [
            'almacen_id' => ['required', 'integer', 'min:1', 'exists:almacens,id'],
            'producto_id' => [
                'required', 'integer', 'min:1', 'exists:productos,id',
                Rule::unique('ofertas', 'producto_id')->where('status', 0)->whereNull('deleted_at')
            ],
            'descuento' => ['required', 'decimal:0,2', 'min:1'],
            'datestart' => [
                'required', 'date', 'date_format:Y-m-d', 'after_or_equal:today'
            ],
            'dateexpire' => [
                'required', 'date', 'date_format:Y-m-d', 'after_or_equal:today', 'after_or_equal:datestart',
            ],
            'limit' => [
                'required', 'integer', 'min:1', 'max:' . $this->limit
            ]
        ];
    }

    public function mount()
    {
        $this->producto = new Producto();
    }


    public function render()
    {
        // $almacens = Almacen::orderBy('name', 'asc')->get();
        $productos = Producto::with('almacens')->orderBy('name', 'asc')->get();
        return view('almacen::livewire.ofertas.create-oferta', compact('productos'));
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->resetValidation();
            $this->resetExcept(['open']);
        }
    }

    public function updatedProductoId($value)
    {
        $this->reset(['almacen_id', 'limit', 'max']);
        if ($value) {
            $this->producto = Producto::findOrFail($value);
        }
    }

    public function updatedAlmacenId($value)
    {
        $this->resetValidation();
        $this->reset(['limit', 'max']);
    }

    public function updatedMax($value)
    {

        $this->resetValidation();
        $this->reset(['max']);

        if ($value == 1) {
            $this->validate([
                'producto_id' => ['required', 'integer', 'min:1', 'exists:productos,id'],
                'almacen_id' => ['required', 'integer', 'min:1', 'exists:almacens,id']
            ]);
            $this->max = 1;
            $maxstock = Producto::findOrFail($this->producto_id)->almacens
                ->find($this->almacen_id)->pivot->cantidad;

            if ($maxstock) {
                $this->limit = FormatoPersonalizado::getValue($maxstock);
            }
        }
    }

    public function save()
    {

        $this->validate();
        DB::beginTransaction();

        try {

            Oferta::create([
                'descuento' => $this->descuento,
                'datestart' => $this->datestart,
                'dateexpire' => $this->dateexpire,
                'limit' => $this->limit,
                'disponible' => $this->limit,
                'vendidos' => 0,
                'almacen_id' => $this->almacen_id,
                'producto_id' => $this->producto_id,
                'user_id' => Auth::user()->id
            ]);

            DB::commit();
            $this->emitTo('almacen::ofertas.show-ofertas', 'render');
            $this->resetValidation();
            $this->reset();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function hydrate()
    {
        $this->dispatchBrowserEvent('render-oferta-select2');
    }
}
