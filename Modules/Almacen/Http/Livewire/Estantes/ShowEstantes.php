<?php

namespace Modules\Almacen\Http\Livewire\Estantes;

use App\Models\Estante;
use App\Rules\CampoUnique;
use App\Rules\Letter;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class ShowEstantes extends Component
{

    use WithPagination, AuthorizesRequests;

    public $open = false;
    public $estante;

    protected $listeners = ['render'];

    protected function rules()
    {
        return [
            'estante.name' => [
                'required', 'min:1', 'max:100',
                new CampoUnique('estantes', 'name', $this->estante->id, true),
            ]
        ];
    }

    public function mount()
    {
        $this->estante = new Estante();
    }

    public function render()
    {
        $estantes = Estante::orderBy('name', 'asc')->paginate(15, ['*'], 'PageEstante');
        return view('almacen::livewire.estantes.show-estantes', compact('estantes'));
    }

    public function edit(Estante $estante)
    {
        $this->authorize('admin.almacen.estantes.edit');
        $this->estante = $estante;
        $this->open = true;
    }

    public function update()
    {
        $this->authorize('admin.almacen.estantes.edit');
        $this->estante->name = trim($this->estante->name);
        $this->validate();
        $this->estante->save();
        $this->reset(['open']);
        $this->dispatchBrowserEvent('updated');
    }

    public function delete(Estante $estante)
    {
        $this->authorize('admin.almacen.estantes.delete');
        $estante->delete();
        $this->dispatchBrowserEvent('deleted');
    }
}
