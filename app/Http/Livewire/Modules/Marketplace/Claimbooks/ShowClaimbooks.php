<?php

namespace App\Http\Livewire\Modules\Marketplace\Claimbooks;

use App\Models\Claimbook;
use App\Models\Sucursal;
use Livewire\Component;
use Livewire\WithPagination;

class ShowClaimbooks extends Component
{

    use WithPagination;

    protected $queryString = [
        'search' => [
            'except' => '',
            'as' => 'buscar'
        ],
        'searchdate' => [
            'except' => '',
            'as' => 'fecha'
        ],
        'searchtype' => [
            'except' => '',
            'as' => 'tipo-reclamo'
        ],
        'searchsucursal' => [
            'except' => '',
            'as' => 'punto-venta'
        ],
    ];

    public $search, $searchdate, $searchtype, $searchsucursal = '';

    public function render()
    {
        $claimbooks = Claimbook::orderBy('date', 'desc');

        if (trim($this->search) !== '') {
            $claimbooks->where('document', 'ilike', '%' . $this->search . '%')
                ->orWhere('name', 'ilike', '%' . $this->search . '%');
        }

        if (trim($this->searchdate) !== '') {
            $claimbooks->whereDate('date', $this->searchdate);
        }

        if (trim($this->searchtype) !== '') {
            $claimbooks->where('tipo_reclamo', $this->searchtype);
        }

        if (trim($this->searchsucursal) !== '') {
            $claimbooks->where('sucursal_id', $this->searchsucursal);
        }

        $claimbooks = $claimbooks->paginate();
        $typereclamos = Claimbook::select('tipo_reclamo')->groupBy('tipo_reclamo')->get();
        $sucursals = Sucursal::whereHas('claimbooks')->get();

        return view('livewire.modules.marketplace.claimbooks.show-claimbooks', compact('claimbooks', 'typereclamos', 'sucursals'));
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSearchdate()
    {
        $this->resetPage();
    }

    public function updatedSearchtype()
    {
        $this->resetPage();
    }

    public function updatedSearchsucursal()
    {
        $this->resetPage();
    }
}
