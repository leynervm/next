<?php

namespace App\Http\Livewire\Admin\Promociones;

use App\Enums\PromocionesEnum;
use App\Models\Pricetype;
use App\Models\Producto;
use App\Models\Promocion;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class ShowPromociones extends Component
{

    use WithPagination;
    use AuthorizesRequests;

    protected $queryString = [
        'estado' => ['except' => Promocion::ACTIVO],
    ];
    protected $listeners = ['render'];
    public $promocion, $pricetype_id, $pricetype;
    public $estado = Promocion::ACTIVO;
    public $open = false;
    public $agotarstock = false;
    public $limitstock;

    protected function rules()
    {
        return [
            'limitstock' => ['required', 'numeric', 'gt:0'],
            'promocion.limit' => ['nullable', Rule::requiredIf(!$this->agotarstock), 'numeric', 'min:1',  'max:' . $this->limitstock],
            'promocion.titulo' => ['nullable', Rule::requiredIf($this->promocion->isCombo()), 'string', 'min:6'],
            'promocion.type' => ['required', 'integer', Rule::in(PromocionesEnum::values())],
            'promocion.descuento' => [
                'nullable',
                Rule::requiredIf(in_array($this->promocion->type, [PromocionesEnum::DESCUENTO->value, PromocionesEnum::OFERTA->value])),
                'numeric',
                'gt:0',
                'max:100',
                'decimal:0,2'
            ],
            'promocion.startdate' => ['nullable', 'date', /* 'after_or_equal:' . now('America/Lima')->format('Y-m-d') */],
            'promocion.expiredate' => ['nullable', Rule::requiredIf(!empty($this->startdate)), 'date', 'after_or_equal:' . now('America/Lima')->format('Y-m-d'), 'after_or_equal:startdate'],
        ];
    }

    public function mount()
    {
        $this->estado = Promocion::ACTIVO;
        $this->promocion = new Promocion();
        $empresa = view()->shared('empresa');
        if ($empresa->usarLista()) {
            $pricetypes = Pricetype::activos()->default();
            if (count($pricetypes->get()) > 0) {
                $this->pricetype = $pricetypes->first();
                $this->pricetype_id = $this->pricetype->id ?? null;
            } else {
                $this->pricetype = Pricetype::activos()->orderBy('id', 'asc')->first();
                $this->pricetype_id = $this->pricetype->id ?? null;
            }
        }
    }

    public function render()
    {
        $pricetypes = Pricetype::activos()->orderBy('id', 'asc')->get();
        $promociones = Promocion::query()->with(['producto' => function ($query) {
            $query->select('id', 'name', 'pricebuy', 'pricesale', 'precio_1', 'precio_2', 'precio_3', 'precio_4', 'precio_5', 'requireserie', 'unit_id')
                ->with(['almacens', 'unit', 'imagen']);
        }, 'itempromos' => function ($query) {
            $query->with(['producto' => function ($subQuery) {
                $subQuery->with(['almacens', 'imagen']);
            }]);
        }]);
        if (trim($this->estado) != '') {
            $promociones->where('status', $this->estado);
        }
        $promociones = $promociones->orderBy('id', 'desc')->paginate();
        return view('livewire.admin.promociones.show-promociones', compact('promociones', 'pricetypes'));
    }

    public function updatedDisponibles($value)
    {
        $this->resetPage();
    }

    public function delete(Promocion $promocion)
    {
        $this->authorize('admin.promociones.delete');
        if ($promocion->itempromos()->exists()) {
            $promocion->itempromos()->delete();
        }
        $promocion->delete();
        $this->dispatchBrowserEvent('toast', toastJSON('Promoción eliminado correctamente'));
    }

    public function finalizarpromocion(Promocion $promocion)
    {
        $this->authorize('admin.promociones.edit');
        $promocion->status = Promocion::FINALIZADO;
        $promocion->save();
        $this->dispatchBrowserEvent('toast', toastJSON('Promoción finalizado correctamente'));
    }

    public function disablepromocion(Promocion $promocion)
    {
        $this->authorize('admin.promociones.edit');
        $promocion->status = $promocion->isActivo() ? Promocion::DESACTIVADO : Promocion::ACTIVO;
        if ($promocion->isActivo()) {
            if (!is_null($promocion->expiredate)) {
                if (Carbon::now('America/Lima')->gt(Carbon::parse($promocion->expiredate)->format('d-m-Y'))) {
                    $mensaje = response()->json([
                        'title' => 'PROMOCIÓN NO DISPONIBLE, LA FECHA HA EXPIRADO !',
                        'text' => null
                    ])->getData();
                    $this->dispatchBrowserEvent('validation', $mensaje);
                    return false;
                }
            }

            $existotherpromociones = Promocion::disponibles()->where('producto_id', $promocion->producto_id)->exists();
            if ($existotherpromociones) {
                $mensaje = response()->json([
                    'title' => "PRODUCTO YA CUENTA CON PROMOCIONES ACTIVAS !",
                    'text' => null
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
            }

            $existproductoitemcombos = Promocion::disponibles()->withWhereHas('itempromos', function ($query) use ($promocion) {
                $query->where('producto_id', $promocion->producto_id);
            })->exists();

            if ($existproductoitemcombos) {
                $mensaje = response()->json([
                    'title' =>  "PRODUCTO SE ENCUENTRA INCLUIDO DENTRO DE UNA PROMOCIÓN (COMBO) !",
                    'text' => null
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
            }
        }
        $promocion->save();
        $this->dispatchBrowserEvent('updated');
    }

    public function updatedPricetypeId($value)
    {
        if ($value) {
            $this->pricetype = Pricetype::find($value);
            $this->pricetype_id = $this->pricetype->id;
        }
    }

    public function edit(Promocion $promocion)
    {
        $this->resetValidation();
        // $this->reset(['promocion']);
        $promocion->load(['itempromos' => function ($db) {
            $db->with(['producto' => function ($query) {
                $query->select('id', 'name', 'pricebuy', 'pricesale', 'precio_1', 'precio_2', 'precio_3', 'precio_4', 'precio_5', 'unit_id')
                    ->with(['almacens', 'unit', 'imagen']);
            }]);
        }, 'producto' => function ($query) {
            $query->select('id', 'name', 'pricebuy', 'pricesale', 'precio_1', 'precio_2', 'precio_3', 'precio_4', 'precio_5', 'unit_id')
                ->with(['almacens', 'imagen', 'unit']);
        }]);

        $this->promocion = $promocion;
        $this->agotarstock = is_null($promocion->limit) ? true : false;
        if (!is_null($this->promocion->startdate)) {
            $this->promocion->startdate = formatDate($promocion->startdate, 'Y-MM-DD');
        }
        if (!is_null($this->promocion->expiredate)) {
            $this->promocion->expiredate = formatDate($promocion->expiredate, 'Y-MM-DD');
        }
        $this->open = true;
    }

    public function update()
    {
        $this->limitstock =  decimalOrInteger(Producto::find($this->promocion->producto_id)->almacens()->sum('cantidad'));
        $this->promocion->limit = $this->agotarstock ? $this->limitstock : $this->promocion->limit;
        if (empty($this->promocion->startdate)) {
            $this->promocion->startdate = null;
        }
        if (empty($this->promocion->expiredate)) {
            $this->promocion->expiredate = null;
        }
        $this->validate();
        if (!empty($this->promocion->expiredate)) {
            $this->promocion->expiredate = Carbon::parse($this->promocion->expiredate)->endOfDay();
        }
        // if ($this->promocion->isCombo()) {
        //     foreach ($this->promocion->itempromos as $item) {
        //         $stocksec = $item->producto->almacens()->sum('cantidad') ?? 0;
        // if ($stocksec < $this->limitstock) {
        //     $mensaje =  response()->json([
        //         'title' => "STOCK DEL PRODUCTO ",
        //         'text' => null
        //     ])->getData();
        //     $this->dispatchBrowserEvent('validation', $mensaje);
        //     return false;
        // }
        //     }
        // }

        $this->promocion->save();
        $this->resetValidation();
        $this->open = false;
    }

    // public function hydrate()
    // {
    //     if ($this->promocion) {
    //         $this->promocion->load(['itempromos' => function ($db) {
    //             $db->with(['producto' => function ($query) {
    //                 $query->select('id', 'name', 'pricebuy', 'pricesale', 'precio_1', 'precio_2', 'precio_3', 'precio_4', 'precio_5', 'unit_id')
    //                     ->with(['almacens', 'imagen', 'unit']);
    //             }]);
    //         }, 'producto' => function ($query) {
    //             $query->select('id', 'name', 'pricebuy', 'pricesale', 'precio_1', 'precio_2', 'precio_3', 'precio_4', 'precio_5', 'unit_id')
    //                 ->with(['almacens', 'imagen', 'unit']);
    //         }]);

    //         if (empty($this->promocion->startdate)) {
    //             $this->promocion->startdate = null;
    //         } else {
    //             // $this->promocion->startdate = ;
    //         }
    //         if (empty($this->promocion->expiredate)) {
    //             $this->promocion->expiredate = null;
    //         } else {
    //             // $this->promocion->startdate = ;
    //         }
    //     }
    // }
}
