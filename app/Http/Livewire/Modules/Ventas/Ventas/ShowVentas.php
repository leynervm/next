<?php

namespace App\Http\Livewire\Modules\Ventas\Ventas;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Ventas\Entities\Venta;

class ShowVentas extends Component
{

    use WithPagination, AuthorizesRequests;

    public $search = '';
    public $date = '';
    public $dateto = '';
    public $searchuser = '';
    public $deletes = false;

    protected $queryString = [
        'search' => ['except' => '', 'as' => 'buscar'],
        'date' => ['except' => '', 'as' => 'fecha'],
        'dateto' => ['except' => '', 'as' => 'hasta'],
        'searchuser' => ['except' => '', 'as' => 'usuario'],
        'deletes' => ['except' => false, 'as' => 'eliminados'],
    ];


    public function render()
    {
        $users = User::whereHas('ventas', function ($query) {
            $query->where('sucursal_id', auth()->user()->sucursal_id);
        })->orderBy('name', 'asc')->get();
        $ventas = Venta::with(['sucursal', 'user', 'client', 'typepayment', 'cajamovimientos', 'cuotas'])
            ->where('sucursal_id', auth()->user()->sucursal_id);

        if (trim($this->search) !== '') {
            $ventas->whereHas('client', function ($query) {
                $query->where('document', 'ilike', '%' . $this->search . '%')
                    ->orWhere('name', 'ilike', '%' . $this->search . '%');
            })->orWhere('seriecompleta', 'ilike', ['%' . $this->search . '%']);
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
            $this->authorize('admin.ventas.deletes');
            $ventas->onlyTrashed();
        }

        $ventas = $ventas->orderBy('date', 'desc')->paginate();

        return view('livewire.modules.ventas.ventas.show-ventas', compact('ventas', 'users'));
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
        $this->authorize('admin.ventas.deletes');
        $this->resetPage();
    }
}
