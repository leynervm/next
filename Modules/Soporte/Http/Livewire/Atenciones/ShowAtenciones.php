<?php

namespace Modules\Soporte\Http\Livewire\Atenciones;

use App\Models\Area;
use App\Rules\CampoUnique;
use App\Rules\Letter;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Soporte\Entities\Atencion;
use Modules\Soporte\Entities\Entorno;

class ShowAtenciones extends Component
{

    use WithPagination;

    public $open = false;
    public $atencion;
    public $entornos;
    public $areas;
    public $selectedEntornos = [];
    public $selectedAreas = [];

    protected $listeners = ['render', 'deleteAtencion' => 'delete'];

    protected function rules()
    {
        return [
            'atencion.name' => [
                'required', 'min:3', 'max:100', new Letter,
                new CampoUnique('atencions', 'name', $this->atencion->id),
            ],
            'atencion.equipamentrequire' => [
                'nullable'
            ],
            'selectedEntornos' => [
                'required', 'array', 'min:1'
            ],
            'selectedAreas' => [
                'required', 'array', 'min:1'
            ]
        ];
    }

    public function mount()
    {
        $this->atencion = new Atencion();
        $this->entornos = Entorno::where('delete', 0)->orderBy('name', 'asc')->get();
        $this->areas = Area::where('delete', 0)->orderBy('name', 'asc')->get();
    }

    public function render()
    {
        $atenciones = Atencion::where('delete', 0)->orderBy('name', 'asc')->paginate();
        return view('soporte::livewire.atenciones.show-atenciones', compact('atenciones'));
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
        $this->selectedEntornos = $atencion->entornos()->pluck('entorno_id')->toArray();
        $this->selectedAreas = $atencion->areas()->pluck('area_id')->toArray();
        $this->open = true;
    }

    public function update()
    {
        $this->atencion->name = trim($this->atencion->name);
        $this->validate();

        $this->atencion->save();
        $this->atencion->entornos()->syncWithPivotValues($this->selectedEntornos, ['user_id' => Auth::user()->id, 'updated_at' => now('America/Lima')]);
        $this->atencion->areas()->syncWithPivotValues($this->selectedAreas, ['user_id' => Auth::user()->id, 'updated_at' => now('America/Lima')]);
        $this->reset(['open']);
    }

    public function confirmDelete(Atencion $atencion)
    {
        $this->dispatchBrowserEvent('soporte::atenciones.confirmDelete', $atencion);
    }

    public function delete(Atencion $atencion)
    {
        $atencion->delete = 1;
        $atencion->equipamentrequire = 0;
        $atencion->save();
        $atencion->entornos()->detach();
        $this->dispatchBrowserEvent('soporte::atenciones.deleted');
    }
}
