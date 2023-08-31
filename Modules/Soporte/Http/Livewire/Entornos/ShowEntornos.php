<?php

namespace Modules\Soporte\Http\Livewire\Entornos;

use App\Rules\CampoUnique;
use App\Rules\DefaultValue;
use App\Rules\Letter;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Soporte\Entities\Entorno;

class ShowEntornos extends Component
{

    use WithPagination;

    public $open = false;
    public $entorno;

    protected $listeners = ['render', 'deleteEntorno' => 'delete'];

    protected function rules()
    {
        return [
            'entorno.name' => [
                'required', 'min:3', 'max:100', new Letter,
                new CampoUnique('entornos', 'name', $this->entorno->id)
            ],
            'entorno.requiredirection' => [
                'nullable', 'max:1'
            ],
            'entorno.default' => [
                'nullable', 'max:1',  new DefaultValue('entornos', 'default', $this->entorno->id)
            ]
        ];
    }

    public function mount()
    {
        $this->entorno = new Entorno();
    }


    public function render()
    {
        $entornos = Entorno::where('delete', 0)->orderBy('name', 'asc')->paginate();
        return view('soporte::livewire.entornos.show-entornos', compact('entornos'));
    }

    public function updatedOpen()
    {
        if ($this->open == false) {
            $this->resetValidation();
        }
    }

    public function edit(Entorno $entorno)
    {
        $this->entorno = $entorno;
        $this->open = true;
    }

    public function update()
    {
        $this->entorno->name = trim($this->entorno->name);
        $this->validate();
        $this->entorno->save();
        $this->reset(['open']);
    }

    public function confirmDelete(Entorno $entorno)
    {
        $this->dispatchBrowserEvent('soporte::entornos.confirmDelete', $entorno);
    }

    public function delete(Entorno $entorno)
    {
        $entorno->delete = 1;
        $entorno->save();
        $this->dispatchBrowserEvent('soporte::entornos.deleted');
    }
}
