<?php

namespace App\Http\Livewire\Modules\Facturacion\Motivos;

use App\Models\Motivotraslado;
use App\Models\Typecomprobante;
use App\Rules\CampoUnique;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rules\Unique;
use Livewire\Component;

class CreateMotivoTraslado extends Component
{

    use AuthorizesRequests;

    public $open = false;
    public $name, $typecomprobante_id;

    protected function rules()
    {
        return [
            'name' => [
                'required', 'string', 'min:6', 'max:255',
                new CampoUnique('motivotraslados', 'name', null, true)
            ],
            'typecomprobante_id' => [
                'nullable', 'integer', 'min:1', 'exists:typecomprobantes,id'
            ]
        ];
    }

    public function mount()
    {
        $typecomprobantes = Typecomprobante::default()->where('code', '09')->orderBy('name', 'asc')->get();
        if (count($typecomprobantes) > 0) {
            $this->typecomprobante_id = $typecomprobantes->first()->id ?? null;
        }
    }

    public function render()
    {
        return view('livewire.modules.facturacion.motivos.create-motivo-traslado');
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->authorize('admin.facturacion.guias.motivos.create');
            $this->resetValidation();
            $this->reset(['name']);
        }
    }

    public function save()
    {

        $this->authorize('admin.facturacion.guias.motivos.create');
        $typecomprobantes = Typecomprobante::default()->where('code', '09')->orderBy('name', 'asc')->get();
        if (count($typecomprobantes) > 0) {
            $this->typecomprobante_id = $typecomprobantes->first()->id ?? null;
        }
        $this->name = mb_strtoupper(trim($this->name), "UTF-8");
        $validateData = $this->validate();

        $motivotraslado = Motivotraslado::withTrashed()->where('name', $this->name)->first();
        if ($motivotraslado && $motivotraslado->trashed()) {
            $motivotraslado->restore();
        } else {
            Motivotraslado::create($validateData);
        }
        $this->resetValidation();
        $this->reset();
        $this->dispatchBrowserEvent('created');
        $this->emitTo('modules.facturacion.motivos.show-motivos-traslado', 'render');
    }
}
