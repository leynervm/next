<?php

namespace App\Http\Livewire\Admin\Cajas;

use App\Models\Caja;
use App\Rules\CampoUnique;
use Livewire\Component;
use Livewire\WithPagination;

class ShowCajas extends Component
{

    use WithPagination;

    public $open = false;
    public $caja;

    protected $listeners = ['render', 'delete'];

    protected function rules()
    {
        return [
            'caja.name' => [
                'required', 'min:3', 'max:100',
                new CampoUnique('cajas', 'name', $this->caja->id, true),
            ],
        ];
    }

    public function mount()
    {
        $this->caja = new Caja();
    }

    public function render()
    {
        $cajas = Caja::orderBy('name', 'asc')->paginate();
        return view('livewire.admin.cajas.show-cajas', compact('cajas'));
    }

    public function edit(Caja $caja)
    {
        $this->caja = $caja;
        $this->resetValidation();
        $this->open = true;
    }

    public function update()
    {
        $this->caja->name = trim($this->caja->name);
        $this->validate();
        $this->caja->save();
        $this->dispatchBrowserEvent('updated');
        $this->reset();
    }

    public function confirmDelete(Caja $caja)
    {
        $this->dispatchBrowserEvent('cajas.confirmDelete', $caja);
    }

    public function delete(Caja $caja)
    {
        $caja->deleteOrFail();
        $this->dispatchBrowserEvent('deleted');
    }
}
