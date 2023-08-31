<?php

namespace Modules\Soporte\Http\Livewire\Status;

use App\Rules\CampoUnique;
use App\Rules\DefaultValue;
use App\Rules\Letter;
use App\Rules\ValidateColor;
use Livewire\Component;
use Modules\Soporte\Entities\Estate;

class CreateStatus extends Component
{

    public $open = false;
    public $finish = 0;
    public $default = 0;
    public $color = "#000000";
    public $name, $descripcion;

    protected function rules()
    {
        return [
            'name' => [
                'required', 'min:3', 'max:100', new Letter,
                new CampoUnique('estates', 'name')
            ],
            'descripcion' => [
                'nullable'
            ],
            'color' => [
                'required', new ValidateColor
            ],
            'finish' => [
                'nullable', 'max:1', new DefaultValue('estates', 'finish')
            ],
            'default' => [
                'nullable', 'max:1', new DefaultValue('estates', 'default')
            ],
        ];
    }

    public function render()
    {
        return view('soporte::livewire.status.create-status');
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->resetValidation();
            $this->reset(['name', 'descripcion', 'color', 'finish', 'default']);
        }
    }

    public function save()
    {
        $this->name = trim($this->name);
        $this->descripcion = trim($this->descripcion);
        $this->color = trim($this->color);
        $this->validate();

        $status = Estate::where('name', mb_strtoupper($this->name, "UTF-8"))
            ->where('delete', 1)->first();

        if ($status) {
            $status->delete = 0;
            $status->descripcion = $this->descripcion;
            $status->color = $this->color;
            $status->finish = $this->finish;
            $status->default = $this->default;
            $status->save();
        } else {
            Estate::create([
                'name' => $this->name,
                'descripcion' => $this->descripcion,
                'color' => $this->color,
                'finish' => $this->finish,
                'default' => $this->default,
            ]);
        }
        $this->emitTo('soporte::status.show-status', 'render');
        $this->reset('name', 'descripcion', 'color', 'finish', 'default', 'open');
    }
}
