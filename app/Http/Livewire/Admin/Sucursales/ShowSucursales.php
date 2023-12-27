<?php

namespace App\Http\Livewire\Admin\Sucursales;

use App\Helpers\FormatoPersonalizado;
use App\Models\Sucursal;
use App\Models\Ubigeo;
use App\Rules\CampoUnique;
use App\Rules\DefaultValue;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ShowSucursales extends Component
{

    use WithPagination;

    public $sucursal;
    protected $listeners = ['render', 'delete', 'setsucursaldefault'];

    protected function rules()
    {
        return [
            'sucursal.name' => [
                'required', 'min:3', 'max:255',
                new CampoUnique('sucursals', 'name', $this->sucursal->id, true),
            ],
            'sucursal.direccion' => [
                'required', 'string', 'min:3', 'max:255'
            ],
            'sucursal.ubigeo_id' => [
                'required', 'integer', 'min:1', 'exists:ubigeos,id',
            ],
            'sucursal.default' => [
                'required', 'integer', 'min:0', 'max:1',
                new DefaultValue('sucursals', 'default', $this->sucursal->id, true)
            ],
            'sucursal.empresa_id' => [
                'required', 'integer', 'min:1', 'exists:empresas,id'
            ]
        ];
    }

    public function render()
    {
        $sucursales = Sucursal::with(['cajas' => function ($query) {
            $query->withTrashed();
        }])->orderBy('id', 'asc')->paginate();
        $ubigeos = Ubigeo::orderBy('region', 'asc')->orderBy('provincia', 'asc')->orderBy('distrito', 'asc')->get();
        return view('livewire.admin.sucursales.show-sucursales', compact('sucursales', 'ubigeos'));
    }

    public function delete(Sucursal $sucursal)
    {

        $users = $sucursal->users()->count();
        $almacens = $sucursal->almacens()->count();
        $ventas = $sucursal->ventas()->count();

        $cadena = FormatoPersonalizado::extraerMensaje([
            'Usuarios' => $users,
            'Almacenes' => $almacens,
            'Ventas' => $ventas,
        ]);

        if ($users > 0 || $almacens > 0 || $ventas > 0) {
            $mensaje = response()->json([
                'title' => 'No se puede eliminar registro, ' . $sucursal->name,
                'text' => "Existen registros vinculados $cadena, eliminarlo causaría un conflicto en la base de datos."
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
        } else {
            $sucursal->forceDelete();
            $this->dispatchBrowserEvent('deleted');
        }
    }

    public function setsucursaldefault(Sucursal $sucursal)
    {
        try {
            DB::beginTransaction();
            Sucursal::query()->update(['default' => 0]);
            $sucursal->default = 1;
            $sucursal->save();
            DB::commit();
            $this->dispatchBrowserEvent('updated');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
