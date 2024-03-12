<?php

namespace App\Http\Livewire\Admin\Cajas;

use App\Models\Box;
use App\Models\Caja;
use App\Rules\CampoUnique;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CreateCaja extends Component
{

    public $open = false;
    public $name, $sucursal_id;

    protected function rules()
    {
        return [
            'sucursal_id' => [
                'required', 'integer', 'min:1', 'exists:sucursals,id'
            ],
            'name' => [
                'required', 'min:3', 'max:100',
                new CampoUnique('cajas', 'name', null, true, 'sucursal_id', $this->sucursal_id),
            ]
        ];
    }

    public function render()
    {
        $sucursals = auth()->user()->sucursals;
        return view('livewire.admin.cajas.create-caja', compact('sucursals'));
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->resetValidation();
            $this->reset();
        }
    }

    public function save()
    {
        $this->name = trim($this->name);
        $this->validate();

        $caja = Box::withTrashed()
            ->where('name', mb_strtoupper($this->name, "UTF-8"))
            ->where('sucursal_id', $this->sucursal_id)->first();

        if ($caja) {
            $caja->status =  0;
            $caja->save();
        } else {
            Box::create([
                'name' => $this->name,
                'sucursal_id' => $this->sucursal_id,
                'status' => 0
            ]);
        }

        $this->dispatchBrowserEvent('created');
        $this->emitTo('admin.cajas.show-cajas', 'render');
        $this->reset();
    }

    public function hydrate()
    {
        $this->dispatchBrowserEvent('render-create-caja');
    }
}
