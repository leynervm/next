<?php

namespace Modules\Almacen\Http\Livewire\Estantes;

use App\Rules\CampoUnique;
use App\Rules\Letter;
use Livewire\Component;
use Modules\Almacen\Entities\Estante;

class CreateEstante extends Component
{

    public $open = false;
    public $name;

    protected function rules()
    {
        return [
            'name' => [
                'required', 'min:1', 'max:100', new Letter,
                new CampoUnique('estantes', 'name', null, true),
            ]
        ];
    }

    public function render()
    {
        return view('almacen::livewire.estantes.create-estante');
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->resetValidation();
            $this->reset('name', 'open');
        }
    }

    public function save()
    {
        $this->name = trim($this->name);
        $this->validate();

        $estante = Estante::withTrashed()
            ->where('name', mb_strtoupper($this->name, "UTF-8"))->first();

        if ($estante) {
            $estante->restore();
        } else {
            Estante::create([
                'name' => $this->name
            ]);
        }

        $this->emitTo('almacen::estantes.show-estantes', 'render');
        $this->reset('name', 'open');
    }
}
