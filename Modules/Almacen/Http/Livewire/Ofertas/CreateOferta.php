<?php

namespace Modules\Almacen\Http\Livewire\Ofertas;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Modules\Almacen\Entities\Almacen;
use Modules\Almacen\Entities\Oferta;
use Modules\Almacen\Entities\Producto;

class CreateOferta extends Component
{

    public $open = false;
    public $productos = [];
    public $producto;
    public $max = 0;
    public $maxlimit = 0;

    public $almacen_id, $producto_id, $descuento, $datestart, $dateexpire, $limit;

    protected function rules()
    {
        return [
            'almacen_id' => [
                'required', 'integer', 'exists:almacens,id',
            ],
            'producto_id' => [
                'required', 'integer', 'exists:productos,id',
                Rule::unique('ofertas', 'producto_id')->where('status', 0)->whereNull('deleted_at')
            ],
            'descuento' => [
                'required', 'decimal:0,2', 'min:1',
            ],
            'datestart' => [
                'required', 'date', 'date_format:Y-m-d', 'after_or_equal:today',
            ],
            'dateexpire' => [
                'required', 'date', 'date_format:Y-m-d', 'after_or_equal:today', 'after_or_equal:datestart',
            ],
            'limit' => [
                'required', 'decimal:0,2', 'min:1', 'max:' . $this->maxlimit
            ]
        ];
    }

    public function mount()
    {
        $this->producto = new Producto();
    }


    public function render()
    {
        $almacens = Almacen::orderBy('name', 'asc')->get();
        // $productos = Producto::all();
        return view('almacen::livewire.ofertas.create-oferta', compact('almacens'));
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->resetValidation();
            $this->resetExcept('open');
        }
    }

    public function updatedAlmacenId($value)
    {

        $this->resetValidation(['max']);
        $this->reset(['producto_id', 'productos', 'max', 'producto', 'maxlimit']);
        if ($value) {
            $this->productos = Almacen::findOrFail($value)->productos()->orderBy('name', 'asc')->get();
        }
    }

    public function updatedProductoId($value)
    {
        $this->reset(['producto']);
        if ($value) {

            $this->producto = Producto::findOrFail($value);
            $this->maxlimit = Producto::findOrFail($this->producto_id)->almacens
                ->find($this->almacen_id)->pivot->cantidad;

            if ($this->max) {
                $this->limit =  $this->maxlimit;
                $this->resetValidation(['max']);
            }
        }
    }

    public function updatedMax($value)
    {

        $this->resetValidation(['max']);
        $this->reset(['max']);

        if ($value == 1) {
            if ($this->almacen_id) {
                if ($this->producto_id) {
                    $this->limit =  Producto::findOrFail($this->producto_id)->almacens
                        ->find($this->almacen_id)->pivot->cantidad;
                    $this->resetValidation(['max']);
                    $this->max = 1;
                } else {
                    $this->addError('max', 'Seleccione el campo producto');
                }
            } else {
                $this->addError('max', 'seleccione el campo almacén');
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
                'vendidos' => 0, //default = 0 Borrar despues de migrar
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
