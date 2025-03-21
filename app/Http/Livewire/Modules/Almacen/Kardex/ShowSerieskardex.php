<?php

namespace App\Http\Livewire\Modules\Almacen\Kardex;

use App\Models\Almacen;
use App\Models\Carshoopitem;
use App\Models\Serie;
use App\Models\Sucursal;
use App\Models\Tvitem;
use Livewire\Component;
use Livewire\WithPagination;

class ShowSerieskardex extends Component
{

    use WithPagination;

    public $date, $dateto;
    public $searchserie = '';
    public $searchalmacen = '';
    public $searchsucursal = '';

    protected $queryString = [
        'date' => [
            'except' => '',
            'as' => 'fecha'
        ],
        'dateto' => [
            'except' => '',
            'as' => 'hasta'
        ],
        'searchserie' => [
            'except' => '',
            'as' => 'serie'
        ],
        'searchalmacen' => [
            'except' => '',
            'as' => 'almacen'
        ],
        'searchsucursal' => [
            'except' => '',
            'as' => 'sucursal'
        ],
    ];

    public function render()
    {

        $sucursals = Sucursal::withTrashed()->whereHas('almacens')->get();
        $almacens = Almacen::whereHas('series')->get();
        $serieskardex = Serie::with(['almacen', 'producto', 'almacencompra.compraitem.compra.proveedor', 'user', 'itemseries' => function ($query) {
            $query->with(['seriable' => function ($subq) {
                // $subq->when(Tvitem::class, fn($q) => $q->with(['tvitem']))
                //     ->when(Carshoopitem::class, fn($q) => $q->with([

                //     ]));
            }]);
        }]);

        if ($this->searchalmacen !== '') {
            $serieskardex->where('almacen_id', $this->searchalmacen);
        } else {
            $serieskardex->whereIn('almacen_id', auth()->user()->sucursal->almacens->pluck('id')->toArray());
        }

        if ($this->searchserie !== '') {
            $serieskardex->where('serie', 'ilike', '%' . $this->searchserie . '%');
        }

        if ($this->date) {
            if ($this->dateto) {
                $serieskardex->whereDateBetween('dateout', $this->date, $this->dateto);
            } else {
                $serieskardex->whereDate('dateout', $this->date);
            }
        }

        $serieskardex = $serieskardex->whereIn('status', [Serie::SALIDA, Serie::RESERVADA])
            ->orderBy('status', 'desc')->orderBy('created_at', 'desc')->paginate();
        return view('livewire.modules.almacen.kardex.show-serieskardex', compact('serieskardex', 'almacens', 'sucursals'));
    }

    public function updatedDate()
    {
        $this->resetPage();
    }

    public function updatedDateto()
    {
        $this->resetPage();
    }

    public function updatedSearchserie()
    {
        $this->resetPage();
    }

    public function updatedSearchsucursal()
    {
        $this->resetPage();
    }

    public function updatedSearchalmacen()
    {
        $this->resetPage();
    }
}
