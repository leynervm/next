<?php

namespace Modules\Ventas\Http\Livewire\Ventas;

use Livewire\Component;
use Modules\Ventas\Entities\Venta;

use Livewire\WithPagination;

class ShowVentas extends Component
{

    use WithPagination;

    
    public function render()
    {
        $ventas = Venta::orderBy('date', 'desc')->paginate();
        return view('ventas::livewire.ventas.show-ventas', compact('ventas'));
    }
}
