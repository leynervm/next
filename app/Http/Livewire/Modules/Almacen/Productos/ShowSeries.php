<?php

namespace App\Http\Livewire\Modules\Almacen\Productos;

use App\Models\Producto;
use App\Models\Serie;
use App\Rules\CampoUnique;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ShowSeries extends Component
{

    use WithPagination, AuthorizesRequests;
    public $producto;

    public $searchseriealmacen = '';
    public $disponibles = "0";
    public $almacen_id, $serie;

    protected $queryString = [
        'searchseriealmacen' => [
            'except' => '',
            'as' => 'series-almacen'
        ],
        'disponibles' => [
            'except' => '0',
            'as' => 'disponibles'
        ]
    ];

    // Escucha render method desde show-productos
    public function rules()
    {
        return [
            'producto.id' => ['required', 'integer', 'min:1', 'exists:productos,id'],
            'almacen_id' => ['required', 'integer', 'min:1', 'exists:almacens,id'],
            'serie' => ['required', 'string', 'min:3', new CampoUnique('series', 'serie', null, true)]
        ];
    }

    public function mount(Producto $producto)
    {
        $this->producto = $producto;
    }

    public function render()
    {

        $seriesalmacen = $this->producto->series()->with(['almacen' => function ($query) {
            $query->withTrashed();
        }])/* ->whereHas('almacen') */->orderBy('serie', 'asc');

        if (trim($this->disponibles) !== '') {
            $seriesalmacen->where('status', $this->disponibles);
        }

        if (trim($this->searchseriealmacen) !== '') {
            $seriesalmacen->where('almacen_id', $this->searchseriealmacen);
        }
        $seriesalmacen = $seriesalmacen->paginate(30);

        return view('livewire.modules.almacen.productos.show-series', compact('seriesalmacen'));
    }

    public function resetfilter()
    {
        $this->reset(['searchseriealmacen', 'disponibles', 'almacen_id']);
        $this->resetPage();
    }

    public function save()
    {
        $this->authorize('admin.almacen.productos.series.edit');
        $this->serie = trim(mb_strtoupper($this->serie, "UTF-8"));
        $this->validate();
        DB::beginTransaction();
        try {
            $seriesDisponibles = $this->producto->seriesdisponibles
                ->where('almacen_id', $this->almacen_id)->count();

            $stockAlmacen = $this->producto->almacens
                ->find($this->almacen_id)->pivot->cantidad;

            if ($seriesDisponibles >= $stockAlmacen) {
                $this->addError('serie', 'Serie sobrepase el stock disponible en almacén.');
                return false;
            }

            $existSerie = Serie::onlyTrashed()->where('serie', $this->serie)->first();
            $serie = [
                'date' => now('America/Lima'),
                'serie' => $this->serie,
                'almacen_id' => $this->almacen_id,
                'user_id' => auth()->user()->id,
            ];

            if ($existSerie) {
                $existSerie->restore();
                $serie['created_at'] = now('America/Lima');
                $serie['updated_at'] = now('America/Lima');
                $existSerie->update($serie);
            } else {
                $this->producto->series()->create($serie);
            }
            DB::commit();
            $this->resetValidation();
            $this->reset(['serie', 'almacen_id']);
            $this->producto->refresh();
            $this->dispatchBrowserEvent('created');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(Serie $serie)
    {
        $this->authorize('admin.almacen.productos.series.edit');
        $serie->load(['carshoopserie', 'itemserie']);

        if (!empty($serie->itemserie)) {
            $mensaje = response()->json([
                'title' => "LA SERIE " . $serie->serie . " SE ENCUENTRA VINCULADO A OTROS REGISTROS",
                'text' => null
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }

        if (!empty($serie->carshoopserie)) {
            $mensaje = response()->json([
                'title' => "LA SERIE " . $serie->serie . " SE ENCUENTRA AGREGADO EN EL CARRITO DE VENTAS",
                'text' => null
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }

        DB::beginTransaction();
        try {
            if ($serie->isDisponible()) {
                $serie->forceDelete();
                DB::commit();
                $this->producto->refresh();
                $this->dispatchBrowserEvent('deleted');
            } else {
                $mensaje = response()->json([
                    'title' => 'LA SERIE ' . $serie->serie . ' SE ENCUENTRA VINCULADO A OTROS REGISTROS',
                    'text' => null,
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updatedSearchseriealmacen()
    {
        $this->resetPage();
    }
}
