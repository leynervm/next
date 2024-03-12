<?php

namespace Modules\Almacen\Http\Livewire\Almacenareas;

use App\Models\Almacenarea;
use App\Rules\CampoUnique;
use App\Rules\Letter;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class ShowAlmacenareas extends Component
{

    use WithPagination, AuthorizesRequests;

    public $open = false;
    public $almacenarea;

    protected $listeners = ['render'];

    protected function rules()
    {
        return [
            'almacenarea.name' => [
                'required', 'min:1', 'max:100',
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
        $this->authorize('admin.almacen.almacenareas.edit');
        $this->almacenarea = $almacenarea;
        $this->open = true;
    }

    public function update()
    {
        $this->authorize('admin.almacen.almacenareas.edit');
        $this->almacenarea->name = trim($this->almacenarea->name);
        $this->validate();
        $this->almacenarea->save();
        $this->reset(['open']);
        $this->dispatchBrowserEvent('updated');
    }

    public function delete(Almacenarea $almacenarea)
    {
        $this->authorize('admin.almacen.almacenareas.delete');
        $almacenarea->delete();
        $this->dispatchBrowserEvent('deleted');
    }
}
