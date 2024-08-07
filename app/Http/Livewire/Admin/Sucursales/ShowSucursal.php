<?php

namespace App\Http\Livewire\Admin\Sucursales;

use App\Models\Sucursal;
use App\Models\Typesucursal;
use App\Models\Ubigeo;
use App\Rules\CampoUnique;
use App\Rules\DefaultValue;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ShowSucursal extends Component
{

    use AuthorizesRequests;

    public $sucursal;

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
            'sucursal.typesucursal_id' => [
                'required', 'integer', 'min:1', 'exists:typesucursals,id',
            ],
            'sucursal.codeanexo' => [
                'required', 'string', 'min:4', 'max:4',
                new CampoUnique('sucursals', 'codeanexo', $this->sucursal->id, true),
            ],
            'sucursal.default' => [
                'required', 'integer', 'min:0', 'max:1',
                new DefaultValue('sucursals', 'default', $this->sucursal->id, true)
            ]
        ];
    }

    public function mount(Sucursal $sucursal)
    {
        $this->sucursal = $sucursal;
    }

    public function render()
    {
        $ubigeos = Ubigeo::orderBy('region', 'asc')->orderBy('provincia', 'asc')->orderBy('distrito', 'asc')->get();
        $typesucursals = Typesucursal::orderBy('name', 'asc')->get();
        return view('livewire.admin.sucursales.show-sucursal', compact('ubigeos', 'typesucursals'));
    }

    public function update()
    {

        $this->authorize('admin.administracion.sucursales.edit');
        $this->sucursal->codeanexo = trim($this->sucursal->codeanexo);
        $this->sucursal->name = trim($this->sucursal->name);
        $this->sucursal->direccion = trim($this->sucursal->direccion);
        $this->sucursal->default = $this->sucursal->default == 1 ? 1 : 0;
        $validateData = $this->validate();

        try {
            DB::beginTransaction();
            $this->sucursal->save();
            DB::commit();
            $this->resetValidation();
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
