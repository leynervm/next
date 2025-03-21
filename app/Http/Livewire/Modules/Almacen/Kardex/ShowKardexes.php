<?php

namespace App\Http\Livewire\Modules\Almacen\Kardex;

use App\Models\Almacen;
use App\Models\Kardex;
use App\Models\Sucursal;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class ShowKardexes extends Component
{

    use WithPagination;

    public $date, $dateto;
    public $searchproducto = '';
    public $searchalmacen = '';
    public $searchuser = '';
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
        'searchproducto' => [
            'except' => '',
            'as' => 'producto'
        ],
        'searchalmacen' => [
            'except' => '',
            'as' => 'almacen'
        ],
        'searchuser' => [
            'except' => '',
            'as' => 'usuario'
        ],
        'searchsucursal' => [
            'except' => '',
            'as' => 'sucursal'
        ],
    ];

    public function render()
    {

        $sucursals = Sucursal::withTrashed()->whereHas('kardexes')->get();
        $users = User::whereHas('kardexes')->get();
        $almacens = Almacen::whereHas('kardexes')->get();
        $kardexes = Kardex::with(['user', 'almacen'])->withWhereHas('sucursal', function ($query) {
            if ($this->searchsucursal !== '') {
                $query->where('id', $this->searchsucursal);
            } else {
                $query->where('id', auth()->user()->sucursal_id);
            }
        })->withWhereHas('producto', function ($query) {
            if ($this->searchproducto !== '') {
                $query->where('name', 'ilike', '%' . $this->searchproducto . '%');
            }
        });

        if ($this->date) {
            if ($this->dateto) {
                $kardexes->whereDateBetween('date', $this->date, $this->dateto);
            } else {
                $kardexes->whereDate('date', $this->date);
            }
        }

        if ($this->searchalmacen !== '') {
            $kardexes->where('almacen_id', $this->searchalmacen);
        }
        if ($this->searchuser !== '') {
            $kardexes->where('user_id', $this->searchuser);
        }

        $kardexes = $kardexes->orderBy('date', 'desc')->paginate();

        return view('livewire.modules.almacen.kardex.show-kardexes', compact('kardexes', 'sucursals', 'users', 'almacens'));
    }

    public function updatedSearchproducto()
    {
        $this->resetPage();
    }

    public function updatedDate()
    {
        $this->resetPage();
    }

    public function updatedDateto()
    {
        $this->resetPage();
    }

    public function updatedSearchuser()
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
