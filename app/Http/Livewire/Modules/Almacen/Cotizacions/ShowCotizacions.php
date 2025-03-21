<?php

namespace App\Http\Livewire\Modules\Almacen\Cotizacions;

use App\Models\Cotizacion;
use Livewire\Component;
use Livewire\WithPagination;

class ShowCotizacions extends Component
{

    use WithPagination;

    public function render()
    {
        $cotizacions = Cotizacion::with(['sucursal', 'typepayment', 'user', 'lugar', 'client', 'moneda'])
            ->orderByDesc('date')->paginate();
        return view('livewire.modules.almacen.cotizacions.show-cotizacions', compact('cotizacions'));
    }
}
