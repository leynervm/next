<?php

namespace Modules\Almacen\Http\Livewire\Almacens;

use App\Helpers\FormatoPersonalizado;
use App\Models\Sucursal;
use App\Rules\CampoUnique;
use App\Rules\DefaultValue;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Almacen;

class ShowAlmacens extends Component
{

    use WithPagination;

    public $open = false;
    public $openalmacen = false;
    public $name;
    public $default = 0;
    public $almacen, $sucursal;

    protected $listeners = ['render', 'delete'];

    protected function rules()
    {
        return [
            'name' => [
                'required', 'min:3', 'max:100',
                new CampoUnique('almacens', 'name', $this->almacen->id, true),
            ],
            'default' => [
                'required', 'integer', 'min:0', 'max:1',
                new DefaultValue('almacens', 'default', $this->almacen->id ?? null, true, 'sucursal_id', $this->sucursal->id),
            ],
            'sucursal.id' => [
                'required', 'integer', 'min:1', 'exists:sucursals,id'
            ],
        ];
    }

    public function mount()
    {
        $this->almacen = new Almacen();
        $this->sucursal = new Sucursal();
    }

    public function render()
    {
        $sucursales = Sucursal::with(['almacens' => function ($query) {
            $query->orderBy('default', 'desc')->orderBy('name', 'asc');
        }])->orderBy('default', 'desc')->orderBy('status', 'asc')->orderBy('name', 'asc')->get();
        // $almacens = Almacen::orderBy('name', 'asc')->paginate();
        return view('almacen::livewire.almacens.show-almacens', compact('sucursales'));
    }

    public function openalmacen(Sucursal $sucursal)
    {
        $this->sucursal = $sucursal;
        $this->almacen = new Almacen();
        $this->open = true;
    }

    public function edit(Sucursal $sucursal, Almacen $almacen)
    {
        $this->resetValidation();
        $this->reset(['name', 'default']);
        $this->sucursal = $sucursal;
        $this->almacen = $almacen;
        $this->name = trim($this->almacen->name);
        $this->default = $this->almacen->default == 1 ? 1 : 0;
        $this->open = true;
    }

    public function update()
    {

        $this->default = $this->default == 1 ? 1 : 0;
        $this->validate();
        DB::beginTransaction();
        try {
            $this->sucursal->almacens()->updateOrCreate(
                ['id' => $this->almacen->id],
                [
                    'name' =>  $this->name,
                    'default' =>  $this->default,
                ]
            );
            DB::commit();
            $this->resetValidation();
            if ($this->almacen->id) {
                $this->dispatchBrowserEvent('updated');
            } else {
                $this->dispatchBrowserEvent('created');
            }
            $this->reset(['open', 'name', 'default', 'sucursal', 'almacen']);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(Almacen $almacen)
    {
        $series = $almacen->series()->count();
        $productos = $almacen->productos()->count();
        $tvitems = $almacen->tvitems()->count();
        $compraitems = $almacen->compraitems()->count();
        // dd("Series => ", $series, "Productos => ", $productos, "TVitems => ", $tvitems, "Compraitems => ", $compraitems);

        $cadena = FormatoPersonalizado::extraerMensaje([
            'Series' => $series,
            'Productos' => $productos,
            'Items_de_ventas' => $tvitems,
            'Items_de_compras' => $compraitems
        ]);

        if ($series > 0 || $productos > 0 || $tvitems > 0 || $compraitems > 0) {
            $mensaje = response()->json([
                'title' => 'No se puede eliminar el registro con nombre ' . $almacen->name,
                'text' => "Existen registros vinculados al almacén $cadena, eliminarlo causaría un conflicto en la base de datos."
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
        } else {
            $almacen->forceDelete();
            $this->dispatchBrowserEvent('deleted');
        }
    }

    public function hydrate()
    {
        $this->dispatchBrowserEvent('render-show-almacens');
    }
}
