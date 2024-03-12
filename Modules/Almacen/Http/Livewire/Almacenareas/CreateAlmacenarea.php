<?php

namespace Modules\Almacen\Http\Livewire\Almacenareas;

use App\Models\Almacenarea;
use App\Rules\CampoUnique;
use App\Rules\Letter;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class CreateAlmacenarea extends Component
{

    use AuthorizesRequests;

    public $open = false;
    public $name;

    protected function rules()
    {
        return [
            'name' => [
                'required', 'min:1', 'max:100',
                new CampoUnique('almacenareas', 'name', null, true),
            ]
        ];
    }

    public function render()
    {
        return view('almacen::livewire.almacenareas.create-almacenarea');
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->authorize('admin.almacen.almacenareas.create');
            $this->resetValidation();
            $this->reset('name', 'open');
        }
    }

    public function save()
    {

        $this->authorize('admin.almacen.almacenareas.create');
        $this->name = trim($this->name);
        $this->validate();

        $almacenarea = Almacenarea::withTrashed()
            ->where('name', mb_strtoupper($this->name, "UTF-8"))->first();

        if ($almacenarea) {
            $almacenarea->restore();
        } else {
            Almacenarea::create([
                'name' => $this->name
            ]);
        }

        $this->emitTo('almacen::almacenareas.show-almacenareas', 'render');
        $this->reset();
        $this->dispatchBrowserEvent('created');
    }
}
