<?php

namespace App\Http\Livewire\Modules\Almacen\Compras;

use App\Enums\MovimientosEnum;
use App\Models\Cajamovimiento;
use App\Models\Concept;
use App\Models\Kardex;
use App\Models\Methodpayment;
use App\Models\Monthbox;
use App\Models\Openbox;
use App\Models\Pricetype;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\Serie;
use App\Models\Typepayment;
use App\Rules\ValidateReferenciaCompra;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Modules\Almacen\Entities\Compra;
use Modules\Almacen\Entities\Compraitem;

class ShowCompra extends Component
{

    use AuthorizesRequests;

    public $compra, $compraitem;
    public $almacens = [];
    public $open = false;
    public $openadd = false;
    public $openproducto = false;
    public $showtipocambio = false;
    public $paymentactual = 0;
    public $pendiente = 0;
    public $openbox, $monthbox, $methodpayment_id, $concept_id, $detalle, $moneda_id;
    public $totalamount, $tipocambio;

    public $requireserie, $priceunitario, $priceventa, $descuentounitario,
        $typedescuento, $igvunitario, $sumstock;
    public $pricetype, $pricetype_id;

    protected function rules()
    {
        return [
            'compra.sucursal_id' => ['required', 'integer', 'min:1', 'exists:sucursals,id'],
            'compra.proveedor_id' => ['required', 'integer', 'min:1', 'exists:proveedors,id'],
            'compra.date' => ['required', 'date', 'before_or_equal:today'],
            'compra.moneda_id' => ['required', 'integer', 'min:1', 'exists:monedas,id'],
            'compra.tipocambio' => ['nullable', Rule::requiredIf($this->compra->moneda->isDolar()), 'numeric', 'gt:0', 'decimal:0,3'],
            'compra.referencia' => [
                'required',
                'string',
                'min:6',
                'regex:/^[a-zA-Z][a-zA-Z0-9][0-9]{2}-(?!0+$)\d{1,8}$/',
                new ValidateReferenciaCompra($this->compra->proveedor_id, $this->compra->sucursal_id, $this->compra->id)
            ],
            'compra.guia' => ['nullable', 'string', 'min:6', 'regex:/^[a-zA-Z][a-zA-Z0-9][0-9]{2}-(?!0+$)\d{1,8}$/'],
            // 'gravado' => ['required', 'numeric', 'min:0', 'decimal:0,4', 'regex:/^\d{0,8}(\.\d{0,4})?$/'],
            // 'exonerado' => ['required', 'numeric', 'min:0', 'decimal:0,4', 'regex:/^\d{0,8}(\.\d{0,4})?$/'],
            // 'igv' => ['required', 'numeric', 'min:0', 'decimal:0,4', 'regex:/^\d{0,8}(\.\d{0,4})?$/'],
            // 'descuento' => ['required', 'numeric', 'min:0', 'decimal:0,4', 'regex:/^\d{0,8}(\.\d{0,4})?$/'],
            // 'otros' => ['required', 'numeric', 'min:0', 'decimal:0,4', 'regex:/^\d{0,8}(\.\d{0,4})?$/'],
            // 'total' => ['required', 'numeric', 'gt:0', 'decimal:0,4', 'regex:/^\d{0,8}(\.\d{0,4})?$/'],
            'compra.typepayment_id' => ['required', 'integer', 'min:1', 'exists:typepayments,id'],
            'compra.detalle' => ['nullable', 'string']
        ];
    }

    public function mount(Compra $compra)
    {
        $this->compra = $compra;
        $this->compraitem = new Compraitem;
        $this->moneda_id = $compra->moneda_id;
        $this->openbox = Openbox::mybox(auth()->user()->sucursal_id)->first();
        $this->monthbox = Monthbox::usando($this->compra->sucursal_id)->first();
        $this->pendiente = $compra->total - $this->compra->cajamovimientos()->sum('amount');

        if ($this->compra->sucursal->empresa->usarLista()) {
            $pricetypes = Pricetype::default()->orderBy('id', 'asc');
            if ($pricetypes->exists()) {
                $this->pricetype = $pricetypes->first();
                $this->pricetype_id = $this->pricetype->id ?? null;
            } else {
                $this->pricetype = Pricetype::orderBy('id', 'asc')->first();
                $this->pricetype_id = $this->pricetype->id ?? null;
            }
        }
    }

    public function render()
    {
        $proveedores = Proveedor::orderBy('name', 'asc')->get();
        $typepayments = Typepayment::orderBy('name', 'asc')->get();
        $methodpayments = Methodpayment::orderBy('name', 'asc')->get();
        $pricetypes = Pricetype::activos()->orderBy('id', 'asc')->get();
        if (auth()->user()->isAdmin()) {
            $sucursals = mi_empresa()->sucursals()->orderBy('codeanexo', 'asc')->get();
        } else {
            $sucursals = auth()->user()->sucursal()->get();
        }

        if ($this->monthbox && $this->openbox) {
            $diferencias = Cajamovimiento::with('moneda')->diferencias($this->monthbox->id, $this->openbox->id, auth()->user()->sucursal_id)->get();
            $diferenciasbytype = Cajamovimiento::diferenciasByType($this->openbox->id, auth()->user()->sucursal_id)->get();
        } else {
            $diferencias = [];
            $diferenciasbytype = [];
        }

        $productos = Producto::query()->select(
            'productos.id',
            'productos.name',
            'marca_id',
            'category_id',
            'subcategory_id',
            'requireserie',
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
            ->addSelect(['image' => function ($query) {
                $query->select('url')->from('images')
                    ->whereColumn('images.imageable_id', 'productos.id')
                    ->where('images.imageable_type', Producto::class)
                    ->orderBy('default', 'desc')->limit(1);
            }])
            ->withCount(['almacens as stock' => function ($query) {
                $query->select(DB::raw('COALESCE(SUM(almacen_producto.cantidad),0)')); // Suma de la cantidad en la tabla pivote
            }])->with(['unit', 'almacens'])->visibles()->orderByDesc('novedad')
            ->orderBy('subcategories.orden', 'ASC')
            ->orderBy('categories.orden', 'ASC')->get();

        return view('livewire.modules.almacen.compras.show-compra', compact('productos', 'methodpayments', 'typepayments', 'proveedores', 'sucursals', 'diferencias', 'pricetypes', 'diferenciasbytype'));
    }

    public function update()
    {
        $this->validate();
        $this->compra->save();
        $this->compra->refresh();
        $this->dispatchBrowserEvent('updated');
        $this->resetValidation();
        $this->resetExcept(['compra', 'compraitem']);
        return redirect()->route('admin.almacen.compras.edit', $this->compra->id);
    }

    public function delete()
    {

        $this->authorize('admin.almacen.compras.delete');
        foreach ($this->compra->compraitems as $compraitem) {
            $exists_seriesout = $compraitem->almacencompras()->WhereHas('series', function ($query) {
                $query->whereNotNull('dateout');
            })->count();
            if ($exists_seriesout) {
                $message = response()->json([
                    'title' => "LA COMPRA CUENTA CON SERIES QUE HAN SIDO MARCADAS COMO SALIDAS EN OTROS REGISTROS.",
                    'text' => null
                ])->getData();
                $this->dispatchBrowserEvent('validation', $message);
                return false;
            }
        }

        if ($this->compra->cuotas()->withWhereHas('cajamovimientos')->count() > 0) {
            $mensaje = response()->json([
                'title' => "PRIMERO DEBE ELIMINAR LOS PAGOS REGISTRADOS EN LA COMPRA.",
                'text' => null
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }

        DB::beginTransaction();
        try {
            $this->compra->compraitems()->with(['almacencompras', 'producto.almacens',])->each(function ($compraitem) {
                $compraitem->almacencompras()->with(['series', 'kardex'])->each(function ($almacencompra) use ($compraitem) {
                    $productoAlmacen = $compraitem->producto->almacens->find($almacencompra->almacen_id);

                    if ($productoAlmacen->pivot->cantidad - $almacencompra->cantidad < 0) {
                        $mensaje = response()->json([
                            'title' => `STOCK DEL PRODUCTO $compraitem->producto->name en almacen "$almacencompra->almacen->name" es menor a 0.`,
                            'text' => null
                        ])->getData();
                        $this->dispatchBrowserEvent('validation', $mensaje);
                        return false;
                    }
                    if ($almacencompra->kardex) {
                        $almacencompra->kardex()->delete();
                    }
                    $compraitem->producto->almacens()->updateExistingPivot($almacencompra->almacen_id, [
                        'cantidad' => $productoAlmacen->pivot->cantidad - $almacencompra->cantidad,
                    ]);
                    $almacencompra->series()->forceDelete();
                });

                $compraitem->producto->pricebuy = $compraitem->oldprice;
                $compraitem->producto->pricesale = $compraitem->oldpricesale;
                $compraitem->producto->save();
                $compraitem->forceDelete();
            });

            if ($this->compra->cajamovimientos()->count() > 0) {
                $this->compra->cajamovimientos()->delete();
            }
            $this->compra->delete();
            DB::commit();
            $this->dispatchBrowserEvent('deleted');
            return redirect()->route('admin.almacen.compras');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function openmodal()
    {
        $this->authorize('admin.almacen.compras.pagos');
        $this->reset(['tipocambio', 'totalamount', 'showtipocambio', 'moneda_id']);
        $this->resetValidation();
        $this->pendiente = (float) ($this->compra->total - $this->compra->cajamovimientos()->sum('amount'));
        $this->paymentactual = $this->pendiente;
        $this->methodpayment_id = Methodpayment::default()->first()->id ?? null;
        // $this->moneda_id = $this->compra->moneda_id;
        $this->open = true;
    }

    public function savepayment()
    {
        $this->authorize('admin.almacen.compras.pagos');
        if ($this->compra->sucursal_id <> auth()->user()->sucursal_id) {
            $mensaje =  response()->json([
                'title' => 'SUCURSAL DE COMPRA DIFERENTE A SUCURSAL DE APERTURA DE CAJA !',
                'text' => "No se pueden realizar movimientos en caja de una sucursal diferente a caja aperturada."
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }

        if (!$this->monthbox || !$this->monthbox->isUsing()) {
            $this->dispatchBrowserEvent('validation', getMessageMonthbox());
            return false;
        }

        if (!$this->openbox || !$this->openbox->isActivo()) {
            $this->dispatchBrowserEvent('validation', getMessageOpencaja());
            return false;
        }

        $this->paymentactual = empty($this->paymentactual) ? 0 : (float) $this->paymentactual;
        $this->concept_id = Concept::Compra()->first()->id ?? null;

        $istransferencia = false;
        if ($this->methodpayment_id) {
            $methodpayment = Methodpayment::find($this->methodpayment_id);
            $istransferencia = $methodpayment->isTransferencia();
        }

        $this->totalamount = $this->paymentactual;
        $this->tipocambio = empty($this->tipocambio) ? null : (float) $this->tipocambio;

        if ($this->compra->moneda_id != $this->moneda_id) {
            if ($this->paymentactual > 0  && $this->tipocambio > 0) {
                $monedaConvertir = $this->compra->moneda->isDolar() ? 'PEN' : 'USD';
                $this->totalamount = convertMoneda($this->paymentactual, $monedaConvertir, $this->tipocambio);
            }
        }

        $this->validate([
            'openbox.id' => ['required', 'integer', 'min:1', 'exists:openboxes,id'],
            'paymentactual' => ['required', 'numeric', 'min:0', 'gt:0', 'decimal:0,3', 'lte:' . $this->pendiente, 'regex:/^\d{0,8}(\.\d{0,3})?$/'],
            'totalamount' => ['required', 'numeric', 'min:0', 'gt:0', 'decimal:0,3', 'regex:/^\d{0,8}(\.\d{0,3})?$/'],
            'tipocambio' => [
                'nullable',
                Rule::requiredIf($this->showtipocambio),
                'numeric',
                'min:0',
                'gt:0',
                'decimal:0,3',
                'regex:/^\d{0,3}(\.\d{0,3})?$/'
            ],
            'openbox.id' => ['required', 'integer', 'min:1', 'exists:openboxes,id'],
            'monthbox.id' => ['required', 'integer', 'min:1', 'exists:monthboxes,id'],
            'moneda_id' => ['required', 'integer', 'min:1', 'exists:monedas,id'],
            'concept_id' => ['required', 'integer', 'min:1', 'exists:concepts,id'],
            'methodpayment_id' => ['required', 'integer', 'min:1', 'exists:methodpayments,id'],
            'detalle' => [Rule::requiredIf($istransferencia), 'string'],
        ]);

        try {

            DB::beginTransaction();
            $saldocaja = Cajamovimiento::saldo($methodpayment->type, $this->monthbox->id, $this->openbox->id, $this->compra->sucursal_id, $this->moneda_id)
                ->first()->diferencia ?? 0;
            $forma = $methodpayment->isEfectivo() ? 'EFECTIVO' : 'TRANSFERENCIA';

            if (($saldocaja - $this->totalamount) < 0) {
                $mensaje =  response()->json([
                    'title' => 'SALDO DE CAJA INSUFICIENTE PARA REALIZAR PAGO DE COMPRA MEDIANTE ' . $forma . ' !',
                    'text' => "Monto de egreso en moneda seleccionada supera el saldo disponible en caja, mediante $forma."
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
            }

            $payment = $this->compra->savePayment(
                $this->compra->sucursal_id,
                number_format($this->paymentactual, 3, '.', ''),
                $this->totalamount,
                $this->showtipocambio ? number_format($this->tipocambio, 3, '.', '') : null,
                $this->moneda_id,
                $this->methodpayment_id,
                MovimientosEnum::EGRESO->value,
                $this->concept_id,
                $this->openbox->id,
                $this->monthbox->id,
                $this->compra->referencia,
                trim($this->detalle)
            );

            DB::commit();
            $this->resetValidation();
            $this->reset(['open', 'methodpayment_id', 'detalle', 'paymentactual', 'tipocambio', 'totalamount', 'showtipocambio', 'moneda_id']);
            $this->compra->refresh();
            $this->dispatchBrowserEvent('created');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deletepay(Cajamovimiento $cajamovimiento)
    {

        $this->authorize('admin.almacen.compras.pagos');
        DB::beginTransaction();
        try {
            $cajamovimiento->delete();
            DB::commit();
            $this->compra->refresh();
            $this->dispatchBrowserEvent('deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function edit(Compraitem $compraitem)
    {
        $this->resetValidation();
        $this->setAlmacens($compraitem);
        $this->requireserie = $compraitem->producto->isRequiredserie();
        $this->priceunitario = $compraitem->price;
        $this->typedescuento = $compraitem->descuento > 0 ? '1' : '0';
        $this->igvunitario = $compraitem->igv;
        $this->descuentounitario = $compraitem->descuento;
        $this->priceventa = $compraitem->producto->pricesale;
        $this->compraitem = $compraitem;
        $this->openproducto = true;
    }

    public function setAlmacens($compraitem)
    {
        $unit = $compraitem->producto->unit->name ?? '';
        $arrayalmacens = $compraitem->almacencompras()->with(['almacen', 'series' => function ($query) {
            $query->select('id', 'serie', 'dateout', 'status', 'almacencompra_id');
        }])->get()->toArray();
        $combined = $compraitem->producto->almacens->map(function ($item) use ($arrayalmacens, $unit) {
            $almacencompra = collect($arrayalmacens)->firstWhere('almacen_id', $item['id']);
            if ($almacencompra) {
                $almacencompra['cantidad'] = decimalOrInteger($almacencompra['cantidad']);
                $almacencompra['series'] = $almacencompra['series'];
            } else {
                $almacencompra['cantidad'] = 0;
                $almacencompra['series'] = [];
            }
            $almacencompra['id'] = $item->id;
            $almacencompra['name'] =  $item->name;
            $almacencompra['unit'] =  $unit;
            $almacencompra['stock_actual'] =  $item->pivot->cantidad;
            $almacencompra['newserie'] = '';
            return $almacencompra;
        })->toArray();

        // dd($combined);
        $this->almacens = $combined;
    }

    public function updateitem($closemodal = false)
    {

        $this->sumstock = collect($this->almacens)->sum('cantidad');

        $this->validate([
            'sumstock' =>   ['required', 'numeric', 'gt:0', 'decimal:0,2'],
            'priceunitario' => ['required'],
            'priceventa' => ['required'],
            'descuentounitario' => ['required'],
            'typedescuento' => ['required'],
            'igvunitario' => ['required'],
        ]);

        DB::beginTransaction();
        try {

            foreach ($this->almacens as $key => $almacen) {
                $almacen_id = (int) $almacen['id'];
                $productoAlmacen = $this->compraitem->producto->almacens->find($almacen_id);
                $almacencompra = $this->compraitem->almacencompras()->firstOrCreate(['almacen_id' => $almacen_id], [
                    'almacen_id' => $almacen['id'],
                    'cantidad' => $almacen['cantidad']
                ]);

                if ($this->compraitem->producto->isRequiredserie()) {
                    if (count($almacen['series']) != $almacen['cantidad']) {
                        $this->addError("almacens.$key.newserie", 'Series agregadas no coinciden con stock entrante.');
                        $message = response()->json([
                            'title' => "SERIES AGREGADAS NO COINCIDEN CON STOCK ENTRANTE.",
                            'text' => null,
                        ])->getData();
                        $this->dispatchBrowserEvent('validation', $message);
                        return false;
                    }

                    foreach ($almacen['series'] as $serial) {
                        if (is_null($serial['id']) && $almacencompra) {
                            $almacencompra->series()->create([
                                'serie' => trim($serial['serie']),
                                'almacen_id' => $almacen_id,
                                'producto_id' => $this->compraitem->producto_id,
                                'user_id' => auth()->user()->id,
                            ]);
                        }
                    }
                }

                $almacenDB = $this->compraitem->almacencompras()->where('almacen_id', $almacen_id)->first();
                $cantidadSaved = $almacenDB->cantidad;

                if ($cantidadSaved > $almacen['cantidad']) {
                    // dd($productoAlmacen->pivot->cantidad - ($cantidadSaved - $almacen['cantidad']));
                    $this->compraitem->producto->almacens()->updateExistingPivot($almacen_id, [
                        'cantidad' => $productoAlmacen->pivot->cantidad - ($cantidadSaved - $almacen['cantidad']),
                    ]);
                } else {
                    // dd($productoAlmacen->pivot->cantidad + ($almacen['cantidad'] - $cantidadSaved));
                    $this->compraitem->producto->almacens()->updateExistingPivot($almacen_id, [
                        'cantidad' => $productoAlmacen->pivot->cantidad + ($almacen['cantidad'] - $cantidadSaved),
                    ]);
                }
                // dd($cantidadSaved);
                if ($almacencompra) {
                    $almacencompra->cantidad = $almacen['cantidad'];
                    $almacencompra->save();

                    if ($almacencompra->kardex) {
                        if ($almacencompra->kardex->cantidad <> $almacen['cantidad']) {
                            if ($almacencompra->kardex->cantidad < $almacen['cantidad']) {
                                $diferencia = $almacen['cantidad'] - $almacencompra->kardex->cantidad;
                                $almacencompra->kardex->newstock = $almacencompra->kardex->newstock + $diferencia;
                            } else {
                                $diferencia = $almacencompra->kardex->cantidad - $almacen['cantidad'];
                                $almacencompra->kardex->newstock = $almacencompra->kardex->newstock - $diferencia;
                            }
                        }
                        $almacencompra->kardex->cantidad = $almacen['cantidad'];
                        $almacencompra->kardex->save();
                    } else {
                        // dd( $almacen);
                        $almacencompra->saveKardex(
                            $this->compraitem->producto_id,
                            $almacen['id'],
                            $almacen['stock_actual'],
                            $almacen['stock_actual'] +  $almacen['cantidad'],
                            $almacen['cantidad'],
                            '+',
                            Kardex::ENTRADA_ALMACEN,
                            $this->compra->seriecompleta
                        );
                    }
                }
            }

            $priceunitario = $this->priceunitario;
            $descuentounitario =  $this->descuentounitario;
            $subtotaldescuento = $this->descuentounitario * $this->sumstock;
            $subtotaligv = $this->igvunitario * $this->sumstock;

            if ($this->typedescuento > 0) {
                if ($this->typedescuento == '2') {
                    $priceunitario = $this->priceunitario - $descuentounitario;
                    // $this->priceunitario = $this->priceunitario;
                } elseif ($this->typedescuento == '3') {
                    $descuentounitario = number_format($this->descuentounitario / $this->sumstock, 2, '.', '');
                    $subtotaldescuento = $this->descuentounitario;
                    // $this->descuentounitario = number_format($subtotaldescuento / $this->sumstock, 2, '.', '');
                }
            } else {
                $descuentounitario = 0;
                $subtotaldescuento = 0;
            }

            $pricebuy = $priceunitario + $this->igvunitario;
            $this->compraitem->price = $priceunitario;
            $this->compraitem->cantidad = $this->sumstock;
            $this->compraitem->descuento = $descuentounitario;
            $this->compraitem->subtotaligv = $subtotaligv;
            $this->compraitem->subtotaldescuento = $subtotaldescuento;
            $this->compraitem->total = ($this->priceunitario * $this->sumstock) + $subtotaligv;
            $this->compraitem->subtotal = ($this->priceunitario * $this->sumstock) + $subtotaligv + $subtotaldescuento;
            $this->compraitem->save();

            $this->compraitem->producto->pricebuy =  $this->compra->moneda->isDolar() ?
                number_format($this->pricebuy * $this->compra->tipocambio, 2, '.', '') :
                number_format($this->pricebuy, 2, '.', '');
            if (!mi_empresa()->usarLista()) {
                $this->compraitem->producto->pricesale = $this->priceventa;
            }

            $this->compraitem->producto->save();
            $this->compraitem->producto->load(['promocions' => function ($query) {
                $query->with(['itempromos.producto' => function ($subQuery) {
                    $subQuery->with('unit')->addSelect(['image' => function ($q) {
                        $q->select('url')->from('images')
                            ->whereColumn('images.imageable_id', 'productos.id')
                            ->where('images.imageable_type', Producto::class)
                            ->orderBy('default', 'desc')->limit(1);
                    }]);
                }])->availables()->disponibles()->take(1);
            }]);
            $this->compraitem->producto->assignPrice();
            $this->setTotal();
            DB::commit();
            $this->compraitem->refresh();
            $this->compra->refresh();
            $this->resetValidation();
            // dd($this->compra, $this->compra->compraitems()->sum('total'));
            if ($closemodal) {
                $this->resetExcept(['compra', 'compraitem']);
            } else {
                $this->resetExcept(['compra', 'compraitem', /* 'openproducto' */]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function addserie(int $index)
    {

        DB::beginTransaction();
        try {

            $this->almacens[$index]['newserie'] = trim(mb_strtoupper($this->almacens[$index]['newserie'], "UTF-8"));
            $validateData = $this->validate([
                "almacens.$index.newserie" => ['required', 'string', 'min:6']
            ]);

            if (count($this->almacens[$index]['series']) >= $this->almacens[$index]['cantidad']) {
                $this->addError("almacens.$index.newserie", 'Series superan a la cantidad entrante.');
                return false;
            }

            $exists = collect($this->almacens ?? [])->flatMap(function ($item) {
                return $item['series'];
            })->firstWhere('serie', $this->almacens[$index]['newserie']);

            if ($exists) {
                $this->addError("almacens.$index.newserie", 'Serie ya se encuentra agregado.');
                return false;
            }

            $existsDB = Serie::where('serie', $this->almacens[$index]['newserie'])->exists();
            if ($existsDB) {
                $this->addError("almacens.$index.newserie", 'Serie ya existe en la base de datos.');
                return false;
            }

            $arraySerie = [
                'id' => null,
                'serie' =>  $this->almacens[$index]['newserie'],
            ];
            $this->almacens[$index]['series'][] = $arraySerie;
            DB::commit();
            $this->almacens[$index]['newserie'] = '';
            $this->resetValidation();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function removeserie($almacenindex, $index)
    {
        DB::beginTransaction();
        try {
            if (isset($this->almacens[$almacenindex]['series'][$index])) {
                $serie_id = $this->almacens[$almacenindex]['series'][$index]['id'] ?? null;
                $almacen_id = $this->almacens[$almacenindex]['id'];

                if (!is_null($serie_id)) {
                    $stockPivot = $this->compraitem->producto->almacens->find($almacen_id);
                    if ($stockPivot) {
                        if ($stockPivot->pivot->cantidad < 1) {
                            $mensaje = response()->json([
                                'title' => "NO EXISTE SUFICIENTE CANTIDAD PARA DISMINUIR STOCK EN ALMACÉN.",
                                'text' => null
                            ])->getData();
                            $this->dispatchBrowserEvent('validation', $mensaje);
                            return false;
                        }

                        $serie = Serie::find($serie_id);
                        if ($serie->isSalida()) {
                            $message = response()->json([
                                'title' => "LA SERIE HA SIDO MARCADA COMO SALIDA Y NO SE PUEDE DESHACER EL PROCESO.",
                                'text' => null
                            ])->getData();
                            $this->dispatchBrowserEvent('validation', $message);
                            return false;
                        }
                        $serie->forceDelete();

                        $this->compraitem->producto->almacens()->updateExistingPivot($almacen_id, [
                            'cantidad' => $stockPivot->pivot->cantidad - 1,
                        ]);

                        $almacencompra = $this->compraitem->almacencompras()->where('almacen_id', $almacen_id)->first();
                        if ($almacencompra) {
                            $almacencompra->cantidad = $almacencompra->cantidad - 1;
                            $almacencompra->save();
                        }
                    } else {
                        $mensaje = response()->json([
                            'title' => "ALMACÉN YA NO SE ENCUENTRA DISPONIBLE EN EL PRODUCTO SELECCIONADO",
                            'text' => null
                        ])->getData();
                        $this->dispatchBrowserEvent('validation', $mensaje);
                    }
                }

                unset($this->almacens[$almacenindex]['series'][$index]);
                $this->almacens[$almacenindex]['series'] = array_values($this->almacens[$almacenindex]['series']);
                DB::commit();
                $this->compraitem->refresh();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteitemcompra(Compraitem $compraitem)
    {
        DB::beginTransaction();
        try {
            foreach ($compraitem->almacencompras as $item) {
                $outseries = $item->series()->whereNotNull('dateout')->count();
                if ($outseries) {
                    $message = response()->json([
                        'title' => 'ITEM DE COMPRA CONTIENE SERIES QUE HAN SIDO REGISTRADO COMO SALIDAS !',
                        'text' => null,
                    ])->getData();
                    $this->dispatchBrowserEvent('validation', $message);
                    return false;
                }

                $stockPivot = $compraitem->producto->almacens->find($item->almacen_id);
                if ($stockPivot) {
                    if ($stockPivot->pivot->cantidad < $item->cantidad) {
                        $mensaje = response()->json([
                            'title' => 'NO EXISTE SUFICIENTE STOCK PARA DESCONTAR LAS CANTIDAD ENTRANTE EN COMPRA',
                            'text' => null
                        ])->getData();
                        $this->dispatchBrowserEvent('validation', $mensaje);
                        return false;
                    }
                    $compraitem->producto->almacens()->updateExistingPivot($item->almacen_id, [
                        'cantidad' => $stockPivot->pivot->cantidad - $item->cantidad,
                    ]);
                }

                if ($item->kardex) {
                    $item->kardex()->delete();
                }
                $item->series()->forceDelete();
                $item->delete();
            }

            $compraitem->producto->pricebuy = $compraitem->oldprice;
            $compraitem->producto->pricesale = $compraitem->oldpricesale;
            $compraitem->producto->save();
            $compraitem->producto->assignPrice();
            $compraitem->forceDelete();
            $this->setTotal();
            DB::commit();
            $this->resetValidation();
            $this->compra->refresh();
            $this->dispatchBrowserEvent('deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function setTotal($validatePago = true)
    {
        $total  = $this->compra->compraitems()->sum('total');

        if ($validatePago) {
            $totalpagado = $this->compra->cajamovimientos()->sum('amount');
            if ($totalpagado > $total) {
                $message = response()->json([
                    'title' => "MONTO DEL PAGO REGISTRADO ES SUPERIOR AL TOTAL DE LA COMPRA.",
                    'text' => 'Eliminar pago de compra y volver actualizar los datos del item.',
                ])->getData();
                $this->dispatchBrowserEvent('validation', $message);
                return false;
            }
        }

        $this->compra->total = $total;
        $this->compra->igv = $this->compra->compraitems()->sum('subtotaligv');
        $this->compra->descuento = $this->compra->compraitems()->sum('subtotaldescuento');
        if ($this->compra->isExonerado()) {
            $this->compra->exonerado = $total;
            $this->compra->gravado = 0;
        } else {
            $this->compra->gravado = $total;
            $this->compra->exonerado = 0;
        }

        $this->compra->save();
    }

    public $producto_id, $subtotaldsctoitem = 0, $subtotaligvitem = 0,
        $pricebuy = 0, $subtotalitem = 0, $totalitem = 0;

    public function updatingOpenadd()
    {
        if (!$this->openadd) {
            $this->reset([
                'producto_id',
                'almacens',
                'priceunitario',
                'igvunitario',
                'subtotaligvitem',
                'descuentounitario',
                'pricebuy',
                'subtotalitem',
                'priceventa',
                'subtotaldsctoitem',
                'totalitem',
                'typedescuento',
                'requireserie'
            ]);
            $this->resetValidation();
            $this->typedescuento = 0;
        }
    }

    public function updatedProductoId()
    {
        $this->resetValidation();
        $almacens = [];
        if (!empty($this->producto_id)) {
            $producto = Producto::with(['almacens', 'unit'])->find($this->producto_id);
            foreach ($producto->almacens as $item) {
                $almacens[] = [
                    'id' => $item->id,
                    'name' => $item->name,
                    'stock_actual' => $item->pivot->cantidad,
                    'cantidad' => 0,
                    'series' => [],
                    'newserie' => '',
                    'addseries' => $producto->isRequiredserie(),
                    'unit' => $producto->unit->name
                ];
            }
        }
        $this->almacens = $almacens;
    }

    public function addproducto($closemodal = false)
    {
        DB::beginTransaction();
        try {
            $this->validate([
                'producto_id' => [
                    'required',
                    'integer',
                    'min:1',
                    'exists:productos,id',
                    Rule::unique('compraitems', 'producto_id')->where('compra_id', $this->compra->id)
                ],
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
                'priceventa' => !$this->compra->sucursal->empresa->usarlista() ? ['required', 'numeric', 'decimal:0,2', 'gt:0'] : ['nullable', 'min:0']
            ]);

            $myalmacens = collect($this->almacens)->filter(function ($item) {
                return $item['cantidad'] > 0;
            })->toArray();

            $producto = Producto::with(['unit'])->addSelect(['image' => function ($query) {
                $query->select('url')->from('images')
                    ->whereColumn('images.imageable_id', 'productos.id')
                    ->where('images.imageable_type', Producto::class)
                    ->orderBy('default', 'desc')->limit(1);
            }])->find($this->producto_id);

            if ($producto->requireserie) {
                foreach ($myalmacens as $key => $item) {
                    if (count($item['series']) != $item['cantidad']) {
                        $this->addError("almacens.$key.cantidad", 'Series agregadas no coinciden con la cantidad entrante.');
                        return false;
                    }
                }
            }

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
            if ($this->compra->moneda->isDolar()) {
                $pricebuysoles = $this->pricebuy * $this->tipocambio ?? 1;
            }

            $compraitem = $this->compra->compraitems()->create([
                'cantidad' => $this->sumstock,
                'price' => $this->pricebuy,
                'oldprice' => $producto->pricebuy,
                'oldpricesale' => $producto->pricesale,
                'igv' => $this->igvunitario,
                'descuento' => $this->descuentounitario,
                'subtotaligv' => $this->subtotaligvitem,
                'subtotaldescuento' => $this->subtotaldsctoitem,
                'subtotal' => $this->subtotalitem,
                'total' => $this->totalitem,
                'producto_id' => $this->producto_id,
                // 'almacens' => $myalmacens,
            ]);

            foreach ($myalmacens as $almacen) {
                $almacencompra = $compraitem->almacencompras()->create([
                    'cantidad' => $almacen['cantidad'],
                    'almacen_id' => $almacen['id'],
                ]);

                if ($producto->isRequiredserie()) {
                    foreach ($almacen['series'] as $serie) {
                        $almacencompra->series()->create([
                            'serie' => trim($serie['serie']),
                            'almacen_id' => $almacen['id'],
                            'producto_id' => $this->producto_id,
                            'user_id' => auth()->user()->id,
                        ]);
                    }
                }

                $stockPivot = $producto->almacens->find($almacen['id']);

                $almacencompra->saveKardex(
                    $this->producto_id,
                    $almacen['id'],
                    $stockPivot->pivot->cantidad,
                    $stockPivot->pivot->cantidad +  $almacen['cantidad'],
                    $almacen['cantidad'],
                    '+',
                    Kardex::ENTRADA_ALMACEN,
                    $this->compra->referencia
                );
                $producto->almacens()->updateExistingPivot($almacen['id'], [
                    'cantidad' => $stockPivot->pivot->cantidad + $almacen['cantidad'],
                ]);
            }

            $producto->pricebuy =  $this->compra->moneda->isDolar() ?
                number_format($this->pricebuy * $this->compra->tipocambio, 2, '.', '') :
                number_format($this->pricebuy, 2, '.', '');
            if (!view()->shared('empresa')->usarLista()) {
                $producto->pricesale = $this->priceventa;
            }

            $producto->save();
            $producto->assignPrice();
            DB::commit();
            $this->compra->refresh();
            $this->setTotal(false);
            $this->resetValidation();
            $this->reset([
                'producto_id',
                'almacens',
                'priceunitario',
                'igvunitario',
                'subtotaligvitem',
                'descuentounitario',
                'pricebuy',
                'subtotalitem',
                'priceventa',
                'subtotaldsctoitem',
                'totalitem',
                'typedescuento',
                'requireserie',
            ]);

            if ($closemodal) {
                $this->openadd = false;
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updatedPricetypeId($value)
    {
        if ($value) {
            $this->pricetype = Pricetype::find($value);
            $this->pricetype_id = $this->pricetype->id;
        }
    }

    public function hydrate()
    {
        $this->compra->refresh();
    }
}
