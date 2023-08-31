<?php

namespace App\Http\Livewire\Admin\Cajas;

use App\Models\Caja;
use App\Rules\CampoUnique;
use Livewire\Component;

class CreateCaja extends Component
{

    public $open = false;
    public $name;

    protected function rules()
    {
        return [
            'name' => [
                'required', 'min:3', 'max:100',
                new CampoUnique('cajas', 'name', null, true),
            ],
        ];
    }

    public function render()
    {
        return view('livewire.admin.cajas.create-caja');
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

        $caja = Caja::withTrashed()
            ->where('name', mb_strtoupper($this->name, "UTF-8"))->first();

        if ($caja) {
            $caja->user_id =  null;
            $caja->status =  0;
            $caja->save();
        } else {
            Caja::create([
                'name' => $this->name,
                'status' => 0
            ]);
        }

        $this->emitTo('admin.cajas.show-cajas', 'render');
        $this->reset();
    }
}
