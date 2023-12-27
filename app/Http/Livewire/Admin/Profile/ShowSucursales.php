<?php

namespace App\Http\Livewire\Admin\Profile;

use App\Models\Sucursal;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Modules\Almacen\Entities\Almacen;

class ShowSucursales extends Component
{

    public $sucursal_id, $almacen_id;
    public $almacens = [];

    public $default;

    protected function rules()
    {
        return [
            'sucursal_id' => ['required', 'integer', 'min:1', 'exists:sucursals,id'],
            'almacen_id' => ['nullable', 'integer', 'min:1', 'exists:almacens,id']
        ];
    }

    public function mount()
    {
        
        $sucursal = auth()->user()->sucursalDefault()->with('almacens')->first();
        // $this->default = $sucursal;
        if ($sucursal) {
            $this->sucursal_id =  $sucursal->id;       
            $this->almacen_id = $sucursal->pivot->almacen_id;
            $this->almacens = $sucursal->almacens;
        }
    }


    public function render()
    {
        $sucursals = auth()->user()->sucursals;
        return view('livewire.admin.profile.show-sucursales', compact('sucursals'));
    }


    public function updatedSucursalId($value)
    {
        $this->reset(['almacens', 'almacen_id']);
        if ($value) {
            $this->almacens = Sucursal::find($value)->almacens;
        }
    }

    public function updateProfileSucursal()
    {

        $this->validate();
        try {
            DB::beginTransaction();

            auth()->user()->sucursals()->each(function ($sucursal) {
                auth()->user()->sucursals()->updateExistingPivot($sucursal->id, [
                    'default' => $this->sucursal_id == $sucursal->id ? 1 : 0,
                    'almacen_id' => $this->sucursal_id == $sucursal->id ? null : null,
                ]);
            });

            auth()->user()->sucursals()->updateExistingPivot($this->sucursal_id, [
                'almacen_id' => $this->almacen_id ?? null
            ]);

            DB::commit();
            $this->dispatchBrowserEvent('updated');
            // return redirect()->route('admin.proveedores');
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
        $this->dispatchBrowserEvent('render-show-sucursals');
    }
}
