<?php

namespace App\Http\Livewire\Modules\Almacen\Cotizacions;

use App\Models\Almacen;
use App\Models\Cotizacion;
use App\Models\Cotizaciongarantia;
use App\Models\Otheritem;
use App\Models\Pricetype;
use App\Models\Producto;
use App\Models\Seriecomprobante;
use App\Models\Tvitem;
use App\Models\Typegarantia;
use App\Models\Ubigeo;
use App\Models\Unit;
use App\Rules\ValidateDocument;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Modules\Facturacion\Entities\Comprobante;
use Modules\Ventas\Entities\Venta;
use Nwidart\Modules\Facades\Module;

class ShowCotizacion extends Component
{

    public $cotizacion, $empresa;
    public $addadress = false, $open = false, $opencpe = false;
    public $pricetype, $pricetype_id;
    public $producto, $pricesale, $cantidad, $producto_id;
    public $productofree_id, $cantidadfree;
    public $timegarantia, $datecodegarantia, $typegarantia_id;
    public $direccioninstalacion, $ubigeo_id;
    public $nameother, $cantidadother, $priceother, $marcaother;
    public $typecomprobante_id = '', $client_id, $document, $name,
        $direccion, $observaciones;

    public $otheritem = [
        'id' => '',
        'name' => '',
        'cantidad' => '',
        'price' => '',
        'marca' => ''
    ];

    protected function rules()
    {
        return [
            'cotizacion.direccion' => ['required', 'string', 'min:6',],
            'cotizacion.afectacionigv' => ['required'],
            'cotizacion.entrega' => ['required', 'integer', 'min:1'],
            'cotizacion.datecode' => ['required', 'string', 'in:D,M,Y'],
            'cotizacion.validez' => ['required', 'date', 'date_format:Y-m-d', function ($attribute, $value, $fail) {
                if (Carbon::parse($value)->lt(Carbon::parse($this->cotizacion->date)->format('Y-m-d'))) {
                    $fail("El campo fecha de validéz debe ser posterior o igual a " . formatDate($this->cotizacion->date, 'DD/MM/Y') . ".");
                }
            }],
            'cotizacion.detalle' => ['nullable', 'string'],
            'cotizacion.typepayment_id' => ['required', 'integer', 'min:1', 'exists:typepayments,id'],
            'cotizacion.moneda_id' => ['required', 'integer', 'min:1', 'exists:monedas,id'],
            'cotizacion.client_id' => ['required', 'integer', 'min:1', 'exists:clients,id'],
            'addadress' => ['required', 'boolean'],
            'direccioninstalacion' => ['nullable', Rule::requiredIf($this->addadress), 'string', 'min:6'],
            'ubigeo_id' => ['nullable', Rule::requiredIf($this->addadress), 'integer', 'min:1', 'exists:ubigeos,id'],
        ];
    }

    public function mount(Cotizacion $cotizacion)
    {
        $this->cotizacion = $cotizacion;
        $this->empresa = view()->shared('empresa');
        if ($this->cotizacion->lugar) {
            $this->addadress = true;
            $this->direccioninstalacion = $this->cotizacion->lugar->name;
            $this->ubigeo_id = $this->cotizacion->lugar->ubigeo_id;
        }

        if ($this->empresa->usarLista()) {
            $pricetype = Pricetype::activos()->orderByDesc('default')
                ->orderBy('id', 'asc')->first();

            if ($pricetype) {
                $this->pricetype = $pricetype;
                $this->pricetype_id = $pricetype->id;
            }
        }
    }

    public function render()
    {
        $typegarantias = Typegarantia::orderBy('id', 'asc')->get();
        $pricetypes = Pricetype::activos()->orderBy('id', 'asc')->get();
        $ubigeos = Ubigeo::orderBy('region', 'asc')->orderBy('provincia', 'asc')->orderBy('distrito', 'asc')->get();
        $productos = Producto::query()->select(
            'productos.id',
            'productos.name',
            'marca_id',
            'category_id',
            'subcategory_id',
            'visivility',
            'novedad',
            'pricebuy',
            'pricesale',
            'precio_1',
            'precio_2',
            'precio_3',
            'precio_4',
            'precio_5',
            'marcas.name as name_marca',
            'categories.name as name_category',
            'subcategories.name as name_subcategory',
        )->leftJoin('marcas', 'productos.marca_id', '=', 'marcas.id')
            ->leftJoin('subcategories', 'productos.subcategory_id', '=', 'subcategories.id')
            ->leftJoin('categories', 'productos.category_id', '=', 'categories.id')
            ->with(['almacens', 'imagen'])->visibles()->orderByDesc('novedad')
            ->orderBy('subcategories.orden', 'ASC')
            ->orderBy('categories.orden', 'ASC')->get();

        $typecomprobantes = auth()->user()->sucursal->seriecomprobantes()->withTrashed()
            ->select('seriecomprobantes.*')->join('typecomprobantes', 'seriecomprobantes.typecomprobante_id', '=', 'typecomprobantes.id')
            ->when(Module::isDisabled('Facturacion'), function ($query) {
                $query->whereHas('typecomprobante', function ($q) {
                    $q->default();
                });
            })->whereNotIn('typecomprobantes.code', ['07', '09', '13'])
            ->orderBy('seriecomprobantes.default', 'desc')->orderBy('typecomprobantes.code', 'asc')
            ->orderBy('seriecomprobantes.default', 'desc')->with('typecomprobante')->get();

        return view('livewire.modules.almacen.cotizacions.show-cotizacion', compact('typegarantias', 'pricetypes', 'ubigeos', 'productos', 'typecomprobantes'));
    }

    public function getPricesale()
    {
        if (!empty($this->producto_id) && !empty($this->pricetype_id)) {
            $this->producto = Producto::find($this->producto_id);
            $this->pricesale = getPrecioventa($this->producto, $this->pricetype);
        } else {
            $this->pricesale = '';
        }
    }

    public function updatedPricetypeId($value)
    {
        if (!empty($value)) {
            $this->pricetype = Pricetype::find($value);
            Self::getPricesale();
        }
    }

    public function updatedProductoId()
    {
        Self::getPricesale();
    }

    public function addproducto()
    {
        $this->validate([
            'pricesale' => ['required', 'numeric', 'min:1', 'decimal:0,4'],
            'cantidad' => ['required', 'numeric', 'min:1', 'decimal:0,2'],
            'producto_id' => ['required', 'integer', 'min:1', 'exists:productos,id'],
        ]);

        // if ($this->moneda->isDolar()) {
        //     $pricesale = convertMoneda($pricesale, 'USD', $this->empresa->tipocambio, 2);
        // } else {
        //     $pricesale = decimalOrInteger($pricesale, 2);
        // }

        $pricesale = $this->pricesale;
        $igvsale = 0;
        $subtotaligv = 0;

        DB::beginTransaction();
        try {
            if ($this->cotizacion->isAfectacionIGV()) {
                $price_igv = getPriceIGV($pricesale, $this->empresa->igv);
                $pricesale = $price_igv->price;
                $igvsale = $price_igv->igv;
                $subtotaligv = number_format($igvsale * $this->cantidad, 2, '.', '');
            }

            $subtotal = number_format($pricesale * $this->cantidad, 2, '.', '');
            $total = ($pricesale + $igvsale) * $this->cantidad;

            $this->cotizacion->tvitems()->create([
                'date' => now('America/Lima'),
                'pricebuy' => $this->producto->pricebuy,
                'cantidad' => $this->cantidad,
                'price' => $pricesale,
                'igv' => $igvsale,
                'subtotaligv' =>  $subtotaligv,
                'subtotal' => $subtotal,
                'total' =>  $total,
                'alterstock' => Almacen::NO_ALTERAR_STOCK,
                'gratuito' => Tvitem::NO_GRATUITO,
                'user_id' => auth()->user()->id,
                'producto_id' => $this->producto_id,
            ]);
            // $this->cotizacion->subtotal = $this->cotizacion->subtotal + $subtotal;
            // $this->cotizacion->igv = $this->cotizacion->igv + $subtotaligv;
            // $this->cotizacion->total = $this->cotizacion->total + $total;
            // $this->cotizacion->save();
            DB::commit();
            $this->dispatchBrowserEvent('toast', toastJSON('PRODUCTO AGREGADO CORRECTAMENTE'));
            $this->cotizacion->refresh();
            $this->reset(['producto', 'producto_id', 'cantidad']);
            $this->resetValidation();
            Self::updateTotal();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deletetvitem(Tvitem $tvitem)
    {
        if ($tvitem) {
            DB::beginTransaction();
            try {
                $tvitem->load('kardex');
                if ($tvitem->kardex) {
                    dd('Verificar para descontar stock');
                }
                // if (!$tvitem->isGratuito()) {
                //     $this->cotizacion->subtotal = $this->cotizacion->subtotal - $tvitem->subtotal;
                //     $this->cotizacion->igv = $this->cotizacion->igv - $tvitem->subtotaligv;
                //     $this->cotizacion->total = $this->cotizacion->total - $tvitem->total;
                //     $this->cotizacion->save();
                // }
                $tvitem->delete();
                DB::commit();
                $this->cotizacion->refresh();
                Self::updateTotal();
                $this->dispatchBrowserEvent('deleted');
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            } catch (\Throwable $e) {
                DB::rollBack();
                throw $e;
            }
        }
        $this->resetValidation();
    }

    public function resetproducto()
    {
        $this->reset(['producto', 'producto_id', 'cantidad']);
        $this->resetValidation();
    }

    public function addproductofree()
    {
        $this->validate([
            'productofree_id' => ['required', 'integer', 'min:1', 'exists:productos,id'],
            'cantidadfree' => ['required', 'numeric', 'min:1', 'decimal:0,2'],
        ]);

        $producto = Producto::with(['imagen', 'unit'])->find($this->productofree_id);
        $this->cotizacion->tvitems()->create([
            'date' => now('America/Lima'),
            'pricebuy' => $producto->pricebuy,
            'cantidad' => $this->cantidadfree,
            'price' => 0,
            'igv' => 0,
            'subtotaligv' => 0,
            'subtotal' => 0,
            'total' => 0,
            'alterstock' => Almacen::NO_ALTERAR_STOCK,
            'gratuito' => Tvitem::GRATUITO,
            'user_id' => auth()->user()->id,
            'producto_id' => $this->productofree_id,
        ]);
        $this->reset(['productofree_id', 'cantidadfree']);
        $this->cotizacion->refresh();
        $this->resetValidation();
        $this->dispatchBrowserEvent('toast', toastJSON('OFERTA AGREGADO CORRECTAMENTE'));
    }

    public function addgarantia()
    {
        $this->validate([
            'timegarantia' => ['required', 'integer', 'min:1'],
            'datecodegarantia' => ['required', 'string', 'in:D,M,Y'],
            'typegarantia_id' => ['required', 'integer', 'min:1', 'exists:typegarantias,id'],
        ]);

        $item = $this->cotizacion->cotizaciongarantias()->where('typegarantia_id', $this->typegarantia_id)->exists();

        if ($item) {
            $this->addError('typegarantia_id', 'El tipo de garantía ya se encuentra agregado.');
            return false;
        }

        $this->cotizacion->cotizaciongarantias()->create([
            'time' => $this->timegarantia,
            'datecode' => $this->datecodegarantia,
            'typegarantia_id' => $this->typegarantia_id,
        ]);
        $this->cotizacion->refresh();
        $this->reset(['timegarantia', 'datecodegarantia', 'typegarantia_id']);
        $this->resetValidation();
        $this->dispatchBrowserEvent('toast', toastJSON('GARANTÍA AGREGADO CORRECTAMENTE'));
    }


    public function deletegarantia(Cotizaciongarantia $garantia)
    {
        if ($garantia) {
            $garantia->delete();
            $this->cotizacion->refresh();
            $this->dispatchBrowserEvent('deleted');
        }
        $this->resetValidation();
    }

    public function resetgarantia()
    {
        $this->reset(['timegarantia', 'datecodegarantia', 'typegarantia_id']);
        $this->resetValidation();
    }

    public function update()
    {
        $this->validate();
        $this->cotizacion->save();
        if ($this->addadress) {
            $lugar = $this->cotizacion->lugar()->updateOrCreate([
                'id' => $this->cotizacion->lugar->id ?? null
            ], [
                'name' => $this->direccioninstalacion,
                'ubigeo_id' => $this->ubigeo_id,
            ]);
            $this->direccioninstalacion = $lugar->name;
        } else {
            if ($this->cotizacion->lugar) {
                $this->cotizacion->lugar->delete();
            }
        }
        $this->cotizacion->refresh();
        $this->dispatchBrowserEvent('updated');
    }


    public function addotherproducto()
    {
        $this->nameother = trim(mb_strtoupper($this->nameother, "UTF-8"));
        $this->marcaother = !empty($this->marcaother) ? trim(mb_strtoupper($this->marcaother, "UTF-8")) : null;
        $this->validate([
            'cotizacion.afectacionigv' => ['required', 'integer', 'in:0,1'],
            'nameother' => ['required', 'string', 'min:3',],
            'cantidadother' => ['required', 'numeric', 'gt:0', 'decimal:0,2'],
            'priceother' => ['required', 'numeric', 'gt:0', 'decimal:0,3'],
            'marcaother' => ['nullable', 'string', 'min:2',],
            'cotizacion.moneda_id' => ['required', 'integer', 'min:1', 'exists:monedas,id'],
        ]);

        $collect = collect($this->cotizacion->otheritems ?? []);
        $item = $collect->firstWhere('name', $this->nameother);

        if ($item) {
            $this->addError('nameother', 'Item agregado con la misma descripción.');
            return false;
        }

        $pricesale = $this->priceother;
        $igvsale = 0;
        $subtotaligv = 0;

        if ($this->cotizacion->isAfectacionIGV()) {
            $price_igv = getPriceIGV($pricesale, $this->empresa->igv);
            $pricesale = $price_igv->price;
            $igvsale = $price_igv->igv;
            $subtotaligv = number_format($igvsale * $this->cantidadother, 2, '.', '');
        }

        $subtotal = number_format($pricesale * $this->cantidadother, 2, '.', '');
        $total = ($pricesale + $igvsale) * $this->cantidadother;

        $this->cotizacion->otheritems()->create([
            'date' => now('America/Lima'),
            'name' => $this->nameother,
            'marca' => $this->marcaother,
            'pricebuy' => 0,
            'cantidad' => $this->cantidadother,
            'price' => $pricesale,
            'igv' => $igvsale,
            'subtotaligv' => $subtotaligv,
            'subtotal' => $subtotal,
            'total' => $total,
            'user_id' => auth()->user()->id,
            'unit_id' => Unit::where('code', 'NIU')->first()->id,
        ]);
        $this->resetValidation();
        $this->reset(['nameother', 'cantidadother', 'priceother', 'marcaother']);
        $this->dispatchBrowserEvent('toast', toastJSON('AGREGADO CORRECTAMENTE'));
        $this->cotizacion->refresh();
        Self::updateTotal();
    }

    public function edititemother(Otheritem $otheritem)
    {
        if ($otheritem) {
            $this->open = true;
            $this->resetValidation();
            // $this->otheritem = $otheritem;
            $this->otheritem['id'] = $otheritem->id;
            $this->otheritem['name'] = $otheritem->name;
            $this->otheritem['marca'] = $otheritem->marca;
            $this->otheritem['cantidad'] = decimalOrInteger($otheritem->cantidad);
            $this->otheritem['price'] = number_format($otheritem->price, 2, '.', '');
        }
    }

    public function updateitemother()
    {
        $validateData = $this->validate([
            'otheritem.id' => ['required', 'integer', 'min:1', 'exists:otheritems,id'],
            'otheritem.name' => ['required', 'string', 'min:3',],
            'otheritem.cantidad' => ['required', 'numeric', 'gt:0', 'decimal:0,2'],
            'otheritem.price' => ['required', 'numeric', 'gt:0', 'decimal:0,3'],
            'otheritem.marca' => ['nullable', 'string', 'min:2',],
        ]);

        $pricesale = $this->otheritem['price'];
        $igvsale = 0;
        $subtotaligv = 0;

        if ($this->cotizacion->isAfectacionIGV()) {
            $price_igv = getPriceIGV($pricesale, $this->empresa->igv);
            $pricesale = $price_igv->price;
            $igvsale = $price_igv->igv;
            $subtotaligv = number_format($igvsale * $this->otheritem['cantidad'], 2, '.', '');
        }

        $subtotal = number_format($pricesale * $this->otheritem['cantidad'], 2, '.', '');
        $total = ($pricesale + $igvsale) * $this->otheritem['cantidad'];

        $otheritem = Otheritem::find($this->otheritem['id']);
        if ($otheritem) {
            $otheritem->name = $this->otheritem['name'];
            $otheritem->marca = $this->otheritem['marca'];
            $otheritem->cantidad = $this->otheritem['cantidad'];
            $otheritem->price = $pricesale;
            $otheritem->igv = $igvsale;
            $otheritem->subtotaligv = $subtotaligv;
            $otheritem->subtotal = $subtotal;
            $otheritem->total = $total;
            $otheritem->user_id = auth()->user()->id;
            $otheritem->save();
            $this->cotizacion->refresh();
            Self::updateTotal();
            $this->dispatchBrowserEvent('updated');
            $this->reset(['open', 'otheritem']);
        }
    }

    public function removeitemother(Otheritem $otheritem)
    {
        if ($otheritem) {
            $otheritem->forceDelete();
            $this->cotizacion->refresh();
            $this->dispatchBrowserEvent('deleted');
        }
        Self::updateTotal();
        $this->resetValidation();
    }

    public function resetotherproducto()
    {
        $this->resetValidation();
        $this->reset(['nameother', 'cantidadother', 'priceother', 'marcaother']);
    }

    public function updateTotal()
    {
        $subtotal = $this->cotizacion->tvitems->where('gratuito', Tvitem::NO_GRATUITO)->sum('subtotal');
        $igv = $this->cotizacion->tvitems->where('gratuito', Tvitem::NO_GRATUITO)->sum('subtotaligv');
        $total = $this->cotizacion->tvitems->where('gratuito', Tvitem::NO_GRATUITO)->sum('total');

        $subtotalothers = $this->cotizacion->otheritems->where('gratuito', Tvitem::NO_GRATUITO)->sum('subtotal');
        $igvothers = $this->cotizacion->otheritems->where('gratuito', Tvitem::NO_GRATUITO)->sum('subtotaligv');
        $totalothers = $this->cotizacion->otheritems->where('gratuito', Tvitem::NO_GRATUITO)->sum('total');

        $this->cotizacion->subtotal = $subtotal + $subtotalothers;
        $this->cotizacion->igv = $igv + $igvothers;
        $this->cotizacion->total = $total + $totalothers;
        $this->cotizacion->save();
    }

    public function aprobar()
    {
        $this->cotizacion->status = Cotizacion::APROBED;
        $this->cotizacion->save();
        $this->cotizacion->refresh();
        $mensaje = response()->json([
            'icon' => 'success',
            'title' => 'COTIZACION ' . $this->cotizacion->seriecompleta . ' APROVADA CORRECTAMENTE'
        ])->getData();
        $this->dispatchBrowserEvent('validation', $mensaje);
    }

    public function desaprobar()
    {
        $this->cotizacion->status = Cotizacion::DEFAULT;
        $this->cotizacion->save();
        $this->cotizacion->refresh();
        $mensaje = response()->json([
            'icon' => 'success',
            'title' => 'COTIZACION ' . $this->cotizacion->seriecompleta . ' DESAPROVADA CORRECTAMENTE'
        ])->getData();
        $this->dispatchBrowserEvent('validation', $mensaje);
    }

    public function openmodalcpe()
    {
        $this->document = $this->cotizacion->client->document;
        $this->name = $this->cotizacion->client->name;
        $this->direccion = $this->cotizacion->direccion;
        $this->client_id = $this->cotizacion->client_id;
        $this->observaciones = $this->cotizacion->detalle;
        $this->opencpe = true;
    }

    public function savecomprobante()
    {
        $seriecomprobante = null;
        if (!empty($this->typecomprobante_id)) {
            $seriecomprobante = Seriecomprobante::with('typecomprobante')->find($this->typecomprobante_id);
        }

        $validateCPE = $this->validate([
            'document' => ['required', 'numeric', new ValidateDocument, 'regex:/^\d{8}(?:\d{3})?$/', ($seriecomprobante && $seriecomprobante->typecomprobante->code == '01') ? 'regex:/^\d{11}$/' : ''],
            'name' => ['required', 'string'],
            'direccion' => ['required', 'string'],
            'observaciones' => ['nullable', 'string'],
            'typecomprobante_id' => ['required', 'integer', 'min:1', 'exists:seriecomprobantes,id'],
            'client_id' => ['required', 'integer', 'min:1', 'exists:clients,id'],
        ]);

        DB::beginTransaction();
        try {
            $counter = 1;
            $numeracion = $this->empresa->isProduccion() ? $seriecomprobante->contador + 1 : $seriecomprobante->contadorprueba + 1;

            $venta = Venta::create([
                'date' => now('America/Lima'),
                'seriecompleta' => $seriecomprobante->serie . '-' . $numeracion,
                'direccion' => $this->direccion,
                'observaciones' => $this->observaciones,
                'exonerado' => number_format($this->cotizacion->igv > 0 ? 0 : $this->cotizacion->subtotal, 3, '.', ''),
                'gravado' => number_format($this->cotizacion->igv > 0 ? $this->cotizacion->subtotal : 0, 3, '.', ''),
                'gratuito' => number_format(0, 3, '.', ''),
                'inafecto' => number_format(0, 3, '.', ''),
                'descuento' => number_format(0, 3, '.', ''),
                'otros' => number_format(0, 3, '.', ''),
                'igv' => number_format($this->cotizacion->igv, 3, '.', ''),
                'igvgratuito' => number_format(0, 3, '.', ''),
                'subtotal' => number_format($this->cotizacion->subtotal, 3, '.', ''),
                'total' => number_format($this->cotizacion->total, 3, '.', ''),
                'paymentactual' => 0,
                'tipocambio' => $this->cotizacion->tipocambio,
                'increment' => 0,
                'typepayment_id' => $this->cotizacion->typepayment_id,
                'client_id' => $this->client_id,
                'seriecomprobante_id' => $this->typecomprobante_id,
                'moneda_id' => $this->cotizacion->moneda_id,
                'sucursal_id' => $this->cotizacion->sucursal_id,
                'user_id' => auth()->user()->id,
            ]);

            if (Module::isEnabled('Facturacion')) {
                if ($seriecomprobante->typecomprobante->isSunat()) {
                    $comprobante = $venta->createComprobante();
                }
            }

            if (count($this->cotizacion->tvitems) > 0) {
                foreach ($this->cotizacion->tvitems as $item) {
                    $tvitem = $venta->tvitems()->create([
                        'date' => now('America/Lima'),
                        'cantidad' => $item->cantidad,
                        'pricebuy' => $item->pricebuy,
                        'price' => ($item->price),
                        'igv' => ($item->igv),
                        'subtotaligv' => ($item->subtotaligv),
                        'subtotal' => ($item->subtotal),
                        'total' => ($item->total),
                        'status' => $item->status,
                        // 'alterstock' => $item->alterstock,
                        'alterstock' => Almacen::DISMINUIR_STOCK,
                        'gratuito' => $item->gratuito,
                        'increment' => $item->increment,
                        'promocion_id' => $item->promocion_id,
                        'almacen_id' => $item->almacen_id,
                        'producto_id' => $item->producto_id,
                        'user_id' => auth()->user()->id,
                        'moneda_id' => $this->cotizacion->moneda_id,
                        'sucursal_id' => $this->cotizacion->sucursal_id,
                    ]);

                    if (isset($comprobante)) {
                        if ($item->gratuito) {
                            $afectacion = $item->igv > 0 ? '15' : '21';
                        } else {
                            $afectacion = $item->igv > 0 ? '10' : '20';
                        }

                        $codeafectacion = $item->igv > 0 ? '1000' : '9997';
                        $nameafectacion = $item->igv > 0 ? 'IGV' : 'EXO';
                        $typeafectacion = $item->igv > 0 ? 'VAT' : 'VAT';
                        $abreviatureafectacion = $item->igv > 0 ? 'S' : 'E';

                        if (Module::isEnabled('Facturacion')) {
                            if ($seriecomprobante->typecomprobante->isSunat()) {
                                $comprobante->facturableitems()->create([
                                    'item' => $counter,
                                    'descripcion' => $item->producto->name,
                                    'code' => $item->producto->code,
                                    'cantidad' => $item->cantidad,
                                    'price' => number_format($item->price, 3, '.', ''),
                                    'igv' => number_format($item->igv, 3, '.', ''),
                                    'subtotaligv' => number_format($item->subtotaligv, 3, '.', ''),
                                    'subtotal' => number_format($item->subtotal, 3, '.', ''),
                                    'total' => number_format($item->total, 3, '.', ''),
                                    'unit' => $item->producto->unit->code,
                                    'codetypeprice' => $item->gratuito ? '02' : '01', //01: Precio unitario (incluye el IGV) 02: Valor referencial unitario en operaciones no onerosas
                                    'afectacion' => $afectacion,
                                    'codeafectacion' => $item->gratuito ? '9996' : $codeafectacion,
                                    'nameafectacion' => $item->gratuito ? 'GRA' : $nameafectacion,
                                    'typeafectacion' => $item->gratuito ? 'FRE' : $typeafectacion,
                                    'abreviatureafectacion' => $item->gratuito ? 'Z' : $abreviatureafectacion,
                                    'percent' => $item->igv > 0 ? $this->empresa->igv : 0,
                                ]);
                            }
                        }

                        $counter++;
                    }
                }
            }

            if (count($this->cotizacion->otheritems) > 0) {
                foreach ($this->cotizacion->otheritems as $item) {
                    $otheritem = $venta->otheritems()->create([
                        'date' => now('America/Lima'),
                        'name' => $item->name,
                        'marca' => $item->marca,
                        'pricebuy' => $item->pricebuy,
                        'cantidad' => $item->cantidad,
                        'price' => $item->price,
                        'igv' => $item->igv,
                        'subtotaligv' => $item->subtotaligv,
                        'subtotal' => $item->subtotal,
                        'total' => $item->total,
                        'gratuito' => $item->gratuito,
                        'user_id' => auth()->user()->id,
                        'unit_id' => $item->unit_id,
                    ]);

                    if (isset($comprobante)) {
                        if ($item->gratuito) {
                            $afectacion = $item->igv > 0 ? '15' : '21';
                        } else {
                            $afectacion = $item->igv > 0 ? '10' : '20';
                        }

                        $codeafectacion = $item->igv > 0 ? '1000' : '9997';
                        $nameafectacion = $item->igv > 0 ? 'IGV' : 'EXO';
                        $typeafectacion = $item->igv > 0 ? 'VAT' : 'VAT';
                        $abreviatureafectacion = $item->igv > 0 ? 'S' : 'E';

                        if (Module::isEnabled('Facturacion')) {
                            if ($seriecomprobante->typecomprobante->isSunat()) {
                                $comprobante->facturableitems()->create([
                                    'item' => $counter,
                                    'descripcion' => $item->name,
                                    'code' => $item->id,
                                    'cantidad' => $item->cantidad,
                                    'price' => number_format($item->price, 3, '.', ''),
                                    'igv' => number_format($item->igv, 3, '.', ''),
                                    'subtotaligv' => number_format($item->subtotaligv, 3, '.', ''),
                                    'subtotal' => number_format($item->subtotal, 3, '.', ''),
                                    'total' => number_format($item->total, 3, '.', ''),
                                    'unit' => $item->unit->code,
                                    'codetypeprice' => $item->gratuito ? '02' : '01', //01: Precio unitario (incluye el IGV) 02: Valor referencial unitario en operaciones no onerosas
                                    'afectacion' => $afectacion,
                                    'codeafectacion' => $item->gratuito ? '9996' : $codeafectacion,
                                    'nameafectacion' => $item->gratuito ? 'GRA' : $nameafectacion,
                                    'typeafectacion' => $item->gratuito ? 'FRE' : $typeafectacion,
                                    'abreviatureafectacion' => $item->gratuito ? 'Z' : $abreviatureafectacion,
                                    'percent' => $item->igv > 0 ? $this->empresa->igv : 0,
                                ]);
                            }
                        }

                        $counter++;
                    }
                }
            }

            if ($this->empresa->isProduccion()) {
                $seriecomprobante->contador = $numeracion;
            } else {
                $seriecomprobante->contadorprueba = $numeracion;
            }
            $seriecomprobante->save();
            $venta->cotizable()->create([
                'date' => now('America/Lima'),
                'user_id' => auth()->user()->id,
                'cotizacion_id' => $this->cotizacion->id
            ]);
            DB::commit();
            $this->cotizacion->refresh();
            $this->resetValidation();
            $this->opencpe = false;
            $this->dispatchBrowserEvent('toast', toastJSON('COMPROBANTE ' . $venta->seriecompleta . ' GENERADO CORRECTAMENTE'));
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function searchcliente()
    {
        $this->resetValidation();
        $this->document = trim($this->document);
        $this->validate(['document' => ['required', 'numeric', new ValidateDocument, 'regex:/^\d{8}(?:\d{3})?$/',]]);

        $response = Http::withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ])->asForm()->post(route('consultacliente'), [
            'document' => $this->document,
            'autosaved' =>  true,
            'savedireccions' =>  true,
            'searchbd' => true,
            'obtenerlista' => true,
        ]);

        if ($response->ok()) {
            // dd($response->body(), $this->document);
            $cliente = json_decode($response->body());
            if (isset($cliente->success) && $cliente->success) {
                $this->name = $cliente->name;
                $this->direccion = $cliente->direccion;

                if (isset($cliente->id)) {
                    $this->client_id = $cliente->id;
                }
                if ($cliente->birthday) {
                    $this->dispatchBrowserEvent('birthday', $cliente->name);
                }
            } else {
                // $this->name = '';
                // $this->direccion = '';
                $mensaje =  response()->json([
                    'title' => $cliente->error,
                    'text' => null
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
            }
        } else {
            $mensaje =  response()->json([
                'title' => 'Error:' . $response->status() . ' ' . $response->json(),
                'text' => null
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }
    }

    public function limpiarcliente()
    {
        $this->resetValidation();
        $this->reset(['client_id', 'document', 'name', 'direccion']);
        // $this->document = $this->cotizacion->client->document;
        // $this->name = $this->cotizacion->client->name;
        // $this->direccion = $this->cotizacion->direccion;
        // $this->client_id = $this->cotizacion->client_id;
    }
}
