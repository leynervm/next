<?php

namespace App\Http\Livewire\Admin\Cajas;

use App\Models\Caja;
use App\Models\Cajamovimiento;
use App\Models\Concept;
use App\Models\Methodpayment;
use App\Models\Sucursal;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class ShowMovimientos extends Component
{

    use WithPagination;

    public $date, $dateto;
    public $searchtype = '';
    public $searchmethodpayment = '';
    public $searchconcept = '';
    public $searchuser = '';
    public $searchsucursal = '';
    public $searchcaja = '';

    protected $queryString = [
        'date' => [
            'except' => '',
            'as' => 'fecha'
        ],
        'dateto' => [
            'except' => '',
            'as' => 'hasta'
        ],
        'searchtype' => [
            'except' => '',
            'as' => 'movimiento'
        ],
        'searchmethodpayment' => [
            'except' => '',
            'as' => 'forma-pago'
        ],
        'searchconcept' => [
            'except' => '',
            'as' => 'concepto'
        ],
        'searchuser' => [
            'except' => '',
            'as' => 'usuario'
        ],
        'searchsucursal' => [
            'except' => '',
            'as' => 'sucursal'
        ],
        'searchcaja' => [
            'except' => '',
            'as' => 'caja'
        ],
    ];

    public function render()
    {
        $methodpayments = Methodpayment::whereHas('cajamovimientos')->get();
        $concepts = Concept::whereHas('cajamovimientos')->get();
        $users = User::whereHas('cajamovimientos')->get();
        $sucursals = Sucursal::withTrashed()->whereHas('cajamovimientos')->get();
        $cajas = Caja::whereHas('opencajas', function ($query) {
            $query->whereHas('cajamovimientos');
        })->withTrashed()->get();

        $movimientos = Cajamovimiento::withWhereHas('sucursal', function ($query) {
            $query->withTrashed();
            if ($this->searchsucursal !== '') {
                $query->where('id', $this->searchsucursal);
            } else {
                $query->where('id', auth()->user()->sucursal_id);
            }
        });

        if ($this->date) {
            if ($this->dateto) {
                $movimientos->whereDateBetween('date', $this->date, $this->dateto);
            } else {
                $movimientos->whereDate('date', $this->date);
            }
        }
        if ($this->searchtype !== '') {
            $movimientos->where('typemovement', $this->searchtype);
        }
        if ($this->searchmethodpayment !== '') {
            $movimientos->where('methodpayment_id', $this->searchmethodpayment);
        }
        if ($this->searchconcept !== '') {
            $movimientos->where('concept_id', $this->searchconcept);
        }
        if ($this->searchuser !== '') {
            $movimientos->where('user_id', $this->searchuser);
        }

        if ($this->searchcaja !== '') {
            $movimientos->whereHas('opencaja', function ($query) {
                $query->where('caja_id', $this->searchcaja);
            });
        }

        $movimientos = $movimientos->orderBy('date', 'desc')->paginate();
        return view('livewire.admin.cajas.show-movimientos', compact('movimientos', 'methodpayments', 'concepts', 'users', 'sucursals', 'cajas', 'movimientos'));
    }

    public function hydrate()
    {
        $this->dispatchBrowserEvent('render-show-movimientos');
    }
}
