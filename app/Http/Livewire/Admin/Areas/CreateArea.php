<?php

namespace App\Http\Livewire\Admin\Areas;

use App\Models\Areawork;
use App\Rules\CampoUnique;
use App\Rules\Letter;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Modules\Soporte\Entities\Atencion;
use Modules\Soporte\Entities\Entorno;

class CreateArea extends Component
{

    public $open = false;
    public $name, $slug;
    public $visible = 0;
    public $arrayAtencions = [];
    public $arrayEntornos = [];

    protected function rules()
    {
        return [
            'name' => [
                'required', 'min:3', 'max:100', new Letter,
                new CampoUnique('areas', 'name'),
            ],
            'slug' => [
                'required', 'min:3', 'max:100',
                new CampoUnique('areas', 'slug'),
            ],
            'visible' => [
                'nullable', 'integer'
            ],
            'arrayEntornos' => [
                'required', 'min:1'
            ]

        ];
    }


    public function render()
    {
        $atenciones = Atencion::where('delete', 0)->orderBy('name', 'asc')->get();
        $entornos = Entorno::where('delete', 0)->orderBy('name', 'asc')->get();
        return view('livewire.admin.areas.create-area', compact('atenciones', 'entornos'));
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->resetValidation();
            $this->reset('name', 'visible', 'slug', 'arrayAtencions', 'arrayEntornos', 'open');
        }
    }

    public function save()
    {
        $this->name = trim($this->name);
        $this->slug = trim($this->slug);
        $this->validate();

        $area = Areawork::where('name', mb_strtoupper($this->name, "UTF-8"))
            ->where('delete', 1)->first();

        if ($area) {
            $area->delete = 0;
            $area->visible =  $this->visible;
            $area->save();
        } else {
            $area = Areawork::create([
                'name' => $this->name,
                'slug' => $this->slug,
                'visible' => $this->visible,
            ]);
        }

        // $area->atencions()->attach($this->arrayAtencions, ['user_id' => Auth::user()->id, 'created_at' => now('America/Lima')]);
        $area->entornos()->attach($this->arrayEntornos, ['user_id' => Auth::user()->id, 'created_at' => now('America/Lima')]);

        $this->emitTo('admin.areas.show-areas', 'render');
        $this->reset('name', 'visible', 'slug', 'arrayAtencions', 'arrayEntornos', 'open');
    }
}
