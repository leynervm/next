<?php

namespace App\Http\Livewire\Admin\Almacens;

use App\Models\Almacen;
use App\Rules\CampoUnique;
use App\Rules\DefaultValue;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateAlmacen extends Component
{

    use AuthorizesRequests;

    public $open = false;
    public $name;
    public $default = 0;

    protected function rules()
    {
        return [
            'name' => [
                'required', 'string', 'min:3', 'max:100',
                new CampoUnique('almacens', 'name', null, true)
            ],
            'default' => [
                'required', 'integer', 'min:0', 'max:1',
                new DefaultValue('almacens', 'default', null, true)
            ],
        ];
    }

    public function render()
    {
        return view('livewire.admin.almacens.create-almacen');
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->authorize('admin.almacen.create');
            $this->reset();
            $this->resetValidation();
        }
    }

    public function save()
    {

        $this->authorize('admin.almacen.create');
        $this->name = trim($this->name);
        $this->default = $this->default == '1' ? 1 : 0;
        $validateData = $this->validate();
        DB::beginTransaction();
        try {

            $almacen = Almacen::onlyTrashed()
                ->whereRaw('UPPER(name) = ?', [mb_strtoupper($this->name, "UTF-8")])
                ->first();

            if ($almacen) {
                $almacen->restore();
            } else {
                Almacen::create($validateData);
            }
            DB::commit();
            $this->dispatchBrowserEvent('created');
            $this->emitTo('admin.almacens.show-almacens', 'render');
            $this->resetValidation();
            $this->reset();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
