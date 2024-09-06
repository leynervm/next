<?php

namespace App\Http\Livewire\Modules\Almacen\Compras;

use App\Models\Kardex;
use App\Models\Methodpayment;
use App\Models\Moneda;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\Typepayment;
use App\Rules\ValidateReferenciaCompra;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Modules\Almacen\Entities\Compra;

class CreateCompra extends Component
{

    use AuthorizesRequests;

    public $moneda;
    public $date, $proveedor_id, $moneda_id, $referencia, $guia,
        $sucursal_id, $tipocambio, $typepayment_id, $detalle;

    public $exonerado = 0;
    public $gravado = 0;
    public $igv = 0;
    public $otros = 0;
    public $descuento = 0;
    public $subtotal = 0;
    public $total = 0;


    public $producto_id;
    public $sumstock = 0;
    public $almacens = [];
    public $priceunitario = 0, $igvunitario = 0, $subtotaligvitem = 0,
        $descuentounitario = 0, $pricebuy = 0, $subtotalitem = 0,
        $priceventa = 0, $subtotaldsctoitem = 0, $totalitem = 0;
    public $afectacion = 'S';
    public $typedescuento = '0';
    public $requireserie = false;

    public $itemcompras = [];

    protected function rules()
    {
        return [
            'sucursal_id' => ['required', 'integer', 'min:1', 'exists:sucursals,id'],
            'proveedor_id' => ['required', 'integer', 'min:1', 'exists:proveedors,id'],
            'date' => ['required', 'date', 'before_or_equal:today'],
            'moneda_id' => ['required', 'integer', 'min:1', 'exists:monedas,id'],
            'tipocambio' => ['nullable', Rule::requiredIf($this->moneda->isDolar()), 'regex:/^\d{0,3}(\.\d{0,3})?$/'],
            'referencia' => [
                'required',
                'string',
                'min:6',
                'regex:/^[a-zA-Z][a-zA-Z0-9][0-9]{2}-(?!0+$)\d{1,8}$/',
                new ValidateReferenciaCompra($this->proveedor_id, $this->sucursal_id)
            ],
            'guia' => ['nullable', 'string', 'min:6', 'regex:/^[a-zA-Z][a-zA-Z0-9][0-9]{2}-(?!0+$)\d{1,8}$/'],
            'gravado' => ['required', 'numeric', 'min:0', 'decimal:0,4', 'regex:/^\d{0,8}(\.\d{0,4})?$/'],
            'exonerado' => ['required', 'numeric', 'min:0', 'decimal:0,4', 'regex:/^\d{0,8}(\.\d{0,4})?$/'],
            'igv' => ['required', 'numeric', 'min:0', 'decimal:0,4', 'regex:/^\d{0,8}(\.\d{0,4})?$/'],
            'descuento' => ['required', 'numeric', 'min:0', 'decimal:0,4', 'regex:/^\d{0,8}(\.\d{0,4})?$/'],
            'otros' => ['required', 'numeric', 'min:0', 'decimal:0,4', 'regex:/^\d{0,8}(\.\d{0,4})?$/'],
            'total' => ['required', 'numeric', 'gt:0', 'decimal:0,4', 'regex:/^\d{0,8}(\.\d{0,4})?$/'],
            'typepayment_id' => ['required', 'integer', 'min:1', 'exists:typepayments,id'],
            // 'detalle' => ['nullable', 'string'],
            'afectacion' => ['required', 'in:S,E'],
            'itemcompras' => ['required', 'array', 'min:1']
        ];
    }

    public function mount()
    {
        $this->sucursal_id = auth()->user()->sucursal_id;
        $this->moneda = new Moneda();
        $this->date = now('America/Lima')->format('Y-m-d');
    }

    public function render()
    {
        $proveedores = Proveedor::orderBy('name', 'asc')->get();
        $monedas = Moneda::all();
        $typepayments = Typepayment::orderBy('name', 'asc')->get();
        $methodpayments = Methodpayment::orderBy('name', 'asc')->get();

        if (auth()->user()->isAdmin()) {
            $sucursals = mi_empresa()->sucursals()->orderBy('codeanexo', 'asc')->get();
        } else {
            $sucursals = auth()->user()->sucursal()->get();
        }

        return view('livewire.modules.almacen.compras.create-compra', compact('proveedores', 'typepayments', 'monedas', 'methodpayments', 'sucursals'));
    }

    public function save()
    {

        // dd($this->itemcompras);
        $this->authorize('admin.almacen.compras.create');
        if ($this->moneda_id) {
            $this->moneda = Moneda::find($this->moneda_id);
        }

        $this->tipocambio = $this->moneda->isDolar() ? $this->tipocambio : null;
        $validateData = $this->validate();
        DB::beginTransaction();
        try {
            $compra = Compra::create($validateData);

            foreach ($this->itemcompras as $item) {

                $producto = Producto::with('almacens')->find($item['producto_id']);

                $compraitem = $compra->compraitems()->create([
                    'cantidad' => $item['sumstock'],
                    'price' => $item['priceunitario'],
                    'oldprice' => $producto->pricebuy,
                    'oldpricesale' => $producto->pricesale,
                    'igv' => $item['igvunitario'],
                    'descuento' => $item['descuentounitario'],
                    'subtotaligv' => $item['subtotaligvitem'],
                    'subtotaldescuento' => $item['subtotaldsctoitem'],
                    'subtotal' => $item['subtotalitem'],
                    'total' => $item['totalitem'],
                    'producto_id' => $item['producto_id'],
                ]);

                foreach ($item['almacens'] as $almacen) {
                    $almacencompra = $compraitem->almacencompras()->create([
                        'cantidad' => $almacen['cantidad'],
                        'almacen_id' => $almacen['id'],
                    ]);

                    if ($item['requireserie']) {
                        if (count($almacen['series']) != $almacen['cantidad']) {
                            $message = response()->json([
                                'title' => "SERIES AGREGADAS NO COINCIDEN CON EL STOCK ENTRANTE.",
                                'text' => null,
                            ])->getData();
                            $this->dispatchBrowserEvent('validation', $message);
                            return false;
                        }
                        foreach ($almacen['series'] as $serie) {
                            $almacencompra->series()->create([
                                'serie' => trim($serie),
                                'almacen_id' => $almacen['id'],
                                'producto_id' => $item['producto_id'],
                                'user_id' => auth()->user()->id,
                            ]);
                        }
                    }

                    $mystock = $producto->almacens()->where('almacen_id', $almacen['id'])->first();

                    $almacencompra->saveKardex(
                        $item['producto_id'],
                        $almacen['id'],
                        $mystock->pivot->cantidad,
                        $mystock->pivot->cantidad +  $almacen['cantidad'],
                        $almacen['cantidad'],
                        '+',
                        Kardex::ENTRADA_ALMACEN,
                        $this->referencia
                    );
                    $producto->almacens()->updateExistingPivot($almacen['id'], [
                        'cantidad' => $mystock->pivot->cantidad + $almacen['cantidad'],
                    ]);
                }

                $producto->pricebuy =  $this->moneda->isDolar() ?
                    number_format(($item['pricebuy']) * $this->tipocambio, 2, '.', '') :
                    number_format($item['pricebuy'], 2, '.', '');
                if (!mi_empresa()->usarLista()) {
                    $producto->pricesale = $item['priceventa'];
                }

                $producto->save();
                $producto->assignPriceProduct();
            }

            DB::commit();
            $this->dispatchBrowserEvent('created');
            $this->resetValidation();
            $this->resetExcept(['moneda']);
            return redirect()->route('admin.almacen.compras.edit', $compra);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function addproducto($producto = null)
    {

        if ($this->moneda_id) {
            $this->moneda = Moneda::find($this->moneda_id);
        }

        $data = $this->validate([
            'moneda_id' => ['required', 'integer', 'min:1', 'exists:monedas,id'],
            'producto_id' => ['required', 'integer', 'min:1', 'exists:productos,id'],
            'tipocambio' => [
                'nullable',
                Rule::requiredIf($this->moneda->isDolar()),
                'regex:/^\d{0,3}(\.\d{0,3})?$/'
            ],
            'afectacion' => ['required', 'string', 'in:S,E'],
            'almacens' => ['required', 'array', 'min:1'],
            'sumstock' => ['required', 'integer', 'gt:0'],
            'priceunitario' => ['required', 'numeric', 'gt:0'],
            'igvunitario' => ['nullable', 'numeric', 'min:0'],
            'descuentounitario' =>  $this->typedescuento > 0 ? ['required', 'numeric', 'decimal:0,2', 'gt:0'] : ['nullable'],
            'pricebuy' => ['required', 'numeric', 'gt:0'],
            'typedescuento' => ['required', 'integer', 'min:0', 'in:0,1,2,3'],
            'subtotaligvitem' => ['required', 'numeric', 'min:0'],
            'subtotalitem' => ['required', 'numeric', 'gt:0'],
            'subtotaldsctoitem' => ['nullable', 'numeric', 'min:0'],
            'priceventa' => !mi_empresa()->usarlista() ? [
                'required',
                'numeric',
                'decimal:0,2',
                'gt:0'
            ] : [
                'nullable',
                'min:0'
            ]
        ]);

        if ($producto['requireserie']) {
            foreach ($this->almacens as $key => $item) {
                // dd($item, $key, $producto, count($item['series']) !== $item['cantidad']);
                if ($item['cantidad'] > 0 && count($item['series']) != $item['cantidad']) {
                    $this->addError("almacens.$key.cantidad", 'Series agregadas no coinciden con la cantidad entrante.');
                    return false;
                }
            }
        }

        $item = collect($this->itemcompras)->firstWhere('producto_id', $this->producto_id);

        if ($item) {
            $this->addError('producto_id', 'El valor del campo producto ya está en uso.');
            return false;
        }

        $almacens = collect($this->almacens);
        $myalmacens = $almacens->filter(function ($item) {
            return $item['cantidad'] > 0;
        });

        if ($this->typedescuento > 0) {
            if ($this->typedescuento == '2') {
                $this->priceunitario = $this->pricebuy;
            } elseif ($this->typedescuento == '3') {
                $this->descuentounitario = number_format($this->subtotaldsctoitem / $this->sumstock, 2, '.', '');
            }
        } else {
            $this->descuentounitario = 0;
            $this->subtotaldsctoitem = 0;
        }

        $pricebuysoles = 0;
        if ($this->moneda->isDolar()) {
            $pricebuysoles = $this->pricebuy * $this->tipocambio ?? 1;
        }

        $this->itemcompras[] = [
            'id' => uniqid(),
            'producto_id' => $this->producto_id,
            'name' => $producto['name'],
            'unit' => $producto['unit'],
            'sumstock' => $this->sumstock,
            'priceunitario' => $this->priceunitario,
            'igvunitario' => $this->igvunitario,
            'descuentounitario' => $this->descuentounitario,
            'pricebuy' => $this->pricebuy,
            'pricebuysoles' => $pricebuysoles,
            'subtotaligvitem' => $this->subtotaligvitem,
            'subtotalitem' => $this->subtotalitem,
            'totalitem' => $this->totalitem,
            'subtotaldsctoitem' => $this->subtotaldsctoitem,
            'priceventa' => $this->priceventa,
            'almacens' => $myalmacens,
            'image' => $producto['image_url'],
            'requireserie' => $this->requireserie,
            'typedescuento' => $this->typedescuento,
        ];

        if ($this->afectacion == 'S') {
            $this->gravado = formatDecimalOrInteger($this->gravado + ($this->totalitem - $this->subtotaligvitem), 2);
            $this->igv = formatDecimalOrInteger($this->igv + $this->subtotaligvitem, 2);
        } else {
            $this->exonerado = formatDecimalOrInteger($this->exonerado + ($this->totalitem), 2);
        }

        $this->descuento = formatDecimalOrInteger($this->descuento + $this->subtotaldsctoitem, 2);
        $this->total = formatDecimalOrInteger($this->total + $this->totalitem, 2);
        $this->subtotal = formatDecimalOrInteger($this->subtotal + $this->subtotalitem, 2);

        $this->reset([
            'producto_id',
            'almacens',
            'sumstock',
            'priceunitario',
            'igvunitario',
            'descuentounitario',
            'pricebuy',
            'subtotaligvitem',
            'subtotalitem',
            'totalitem',
            'priceventa',
            'subtotaldsctoitem',
            'requireserie',
            'typedescuento'
        ]);
    }

    public function removeitem($producto_id)
    {
        $collect = collect($this->itemcompras ?? []);
        $item = $collect->firstWhere('producto_id', $producto_id);
        // dd($item);

        $itemcompras = $collect->reject(function ($item) use ($producto_id) {
            return $item['producto_id'] == $producto_id;
        });

        $this->itemcompras = $itemcompras->toArray();

        if (count($this->itemcompras) > 0) {
            if ($this->afectacion == 'S') {
                $this->gravado = formatDecimalOrInteger($this->gravado - ($item['subtotalitem'] - $item['subtotaligvitem']), 2);
                $this->igv = formatDecimalOrInteger($this->igv - $item['subtotaligvitem'], 2);
            } else {
                $this->exonerado = formatDecimalOrInteger($this->exonerado - $item['totalitem'], 2);
            }
            $this->descuento = formatDecimalOrInteger($this->descuento - $item['subtotaldsctoitem'], 2);
            $this->total = formatDecimalOrInteger($this->exonerado + $this->gravado + $this->igv, 2);
            $this->subtotal = formatDecimalOrInteger($this->total + $this->descuento, 2);
        } else {
            $this->exonerado = 0;
            $this->gravado = 0;
            $this->igv = 0;
            $this->descuento = 0;
            $this->subtotal = 0;
            $this->total = 0;
        }
    }

    public function addserie(int $index)
    {

        $this->almacens[$index]['newserie'] = trim(mb_strtoupper($this->almacens[$index]['newserie'], "UTF-8"));
        $this->validate([
            "almacens.$index.newserie" => ['required', 'string']
        ]);


        if (count($this->almacens[$index]['series']) >= $this->almacens[$index]['cantidad']) {
            $this->addError("almacens.$index.newserie", 'Series superan a la cantidad entrante.');
            return false;
        }

        $series1 = collect($this->almacens ?? [])->flatMap(function ($item) {
            return $item['series'];
        })->toArray();

        $series2 = collect($this->itemcompras ?? [])->flatMap(function ($item) {
            return collect($item['almacens'])->flatMap(function ($almacen) {
                return $almacen['series'];
            });
        })->toArray();

        $series = array_merge($series1, $series2);

        if (in_array($this->almacens[$index]['newserie'], $series)) {
            $this->addError("almacens.$index.newserie", 'Serie ya se encuentra agregado.');
            return false;
        }

        $this->almacens[$index]['series'][] = $this->almacens[$index]['newserie'];
        $this->almacens[$index]['newserie'] = '';
        $this->resetValidation(["almacens.$index.newserie"]);
    }

    public function removeserie($almacenindex, $index)
    {
        if (isset($this->almacens[$almacenindex]['series'][$index])) {
            unset($this->almacens[$almacenindex]['series'][$index]);
            $this->almacens[$almacenindex]['series'] = array_values($this->almacens[$almacenindex]['series']);
        }
    }

    public function edit($producto_id)
    {
        $collect = collect($this->itemcompras ?? []);
        $item = $collect->firstWhere('producto_id', $producto_id);

        $this->producto_id = $item['producto_id'];
        $this->priceunitario = $item['priceunitario'];
        $this->igvunitario = $item['igvunitario'];
        $this->descuentounitario = $item['descuentounitario'];
        $this->pricebuy = $item['pricebuy'];
        $this->priceventa =  $item['priceventa'];
        $this->requireserie = $item['requireserie'];
        $this->typedescuento = $item['typedescuento'];

        $arrayalmacens = $item['almacens'];
        $combined = Producto::find($producto_id)->almacens->map(function ($item) use ($arrayalmacens) {
            $almacencompra = collect($arrayalmacens)->firstWhere('id', $item['id']);
            if ($almacencompra) {
                $almacen['cantidad'] = formatDecimalOrInteger($almacencompra['cantidad']);
                $almacen['series'] = $almacencompra['series'];
            } else {
                $almacen['cantidad'] = 0;
                $almacen['series'] = [];
            }
            $almacen['id'] = $item->id;
            $almacen['name'] =  $item->name;
            $almacen['newserie'] = '';
            $almacen['pivot'] =  $item->pivot->toArray();
            return $almacen;
        })->toArray();
        // dd($combined);
        $this->almacens = $combined;
        $this->removeitem($producto_id);
    }
}
