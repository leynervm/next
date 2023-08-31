<?php

namespace Modules\Almacen\Http\Livewire\Garantias;

use App\Rules\CampoUnique;
use App\Rules\Letter;
use Livewire\Component;
use Modules\Almacen\Entities\Typegarantia;
use Livewire\WithPagination;

class ShowTypeGarantias extends Component
{

    use WithPagination;

    public $open = false;
    public $typegarantia;

    protected $listeners = ['render', 'delete'];

    protected function rules()
    {
        return [
            'typegarantia.name' => [
                'required', 'min:3', 'max:100', new Letter,
                new CampoUnique('typegarantias', 'name', $this->typegarantia->id, true),
            ],
            'typegarantia.timestring' => [
                'required', 'min:3', 'max:100', 'string'
            ],
            'typegarantia.time' => [
                'required', 'integer', 'min:1'
            ]
        ];
    }

    public function mount()
    {
        $this->typegarantia = new Typegarantia();
    }

    public function render()
    {
        $typegarantias = Typegarantia::orderBy('name', 'asc')->paginate();
        return view('almacen::livewire.garantias.show-type-garantias', compact('typegarantias'));
    }

    public function edit(Typegarantia $typegarantia)
    {
        $this->typegarantia = $typegarantia;
        $this->open = true;
    }

    public function update()
    {
        $this->typegarantia->name = trim($this->typegarantia->name);
        $this->typegarantia->timestring = trim($this->typegarantia->timestring);
        $this->typegarantia->time = $this->typegarantia->time;
        $this->validate();
        $this->typegarantia->save();
        $this->reset(['open']);
    }

    public function confirmDelete(Typegarantia $typegarantia)
    {
        $this->dispatchBrowserEvent('typegarantias.confirmDelete', $typegarantia);
    }

    public function delete(Typegarantia $typegarantia)
    {
        $typegarantia->deleteOrFail();
        $this->dispatchBrowserEvent('deleted');
    }
}
