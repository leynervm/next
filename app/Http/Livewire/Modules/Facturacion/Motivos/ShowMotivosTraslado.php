<?php

namespace App\Http\Livewire\Modules\Facturacion\Motivos;

use App\Models\Motivotraslado;
use App\Rules\CampoUnique;
use Livewire\Component;
use Livewire\WithPagination;

class ShowMotivosTraslado extends Component
{

    use WithPagination;

    protected $listeners = ['render'];

    public $open = false;
    public $motivotraslado;

    protected function rules()
    {
        return [
            'motivotraslado.name' => [
                'required', 'string', 'min:6', 'max:255',
                new CampoUnique('motivotraslados', 'name', $this->motivotraslado->id, true),
            ]
        ];
    }

    public function mount()
    {
        $this->motivotraslado = new Motivotraslado();
    }

    public function render()
    {
        $motivos = Motivotraslado::whereNull('code')->orderBy('code', 'asc')->paginate();
        return view('livewire.modules.facturacion.motivos.show-motivos-traslado', compact('motivos'));
    }

    public function edit(Motivotraslado $motivotraslado)
    {
        $this->resetValidation();
        $this->motivotraslado = $motivotraslado;
        $this->open = true;
    }

    public function update()
    {
        $this->motivotraslado->name = trim($this->motivotraslado->name);
        $this->validate();
        $this->motivotraslado->save();
        $this->dispatchBrowserEvent('updated');
        $this->resetValidation();
        $this->resetExcept(['motivotraslado']);
    }

    public function delete(Motivotraslado $motivotraslado)
    {
        $motivotraslado->delete();
        $this->dispatchBrowserEvent('deleted');
    }
}
