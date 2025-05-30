<?php

namespace App\Http\Livewire\Modules\Soporte\Entornos;

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
                'required',
                'min:3',
                'max:100',
                new Letter,
                new CampoUnique('entornos', 'name', null, true),
            ],
            'requiredirection' => ['required', 'integer', 'min:0', 'max:1'],
            'default' => ['nullable', 'max:1']
        ];
    }

    public function render()
    {
        return view('livewire.modules.soporte.entornos.create-entorno');
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->resetValidation();
            $this->reset(['name', 'requiredirection', 'default']);
        }
    }

    public function save($closemodal = false)
    {
        $this->name = mb_strtoupper(trim($this->name), "UTF-8");
        $this->requiredirection = $this->requiredirection > 0 ? 1 : 0;
        $this->validate();
        $entorno = Entorno::withTrashed()->where('name',  $this->name)->first();

        if ($entorno) {
            if ($entorno->trashed()) {
                $entorno->restore();
            }
            $entorno->requiredirection = $this->requiredirection;
            $entorno->save();
        } else {
            Entorno::create([
                'name' => $this->name,
                'requiredirection' => $this->requiredirection,
            ]);
        }
        $this->emitTo('modules.soporte.entornos.show-entornos', 'render');
        $this->dispatchBrowserEvent('created');
        if ($closemodal) {
            $this->reset();
        } else {
            $this->resetExcept(['open']);
        }
    }
}
