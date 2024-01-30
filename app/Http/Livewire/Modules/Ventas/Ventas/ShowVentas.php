<?php

namespace App\Http\Livewire\Modules\Ventas\Ventas;

use App\Models\Sucursal;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Ventas\Entities\Venta;
use Nwidart\Modules\Facades\Module;

class ShowVentas extends Component
{

    use WithPagination;

    public $search = '';
    public $date = '';
    public $dateto = '';
    public $searchsucursal = '';
    public $searchuser = '';
    public $deletes = false;

    protected $queryString = [
        'search' => ['except' => '', 'as' => 'buscar'],
        'date' => ['except' => '', 'as' => 'fecha'],
        'dateto' => ['except' => '', 'as' => 'hasta'],
        'searchsucursal' => ['except' => '', 'as' => 'sucursal'],
        'searchuser' => ['except' => '', 'as' => 'usuario'],
        'deletes' => ['except' => false, 'as' => 'eliminados'],
    ];


    public function render()
    {
        $sucursals = Sucursal::withTrashed()->whereHas('ventas')->get();
        $users = User::whereHas('ventas')->orderBy('name', 'asc')->get();
        $ventas = Venta::with(['sucursal', 'user', 'client', 'typepayment', 'cajamovimiento', 'cuotas'])
            ->withWhereHas('sucursal', function ($query) {
                $query->withTrashed();
                if ($this->searchsucursal !== '') {
                    $query->where('id', $this->searchsucursal);
                } else {
                    $query->where('id', auth()->user()->sucursal_id);
                }
            });

        if (trim($this->search) !== '') {
            $ventas->whereHas('client', function ($query) {
                $query->where('document', 'ilike', '%' . $this->search . '%')
                    ->orWhere('name', 'ilike', '%' . $this->search . '%');
            })->orWhere('code', 'ilike', ['%' . $this->search . '%']);
            // ->orWhereRaw("CONCAT(code, '-', id) ILIKE ?", ['%' . $this->search . '%']);
        }

        if ($this->date) {
            if ($this->dateto) {
                $ventas->whereDateBetween('date', $this->date, $this->dateto);
            } else {
                $ventas->whereDate('date', $this->date);
            }
        }

        if ($this->searchuser !== '') {
            $ventas->where('user_id', $this->searchuser);
        }

        if ($this->deletes) {
            $ventas->onlyTrashed();
        }

        $ventas = $ventas->orderBy('date', 'desc')->paginate();

        return view('livewire.modules.ventas.ventas.show-ventas', compact('ventas', 'sucursals', 'users'));
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedDate()
    {
        $this->resetPage();
    }

    public function updatedDateto()
    {
        $this->resetPage();
    }

    public function updatedSearchuser()
    {
        $this->resetPage();
    }

    public function updatedDeletes()
    {
        $this->resetPage();
    }

    public function updatedSearchsucursal()
    {
        $this->resetPage();
    }
}
