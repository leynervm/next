<?php

namespace App\Http\Livewire\Admin\Sucursales;

use App\Models\Box;
use App\Models\Sucursal;
use App\Rules\CampoUnique;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class ShowCajas extends Component
{

    use AuthorizesRequests;
    
    public $open = false;
    public $sucursal, $box;
    public $name, $apertura;

    protected $listeners = ['delete'];

    protected function rules()
    {
        return [
            'sucursal.id' => [
                'required', 'integer', 'min:1', 'exists:sucursals,id'
            ],
            'box.name' => [
                'required', 'min:3', 'max:100',
                new CampoUnique('boxes', 'name', $this->box->id, true, 'sucursal_id', $this->sucursal->id),
            ],
            'box.apertura' => [
                'required', 'numeric', 'min:0', 'decimal:0,2'
            ],
        ];
    }

    public function mount(Sucursal $sucursal)
    {
        $this->sucursal = $sucursal;
        $this->box = new Box();
    }

    public function render()
    {
        return view('livewire.admin.sucursales.show-cajas');
    }

    public function edit(Box $box)
    {
        $this->box = $box;
        $this->resetValidation();
        $this->open = true;
    }

    public function save()
    {
        $this->authorize('admin.administracion.sucursales.boxes.edit');
        $this->name = trim($this->name);
        $validateData = $this->validate([
            'sucursal.id' => ['required', 'integer', 'min:1', 'exists:sucursals,id'],
            'name' => [
                'required', 'min:3', 'max:100', new CampoUnique('boxes', 'name', null, true, 'sucursal_id', $this->sucursal->id),
            ],
            'apertura' => [
                'required', 'numeric', 'min:0', 'decimal:0,2'
            ],
        ]);

        $box = $this->sucursal->boxes()->withTrashed()
            ->whereRaw('UPPER(name) = ?', [mb_strtoupper($this->name, "UTF-8")])->first();

        if ($box) {
            if ($box->trashed()) {
                $this->emit('sucursales.confirmRestorecaja', $box);
            }
        } else {
            $this->sucursal->boxes()->create($validateData);
            $this->sucursal->refresh();
            $this->resetValidation();
            $this->reset(['name', 'apertura']);
            $this->dispatchBrowserEvent('created');
        }
    }

    public function update()
    {
        $this->authorize('admin.administracion.sucursales.boxes.edit');
        $this->box->name = trim($this->box->name);
        $this->validate();
        $this->box->save();
        $this->sucursal->refresh();
        $this->resetValidation();
        $this->reset(['open']);
        $this->dispatchBrowserEvent('updated');
    }


    public function delete(Box $box)
    {

        $this->authorize('admin.administracion.sucursales.boxes.edit');
        $opencajas = $this->sucursal->boxes()
            ->withWhereHas('openboxes', function ($query) use ($box) {
                $query->whereNull('closedate')->where('box_id', $box->id);
            })->exists();

        if ($opencajas) {
            $mensaje = response()->json([
                'title' => 'Actualmente La caja se encuentra aperturada, ' . $box->name,
                'text' => "La caja seleccionada se encuentra en uso, cerrar caja e inténtelo nuevamente."
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
        } else {
            $box->delete();
            $this->sucursal->refresh();
            $this->dispatchBrowserEvent('deleted');
        }
    }

    public function restorecaja($id)
    {
        $box = Box::onlyTrashed()->find($id);
        if ($box) {
            $box->restore();
            $this->sucursal->refresh();
            $this->resetValidation();
            $this->reset(['name', 'apertura']);
            $this->dispatchBrowserEvent('toast', toastJSON('Caja habilitada correctamente'));
        }
    }
}
