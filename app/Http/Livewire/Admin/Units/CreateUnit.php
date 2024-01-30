<?php

namespace App\Http\Livewire\Admin\Units;

use App\Models\Unit;
use App\Rules\CampoUnique;
use App\Rules\Letter;
use Livewire\Component;

class CreateUnit extends Component
{

    public $open = false;
    public $name, $abreviatura, $code;

    protected function rules()
    {
        return [
            'name' => [
                'required', 'min:2', 'max:100', new CampoUnique('units', 'name', null, true),
            ],
            // 'abreviatura' => [
            //     'required', 'min:2', 'max:4'
            // ],
            'code' => [
                'required', 'min:1', 'max:4', new CampoUnique('units', 'code', null, true)
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
            $this->resetValidation();
            $this->reset('name', 'abreviatura', 'code');
        }
    }

    public function save()
    {
        $this->name = trim($this->name);
        // $this->abreviatura = trim($this->abreviatura);
        $this->code = trim($this->code);
        $this->validate();

        $unit = Unit::withTrashed()
            ->where('name', mb_strtoupper($this->name, "UTF-8"))->first();

        if ($unit) {
            // $unit->delete = 0;
            // $unit->abreviatura = $this->abreviatura;
            $unit->code = $this->code;
            $unit->restore();
        } else {
            Unit::create([
                'name' => $this->name,
                // 'abreviatura' => $this->abreviatura,
                'code' => $this->code,
            ]);
        }

        $this->emitTo('admin.units.show-units', 'render');
        $this->reset();
    }
}
