<?php

namespace App\Http\Livewire\Admin\Profile;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ShowSucursales extends Component
{

    public $sucursal_id, $almacen_id;
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
        $this->sucursal_id = auth()->user()->employer->sucursal_id;
        // $this->almacen_id = auth()->user()->almacen_id;
    }


    public function render()
    {
        $almacens = auth()->user()->employer->sucursal->almacens;
        return view('livewire.admin.profile.show-sucursales', compact('almacens'));
    }


    public function update()
    {
        $this->almacen_id = empty($this->almacen_id) ? null : $this->almacen_id;
        $this->validate();
        try {
            DB::beginTransaction();
            auth()->user()->almacen_id = $this->almacen_id;
            auth()->user()->save();
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
