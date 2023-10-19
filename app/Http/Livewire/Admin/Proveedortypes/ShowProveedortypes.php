<?php

namespace App\Http\Livewire\Admin\Proveedortypes;

use App\Models\Proveedortype;
use App\Rules\CampoUnique;
use Livewire\Component;
use Livewire\WithPagination;

class ShowProveedortypes extends Component
{

    use WithPagination;

    public $proveedortype;
    public $open = false;
    public $name;

    protected $listeners = ['render', 'delete'];

    protected function rules()
    {
        return [
            'proveedortype.name' => [
                'required', 'min:3', 'max:100',
                new CampoUnique('proveedortypes', 'name', $this->proveedortype->id, true)
            ]
        ];
    }

    public function mount()
    {
        $this->proveedortype = new Proveedortype();
    }

    public function render()
    {
        $proveedortypes = Proveedortype::orderBy('name', 'asc')->paginate();
        return view('livewire.admin.proveedortypes.show-proveedortypes', compact('proveedortypes'));
    }

    public function edit(Proveedortype $proveedortype)
    {
        $this->proveedortype = $proveedortype;
        $this->resetValidation();
        $this->open = true;
    }

    public function update()
    {
        $this->proveedortype->name = trim($this->proveedortype->name);
        $this->validate();
        $this->proveedortype->save();
        $this->reset();
    }


    public function delete(Proveedortype $proveedortype)
    {
        $proveedortype->deleteOrFail();
        $this->dispatchBrowserEvent('deleted');
    }
}
