<?php

namespace App\Http\Livewire\Admin\Units;

use App\Models\Unit;
use App\Rules\CampoUnique;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class CreateUnit extends Component
{

    use AuthorizesRequests;

    public $open = false;
    public $name, $abreviatura, $code;

    protected function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'min:2',
                'max:100',
                new CampoUnique('units', 'name', null, true),
            ],
            'code' => [
                'required',
                'string',
                'min:1',
                'max:4',
                new CampoUnique('units', 'code', null, true)
            ]
        ];
    }

    public function render()
    {
        return view('livewire.admin.units.create-unit');
    }

    public function updatingOpen()
    {
        $this->authorize('admin.administracion.units.create');
        if ($this->open == false) {
            $this->resetValidation();
            $this->reset('name', 'abreviatura', 'code');
        }
    }

    public function save($closemodal = false)
    {

        $this->authorize('admin.administracion.units.create');
        $this->name = mb_strtoupper(trim($this->name), "UTF-8");
        $this->code = mb_strtoupper(trim($this->code), "UTF-8");
        $this->validate();
        $unit = Unit::onlyTrashed()->where('name', $this->name)->first();

        if ($unit) {
            $unit->code = $this->code;
            $unit->restore();
        } else {
            Unit::create([
                'name' => $this->name,
                'code' => $this->code,
            ]);
        }

        $this->emitTo('admin.units.show-units', 'render');
        $this->dispatchBrowserEvent('created');
        if ($closemodal) {
            $this->reset();
        } else {
            $this->resetExcept(['open']);
        }
    }
}
