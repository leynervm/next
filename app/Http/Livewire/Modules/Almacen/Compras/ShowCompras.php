<?php

namespace App\Http\Livewire\Modules\Almacen\Compras;

use App\Models\Sucursal;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Almacen\Entities\Compra;

class ShowCompras extends Component
{

    use WithPagination;

    public $search = '';
    public $searchsucursal = '';
    public $date = '';
    public $deletes = false;

    protected $queryString = [
        'search' => ['except' => '', 'as' => 'buscar'],
        'searchsucursal' => ['except' => '', 'as' => 'sucursal'],
        'date' => ['except' => '', 'as' => 'fecha-compra'],
        'deletes' => ['except' => false, 'as' => 'eliminados'],
    ];

    public function render()
    {

        $compras = Compra::withWhereHas('sucursal', function ($query) {
            $query->withTrashed();
            if ($this->searchsucursal) {
                $query->where('id', $this->searchsucursal);
            } else {
                $query->where('id', auth()->user()->sucursal_id);
            }
        });
        $sucursals = Sucursal::withTrashed()->whereHas('compras')->get();

        if (trim($this->search) !== '') {
            $compras->whereHas('proveedor', function ($query) {
                $query->where('document', 'ilike', '%' . $this->search . '%')
                    ->orWhere('name', 'ilike', '%' . $this->search . '%');
            })->orWhere('referencia', 'ilike', '%' . $this->search . '%');
        }

        if ($this->date) {
            $compras->whereDate('date', $this->date);
        }

        if ($this->deletes) {
            $compras->onlyTrashed();
        }

        $compras = $compras->orderBy('created_at', 'desc')->paginate();

        return view('livewire.modules.almacen.compras.show-compras', compact('compras', 'sucursals'));
    }

    public function updatedDate($value)
    {
        $this->resetPage();
    }

    public function updatedSearchsucursal($value)
    {
        $this->resetPage();
    }
}
