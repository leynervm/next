<?php

namespace Modules\Soporte\Http\Livewire\Tiponotificaciones;

use App\Rules\CampoUnique;
use App\Rules\Letter;
use Livewire\Component;
use Modules\Soporte\Entities\Typenotification;
use Livewire\WithPagination;

class ShowTipoNotificaciones extends Component
{

    use WithPagination;

    public $open = false;
    public $typenotification;

    protected $listeners = ['render', 'deleteTipoNotificacion' => 'delete'];

    protected function rules()
    {
        return [
            'typenotification.name' => [
                'required', 'min:3', 'max:100', new Letter,
                new CampoUnique('typenotifications', 'name', $this->typenotification->id),
            ]
        ];
    }

    public function render()
    {
        $typenotifications = Typenotification::orderBy('name', 'asc')->paginate();
        return view('soporte::livewire.tiponotificaciones.show-tipo-notificaciones', compact('typenotifications'));
    }

    public function updatedOpen()
    {
        if ($this->open == false) {
            $this->resetValidation();
        }
    }

    public function edit(Typenotification $typenotification)
    {
        $this->typenotification = $typenotification;
        $this->open = true;
    }

    public function update()
    {
        $this->typenotification->name = trim($this->typenotification->name);
        $this->validate();
        $this->typenotification->save();
        $this->reset();
    }

    public function confirmDelete(Typenotification $typenotification)
    {
        $this->dispatchBrowserEvent('soporte::tiponotificaciones.confirmDelete', $typenotification);
    }

    public function delete(Typenotification $typenotification)
    {
        $typenotification->delete = 1;
        $typenotification->save();
        $this->dispatchBrowserEvent('soporte::tiponotificaciones.deleted');
    }
}
