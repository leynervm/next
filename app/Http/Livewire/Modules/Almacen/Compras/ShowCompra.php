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

    public $producto_id, $subtotaldsctoitem = 0, $subtotaligvitem = 0,
        $pricebuy = 0, $subtotalitem = 0, $totalitem = 0;

    public $requireserie, $priceunitario, $priceventa, $descuentounitario,
        $typedescuento, $typedescuentoprod, $igvunitario, $sumstock;
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
            ->withCount(['almacens as stock' => function ($query) {
                $query->select(DB::raw('COALESCE(SUM(almacen_producto.cantidad),0)')); // Suma de la cantidad en la tabla pivote
            }])->with(['unit', 'imagen', 'almacens'])->visibles()->orderByDesc('novedad')
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
        $compraitem->load(['kardexes', 'series', 'producto' => function ($query) {
            $query->with(['unit', 'almacens']);
        }]);
        Self::setAlmacens($compraitem);
        $this->compraitem = $compraitem;
        $this->requireserie = $compraitem->producto->isRequiredserie();
        $this->priceunitario = $compraitem->price;
        $this->igvunitario = $compraitem->igv;
        $this->typedescuentoprod = $compraitem->typedescuento;
        $this->descuentounitario = $compraitem->descuento;
        $this->priceventa = $compraitem->producto->pricesale;
        $this->subtotaldsctoitem = $compraitem->subtotaldescuento;
        $this->subtotalitem = $compraitem->total + $compraitem->subtotaldescuento;
        $this->totalitem = $compraitem->total;
        $this->sumstock = collect($this->almacens)->sum('cantidad');
        $this->openproducto = true;
    }

    public function updateitem($closemodal = false)
    {
        $this->sumstock = collect($this->almacens)->sum('cantidad');
        $this->validate([
            'sumstock' =>   ['required', 'numeric', 'gt:0'],
            'priceunitario' => ['required', 'numeric', 'decimal:0,2', 'gt:0'],
            'typedescuentoprod' => ['required', 'integer', 'min:0', 'in:0,1,2,3'],
            'descuentounitario' => $this->typedescuentoprod > 0 ?
                ['required', 'numeric', 'decimal:0,2', 'gt:0'] :
                ['nullable'],
            'igvunitario' => ['required', 'min:0', 'numeric', 'decimal:0,3'],
            'priceventa' => $this->compra->sucursal->empresa->usarlista() ?
                ['nullable', 'min:0'] :
                ['required', 'numeric', 'decimal:0,3', 'gt:0'],
            'almacens' => ['required', 'array', 'min:1'],
        ]);

        DB::beginTransaction();
        try {
            // Verificamos que almenos exista almacenes con cantidad > 0
            $validAlmacens = collect($this->almacens)->filter(function ($item) {
                return $item['cantidad'] > 0;
            })->toArray();

            if (count($validAlmacens) == 0) {
                $message = response()->json([
                    // 'title' => "INGRESAR CANTIDAD MAYOR A CERO AL MENOS EN UN ALMACÉN DISPONIBLE.",
                    'title' => "STOCK TOTAL ENTRANTE DEBE SER MAYOR A CERO.",
                    'text' => null,
                ])->getData();
                $this->dispatchBrowserEvent('validation', $message);
                return false;
            }

            foreach ($this->almacens as $key => $item) {
                if ($item['addseries'] && count($item['series']) != $item['cantidad']) {
                    $this->addError("almacens.$key.cantidad", 'Series agregadas no coinciden con la cantidad entrante.');
                    return false;
                }
            }

            $priceunitario = $this->priceunitario;
            $descuentounitario =  $this->descuentounitario;
            $subtotaldescuento = $this->descuentounitario * $this->sumstock;
            $subtotaligv = $this->igvunitario * $this->sumstock;

            // if ($this->typedescuentoprod > 0) {
            //     if ($this->typedescuentoprod == Compraitem::PRECIO_UNIT_SIN_DSCTO_APLICADO) {
            //         $this->priceunitario = $this->pricebuy;
            //     } elseif ($this->typedescuentoprod == Compraitem::DSCTO_IMPORTE_TOTAL) {
            //         $this->descuentounitario = number_format($this->subtotaldsctoitem / $this->sumstock, 2, '.', '');
            //     }
            // } else {
            //     $this->descuentounitario = 0;
            //     $this->subtotaldsctoitem = 0;
            // }

            if ($this->typedescuentoprod > 0) {
                if ($this->typedescuentoprod == Compraitem::PRECIO_UNIT_SIN_DSCTO_APLICADO) {
                    $priceunitario = $this->priceunitario - $descuentounitario;
                } elseif ($this->typedescuentoprod == Compraitem::DSCTO_IMPORTE_TOTAL) {
                    $descuentounitario = number_format($this->descuentounitario / $this->sumstock, 2, '.', '');
                    $subtotaldescuento = $this->descuentounitario;
                }
            } else {
                $descuentounitario = 0;
                $subtotaldescuento = 0;
            }


            foreach ($this->almacens as $key => $almacen) {
                // 1.- Verificamos si nuevo stock es inferior a existente en caso de existir
                $kardex = $this->compraitem->kardexes()->where('almacen_id', $almacen['id'])->first();
                $stock = $this->compraitem->producto->almacens()->find($almacen['id'])->pivot->cantidad;

                if (!empty($kardex)) {
                    if ($almacen['cantidad'] < $kardex->cantidad) {
                        // 2.-Verificar que diferencia a disminuir exista en stock almacen sino quedara en NEGATIVO.
                        $diferencia = $kardex->cantidad - $almacen['cantidad'];
                        if ($stock < $diferencia) {
                            $this->addError("almacens.$key.cantidad", 'Stock insuficiente para quitar de almacén.');
                            return false;
                        }

                        $kardex->cantidad = $almacen['cantidad'];
                        $kardex->newstock = $kardex->newstock - $diferencia;
                        $this->compraitem->producto->descontarStockProducto($almacen['id'], $diferencia);
                    } else {
                        $diferencia = $almacen['cantidad'] - $kardex->cantidad;
                        $kardex->newstock = $kardex->newstock + $diferencia;
                        $this->compraitem->producto->incrementarStockProducto($almacen['id'], $diferencia);
                    }

                    $kardex->cantidad = $almacen['cantidad'];
                    if ($kardex->cantidad > 0) {
                        $kardex->save();
                    } else {
                        $kardex->delete();
                    }
                } else {
                    if ($almacen['cantidad'] > 0) {
                        $kardex = $this->compraitem->kardexes()->create([
                            'date' => now('America/Lima'),
                            'cantidad' => $almacen['cantidad'],
                            'oldstock' => $stock,
                            'newstock' => $stock + $almacen['cantidad'],
                            'simbolo' => Kardex::SIMBOLO_INGRESO,
                            'detalle' => Kardex::ENTRADA_ALMACEN,
                            'reference' => $this->compra->referencia,
                            'producto_id' => $this->compraitem->producto_id,
                            'almacen_id' => $almacen['id'],
                            'sucursal_id' => $this->compra->sucursal_id,
                            'user_id' => auth()->user()->id,
                        ]);
                        $this->compraitem->producto->incrementarStockProducto($almacen['id'], $almacen['cantidad']);
                    }
                }

                if ($this->compraitem->producto->isRequiredserie() && count($almacen['series']) > 0) {
                    //Validar eliminacion de serie con con id no nulos
                    $idArr = array_filter(array_column($almacen['series'], 'id'));
                    foreach ($this->compraitem->series->where('almacen_id', $key) as $serie) {
                        if (!in_array($serie->id, $idArr)) {
                            if (!Self::validatedeleteserie($serie)) {
                                return;
                            }
                            $serie->forceDelete();
                            $this->compraitem->producto->descontarStockProducto($serie->almacen_id, 1);
                        }
                    }

                    foreach ($almacen['series'] as $serie) {
                        //Serie debe ser unico por producto
                        $itemserie = $this->compraitem->series()->firstOrCreate([
                            'serie' => trim($serie['serie']),
                            'producto_id' => $this->compraitem->producto_id,
                        ], [
                            'almacen_id' => $almacen['id'],
                            'user_id' => auth()->user()->id,
                        ]);
                    }
                }
            }


            $pricebuysoles = $priceunitario + $this->igvunitario;
            $this->compraitem->price = $priceunitario;
            $this->compraitem->igv = $this->igvunitario;
            $this->compraitem->cantidad = $this->sumstock;
            $this->compraitem->typedescuento = $this->typedescuentoprod;
            $this->compraitem->descuento = $descuentounitario;
            $this->compraitem->subtotaligv = $subtotaligv;
            $this->compraitem->subtotaldescuento = $subtotaldescuento;
            $this->compraitem->total = ($this->priceunitario * $this->sumstock) + $subtotaligv;
            $this->compraitem->subtotal = ($this->priceunitario * $this->sumstock) + $subtotaligv + $subtotaldescuento;
            $this->compraitem->save();

            if (!$this->compra->sucursal->empresa->usarLista()) {
                $this->compraitem->producto->pricesale = $this->priceventa;
            }

            if ($this->compra->moneda->isDolar()) {
                $pricebuysoles = $pricebuysoles * $this->compra->tipocambio ?? 1;
            }

            $this->compraitem->producto->pricebuy = $pricebuysoles;
            $this->compraitem->producto->save();
            $this->compraitem->producto->assignPrice();
            $this->setTotal();
            DB::commit();
            $this->compra->refresh();
            $this->resetValidation();
            if ($closemodal) {
                $this->resetExcept(['compra']);
                $this->compraitem = new Compraitem();
            } else {
                $this->resetExcept(['compra', 'compraitem', /* 'openproducto' */]);
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

    public function updatingOpenadd()
    {
        if (!$this->openadd) {
            $this->reset([
                'producto_id',
                'compraitem',
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
            $this->compraitem = new Compraitem;
            $this->resetValidation();
            $this->typedescuento = 0;
        }
    }

    public function addserie(int $index)
    {
        DB::beginTransaction();
        try {
            $this->almacens[$index]['newserie'] = trim(mb_strtoupper($this->almacens[$index]['newserie'], "UTF-8"));
            $validateData = $this->validate([
                "almacens.$index.newserie" => ['required', 'string', 'min:4']
            ], [], [
                "almacens.$index.newserie" => 'serie'
            ]);
            $serie = $this->almacens[$index]['newserie'];

            // if (count($this->almacens[$index]['series']) >= $this->almacens[$index]['cantidad']) {
            //     $this->addError("almacens.$index.newserie", 'Series superan a la cantidad entrante.');
            //     return false;
            // }

            $exists = collect($this->almacens ?? [])->flatMap(function ($item) {
                return $item['series'];
            })->firstWhere('serie', $serie);

            if ($exists) {
                $this->addError("almacens.$index.newserie", 'Serie ya se encuentra agregado.');
                return false;
            }

            $existsDB = Serie::where('serie', $serie)->exists();
            if ($existsDB) {
                $this->addError("almacens.$index.newserie", 'Serie ya existe en la base de datos.');
                return false;
            }

            $arraySerie = [
                'id' => null,
                'serie' =>  $serie,
                'dateout' =>  null,
                'status' =>  Serie::DISPONIBLE,
            ];
            $this->almacens[$index]['series'][] = $arraySerie;
            $this->almacens[$index]['cantidad'] = count($this->almacens[$index]['series']);
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

    public function updatedProductoId()
    {
        $this->resetValidation();
        $almacens = [];
        if (!empty($this->producto_id)) {
            $producto = Producto::with(['almacens', 'unit'])->find($this->producto_id);
            foreach ($producto->almacens as $item) {
                $almacens[$item->id] = [
                    'id' => $item->id,
                    'name' => $item->name,
                    'unit' => $producto->unit->name,
                    'stock_actual' => $item->pivot->cantidad,
                    'cantidad' => 0,
                    'series' => [],
                    'newserie' => '',
                    'addseries' => $producto->isRequiredserie(),
                ];
            }
        }
        $this->almacens = $almacens;
        // dd($this->almacens);
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
                'priceunitario' => ['required', 'numeric', 'decimal:0,3', 'gt:0'],
                'igvunitario' => ['nullable', 'numeric', 'decimal:0,3', 'min:0'],
                'pricebuy' => ['required', 'numeric', 'decimal:0,3', 'gt:0'],
                'typedescuento' => ['required', 'integer', 'min:0', 'in:0,1,2,3'],
                'subtotaligvitem' => ['required', 'numeric', 'min:0'],
                'subtotalitem' => ['required', 'numeric', 'gt:0'],
                'subtotaldsctoitem' => ['nullable', 'numeric', 'min:0'],
                'descuentounitario' =>  $this->typedescuento > 0 ?
                    ['required', 'numeric', 'decimal:0,2', 'gt:0'] :
                    ['nullable'],
                'priceventa' => $this->compra->sucursal->empresa->usarlista() ?
                    ['nullable', 'min:0'] :
                    ['required', 'numeric', 'decimal:0,3', 'gt:0']

            ]);

            $myalmacens = collect($this->almacens)->filter(function ($item) {
                return $item['cantidad'] > 0;
            })->toArray();

            // if ($item->isRequiredserie()) {
            foreach ($myalmacens as $key => $item) {
                if ($item['addseries'] && count($item['series']) != $item['cantidad']) {
                    $this->addError("almacens.$key.cantidad", 'Series agregadas no coinciden con la cantidad entrante.');
                    return false;
                }
            }

            if ($this->typedescuento > 0) {
                if ($this->typedescuento == Compraitem::PRECIO_UNIT_SIN_DSCTO_APLICADO) {
                    $this->priceunitario = $this->pricebuy;
                } elseif ($this->typedescuento == Compraitem::DSCTO_IMPORTE_TOTAL) {
                    $this->descuentounitario = number_format($this->subtotaldsctoitem / $this->sumstock, 2, '.', '');
                }
            } else {
                $this->descuentounitario = 0;
                $this->subtotaldsctoitem = 0;
            }

            $producto = Producto::with('almacens')->find($this->producto_id);
            $compraitem = $this->compra->compraitems()->where('producto_id', $this->producto_id)
                ->where('typedescuento', $this->typedescuento)->first();

            if (!empty($compraitem)) {
                $compraitem->cantidad = $compraitem->cantidad + array_sum(array_column($myalmacens, 'cantidad'));
                $compraitem->price = $this->priceunitario;
                $compraitem->igv = $this->igvunitario;
                $compraitem->descuento = $this->descuentounitario;
                $compraitem->subtotaligv = $this->igvunitario * $compraitem->cantidad;
                $compraitem->subtotaldescuento = $this->descuentounitario > 0 ? $this->descuentounitario * $compraitem->cantidad : 0;
                $compraitem->total = ($this->priceunitario + $this->igvunitario) * $compraitem->cantidad;
                $compraitem->subtotal =  $compraitem->subtotaldescuento + $compraitem->total;
                $compraitem->save();
            } else {
                $compraitem = $this->compra->compraitems()->create([
                    'cantidad' => array_sum(array_column($myalmacens, 'cantidad')),
                    'price' => $this->priceunitario,
                    'igv' => $this->igvunitario,
                    'oldprice' => $producto->pricebuy,
                    'oldpricesale' => $producto->pricesale,
                    'typedescuento' => $this->typedescuento,
                    'descuento' => $this->descuentounitario,
                    'subtotaligv' => $this->subtotaligvitem,
                    'subtotaldescuento' => $this->subtotaldsctoitem,
                    'subtotal' => $this->subtotalitem,
                    'total' => $this->totalitem,
                    'producto_id' => $this->producto_id,
                ]);
            }

            foreach ($myalmacens as $almacen) {

                $kardex = $compraitem->kardexes()->where('almacen_id', $almacen['id'])->first();

                if (!empty($kardex)) {
                    $kardex->cantidad = $kardex->cantidad + $almacen['cantidad'];
                    $kardex->newstock = $kardex->newstock + $almacen['cantidad'];
                    $kardex->save();
                } else {
                    $stock = $producto->almacens->find($almacen['id'])->pivot->cantidad;
                    $kardex = $compraitem->kardexes()->create([
                        'date' => now('America/Lima'),
                        'cantidad' => $almacen['cantidad'],
                        'oldstock' => $stock,
                        'newstock' => $stock + $almacen['cantidad'],
                        'simbolo' => Kardex::SIMBOLO_INGRESO,
                        'detalle' => Kardex::ENTRADA_ALMACEN,
                        'reference' => $this->compra->referencia,
                        'producto_id' => $this->producto_id,
                        'almacen_id' => $almacen['id'],
                        'sucursal_id' => $this->compra->sucursal_id,
                        'user_id' => auth()->user()->id,
                    ]);
                }

                if ($producto->isRequiredserie() && count($almacen['series']) > 0) {
                    foreach ($almacen['series'] as $serie) {
                        $itemserie = $compraitem->series()->create([
                            'serie' => trim($serie['serie']),
                            'almacen_id' => $almacen['id'],
                            'producto_id' => $this->producto_id,
                            'user_id' => auth()->user()->id,
                        ]);
                    }
                }
                $producto->incrementarStockProducto($almacen['id'], $almacen['cantidad']);
            }

            $pricebuysoles = $this->priceunitario + $this->igvunitario;
            if ($this->compra->moneda->isDolar()) {
                $pricebuysoles = $pricebuysoles * $this->tipocambio ?? 1;
            }
            $producto->pricebuy =  $pricebuysoles;
            if (!$this->compra->sucursal->empresa->usarLista()) {
                $producto->pricesale = $this->priceventa;
            }
            $producto->save();
            DB::commit();
            $producto->assignPrice();
            // $this->setTotal();
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
            $this->dispatchBrowserEvent('created');
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
                if (!empty($serie_id)) {
                    $serie = Serie::find($serie_id);
                    if (!Self::validatedeleteserie($serie)) {
                        return; // Detener ejecución si la función retorna false
                    }
                }

                unset($this->almacens[$almacenindex]['series'][$index]);
                $this->almacens[$almacenindex]['series'] = array_values($this->almacens[$almacenindex]['series']);
                $this->almacens[$almacenindex]['cantidad'] = count($this->almacens[$almacenindex]['series']);
                DB::commit();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function validatedeleteserie(Serie $serie, $confirmdelete = true)
    {
        $almacen = $serie->producto->almacens()->find($serie->almacen_id);
        if (empty($almacen)) {
            $mensaje = response()->json([
                'title' => "ALMACÉN NO DISPONIBLE DE SERIE SELECCIONADA",
                'text' => null
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }
        $stock = $almacen->pivot->cantidad;

        if ($stock < 1) {
            $mensaje = response()->json([
                'title' => "NO EXISTE SUFICIENTE CANTIDAD PARA DISMINUIR STOCK EN ALMACÉN.",
                'text' => null
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }

        if (!$serie->isDisponible()) {
            $title = $confirmdelete ? "REMOVIDA" : "";
            $message = response()->json([
                'title' => "SERIE $title [$serie->serie] SE ENCUENTRA OCUPADA, SE ENCUENTRA VINCULADO A OTROS REGISTROS.",
                'text' => null
            ])->getData();
            $this->dispatchBrowserEvent('validation', $message);
            return false;
        }
        return true;
    }

    public function deleteitemcompra(Compraitem $compraitem)
    {
        $compraitem->load(['kardexes', 'series' => function ($query) {
            $query->where('status', '<>', Serie::DISPONIBLE);
        }, 'producto' => function ($query) {
            $query->with(['unit', 'almacens']);
        }]);

        DB::beginTransaction();
        try {
            if (count($compraitem->series) > 0) {
                $message = response()->json([
                    'title' => count($compraitem->series) . " SERIES DEL PRODUCTO ADQUIRIDO SE ENCUENTRAN OCUPADOS, VINCULADOS A OTROS REGISTROS",
                    'text' => null,
                ])->getData();
                $this->dispatchBrowserEvent('validation', $message);
                return false;
            }

            foreach ($compraitem->kardexes as $kardex) {
                $stock = $compraitem->producto->almacens->find($kardex->almacen_id)->pivot->cantidad;
                if ($stock < $kardex->cantidad) {
                    $mensaje = response()->json([
                        'title' => "STOCK INSUFICIENTE EN ALMACEN [" . $kardex->almacen->name . "] PARA DESCONTAR " . $kardex->cantidad . " " . $compraitem->producto->unit->name,
                        'text' => null
                    ])->getData();
                    $this->dispatchBrowserEvent('validation', $mensaje);
                    return false;
                }
                $compraitem->producto->descontarStockProducto($kardex->almacen_id, $kardex->cantidad);
                DB::table('kardexes')->where('id', $kardex->id)->delete();
            }

            DB::table('series')->where('compraitem_id', $compraitem->id)->delete();
            $compraitem->producto->pricebuy = $compraitem->oldprice;
            $compraitem->producto->pricesale = $compraitem->oldpricesale;
            $compraitem->producto->save();
            $compraitem->producto->assignPrice();
            DB::table('compraitems')->where('id', $compraitem->id)->delete();
            DB::commit();
            $this->compra->refresh();
            $this->setTotal();
            $this->resetValidation();
            $this->dispatchBrowserEvent('deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    // public function deleteseriealmacencompra(Serie $serie)
    // {
    //     if (!Self::validatedeleteserie($serie)) {
    //         return;
    //     }
    //     $serie->forceDelete();
    //     $this->compraitem->producto->descontarStockProducto($serie->almacen_id, 1);
    //     $kardex = $this->compraitem->kardexes()->where('almacen_id', $serie->almacen_id)->first();

    //     if ($kardex) {
    //         $kardex->cantidad = $kardex->cantidad - 1;
    //         $kardex->newstock = $kardex->newstock - 1;
    //         $kardex->save();
    //     }
    // }

    public function updatedPricetypeId($value)
    {
        if ($value) {
            $this->pricetype = Pricetype::find($value);
            $this->pricetype_id = $this->pricetype->id;
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

    public function setAlmacens($compraitem)
    {
        $almacens = [];
        foreach ($compraitem->producto->almacens as $almacen) {
            $almacenarray['id'] = $almacen->id;
            $almacenarray['newserie'] = '';
            $almacenarray['cantidad'] = 0;
            $almacenarray['series'] = [];
            $almacenarray['addseries'] = $compraitem->producto->isRequiredserie();

            foreach ($compraitem->kardexes->where('almacen_id', $almacen->id) as $kardex) {
                $almacenarray['cantidad'] = decimalOrInteger($kardex->cantidad);
            }

            $seriesalmacen = $compraitem->series->where('almacen_id', $almacen->id);
            if (count($seriesalmacen) > 0) {
                foreach ($seriesalmacen as $serie) {
                    $arrayserie = [];
                    $arrayserie['id'] = $serie->id;
                    $arrayserie['serie'] = $serie->serie;
                    $arrayserie['dateout'] = $serie->dateout;
                    $arrayserie['status'] = $serie->status;
                    $almacenarray['series'][] = $arrayserie;
                }
            }
            $almacens[$almacen->id] = $almacenarray;
        }
        $this->almacens = $almacens;
    }
}
