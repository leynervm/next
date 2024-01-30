<?php

namespace Modules\Almacen\Http\Livewire\Estantes;

use App\Models\Estante;
use App\Rules\CampoUnique;
use App\Rules\Letter;
use Livewire\Component;
use Livewire\WithPagination;

class ShowEstantes extends Component
{

    use WithPagination;

    public $open = false;
    public $estante;

    protected $listeners = ['render', 'delete'];

    protected function rules()
    {
        return [
            'estante.name' => [
                'required', 'min:1', 'max:100', new Letter,
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
        $this->estante = $estante;
        $this->open = true;
    }

    public function update()
    {
        $this->estante->name = trim($this->estante->name);
        $this->validate();
        $this->estante->save();
        $this->reset(['open']);
    }

    public function confirmDelete(Estante $estante)
    {
        $this->dispatchBrowserEvent('estantes.confirmDelete', $estante);
    }

    public function delete(Estante $estante)
    {
        $estante->deleteOrFail();
        $this->dispatchBrowserEvent('deleted');
    }
}
