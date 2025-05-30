<?php

namespace App\Http\Livewire\Modules\Soporte\Atencions;

use App\Models\Areawork;
use App\Rules\CampoUnique;
use App\Rules\Letter;
use Livewire\Component;
use Modules\Soporte\Entities\Atencion;
use Modules\Soporte\Entities\Entorno;

class CreateAtencion extends Component
{

    public $open = false;
    public $equipamentrequire = 0;
    public $name;
    public $selectedentornos = [];
    public $selectedareaworks = [];

    protected function rules()
    {
        return [
            'name' => [
                'required',
                'min:3',
                'max:100',
                new Letter,
                new CampoUnique('atencions', 'name'),
            ],
            'equipamentrequire' => [
                'required',
                'integer',
                'min:0',
                'max:1'
            ],
            'selectedentornos' => [
                'required',
                'min:1'
            ],
            'selectedareaworks' => [
                'required',
                'min:1'
            ],
        ];
    }

    public function render()
    {
        $entornos = Entorno::orderBy('name', 'asc')->get();
        $areaworks = Areawork::tickets()->orderBy('name', 'asc')->get();
        return view('livewire.modules.soporte.atencions.create-atencion', compact('entornos', 'areaworks'));
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->resetValidation();
            $this->reset(['name', 'equipamentrequire']);
        }
    }

    public function save($closemodal = false)
    {
        $this->name = mb_strtoupper(trim($this->name), "UTF-8");
        $this->validate();
        $atencion = Atencion::withTrashed()->where('name', $this->name)->first();

        if ($atencion) {
            if ($atencion->trashed()) {
                $atencion->restore();
            }
            $atencion->equipamentrequire = $this->equipamentrequire;
            $atencion->save();
        } else {
            $atencion = Atencion::create([
                'name' => $this->name,
                'equipamentrequire' => $this->equipamentrequire
            ]);
        }

        $atencion->entornos()->syncWithPivotValues($this->selectedentornos, [
            'user_id' => auth()->user()->id
        ]);
        $atencion->areaworks()->syncWithPivotValues($this->selectedareaworks, [
            'user_id' => auth()->user()->id
        ]);

        $this->emitTo('modules.soporte.atencions.show-atencions', 'render');
        if ($closemodal) {
            $this->reset();
        } else {
            $this->resetExcept('open');
        }
    }
}
