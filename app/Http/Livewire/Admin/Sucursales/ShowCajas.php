<?php

namespace App\Http\Livewire\Admin\Sucursales;

use App\Models\Caja;
use App\Models\Sucursal;
use App\Rules\CampoUnique;
use Livewire\Component;

class ShowCajas extends Component
{

    public $open = false;
    public $sucursal, $caja;
    public $name;

    protected $listeners = ['delete'];

    protected function rules()
    {
        return [
            'sucursal.id' => [
                'required', 'integer', 'min:1', 'exists:sucursals,id'
            ],
            'caja.name' => [
                'required', 'min:3', 'max:100',
                new CampoUnique('cajas', 'name', $this->caja->id, true, 'sucursal_id', $this->sucursal->id),
            ],
        ];
    }

    public function mount(Sucursal $sucursal)
    {
        $this->sucursal = $sucursal;
        $this->caja = new Caja();
    }

    public function render()
    {
        return view('livewire.admin.sucursales.show-cajas');
    }

    public function edit(Caja $caja)
    {
        $this->caja = $caja;
        $this->resetValidation();
        $this->open = true;
    }

    public function save()
    {
        $this->name = trim($this->name);
        $validateData = $this->validate([
            'sucursal.id' => ['required', 'integer', 'min:1', 'exists:sucursals,id'],
            'name' => [
                'required', 'min:3', 'max:100',
                new CampoUnique('cajas', 'name', null, true, 'sucursal_id', $this->sucursal->id),
            ],
        ]);

        $caja = $this->sucursal->cajas()->withTrashed()
            ->whereRaw('UPPER(name) = ?', [mb_strtoupper($this->name, "UTF-8")])->first();

        if ($caja) {
            if ($caja->trashed()) {
                $this->emit('confirmRestorecaja', $caja);
            }
        } else {
            $this->sucursal->cajas()->create($validateData);
            $this->sucursal->refresh();
            $this->resetValidation();
            $this->reset(['name']);
            $this->dispatchBrowserEvent('created');
        }
    }

    public function update()
    {
        $this->caja->name = trim($this->caja->name);
        $this->validate([
            'caja.name' => [
                'required', 'min:3', 'max:100',
                new CampoUnique('cajas', 'name', $this->caja->id, true, 'sucursal_id', $this->sucursal->id),
            ]
        ]);
        $this->caja->save();
        $this->sucursal->refresh();
        $this->resetValidation();
        $this->reset(['open']);
        $this->dispatchBrowserEvent('updated');
    }


    public function delete(Caja $caja)
    {

        $opencajas = $this->sucursal->cajas()
            ->withWhereHas('opencajas', function ($query) use ($caja) {
                $query->whereNull('closedate')->where('caja_id', $caja->id);
            })->exists();

        if ($opencajas) {
            $mensaje = response()->json([
                'title' => 'Existen cajas aperturadas con la registro a eliminar, ' . $caja->name,
                'text' => "La caja seleccionada se encuentra en uso, cierre la apertura de caja e inténtelo nuevamente."
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
        } else {
            $caja->delete();
            $this->sucursal->refresh();
            $this->dispatchBrowserEvent('deleted');
        }
    }

    public function restorecaja($id)
    {
        $caja = Caja::onlyTrashed()->find($id);
        if ($caja) {
            $caja->restore();
            $this->sucursal->refresh();
            $this->resetValidation();
            $this->reset(['name']);
            $this->dispatchBrowserEvent('toast', toastJSON('Caja habilitada correctamente'));
        }
    }
}
