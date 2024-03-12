<?php

namespace App\Http\Livewire\Admin\Promociones;

use App\Models\Producto;
use App\Models\Promocion;
use App\Rules\ValidatePrincipalCombo;
use App\Rules\ValidateSecondaryCombo;
use App\Rules\ValidateStockCombo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CreatePromocion extends Component
{

    public $open = false;
    public $agotarstock = false;

    public $producto;
    public $producto_id, $almacen_id, $type, $typecombo, $startdate, $expiredate;
    public $limit;

    public $limitstock = 0;
    public $limitstocksec = 0;
    public $descuento;

    public $productosec, $productosec_id, $cantidad;
    public $itempromos = [];

    protected function rules()
    {
        return [
            'producto_id' => [
                'required', 'integer', 'min:1', 'exists:productos,id',
                new ValidatePrincipalCombo($this->producto_id, $this->type),
            ],
            'limit' => ['nullable', Rule::requiredIf(!$this->agotarstock), 'numeric', 'min:1',  'max:' . $this->limitstock, 'decimal:0,2',],
            'startdate' => [
                'nullable', 'date', 'after_or_equal:' . now('America/Lima')->format('Y-m-d')
            ],
            'expiredate' => [
                'nullable', Rule::requiredIf(!empty($this->startdate)), 'date', 'after_or_equal:' . now('America/Lima')->format('Y-m-d'),
                'after_or_equal:startdate'
            ],
            'type' => ['required', 'integer', 'min:0', 'max:2'],
            'itempromos' => ['required', 'array', 'min:1', new ValidateStockCombo($this->producto_id, $this->limit)],
        ];
    }

    public function mount()
    {
        $this->producto = new Producto();
        $this->productosec = new Producto();
    }

    public function render()
    {
        $productos = Producto::whereHas('almacens', function ($query) {
            // $query->where('id', );
        })->orderBy('name', 'asc')->get();
        return view('livewire.admin.promociones.create-promocion', compact('productos'));
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->resetValidation();
            $this->resetExcept(['open']);
            Session::forget('combo');
            $comboCollect = getCombo();

            $comboitems = collect($comboCollect->get('comboitems') ?? []);
            $newscomboitems = $comboitems->push([
                'producto_id' => 34,
                'name' => 'PRODUCTO GRATIS',
                'category' => 'XXX',
                'descuento' => null
            ]);

            // dd($newscomboitems->all());
            // $comboCollect = $comboCollect->merge([
            //     'comboitems' => $newscomboitems,
            // ]);

            // $comboCollect = $comboCollect->merge([
            //     'comboitems' => [
            //         ['producto_id' => 401],
            //     ]
            // ]);
            // dd($comboCollect);
            $comboJSON = response()->json($comboCollect)->getData();
            Session::put('combo', $comboJSON);
            $this->resetValidation();
        }
    }

    public function updatedProductoId($value)
    {
        $this->reset(['producto']);
        if (trim($value) !== '') {
            $this->producto = Producto::with(['almacens', 'images', 'unit'])->find($value);
            $this->limitstock = formatDecimalOrInteger($this->producto->almacens()->sum('cantidad'));
        } else {
            $this->reset(['limit', 'limitstock']);
        }
    }

    public function updatedProductosecId($value)
    {
        $this->reset(['productosec']);
        if (trim($value) !== '') {
            $this->productosec = Producto::with(['almacens'])->find($value);
        }
    }

    public function confirmar()
    {

        if (!empty($this->producto_id) && $this->agotarstock == false) {
            $this->limitstock =  formatDecimalOrInteger(Producto::find($this->producto_id)->almacens()->sum('cantidad'));;
        }
        $this->limit = $this->agotarstock ? null : $this->limit;

        $this->validate([
            'producto_id' => [
                'required', 'integer', 'min:1', 'exists:productos,id',
                new ValidatePrincipalCombo($this->producto_id, $this->type),
            ],
            'limit' => ['nullable', Rule::requiredIf(!$this->agotarstock), 'numeric', 'min:1', 'max:' . $this->limitstock, 'decimal:0,2'],
            'limitstock' => ['required', 'numeric', 'min:0', 'gt:0'],
            'startdate' => [
                'nullable', 'date', 'after_or_equal:' . now('America/Lima')->format('Y-m-d')
            ],
            'expiredate' => [
                'nullable', Rule::requiredIf(!empty($this->startdate)), 'date', 'after_or_equal:' . now('America/Lima')->format('Y-m-d'),
                'after_or_equal:startdate'
            ],
            'type' => ['required', 'integer', 'min:0', 'max:2'],
            'descuento' => ['nullable', 'required_if:type,0', 'numeric', 'min:0', 'gt:0', 'max:100', 'decimal:0,2'],
        ]);

        // $comboCollect = getCombo();
        $promocion = [
            'producto_id' => $this->producto_id,
            'limit' => $this->limit,
            'descuento' => $this->descuento,
            'unit' => $this->producto->unit->name,
            'startdate' => !empty(trim($this->startdate)) ? $this->startdate :  null,
            'expiredate' => !empty(trim($this->expiredate)) ? $this->expiredate : null,
            'type' => $this->type,
            'comboitems' => [],
        ];

        if ($this->type == Promocion::COMBO) {
            $comboCollect = collect($promocion);
            $comboJSON = response()->json($comboCollect)->getData();
            Session::put('combo', $comboJSON);
        } else {
            Promocion::create($promocion);
            $this->reset();
            $this->dispatchBrowserEvent('created');
            $this->emitTo('admin.promociones.show-promociones', 'render');
        }
        $this->resetValidation();
    }

    public function save()
    {
        $this->itempromos = getCombo()->get('comboitems');
        $validateData = $this->validate();

        DB::beginTransaction();
        try {
            $promocion = Promocion::create($validateData);
            foreach ($this->itempromos as $item) {
                $promocion->itempromos()->create([
                    'producto_id' => $item->producto_id,
                    'descuento' => $item->descuento,
                    'typecombo' => $item->typecombo,
                ]);
            }

            DB::commit();
            $this->emitTo('admin.promociones.show-promociones', 'render');
            $this->resetValidation();
            $this->dispatchBrowserEvent('created');
            $this->reset();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function add()
    {
        if (!empty($this->producto_id)) {
            $this->limitstock = formatDecimalOrInteger(Producto::find($this->producto_id)->almacens()->sum('cantidad'));
        }

        if (!empty($this->productosec_id)) {
            $this->limitstocksec = formatDecimalOrInteger(Producto::find($this->productosec_id)->almacens()->sum('cantidad'));
        }

        $this->validate([
            'producto_id' => [
                'required', 'integer', 'min:1', 'exists:productos,id', new ValidatePrincipalCombo($this->producto_id, $this->type)
            ],
            'productosec_id' => [
                'required', 'integer', 'min:1', 'exists:productos,id', 'different:producto_id',
                new ValidateSecondaryCombo()
            ],
            'limit' => ['nullable', Rule::requiredIf(!$this->agotarstock), 'numeric', 'min:1', 'max:' . $this->limitstock, 'decimal:0,2'],
            'limitstock' => ['required', 'numeric', 'min:0', 'gt:0'],
            'limitstocksec' => ['required', 'numeric', 'min:0', 'gt:0'],
            'typecombo' => ['required', 'numeric', 'integer', 'min:0', 'max:2'],
            'descuento' => ['nullable', 'required_if:typecombo,1', 'numeric', 'min:1', 'max:100', 'decimal:0,2']
        ]);

        if ($this->limit > 0) {
            $stockitem = formatDecimalOrInteger(Producto::find($this->productosec_id)->almacens()->sum('cantidad'));
            if ($stockitem < $this->limit) {
                $this->addError('productosec_id', 'Stock del producto no disponible [' . $stockitem . ' UND].');
                return false;
            }
        }

        $producto = Producto::with('category')->find($this->productosec_id);
        $comboCollect = getCombo();
        $comboitems = collect($comboCollect->get('comboitems') ?? []);
        $filtered = $comboitems->filter(function ($item) {
            return $item->producto_id == $this->productosec_id;
        });

        if ($filtered->count() > 0) {
            $this->addError('productosec_id', 'Producto ya se encuentra agregado.');
            return false;
        }

        // dd($comboCollect);

        $newscomboitems = $comboitems->push([
            'producto_id' => $producto->id,
            'name' => $producto->name,
            'descuento' => $this->descuento ?? null,
            'category' => $producto->category->name,
            'typecombo' => $this->typecombo
        ]);

        $comboCollect = $comboCollect->merge([
            'comboitems' => $newscomboitems,
        ]);
        // dd($comboCollect);

        $comboJSON = response()->json($comboCollect)->getData();
        Session::put('combo', $comboJSON);
        $this->resetValidation();
        $this->reset(['productosec_id', 'productosec', 'descuento', 'typecombo']);
    }

    public function deleteitem($id)
    {
        $comboCollect = getCombo();
        $comboitems = collect($comboCollect->get('comboitems') ?? []);
        $newscomboitems = $comboitems->reject(function ($item, $key) use ($id) {
            return $item->producto_id == $id;
        });

        $comboCollect = $comboCollect->merge([
            'comboitems' => $newscomboitems->values(),
        ]);

        // dd($comboCollect, $newscomboitems->values());
        $comboJSON = response()->json($comboCollect)->getData();
        Session::put('combo', $comboJSON);
    }

    public function  cancelcombo()
    {
        Session::forget('combo');
        $this->resetValidation();
        $this->resetExcept('open');
    }
}
