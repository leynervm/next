<?php

namespace App\Http\Livewire\Admin\Bancos;

use App\Models\Banco;
use App\Rules\CampoUnique;
use Livewire\Component;
use Livewire\WithPagination;

class ShowBancos extends Component
{

    use WithPagination;

    public $open = false;
    public $banco;

    protected $listeners = ['render', 'delete'];

    protected function rules()
    {
        return [
            'banco.name' => ['required', 'min:3', 'max:100', new CampoUnique('bancos', 'name', $this->banco->id, true)]
        ];
    }

    public function mount()
    {
        $this->banco = new Banco();
    }
    public function render()
    {
        $bancos = Banco::orderBy('name', 'asc')->paginate();
        return view('livewire.admin.bancos.show-bancos', compact('bancos'));
    }

    public function edit(Banco $banco)
    {
        $this->banco = $banco;
        $this->resetValidation();
        $this->open = true;
    }

    public function update()
    {
        $this->banco->name = trim($this->banco->name);
        $this->validate();
        $this->banco->save();
        $this->reset();
    }

    public function confirmDelete(Banco $banco)
    {
        $this->dispatchBrowserEvent('bancos.confirmDelete', $banco);
    }

    public function delete(Banco $banco)
    {
        $banco->deleteOrFail();
        $this->dispatchBrowserEvent('deleted');
    }
}
