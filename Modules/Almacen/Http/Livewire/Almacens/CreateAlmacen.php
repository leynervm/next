<?php

namespace Modules\Almacen\Http\Livewire\Almacens;

use App\Models\Sucursal;
use App\Rules\CampoUnique;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use App\Models\Almacen;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CreateAlmacen extends Component
{

    use AuthorizesRequests;

    public $open = false;
    public $name, $sucursal_id;
    public $default = 0;

    protected function rules()
    {
        return [
            'name' => [
                'required', 'min:3', 'max:100',
                new CampoUnique('almacens', 'name', null, true),
            ],
            'sucursal_id' => [
                'required', 'integer', 'min:1', 'exists:sucursals,id'
            ],
            'default' => [
                'required', 'integer', 'min:0', 'max:1',
                $this->default ? Rule::unique('almacens', 'default')
                    ->where('sucursal_id', $this->sucursal_id) : ''

            ],
        ];
    }

    public function render()
    {
        $sucursales = Sucursal::Actives()->orderBy('id', 'asc')->get();
        return view('almacen::livewire.almacens.create-almacen', compact('sucursales'));
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->authorize('admin.almacen.create');
            $this->resetValidation();
            $this->reset(['name', 'default', 'sucursal_id', 'open']);
        }
    }

    public function save()
    {
        $this->authorize('admin.almacen.create');
        $this->name = trim($this->name);
        $this->default = $this->default == 1 ? 1 : 0;
        $validateData = $this->validate();

        DB::beginTransaction();
        try {

            $almacen = Almacen::withTrashed()
                ->where('name', mb_strtoupper($this->name, "UTF-8"))
                ->where('sucursal_id', $this->sucursal_id)
                ->first();

            if ($almacen) {
                $almacen->restore();
            } else {
                Almacen::create($validateData);
            }

            DB::commit();
            $this->emitTo('almacen::almacens.show-almacens', 'render');
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
        $this->dispatchBrowserEvent('render-create-almacen');
    }
}
