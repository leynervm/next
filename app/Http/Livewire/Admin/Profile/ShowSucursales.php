<?php

namespace App\Http\Livewire\Admin\Profile;

use App\Models\Sucursal;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ShowSucursales extends Component
{

    public $sucursal_id, $default;
    // public $almacen_id = '';
    // public $almacens = [];

    protected function rules()
    {
        return [
            'sucursal_id' => ['required', 'integer', 'min:1', 'exists:sucursals,id'],
            // 'almacen_id' => ['nullable', 'integer', 'min:1', 'exists:almacens,id']
        ];
    }

    public function mount()
    {
        if (auth()->user()->sucursal) {
            $this->sucursal_id = auth()->user()->sucursal_id;
        }
    }


    public function render()
    {
        $sucursals = Sucursal::orderBy('id', 'asc')->get();
        return view('livewire.admin.profile.show-sucursales', compact('sucursals'));
    }


    public function update()
    {
        if (auth()->user()->isAdmin()) {
            $this->validate();
            try {
                DB::beginTransaction();
                auth()->user()->sucursal_id = $this->sucursal_id;
                auth()->user()->save();
                if (auth()->user()->employer) {
                    auth()->user()->employer->sucursal_id = $this->sucursal_id;
                    auth()->user()->employer->save();
                }
                DB::commit();
                return redirect()->route('profile.show');
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
}
