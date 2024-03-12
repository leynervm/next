<?php

namespace App\Http\Livewire\Admin\Cajamovimientos;

use App\Models\Box;
use App\Models\Cajamovimiento;
use App\Models\Concept;
use App\Models\Methodpayment;
use App\Models\Monthbox;
use App\Models\Sucursal;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class ShowCajamovimientos extends Component
{

    use WithPagination;

    public $date, $dateto;
    public $searchtype = '';
    public $searchmethodpayment = '';
    public $searchconcept = '';
    public $searchuser = '';
    public $searchsucursal = '';
    public $searchcaja = '';
    public $searchmonthbox = '';

    protected $listeners = ['render'];

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
        'searchmonthbox' => [
            'except' => '',
            'as' => 'caja-mensual'
        ]
    ];


    public function render()
    {

        $methodpayments = Methodpayment::whereHas('cajamovimientos')->get();
        $concepts = Concept::whereHas('cajamovimientos')->get();
        $monthboxes = Monthbox::whereHas('cajamovimientos')->get();
        $users = User::whereHas('cajamovimientos')->get();
        $sucursals = Sucursal::withTrashed()->whereHas('cajamovimientos')->get();
        $boxes = Box::whereHas('openboxes', function ($query) {
            $query->whereHas('cajamovimientos');
        })->withTrashed()->get();

        $movimientos = Cajamovimiento::with(['sucursal', 'concept', 'methodpayment', 'user', 'openbox', 'monthbox', 'moneda',])
            ->withWhereHas('sucursal', function ($query) {
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
            $movimientos->where('box_id', $this->searchcaja);
        }

        if ($this->searchmonthbox !== '') {
            $movimientos->where('monthbox_id', $this->searchmonthbox);
        }

        $movimientos = $movimientos->orderBy('date', 'desc')->paginate();
        return view('livewire.admin.cajamovimientos.show-cajamovimientos', compact('movimientos', 'methodpayments', 'concepts', 'users', 'sucursals', 'boxes', 'monthboxes', 'movimientos'));
    }

    public function delete(Cajamovimiento $cajamovimiento)
    {
        $cajamovimiento->forceDelete();
        $this->dispatchBrowserEvent('celeted');
    }

    public function hydrate()
    {
        $this->dispatchBrowserEvent('render-show-movimientos');
    }
}
