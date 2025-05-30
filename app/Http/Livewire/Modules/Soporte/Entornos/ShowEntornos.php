<?php

namespace App\Http\Livewire\Modules\Soporte\Entornos;

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

    protected $listeners = ['render'];

    protected function rules()
    {
        return [
            'entorno.name' => [
                'required',
                'min:3',
                'max:100',
                new Letter,
                new CampoUnique('entornos', 'name', $this->entorno->id, true)
            ],
            'entorno.requiredirection' => [
                'nullable',
                'min:0',
                'max:1'
            ],
            'entorno.default' => [
                'nullable',
                'max:1',
                new DefaultValue('entornos', 'default', $this->entorno->id)
            ]
        ];
    }

    public function mount()
    {
        $this->entorno = new Entorno();
    }


    public function render()
    {
        $entornos = Entorno::orderBy('name', 'asc')->paginate();
        return view('livewire.modules.soporte.entornos.show-entornos', compact('entornos'));
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
        $this->dispatchBrowserEvent('updated');
    }

    public function delete($id)
    {
        if ($id) {
            $entorno = Entorno::withCount('tickets')->find($id);
            if ($entorno) {
                $entorno->atencions()->detach();
                $entorno->areaworks()->detach();
                if ($entorno->tickets_count > 0) {
                    $entorno->delete();
                } else {
                    $entorno->forceDelete();
                }
                return $this->dispatchBrowserEvent('deleted');
            }
        }

        return $this->dispatchBrowserEvent('toast', toastJSON('NO SE ENCONTRÓ EL REGISTRO', 'error'));
    }
}
