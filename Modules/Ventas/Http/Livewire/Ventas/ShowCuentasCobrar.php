<?php

namespace Modules\Ventas\Http\Livewire\Ventas;

use Livewire\Component;
use Modules\Ventas\Entities\Cuota;
use Livewire\WithPagination;
use Modules\Ventas\Entities\Venta;

class ShowCuentasCobrar extends Component
{

    use WithPagination;

    public $search = '';
    public $datepay = '';
    public $amountdeuda = 0;
    public $page = 1;

    protected $queryString = [
        'search' => ['except' => ''],
        'datepay' => ['except' => ''],
        'page' => ['except' => 1]
    ];

    public function mount()
    {
        $this->amountdeuda = Cuota::whereNull('cajamovimiento_id')->sum('amount');
    }

    public function render()
    {

        $this->amountdeuda = Cuota::whereNull('cajamovimiento_id')->sum('amount');
        $cuotas = Venta::whereNull('cajamovimiento_id')
            ->whereHas('nextpagos')->with('nextpagos');

        if (trim($this->search) !== '') {
            $cuotas->whereHas('comprobante', function ($query) {
                $query->where('seriecompleta', 'ilike', '%' . $this->search . '%');
            });
        }

        if (trim($this->datepay) !== '') {
            $cuotas->whereHas('nextpagos', function ($query) {
                $query->where('expiredate', $this->datepay);
            })
                ->with(['nextpagos' => function ($query) {
                    $query->where('expiredate', $this->datepay);
                }]);

            $this->amountdeuda = $cuotas->get()->sum(function ($venta) {
                return $venta->nextpagos->sum('amount');
            });
        }

        $cuotas = $cuotas->orderBy('date', 'desc')->orderBy('total', 'desc')->paginate();

        return view('ventas::livewire.ventas.show-cuentas-cobrar', compact('cuotas'));
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
