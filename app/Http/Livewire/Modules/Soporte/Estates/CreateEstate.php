<?php

namespace App\Http\Livewire\Modules\Soporte\Estates;

use App\Rules\CampoUnique;
use App\Rules\DefaultValue;
use App\Rules\Letter;
use App\Rules\ValidateColor;
use Livewire\Component;
use Modules\Soporte\Entities\Estate;

class CreateEstate extends Component
{

    public $open = false;
    public $finish = 0;
    public $default = 0;
    public $color = "#000000";
    public $name, $descripcion;

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:100', new CampoUnique('estates', 'name', null, true)],
            'descripcion' => ['nullable', 'string', 'min:3'],
            'color' => ['required', new ValidateColor],
            'finish' => ['nullable', 'min:0', 'max:1', new DefaultValue('estates', 'finish')],
            'default' => ['nullable', 'min:0', 'max:1', new DefaultValue('estates', 'default')],
        ];
    }

    public function render()
    {
        return view('livewire.modules.soporte.estates.create-estate');
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->resetValidation();
            $this->reset(['name', 'descripcion', 'color', 'finish', 'default']);
        }
    }

    public function save($closemodal = false)
    {
        $this->name = toStrUppercase($this->name);
        $this->descripcion = toStrUppercase($this->descripcion);
        $this->color = trim($this->color);
        $this->validate();
        $estate = Estate::where('name', $this->name)->withTrashed()->first();

        if ($estate) {
            $estate->descripcion = $this->descripcion;
            $estate->color = $this->color;
            $estate->finish = $this->finish;
            $estate->save();
        } else {
            $estate = Estate::create([
                'name' => $this->name,
                'descripcion' => $this->descripcion,
                'color' => $this->color,
                'finish' => $this->finish,
            ]);
        }
        $this->emitTo('modules.soporte.estates.show-estates', 'render');
        if ($closemodal) {
            $this->reset();
        } else {
            $this->resetExcept(['open']);
        }
    }
}
