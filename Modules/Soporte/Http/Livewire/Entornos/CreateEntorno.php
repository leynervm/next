<?php

namespace Modules\Soporte\Http\Livewire\Entornos;

use App\Rules\CampoUnique;
use App\Rules\Letter;
use Livewire\Component;
use Modules\Soporte\Entities\Entorno;

class CreateEntorno extends Component
{

    public $open = false;
    public $name;
    public $requiredirection = 0;
    public $default = 0;

    protected function rules()
    {
        return [
            'name' => [
                'required', 'min:3', 'max:100', new Letter,
                new CampoUnique('entornos', 'name'),
                'requiredirection' => ['nullable', 'max:1'],
                'default' => ['nullable', 'max:1']
            ]
        ];
    }

    public function render()
    {
        return view('soporte::livewire.entornos.create-entorno');
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->resetValidation();
            $this->reset(['name', 'requiredirection', 'default']);
        }
    }

    public function save()
    {
        $this->name = trim($this->name);
        $this->validate();

        $entorno = Entorno::where('name', mb_strtoupper($this->name, "UTF-8"))
            ->where('delete', 1)->first();

        if ($entorno) {
            $entorno->delete = 0;
            $entorno->requiredirection = $this->requiredirection;
            $entorno->default = $this->default;
            $entorno->save();
        } else {
            Entorno::create([
                'name' => $this->name,
                'requiredirection' => $this->requiredirection,
                'default' => $this->default,
            ]);
        }
        $this->emitTo('soporte::entornos.show-entornos', 'render');
        $this->reset(['name', 'requiredirection', 'default', 'open']);
    }
}
