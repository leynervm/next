<?php

namespace Modules\Ventas\Http\Livewire\Ventas;

use App\Models\Cuota;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Ventas\Entities\Venta;
use Nwidart\Modules\Facades\Module;

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
        $this->amountdeuda = Cuota::doesntHave('cajamovimiento')->sum('amount');
    }

    public function render()
    {

        $cuotas = Venta::withWhereHas('cuotas', function ($query) {
            $query->whereDoesntHave('cajamovimiento')
                ->orderBy('expiredate', 'asc');
            if (trim($this->datepay) !== '') {
                $query->where('expiredate', $this->datepay);
            }
        });

        if (trim($this->search) !== '') {
            if (Module::isEnabled('Facturacion')) {
                $cuotas->withWhereHas('comprobante', function ($query) {
                    if (trim($this->search) !== '') {
                        $query->where('seriecompleta', 'ilike', '%' . $this->search . '%');
                    }
                });
            } else {
                $cuotas->whereRaw("CONCAT(code, '-', id) ILIKE ?", ['%' . $this->search . '%']);
                // ->orWhereRaw("CONCAT(code, '-', id) ILIKE ?", ['%' . $this->search . '%']);
            }
        }

        $cuotas = $cuotas->orderBy('date', 'desc')->paginate();

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
