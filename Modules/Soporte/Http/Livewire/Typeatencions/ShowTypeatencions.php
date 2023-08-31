<?php

namespace Modules\Soporte\Http\Livewire\Typeatencions;

use App\Rules\CampoUnique;
use App\Rules\Letter;
use Livewire\Component;
use Modules\Soporte\Entities\Atencion;
use Modules\Soporte\Entities\Atenciontype;

class ShowTypeatencions extends Component
{

    public $open = false;
    public $atenciontype;

    protected $listeners = ['render', 'deleteAtenciontype' => 'delete'];

    protected function rules()
    {
        return [
            'atenciontype.name' => [
                'required', 'min:3', 'max:100', new Letter,
                new CampoUnique('atenciontypes', 'name'),
            ],
            'atenciontype.atencion_id' => [
                'required', 'exists:atencions,id'
            ]
        ];
    }

    public function render()
    {
        $atenciontypes = Atenciontype::where('delete', 0)->orderBy('name', 'asc')->paginate();
        $atenciones = Atencion::where('delete', 0)->orderBy('name', 'asc')->get();
        return view('soporte::livewire.typeatencions.show-typeatencions', compact('atenciontypes', 'atenciones'));
    }

    public function updatedOpen()
    {
        if ($this->open == false) {
            $this->resetValidation();
        }
    }

    public function edit(Atenciontype $atenciontype)
    {
        $this->atenciontype = $atenciontype;
        $this->open = true;
    }

    public function update()
    {
        $this->atenciontype->name = trim($this->atenciontype->name);
        $this->atenciontype->atencion_id = trim($this->atenciontype->atencion_id);
        $this->validate();

        $this->atenciontype->save();
        $this->reset(['open']);
    }

    public function confirmDelete(Atenciontype $atenciontype)
    {
        $this->dispatchBrowserEvent('soporte::typeatencions.confirmDelete', $atenciontype);
    }

    public function delete(Atenciontype $atenciontype)
    {
        $atenciontype->delete = 1;
        $atenciontype->save();
        $this->dispatchBrowserEvent('soporte::typeatencions.deleted');
    }
}
