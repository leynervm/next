<?php

namespace App\Http\Livewire\Admin\Sucursales;

use App\Helpers\FormatoPersonalizado;
use App\Models\Sucursal;
use App\Models\Ubigeo;
use App\Models\User;
use App\Rules\CampoUnique;
use App\Rules\DefaultValue;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Nwidart\Modules\Facades\Module;

class ShowSucursales extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    public $sucursal;
    protected $listeners = ['render'];

    protected function rules()
    {
        return [
            'sucursal.name' => ['required', 'min:3', 'max:255', new CampoUnique('sucursals', 'name', $this->sucursal->id, true)],
            'sucursal.direccion' => ['required', 'string', 'min:3', 'max:255'],
            'sucursal.ubigeo_id' => ['required', 'integer', 'min:1', 'exists:ubigeos,id'],
            'sucursal.default' => ['required', 'integer', 'min:0', 'max:1', new DefaultValue('sucursals', 'default', $this->sucursal->id, true)],
            'sucursal.empresa_id' => ['required', 'integer', 'min:1', 'exists:empresas,id'],
        ];
    }

    public function mount(Sucursal $sucursal)
    {
        $this->sucursal = $sucursal;
    }

    public function render()
    {
        $sucursales = Sucursal::withTrashed()->with(['boxes' => function ($query) {
            $query->withTrashed();
        }])->with(['seriecomprobantes' => function ($query) {
            $query->whereHas('typecomprobante', function ($query) {
                // $query->where('code', '<>', '09');
                if (Module::isDisabled('Facturacion')) {
                    $query->default();
                }
            });
        }])->orderBy('codeanexo', 'asc')->orderBy('id', 'asc')->paginate();

        $ubigeos = Ubigeo::orderBy('region', 'asc')->orderBy('provincia', 'asc')
            ->orderBy('distrito', 'asc')->get();
        return view('livewire.admin.sucursales.show-sucursales', compact('sucursales', 'ubigeos'));
    }

    public function delete(Sucursal $sucursal, $confirmation = false)
    {
        // $users = $sucursal->users()->count();
        // $almacens = $sucursal->almacens()->count();
        // $ventas = $sucursal->ventas()->count();
        // $comprobantes = $sucursal->comprobantes()->count();
        // $compras = $sucursal->compras()->count();

        // $cadena = FormatoPersonalizado::extraerMensaje([
        //     'Usuarios' => $users,
        //     'Almacenes' => $almacens,
        //     'Ventas' => $ventas,
        //     'Comprobantes' => $comprobantes,
        //     'Usuarios' => $users,
        //     'Compras' => $compras,
        // ]);

        // dd($sucursal->getRelations());

        $this->authorize('admin.administracion.sucursales.delete');
        if ($confirmation) {
            User::query()->where('sucursal_id', $sucursal->id)->update([
                'sucursal_id' => null,
                'almacen_id' => null
            ]);
            $sucursal->default = 0;
            $sucursal->save();
            $sucursal->delete();
            $this->dispatchBrowserEvent('deleted');
        } else {
            if ($sucursal->users()->count() > 0) {
                $this->emit('sucursales.existUserSucursals', $sucursal);
            } else {
                $sucursal->delete();
                $this->dispatchBrowserEvent('deleted');
            }
        }


        // if ($users > 0 || $almacens > 0 || $ventas > 0 || $comprobantes  || $users) {
        //     $mensaje = response()
        //         ->json([
        //             'title' => 'No se puede eliminar registro, ' . $sucursal->name,
        //             'text' => "Existen registros vinculados $cadena, eliminarlo causaría un conflicto en la base de datos.",
        //         ])
        //         ->getData();
        //     $this->dispatchBrowserEvent('validation', $mensaje);
        // } else {
        //     if ($sucursal->isDefault()) {
        //         Sucursal::query()->update(['default' => 0]);
        //     }
        //     $sucursal->delete();
        //     $this->dispatchBrowserEvent('deleted');
        // }
    }

    public function setsucursaldefault(Sucursal $sucursal)
    {
        $this->authorize('admin.administracion.sucursales.edit');
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

    public function restoreSucursal($id)
    {
        $this->authorize('admin.administracion.sucursales.restore');
        $sucursal = Sucursal::onlyTrashed()->find($id);
        if ($sucursal) {
            $anexos = Sucursal::withTrashed()->where('codeanexo', $sucursal->codeanexo)
                ->where('id', '<>', $sucursal->id);

            if ($anexos->exists()) {
                $mensaje = response()->json([
                    'title' => 'Ya existe sucursal con el mismo código de anexo, [' . $sucursal->codeanexo . '] - ' . $anexos->first()->name,
                    'text' => "Existen registros de sucursales con el mismo código de anexo en la base de datos.",
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
            }

            $sucursal->default = 0;
            $sucursal->restore();
            $this->resetValidation();
            $this->dispatchBrowserEvent('toast', toastJSON('Sucursal habilitado correctamente'));
        }
    }
}
