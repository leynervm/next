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
    public $comprobante = '';
    public $datepay = '';
    public $amountdeuda = 0;
    public $page = 1;

    protected $queryString = [
        'search' => ['except' => '', 'as' => 'cliente'],
        'comprobante' => ['except' => ''],
        'datepay' => ['except' => ''],
        'page' => ['except' => 1]
    ];

    public function mount()
    {
        $this->amountdeuda = Cuota::whereHasMorph('cuotable', Venta::class)->doesntHave('cajamovimiento')->sum('amount');
    }

    public function render()
    {

        $cuotas = Venta::with(['client' => function ($query) {
            if (trim($this->search) !== '') {
                $query->where('document', 'ILIKE', '%' . $this->search . '%')
                    ->orWhere('name', 'ILIKE', '%' . $this->search . '%');
            }
        }])->withWhereHas('cuotas', function ($query) {
            $query->whereDoesntHave('cajamovimiento')
                ->orderBy('expiredate', 'asc');
            if (trim($this->datepay) !== '') {
                $query->where('expiredate', $this->datepay);
            }
        });

        if (trim($this->comprobante) !== '') {
            $cuotas->where('code', 'ILIKE', '%' . $this->comprobante . '%');
            // $cuotas->whereRaw("CONCAT(code, '-', id) ILIKE ?", ['%' . $this->search . '%']);
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
