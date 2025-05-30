<?php

namespace App\Http\Livewire\Modules\Soporte\Conditions;

use App\Rules\CampoUnique;
use App\Rules\Letter;
use Livewire\Component;
use Modules\Soporte\Entities\Condition;

class CreateCondition extends Component
{

    public $open = false;
    public $name;
    public $flagpagable = 0;

    protected function rules()
    {
        return [
            'name' => [
                'required',
                'min:3',
                'max:100',
                new Letter,
                new CampoUnique('conditions', 'name', null, true),
            ],
            'flagpagable' => ['required', 'integer', 'min:0', 'max:1']
        ];
    }

    public function render()
    {
        return view('livewire.modules.soporte.conditions.create-condition');
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->resetValidation();
            $this->reset(['name', 'flagpagable']);
        }
    }

    public function save($closemodal = false)
    {
        $this->name = mb_strtoupper(trim($this->name), "UTF-8");
        $this->flagpagable =  $this->flagpagable > 0 ? 1 : 0;
        $this->validate();
        $condition = Condition::withTrashed()->where('name', $this->name)->first();

        if ($condition) {
            if ($condition->trashed()) {
                $condition->restore();
            }
            $condition->flagpagable =  $this->flagpagable;
            $condition->save();
        } else {
            Condition::create([
                'name' => $this->name,
                'flagpagable' => $this->flagpagable,
            ]);
        }
        $this->emitTo('modules.soporte.conditions.show-conditions', 'render');
        if ($closemodal) {
            $this->reset();
        } else {
            $this->resetExcept('open');
        }
    }
}
