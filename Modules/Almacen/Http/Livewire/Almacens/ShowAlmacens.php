<?php

namespace Modules\Almacen\Http\Livewire\Almacens;

use App\Rules\CampoUnique;
use App\Rules\Letter;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Almacen\Entities\Almacen;

class ShowAlmacens extends Component
{

    use WithPagination;

    public $open = false;
    public $almacen;

    protected $listeners = ['render', 'delete'];

    protected function rules()
    {
        return [
            'almacen.name' => [
                'required', 'min:3', 'max:100', new Letter,
                new CampoUnique('almacens', 'name', $this->almacen->id, true),
            ]
        ];
    }

    public function mount()
    {
        $this->almacen = new Almacen();
    }

    public function render()
    {
        $almacens = Almacen::orderBy('name', 'asc')->paginate();
        return view('almacen::livewire.almacens.show-almacens', compact('almacens'));
    }

    public function edit(Almacen $almacen)
    {
        $this->almacen = $almacen;
        $this->open = true;
    }

    public function update()
    {
        $this->almacen->name = trim($this->almacen->name);
        $this->validate();
        $this->almacen->save();
        $this->reset(['open']);
    }

    public function confirmDelete(Almacen $almacen)
    {
        $this->dispatchBrowserEvent('almacens.confirmDelete', $almacen);
    }

    public function delete(Almacen $almacen)
    {
        $almacen->deleteOrFail();
        $this->dispatchBrowserEvent('deleted');
    }
}
