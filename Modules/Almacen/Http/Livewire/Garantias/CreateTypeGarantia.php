<?php

namespace Modules\Almacen\Http\Livewire\Garantias;

use App\Rules\CampoUnique;
use App\Rules\Letter;
use Livewire\Component;
use Modules\Almacen\Entities\Typegarantia;

class CreateTypeGarantia extends Component
{

    public $open = false;
    public $name, $timestring, $time;

    protected function rules()
    {
        return [
            'name' => [
                'required', 'min:3', 'max:100', new Letter,
                new CampoUnique('typegarantias', 'name', null, true),
            ],
            'timestring' => [
                'required', 'min:3', 'max:100', 'string'
            ],
            'time' => [
                'required', 'integer', 'min:1'
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
            $this->reset('name', 'timestring', 'time', 'open');
        }
    }

    public function save()
    {
        $this->name = trim($this->name);
        $this->timestring = trim($this->timestring);
        $this->time = trim($this->time);
        $this->validate();

        $typegarantia = Typegarantia::withTrashed()
            ->where('name', mb_strtoupper($this->name, "UTF-8"))->first();

        if ($typegarantia) {
            $typegarantia->timestring =  $this->timestring;
            $typegarantia->time =  $this->time;
            $typegarantia->restore();
        } else {
            Typegarantia::create([
                'name' => $this->name,
                'timestring' => $this->timestring,
                'time' => $this->time,
                'datecode' => 'MM'
            ]);
        }

        $this->emitTo('almacen::garantias.show-type-garantias', 'render');
        $this->reset('name', 'timestring', 'time', 'open');
    }
}
