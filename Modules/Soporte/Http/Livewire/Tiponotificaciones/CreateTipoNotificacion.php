<?php

namespace Modules\Soporte\Http\Livewire\Tiponotificaciones;

use App\Rules\CampoUnique;
use App\Rules\Letter;
use Livewire\Component;
use Modules\Soporte\Entities\Typenotification;

class CreateTipoNotificacion extends Component
{

    public $open = false;
    public $name;

    protected function rules()
    {
        return [
            'name' => [
                'required', 'min:3', 'max:100', new Letter,
                new CampoUnique('typenotifications', 'name'),
            ]
        ];
    }

    public function render()
    {
        return view('soporte::livewire.tiponotificaciones.create-tipo-notificacion');
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->resetValidation();
            $this->reset(['name']);
        }
    }

    public function save()
    {
        $this->name = trim($this->name);
        $this->validate();

        $typenotificaction = Typenotification::where('name', mb_strtoupper($this->name, "UTF-8"))
            ->where('delete', 1)->first();

        if ($typenotificaction) {
            $typenotificaction->delete = 0;
            $typenotificaction->save();
        } else {
            Typenotification::create([
                'name' => $this->name,
            ]);
        }
        $this->emitTo('soporte::tiponotificaciones.show-tipo-notificaciones', 'render');
        $this->reset(['name', 'open']);
    }
}
