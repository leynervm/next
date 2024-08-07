<?php

namespace App\Http\Livewire\Admin\Sucursales;

use App\Models\Empresa;
use App\Models\Sucursal;
use App\Models\Typesucursal;
use App\Models\Ubigeo;
use App\Rules\CampoUnique;
use App\Rules\DefaultValue;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateSucursal extends Component
{

    use AuthorizesRequests;

    public $open = false;
    public $name, $direccion, $codeanexo, $typesucursal_id, $default,
        $ubigeo_id, $empresa;

    protected function rules()
    {
        return [
            'name' => [
                'required', 'min:3', 'max:255',
                new CampoUnique('sucursals', 'name', null, true),
            ],
            'direccion' => [
                'required', 'string', 'min:3', 'max:255'
            ],
            'ubigeo_id' => [
                'required', 'integer', 'min:1', 'exists:ubigeos,id',
            ],
            'codeanexo' => [
                'required', 'string', 'min:4', 'max:4',
                new CampoUnique('sucursals', 'codeanexo', null, true),
            ],
            'typesucursal_id' => [
                'required', 'integer', 'min:1', 'exists:typesucursals,id',
            ],
            'default' => [
                'required', 'integer', 'min:0', 'max:1',
                new DefaultValue('sucursals', 'default', null, true)
            ],
            'empresa.id' => [
                'required', 'integer', 'min:1', 'exists:empresas,id'
            ]
        ];
    }

    public function mount(Empresa $empresa)
    {
        $this->empresa = $empresa;
    }

    public function render()
    {
        $ubigeos = Ubigeo::orderBy('region', 'asc')->orderBy('provincia', 'asc')->orderBy('distrito', 'asc')->get();
        $typesucursals = Typesucursal::orderBy('name', 'asc')->get();
        return view('livewire.admin.sucursales.create-sucursal', compact('ubigeos', 'typesucursals'));
    }


    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->authorize('admin.administracion.sucursales.create');
            $this->resetValidation();
            $this->resetExcept(['empresa']);
        }
    }

    public function save($closemodal = false)
    {
        $this->authorize('admin.administracion.sucursales.create');
        if (!is_null(mi_empresa()->limitsucursals) && mi_empresa()->sucursals()->withTrashed()->count() >= mi_empresa()->limitsucursals) {
            $mensaje = response()->json([
                'title' => 'Contáctese con su proveedor de servicio !',
                'text' => "Se alcanzó el límite de sucursales permitidos por el sistema, contáctese con su proveedor del servicio."
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }

        $this->name = trim($this->name);
        $this->direccion = trim($this->direccion);
        $this->default = $this->default == 1 ? 1 : 0;
        $this->codeanexo = trim($this->codeanexo);
        $validateData = $this->validate();

        try {
            DB::beginTransaction();
            $sucursal = Sucursal::withTrashed()
                ->whereRaw('UPPER(name) = ?', [mb_strtoupper($this->name, "UTF-8")])->first();

            if ($sucursal) {
                $sucursal->direccion = $this->direccion;
                $sucursal->ubigeo_id = $this->ubigeo_id;
                $sucursal->codeanexo = $this->codeanexo;
                $sucursal->typesucursal_id = $this->typesucursal_id;
                $sucursal->default = $this->default;
                $sucursal->empresa_id = $this->empresa->id;
                if ($sucursal->trashed()) {
                    $sucursal->restore();
                }
            } else {
                $this->empresa->sucursals()->create($validateData);
            }

            DB::commit();
            $this->resetValidation();
            if ($closemodal) {
                $this->resetExcept(['empresa']);
            } else {
                $this->resetExcept(['open', 'empresa']);
            }
            $this->emitTo('admin.sucursales.show-sucursales', 'render');
            $this->dispatchBrowserEvent('created');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
