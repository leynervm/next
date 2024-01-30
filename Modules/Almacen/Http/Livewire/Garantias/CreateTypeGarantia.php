<?php

namespace Modules\Almacen\Http\Livewire\Garantias;

use App\Rules\CampoUnique;
use App\Rules\Letter;
use Livewire\Component;
use App\Models\Typegarantia;

class CreateTypeGarantia extends Component
{

    public $open = false;
    public $name, $datecode, $time;

    protected function rules()
    {
        return [
            'name' => [
                'required', 'min:3', 'max:100', new CampoUnique('typegarantias', 'name', null, false),
            ],
            'datecode' => [
                'required', 'string', 'min:2', 'max:4',
            ],
            'time' => [
                'required', 'integer', 'min:1', 'max:100'
            ]
        ];
    }


    public function render()
    {
        return view('almacen::livewire.garantias.create-type-garantia');
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->resetValidation();
            $this->reset('name', 'datecode', 'time', 'open');
        }
    }

    public function save()
    {
        $this->name = trim($this->name);
        $this->datecode = trim($this->datecode);
        $this->time = trim($this->time);
        $this->validate();

        Typegarantia::create([
            'name' => $this->name,
            'datecode' => $this->datecode,
            'time' => $this->time
        ]);

        $this->emitTo('almacen::garantias.show-type-garantias', 'render');
        $this->reset('name', 'datecode', 'time', 'open');
    }
}
