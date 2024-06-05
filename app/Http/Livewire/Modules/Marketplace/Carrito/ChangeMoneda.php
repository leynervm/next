<?php

namespace App\Http\Livewire\Modules\Marketplace\Carrito;

use App\Models\Moneda;
use Livewire\Component;

class ChangeMoneda extends Component
{

    public $moneda, $moneda_id;
    protected $listeners = ['setMoneda'];

    public function mount()
    {
        $this->moneda = Moneda::default()->first();
        $this->moneda_id = Moneda::default()->first()->id;
    }

    public function render()
    {
        if (mi_empresa()->usepricedolar ?? 0) {
            $monedas = Moneda::orderBy('currency', 'asc')->get();
        } else {
            $monedas = Moneda::where('code', 'PEN')->orderBy('currency', 'asc')->get();
        }
        return view('livewire.modules.marketplace.carrito.change-moneda', compact('monedas'));
    }

    public function setMoneda($moneda_id)
    {
        $this->moneda = Moneda::find($moneda_id);
    }

    public function getMonedaProperty($value)
    {
        return Moneda::find($value);
    }
}
