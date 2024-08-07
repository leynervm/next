<?php

namespace App\Http\Livewire\Modules\Ventas\Ventas;

use App\Models\Cuota;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Ventas\Entities\Venta;

class ShowCuentasCobrar extends Component
{

    use WithPagination;

    public $search = '';
    public $comprobante = '';
    public $datepay = '';
    public $page = 1;

    protected $queryString = [
        'search' => ['except' => '', 'as' => 'cliente'],
        'comprobante' => ['except' => ''],
        'datepay' => ['except' => ''],
        'page' => ['except' => 1]
    ];

    public function render()
    {

        $sumatorias = Venta::with(['moneda'])
            ->where('sucursal_id', auth()->user()->sucursal_id)
            ->whereColumn('paymentactual', '<', 'total')
            ->selectRaw('moneda_id, SUM(total-paymentactual) as total')
            ->groupBy('moneda_id')->orderBy('total', 'desc')->get();

        $cuotas = Venta::with('sucursal')->withWhereHas('client', function ($query) {
            if (trim($this->search) !== '') {
                $query->where('document', 'ILIKE', '%' . $this->search . '%')
                    ->orWhere('name', 'ILIKE', '%' . $this->search . '%');
            }
        })->whereColumn('paymentactual', '<', 'total')
            ->where('sucursal_id', auth()->user()->sucursal_id);

        if (trim($this->comprobante) !== '') {
            $cuotas->where('seriecompleta', 'ILIKE', '%' . $this->comprobante . '%');
            // $cuotas->whereRaw("CONCAT(code, '-', id) ILIKE ?", ['%' . $this->search . '%']);
        }

        $cuotas = $cuotas->orderBy('date', 'desc')->paginate();

        return view('livewire.modules.ventas.ventas.show-cuentas-cobrar', compact('cuotas', 'sumatorias'));
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedDatepay()
    {
        $this->resetPage();
    }
}
