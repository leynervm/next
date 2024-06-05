<?php

namespace App\Http\Livewire\Modules\Almacen\Compras;

use App\Models\Sucursal;
use App\Models\Typepayment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Almacen\Entities\Compra;

class ShowCompras extends Component
{

    use AuthorizesRequests;
    use WithPagination;

    public $search = '';
    public $searchsucursal = '';
    public $date = '';
    public $typepayment = '';
    public $deletes = false;

    protected $queryString = [
        'search' => ['except' => '', 'as' => 'buscar'],
        'searchsucursal' => ['except' => '', 'as' => 'sucursal'],
        'typepayment' => ['except' => '', 'as' => 'tipo-pago'],
        'date' => ['except' => '', 'as' => 'fecha-compra'],
        'deletes' => ['except' => false, 'as' => 'eliminados'],
    ];

    public function render()
    {

        $compras = Compra::with('sucursal')->where('sucursal_id', auth()->user()->sucursal_id);
        $typepayments = Typepayment::whereHas('compras')->get();

        if (trim($this->search) !== '') {
            $compras->whereHas('proveedor', function ($query) {
                $query->where('document', 'ilike', '%' . $this->search . '%')
                    ->orWhere('name', 'ilike', '%' . $this->search . '%');
            })->orWhere('referencia', 'ilike', '%' . $this->search . '%');
        }

        if ($this->date) {
            $compras->whereDate('date', $this->date);
        }

        if (trim($this->typepayment) != '') {
            $compras->where('typepayment_id', $this->typepayment);
        }

        if ($this->deletes) {
            $this->authorize('admin.almacen.compras.deletes');
            $compras->onlyTrashed();
        }

        $compras = $compras->orderBy('created_at', 'desc')->paginate();

        return view('livewire.modules.almacen.compras.show-compras', compact('compras', 'typepayments'));
    }

    public function updatedDate($value)
    {
        $this->resetPage();
    }

    public function updatedSearchsucursal($value)
    {
        $this->resetPage();
    }

    public function updatedDeletes()
    {
        $this->authorize('admin.almacen.compras.deletes');
        $this->resetPage();
    }
}
