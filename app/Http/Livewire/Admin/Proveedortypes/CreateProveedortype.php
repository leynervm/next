<?php

namespace App\Http\Livewire\Admin\Proveedortypes;

use App\Models\Proveedortype;
use App\Rules\CampoUnique;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateProveedortype extends Component
{

    use AuthorizesRequests;

    public $open = false;
    public $name;

    protected function rules()
    {
        return [
            'name' => [
                'required', 'min:3', 'max:100',
                new CampoUnique('proveedortypes', 'name', null, true)
            ]
        ];
    }

    public function render()
    {
        return view('livewire.admin.proveedortypes.create-proveedortype');
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->authorize('admin.proveedores.tipos.create');
            $this->resetValidation();
            $this->reset();
        }
    }

    public function save($closemodal = false)
    {
        $this->authorize('admin.proveedores.tipos.create');
        $this->name = trim($this->name);
        $this->validate();

        try {
            DB::beginTransaction();
            $proveedortype = Proveedortype::withTrashed()
                ->where('name', mb_strtoupper($this->name, "UTF-8"))->first();

            if ($proveedortype) {
                $proveedortype->restore();
            } else {
                $proveedortype = Proveedortype::create([
                    'name' => $this->name,
                ]);
            }

            DB::commit();
            $this->emitTo('admin.proveedortypes.show-proveedortypes', 'render');
            if ($closemodal) {
                $this->reset();
            } else {
                $this->resetExcept(['open']);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
