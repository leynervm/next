<?php

namespace Modules\Soporte\Http\Livewire\Typeatencions;

use App\Rules\CampoUnique;
use App\Rules\Letter;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Modules\Soporte\Entities\Atencion;
use Modules\Soporte\Entities\Atenciontype;

class CreateTypeatencion extends Component
{

    public $open = false;
    public $equipamentrequire = 0;
    public $name, $atencion_id;

    protected function rules()
    {
        return [
            'name' => [
                'required', 'min:3', 'max:100', new Letter,
                new CampoUnique('atenciontypes', 'name'),
            ],
            'atencion_id' => [
                'required', 'exists:atencions,id'
            ]
        ];
    }


    public function render()
    {
        $atenciones = Atencion::where('delete', 0)->orderBy('name', 'asc')->get();
        return view('soporte::livewire.typeatencions.create-typeatencion', compact('atenciones'));
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->resetValidation();
            $this->reset(['name', 'atencion_id']);
        }
    }

    public function save()
    {
        $this->name = trim($this->name);
        $this->atencion_id = trim($this->atencion_id);
        $this->validate();

        $atenciontype = Atenciontype::where('name', mb_strtoupper($this->name, "UTF-8"))
            ->where('delete', 1)->first();

        if ($atenciontype) {
            $atenciontype->delete = 0;
            $atenciontype->atencion_id = $this->atencion_id;
            $atenciontype->user_id = Auth::user()->id;
            $atenciontype->save();
        } else {
            Atenciontype::create([
                'name' => $this->name,
                'atencion_id' => $this->atencion_id,
                'user_id' => Auth::user()->id,
            ]);
        }
        $this->emitTo('soporte::typeatencions.show-typeatencions', 'render');
        $this->reset('name', 'atencion_id', 'open');
    }
}
