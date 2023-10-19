<?php

namespace Modules\Ventas\Http\Livewire\Ventas;

use Livewire\Component;
use Modules\Ventas\Entities\Venta;

use Livewire\WithPagination;

class ShowVentas extends Component
{

    use WithPagination;

    public $search = '';
    public $date = '';

    protected $queryString  = [
        'search' => ['except' => '', 'as' => 'buscar'],
        'date' => ['except' => '', 'as' => 'fecha-venta'],
    ];

    public function render()
    {
        $ventas = Venta::orderBy('date', 'desc');

        if (trim($this->search) !== '') {
            $ventas->whereHas('client', function ($query) {
                $query->where('document', 'ilike', '%' . $this->search . '%')
                    ->orWhere('name', 'ilike', '%' . $this->search . '%');
            })->orWhereHas('comprobante',  function ($query) {
                $query->where('seriecompleta', 'ilike', '%' . $this->search . '%');
            });
        }

        if ($this->date) {
            $ventas->whereDate('date', $this->date);
        }

        $ventas = $ventas->paginate();


        return view('ventas::livewire.ventas.show-ventas', compact('ventas'));
    }
}
