<?php

namespace Modules\Soporte\Http\Livewire\Atenciones;

use App\Models\Area;
use App\Rules\CampoUnique;
use App\Rules\Letter;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Modules\Soporte\Entities\Atencion;
use Modules\Soporte\Entities\Entorno;
use Modules\Soporte\Entities\Estate;

class CreateAtencion extends Component
{

    public $open = false;
    public $equipamentrequire = 0;
    public $name;
    public $arrayEntornos = [];
    public $arrayAreas = [];

    protected function rules()
    {
        return [
            'name' => [
                'required', 'min:3', 'max:100', new Letter,
                new CampoUnique('atencions', 'name'),
            ],
            'equipamentrequire' => [
                'nullable', 'max:1'
            ],
            'arrayEntornos' => [
                'required', 'min:1'
            ],
            'arrayAreas' => [
                'required', 'min:1'
            ],
        ];
    }

    public function render()
    {
        $entornos = Entorno::where('delete', 0)->orderBy('name', 'asc')->get();
        $areas = Area::where('delete', 0)->orderBy('name', 'asc')->get();
        return view('soporte::livewire.atenciones.create-atencion', compact('entornos', 'areas'));
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->resetValidation();
            $this->reset(['name', 'equipamentrequire']);
        }
    }

    public function save()
    {
        $this->name = trim($this->name);
        $this->validate();

        $atencion = Atencion::where('name', mb_strtoupper($this->name, "UTF-8"))
            ->where('delete', 1)->first();

        if ($atencion) {
            $atencion->delete = 0;
            $atencion->equipamentrequire = $this->equipamentrequire;
            $atencion->save();
        } else {
            $atencion = Atencion::create([
                'name' => $this->name,
                'equipamentrequire' => $this->equipamentrequire
            ]);
        }

        $atencion->entornos()->attach($this->arrayEntornos, ['user_id' => Auth::user()->id, 'created_at' => now('America/Lima')]);
        $atencion->areas()->attach($this->arrayAreas, ['user_id' => Auth::user()->id, 'created_at' => now('America/Lima')]);

        $this->emitTo('soporte::atenciones.show-atenciones', 'render');
        $this->reset('name', 'equipamentrequire', 'arrayEntornos', 'open');
    }
}
