<?php

namespace Modules\Almacen\Http\Livewire\Ofertas;

use App\Models\Pricetype;
use App\Models\Rango;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Almacen\Entities\Oferta;
use Modules\Almacen\Entities\Producto;

class ShowOfertas extends Component
{

    use WithPagination;

    public $oferta;
    public $open = false;
    public $max = 0;
    public $minlimit = 0;
    public $maxlimit = 0;

    protected $listeners = ['render', 'delete'];

    protected function rules()
    {
        return [
            'oferta.descuento' => [
                'required', 'decimal:0,2', 'min:1',
            ],
            'oferta.dateexpire' => [
                'required', 'date', 'date_format:Y-m-d', 'after_or_equal:datestart',
            ],
            'oferta.limit' => [
                'required', 'decimal:0,2', 'min:' . $this->minlimit, 'max:' . $this->maxlimit
            ]
        ];
    }

    public function mount()
    {
        $this->oferta = new Oferta();
    }

    public function render()
    {
        $ofertas = Oferta::orderBy('datestart', 'asc')->orderBy('status', 'asc')
            ->orderBy('id', 'asc')->paginate();
        $pricetypes = Pricetype::orderBy('ganancia', 'asc')->get();

        foreach ($ofertas as $item) {

            $pricebuy = $item->producto->pricebuy;
            $gananciaRango = Rango::where('desde', '<=', $pricebuy)
                ->where('hasta', '>=', $pricebuy)
                ->first();

            if ($gananciaRango) {
                $porcentajeGanancia = $gananciaRango->incremento;
                $precioVenta = $pricebuy + ($pricebuy * $porcentajeGanancia / 100);
                // Asignar el precio de venta al producto
                $item->priceventa = $precioVenta;
            } else {

                $item->priceventa = $pricebuy;
                // Si no se encuentra un rango de ganancia para el precio de compra dado,
                // $product->precio_venta = $precioCompra; // Asignar el mismo precio de compra como precio de venta.
            }
        }

        return view('almacen::livewire.ofertas.show-ofertas', compact('ofertas', 'pricetypes'));
    }

    public function updatedMax($value)
    {

        $this->resetValidation(['max']);
        $this->reset(['max']);

        if ($value == 1) {
            if ($this->oferta->almacen_id) {
                if ($this->oferta->producto_id) {

                    $this->oferta->limit =  Producto::findOrFail($this->oferta->producto_id)->almacens
                        ->find($this->oferta->almacen_id)->pivot->cantidad;
                    $this->resetValidation(['max']);
                    $this->max = 1;
                } else {
                    $this->addError('max', 'Seleccione el campo producto_');
                }
            } else {
                $this->addError('max', 'seleccione el campo almacén');
            }
        }
    }

    public function edit(Oferta $oferta)
    {
        $this->oferta = $oferta;
        $this->minlimit = $oferta->vendidos == 0 ? 1 : $oferta->vendidos;
        $this->oferta->dateexpire = Carbon::parse($this->oferta->dateexpire)->format('Y-m-d');
        $this->maxlimit =  Producto::findOrFail($oferta->producto_id)->almacens
            ->find($oferta->almacen_id)->pivot->cantidad;
        $this->resetValidation();
        $this->open = true;
    }

    public function update()
    {
        $this->validate();
        $this->oferta->disponible = $this->oferta->limit - $this->oferta->vendidos;
        $this->oferta->save();
        $this->reset(['open', 'max']);
    }

    public function confirmDelete(Oferta $oferta)
    {
        $this->dispatchBrowserEvent('ofertas.confirmDelete', $oferta);
    }

    public function delete(Oferta $oferta)
    {
        $oferta->deleteOrFail();
        $this->dispatchBrowserEvent('deleted');
    }
}
