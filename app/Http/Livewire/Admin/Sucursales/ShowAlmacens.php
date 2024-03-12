<?php

namespace App\Http\Livewire\Admin\Sucursales;

use App\Models\Sucursal;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use App\Models\Almacen;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ShowAlmacens extends Component
{

    use AuthorizesRequests;

    public $open = false;
    public $sucursal, $almacen_id;

    protected function rules()
    {
        return [
            'almacen_id' => [
                'required', 'integer', 'min:1',
                Rule::unique('almacen_sucursal')->where('sucursal_id', $this->sucursal->id)
            ],
            'sucursal.id' => ['required', 'integer', 'min:1', 'exists:sucursals,id']
        ];
    }

    public function mount(Sucursal $sucursal)
    {
        $this->sucursal = $sucursal;
    }

    public function render()
    {
        $almacens = Almacen::whereDoesntHave('sucursals', function ($query) {
            $query->where('sucursal_id', $this->sucursal->id);
        })->orderBy('default', 'desc')->orderBy('name', 'asc')->get();
        return view('livewire.admin.sucursales.show-almacens', compact('almacens'));
    }

    public function save()
    {

        $this->authorize('admin.administracion.sucursales.almacenes.edit');
        $this->validate();
        DB::beginTransaction();
        try {
            $this->sucursal->almacens()->attach($this->almacen_id);
            DB::commit();
            $this->reset(['almacen_id']);
            $this->resetValidation();
            $this->sucursal->refresh();
            $this->dispatchBrowserEvent('created');
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

        $this->authorize('admin.administracion.sucursales.almacenes.edit');
        DB::beginTransaction();
        try {
            $this->sucursal->almacens()->detach($almacen->id);
            DB::commit();
            $this->sucursal->refresh();
            $this->dispatchBrowserEvent('deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
