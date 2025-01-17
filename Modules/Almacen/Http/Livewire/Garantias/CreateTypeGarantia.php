<?php

namespace Modules\Almacen\Http\Livewire\Garantias;

use App\Rules\CampoUnique;
use App\Rules\Letter;
use Livewire\Component;
use App\Models\Typegarantia;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CreateTypeGarantia extends Component
{

    use AuthorizesRequests;
    public $open = false;
    public $name, $datecode, $timewarranty;


    protected function rules()
    {
        return [
            'name' => [
                'required',
                'min:3',
                'max:100',
                new CampoUnique('typegarantias', 'name', null),
            ],
            'datecode' => ['required', 'string', 'min:2', 'max:4',],
            'timewarranty' => ['required', 'integer', 'min:0', 'max:100']
        ];
    }


    public function render()
    {
        return view('almacen::livewire.garantias.create-type-garantia');
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->authorize('admin.almacen.typegarantias.create');
            $this->resetValidation();
            $this->reset();
        }
    }

    public function save($closemodal = false)
    {
        $this->authorize('admin.almacen.typegarantias.create');
        $this->name = trim($this->name);
        $this->datecode = trim($this->datecode);
        $this->timewarranty = trim($this->timewarranty);
        $this->validate();

        Typegarantia::create([
            'name' => $this->name,
            'datecode' => $this->datecode,
            'time' => $this->timewarranty
        ]);

        $this->emitTo('almacen::garantias.show-type-garantias', 'render');
        if ($closemodal) {
            $this->reset();
        } else {
            $this->resetExcept(['open']);
        }
        $this->resetValidation();
        $this->dispatchBrowserEvent('created');
    }
}
