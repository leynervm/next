<?php

namespace App\Http\Livewire\Admin\Concepts;

use App\Enums\MovimientosEnum;
use App\Models\Concept;
use App\Rules\CampoUnique;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rules\Enum;
use Livewire\Component;
use Livewire\WithPagination;

class ShowConcepts extends Component
{

    use AuthorizesRequests;
    use WithPagination;

    public $open = false;
    public $concept;

    protected $listeners = ['render'];

    protected function rules()
    {
        return [
            'concept.name' => [
                'required', 'min:3', 'max:100', new CampoUnique('concepts', 'name', $this->concept->id, true)
            ],
            'concept.typemovement' => [
                'required', 'string', new Enum(MovimientosEnum::class),
            ]
        ];
    }

    public function mount()
    {
        $this->concept = new Concept();
    }

    public function render()
    {
        $concepts = Concept::orderBy('default', 'asc')->orderBy('name', 'asc')->paginate();
        return view('livewire.admin.concepts.show-concepts', compact('concepts'));
    }

    public function edit(Concept $concept)
    {
        $this->authorize('admin.cajas.conceptos.edit');
        $this->concept = $concept;
        $this->resetValidation();
        $this->open = true;
    }

    public function update()
    {
        $this->authorize('admin.cajas.conceptos.edit');
        $this->concept->name = trim($this->concept->name);
        $this->validate();
        $this->concept->save();
        $this->resetExcept(['concept']);
    }

    public function delete(Concept $concept)
    {
        $this->authorize('admin.cajas.conceptos.delete');
        if ($concept->cajamovimientos()->exists()) {
            $mensaje = response()->json([
                'title' => 'CONCEPTO DE PAGO ESTÁ VINCULADO A MOVIMIENTOS !',
                'text' => 'No se puede eliminar el concepto de pago, cuenta con registros de movimientos vinculados.',
                'type' => 'warning'
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        } else {
            $concept->delete();
            $this->dispatchBrowserEvent('deleted');
        }
    }
}
