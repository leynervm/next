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
                'required', 'min:2', 'max:100',
                new CampoUnique('units', 'name', null, true),
            ],
            'code' => [
                'required', 'min:1', 'max:4',
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
        if ($this->open == false) {
            $this->authorize('admin.administracion.units.create');
            $this->resetValidation();
            $this->reset('name', 'abreviatura', 'code');
        }
    }

    public function save($closemodal = false)
    {

        $this->authorize('admin.administracion.units.create');
        $this->name = trim($this->name);
        $this->code = trim($this->code);
        $this->validate();

        $unit = Unit::withTrashed()
            ->whereRaw('UPPER(name) = ?', [mb_strtoupper($this->name, "UTF-8")])->first();

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
