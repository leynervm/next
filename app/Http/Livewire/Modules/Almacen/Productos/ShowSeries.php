<?php

namespace App\Http\Livewire\Modules\Almacen\Productos;

use App\Models\Producto;
use App\Models\Serie;
use App\Rules\CampoUnique;
use Livewire\Component;
use Livewire\WithPagination;

class ShowSeries extends Component
{

    use WithPagination;
    public $producto;

    public $searchseriealmacen = [];
    public $disponibles = "0";
    public $almacen_id, $serie;

    protected $queryString = [
        'searchseriealmacen' => [
            'except' => [],
            'as' => 'filtrar-almacen'
        ],
        'disponibles' => [
            'except' => '0',
            'as' => 'disponibles'
        ]
    ];

    // Escucha render method desde show-productos
    protected $listeners = ['resetfilter', 'delete'];

    public function rules()
    {
        return [
            'producto.id' => [
                'required', 'integer', 'min:1', 'exists:productos,id'
            ],
            'almacen_id' => [
                'required', 'integer', 'min:1', 'exists:almacens,id'
            ],
            'serie' => [
                'required', 'string', 'min:3', new CampoUnique('series', 'serie', null, true)
            ]
        ];
    }

    public function mount(Producto $producto)
    {
        $this->producto = $producto;
    }

    public function render()
    {

        $seriesalmacen = $this->producto->series()->orderBy('serie', 'asc');

        if (trim($this->disponibles) !== '') {
            $seriesalmacen->where('status', $this->disponibles);
        }

        if (count($this->searchseriealmacen)) {
            $seriesalmacen->whereIn('almacen_id', $this->searchseriealmacen);
        }
        $seriesalmacen = $seriesalmacen->paginate(30);

        return view('livewire.modules.almacen.productos.show-series', compact('seriesalmacen'));
    }

    public function resetfilter()
    {

        $this->reset(['searchseriealmacen', 'disponibles', 'almacen_id']);
        $this->resetPage();
        $this->render();
    }

    public function save()
    {
        $this->serie = trim(mb_strtoupper($this->serie, "UTF-8"));
        $this->validate();

        $seriesDisponibles = $this->producto->seriesdisponibles
            ->where('almacen_id', $this->almacen_id)->count();

        $stockAlmacen = $this->producto->almacens
            ->find($this->almacen_id)->pivot->cantidad;

        if ($seriesDisponibles >= $stockAlmacen) {
            $this->addError('serie', 'Serie sobrepase el stock disponible en almacén.');
            return false;
        }

        $this->producto->series()->create([
            'date' => now('America/Lima'),
            'serie' => $this->serie,
            'almacen_id' => $this->almacen_id,
            'user_id' => auth()->user()->id,
        ]);

        $this->resetValidation();
        $this->reset(['serie', 'almacen_id']);
        $this->producto->refresh();
        $this->dispatchBrowserEvent('created');
    }

    public function delete(Serie $serie)
    {

        $itemseries = $serie->itemserie()->exists();

        $cadena = extraerMensaje([
            'Items_ventas' => $itemseries,
        ]);

        if ($itemseries > 0) {
            $mensaje = response()->json([
                'title' => 'No se puede eliminar registro, ' . $serie->serie,
                'text' => "Existen registros vinculados $cadena, eliminarlo causaría un conflicto en la base de datos."
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
        } else {

            if ($serie->status == 1) {
                $mensaje = response()->json([
                    'title' => 'No se puede eliminar serie, ' . $serie->serie,
                    'text' => "La serie se encuentra agregado al carrito de ventas, eliminarlo causaría un conflicto en la base de datos."
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
            } else {
                $serie->deleteOrFail();
                $this->producto->refresh();
                $this->dispatchBrowserEvent('deleted');
            }
        }
    }
}
