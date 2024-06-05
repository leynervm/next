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

        $sumatorias = Cuota::with(['moneda'])
            ->where('sucursal_id', auth()->user()->sucursal_id)
            ->whereHasMorph('cuotable', Venta::class)->doesntHave('cajamovimiento')
            ->selectRaw('moneda_id, SUM(amount) as total')
            ->groupBy('moneda_id')->orderBy('total', 'desc')->get();

        $cuotas = Venta::with('sucursal')->withWhereHas('client', function ($query) {
            if (trim($this->search) !== '') {
                $query->where('document', 'ILIKE', '%' . $this->search . '%')
                    ->orWhere('name', 'ILIKE', '%' . $this->search . '%');
            }
        })->withWhereHas('cuotas', function ($query) {
            $query->whereDoesntHave('cajamovimiento')
                ->orderBy('expiredate', 'asc');
            if (trim($this->datepay) !== '') {
                $query->where('expiredate', $this->datepay);
            }
        })->where('sucursal_id', auth()->user()->sucursal_id);

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
        // $this->reset(['datepay']);
    }

    public function updatedDatepay()
    {
        $this->resetPage();
        // $this->reset(['search']);
    }
}
