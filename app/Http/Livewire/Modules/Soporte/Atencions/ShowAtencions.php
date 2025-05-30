<?php

namespace App\Http\Livewire\Modules\Soporte\Atencions;

use App\Models\Areawork;
use App\Rules\CampoUnique;
use App\Rules\Letter;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Soporte\Entities\Atencion;
use Modules\Soporte\Entities\Entorno;

class ShowAtencions extends Component
{
    use WithPagination;

    public $open = false;
    public $atencion;
    public $entornos;
    public $areas;
    public $selectedentornos = [];
    public $selectedareaworks = [];

    protected $listeners = ['render'];

    protected function rules()
    {
        return [
            'atencion.name' => [
                'required',
                'min:3',
                'max:100',
                new Letter,
                new CampoUnique('atencions', 'name', $this->atencion->id),
            ],
            'atencion.equipamentrequire' => [
                'required',
                'integer',
                'min:0',
                'max:1'
            ],
            'selectedentornos' => [
                'required',
                'array',
                'min:1'
            ],
            'selectedareaworks' => [
                'required',
                'array',
                'min:1'
            ]
        ];
    }

    public function mount()
    {
        $this->atencion = new Atencion();
        $this->entornos = Entorno::orderBy('name', 'asc')->get();
        $this->areas = Areawork::orderBy('name', 'asc')->get();
    }

    public function render()
    {
        $atencions = Atencion::with(['entornos', 'areaworks'])->orderBy('name', 'asc')->paginate();
        $entornos = Entorno::orderBy('name', 'asc')->get();
        $areaworks = Areawork::tickets()->orderBy('name', 'asc')->get();
        return view('livewire.modules.soporte.atencions.show-atencions', compact('atencions', 'entornos', 'areaworks'));
    }

    public function updatedOpen()
    {
        if ($this->open == false) {
            $this->resetValidation();
        }
    }

    public function edit(Atencion $atencion)
    {
        $this->atencion = $atencion;
        $this->selectedentornos = $atencion->entornos()->pluck('entorno_id')->toArray();
        $this->selectedareaworks = $atencion->areaworks()->pluck('areawork_id')->toArray();
        $this->open = true;
    }

    public function update()
    {
        $this->atencion->name = trim($this->atencion->name);
        $this->validate();
        $this->atencion->save();
        $this->atencion->entornos()->syncWithPivotValues($this->selectedentornos, [
            'user_id' => auth()->user()->id
        ]);
        $this->atencion->areaworks()->syncWithPivotValues($this->selectedareaworks, [
            'user_id' => auth()->user()->id
        ]);
        $this->reset(['open']);
    }

    public function delete($id)
    {
        if ($id) {
            $atencion = Atencion::withCount('tickets')->find($id);
            if ($atencion) {
                $atencion->entornos()->detach();
                $atencion->areaworks()->detach();
                if ($atencion->tickets_count > 0) {
                    $atencion->delete();
                } else {
                    $atencion->forceDelete();
                }
                return $this->dispatchBrowserEvent('deleted');
            }
        }

        return $this->dispatchBrowserEvent('toast', toastJSON('NO SE ENCONTRÓ EL REGISTRO', 'error'));
    }
}
