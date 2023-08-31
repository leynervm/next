<?php

namespace Modules\Soporte\Http\Livewire\Condiciones;

use App\Rules\CampoUnique;
use App\Rules\Letter;
use Livewire\Component;
use Modules\Soporte\Entities\Condition;


class CreateCondicion extends Component
{

    public $open = false;
    public $name;
    public $flagpagable = 0;

    protected function rules()
    {
        return [
            'name' => [
                'required', 'min:3', 'max:100', new Letter,
                new CampoUnique('conditions', 'name'),
            ],
            'flagpagable' => ['nullable', 'integer']
        ];
    }

    public function render()
    {
        return view('soporte::livewire.condiciones.create-condicion');
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->resetValidation();
            $this->reset(['name', 'flagpagable']);
        }
    }

    public function save()
    {
        $this->name = trim($this->name);
        $this->validate();

        $condition = Condition::where('name', mb_strtoupper($this->name, "UTF-8"))
            ->where('delete', 1)->first();

        if ($condition) {
            $condition->delete = 0;
            $condition->flagpagable =  $this->flagpagable;
            $condition->save();
        } else {
            Condition::create([
                'name' => $this->name,
                'flagpagable' => $this->flagpagable,
            ]);
        }
        $this->emitTo('soporte::condiciones.show-condiciones', 'render');
        $this->reset('name', 'flagpagable', 'open');
    }
}
