<?php

namespace App\Http\Livewire\Modules\Soporte\Tickets;

use Livewire\Component;
use Livewire\WithPagination;
use Modules\Soporte\Entities\Ticket;

class ShowTickets extends Component
{

    use WithPagination;

    public function render()
    {
        $tickets = Ticket::with([
            'client',
            'telephones',
            'contact',
            'estate',
            'atencion',
            'condition',
            'priority',
            'entorno',
            'areawork',
            'sucursal',
            'user',
            'userasigned',
            'direccion' => function ($q) {
                $q->with('ubigeo');
            },
            'equipo' => function ($q) {
                $q->with(['typeequipo', 'marca']);
            }
        ])->orderByDesc('date')->paginate();
        return view('livewire.modules.soporte.tickets.show-tickets', compact('tickets'));
    }
}
