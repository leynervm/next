<?php

namespace Modules\Almacen\Http\Livewire\Almacenareas;

use App\Models\Almacenarea;
use App\Rules\CampoUnique;
use App\Rules\Letter;
use Livewire\Component;
use Livewire\WithPagination;

class ShowAlmacenareas extends Component
{

    use WithPagination;

    public $open = false;
    public $almacenarea;

    protected $listeners = ['render', 'delete'];

    protected function rules()
    {
        return [
            'almacenarea.name' => [
                'required', 'min:1', 'max:100', new Letter,
                new CampoUnique('almacenareas', 'name', $this->almacenarea->id, true),
            ]
        ];
    }

    public function mount()
    {
        $this->almacenarea = new Almacenarea();
    }

    public function render()
    {
        $almacenareas = Almacenarea::orderBy('name', 'asc')->paginate(15);
        return view('almacen::livewire.almacenareas.show-almacenareas', compact('almacenareas'));
    }

    public function edit(Almacenarea $almacenarea)
    {
        $this->almacenarea = $almacenarea;
        $this->open = true;
    }

    public function update()
    {
        $this->almacenarea->name = trim($this->almacenarea->name);
        $this->validate();
        $this->almacenarea->save();
        $this->reset(['open']);
    }

    public function confirmDelete(Almacenarea $almacenarea)
    {
        $this->dispatchBrowserEvent('almacenareas.confirmDelete', $almacenarea);
    }

    public function delete(Almacenarea $almacenarea)
    {
        $almacenarea->deleteOrFail();
        $this->dispatchBrowserEvent('deleted');
    }
}
