<div class="flex flex-col gap-8" x-data="{
    proveedor_id: @entangle('compra.proveedor_id').defer,
    sucursal_id: @entangle('compra.sucursal_id').defer,
    typepayment_id: @entangle('compra.typepayment_id').defer,
    compratipocambio: @entangle('compra.tipocambio').defer,
    pricetype_id: @entangle('pricetype_id'),
    typedescuento: @entangle('typedescuento').defer,
    afectacion: '{{ $compra->afectacion }}',
    afectacion: '{{ $compraitem->igv > 0 ? 'S' : 'E' }}',
    tipocambio: '{{ $compra->tipocambio }}',
    istransferencia: false,
    detalle: @entangle('detalle').defer,
    reset() {
        this.istransferencia = false;
        this.detalle = '';
    }
}">
    <div wire:loading.flex class="loading-overlay fixed hidden z-[99]">
        <x-loading-next />
    </div>

    <x-simple-card class="flex flex-col gap-1 rounded-md cursor-default p-3">
        <form wire:submit.prevent="update" class="w-full flex flex-col gap-2">
            <div class="w-full grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-2">
                <div class="w-full">
                    <x-label value="Fecha compra :" />
                    <x-input type="date" class="block w-full" wire:model.defer="compra.date" />
                    <x-jet-input-error for="compra.date" />
                </div>

                <div class="w-full">
                    <x-label value="Tipo afectación :" />
                    <x-disabled-text :text="$compra->isExonerado() ? 'EXONERAR IGV' : 'IGV 18%'" />
                </div>

                <div class="w-full">
                    <x-label value="Moneda :" />
                    <x-disabled-text :text="$compra->moneda->currency" />
                </div>

                @if ($compra->moneda->isDolar())
                    <div class="w-full">
                        <x-label value="Tipo Cambio :" />
                        <x-input class="block w-full" x-model="compratipocambio" 
                            x-mask:dynamic="$money($input, '.', '', 3)" onkeypress="return validarDecimal(event, 7)" />
                        <x-jet-input-error for="compra.tipocambio" />
                    </div>
                @endif

                <div class="w-full">
                    <x-label value="Boleta/ Factura compra :" />
                    <x-input class="block w-full" wire:model.defer="compra.referencia"
                        placeholder="Boleta o factura de compra..." />
                    <x-jet-input-error for="compra.referencia" />
                </div>

                <div class="w-full">
                    <x-label value="Guía de compra :" />
                    <x-input class="block w-full" wire:model.defer="guia" placeholder="Guia de compra..." />
                    <x-jet-input-error for="guia" />
                </div>

                <div class="w-full">
                    <x-label value="Tipo pago :" />
                    <div id="parenttypepaymentcompra_id" class="relative" x-init="select2Typepayment">
                        <x-select class="block w-full" x-ref="selecttp" id="typepaymentcompra_id"
                            data-placeholder="null">
                            <x-slot name="options">
                                @if (count($typepayments))
                                    @foreach ($typepayments as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->name }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="compra.typepayment_id" />
                </div>

                <div class="w-full xs:col-span-2">
                    <x-label value="Proveedor :" />
                    <div id="parentproveedorcompra_id" class="relative" x-init="selec2Proveedor">
                        <x-select class="block w-full" x-ref="selectproveedoredit" id="proveedorcompra_id"
                            data-minimum-results-for-search="3">
                            <x-slot name="options">
                                @if (count($proveedores))
                                    @foreach ($proveedores as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->document }} - {{ $item->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="compra.proveedor_id" />
                </div>

                <div class="w-full xs:col-span-2">
                    <x-label value="Sucursal :" />
                    <div id="parentsucursalcompra_id" class="relative" x-init="selec2Sucursal" wire:ignore>
                        <x-select class="block w-full" x-ref="selectsucursal" id="sucursalcompra_id"
                            data-minimum-results-for-search="3">
                            <x-slot name="options">
                                @if (count($sucursals) > 0)
                                    @foreach ($sucursals as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="compra.sucursal_id" />
                </div>

                <div class="w-full xs:col-span-2">
                    <x-label value="Observaciones / Detalles :" />
                    <x-text-area class="block w-full" rows="1" wire:model.defer="compra.detalle"></x-text-area>
                    <x-jet-input-error for="compra.detalle" />
                </div>
            </div>

            <div class="w-full text-colorsubtitleform pt-3">
                <h3 class="font-semibold text-sm text-end leading-4">
                    <small class="font-medium text-[10px]">EXONERADO </small>
                    {{ number_format($compra->exonerado, 2, '.', ', ') }}
                </h3>

                <h3 class="font-semibold text-sm text-end leading-4">
                    <small class="font-medium text-[10px]">GRAVADO</small>
                    {{ number_format($compra->gravado, 2, '.', ', ') }}
                </h3>

                <h3 class="font-semibold text-sm text-end leading-4">
                    <small class="font-medium text-[10px]">IGV</small>
                    {{ number_format($compra->igv, 2, '.', ', ') }}
                </h3>

                <h3 class="font-semibold text-sm text-end leading-4">
                    <small class="font-medium text-[10px]">SUBTOTAL {{ $compra->moneda->simbolo }}</small>
                    {{ number_format($compra->total + $compra->descuento, 2, '.', ', ') }}
                </h3>

                @if ($compra->descuento > 0)
                    <h3 class="font-semibold text-sm text-end leading-4">
                        <small class="font-medium text-[10px]">DESCUENTOS {{ $compra->moneda->simbolo }}</small>
                        {{ number_format($compra->descuento, 2, '.', ', ') }}
                    </h3>
                @endif

                <h3 class="font-semibold text-3xl leading-normal text-colortitleform text-end">
                    <small class="text-[10px] font-medium">{{ $compra->moneda->simbolo }}</small>
                    {{ number_format($compra->total, 2, '.', ', ') }}
                </h3>
            </div>

            @can('admin.almacen.compras.delete')
                <div class="w-full flex flex-wrap gap-2 items-end justify-between">
                    <x-link-button target="_blank" href="{{ route('admin.almacen.compras.print.a4', $compra) }}">IMPRIMIR
                        A4</x-link-button>

                    <div class="w-full flex-1 flex gap-2 items-end justify-end">
                        <x-button-secondary onclick="comfirmDelete()" wire:loading.attr="disabled">
                            {{ __('ELIMINAR') }}</x-button-secondary>
                        <x-button type="submit" wire:loading.attr="disabled">
                            {{ __('Save') }}</x-button>
                    </div>
                </div>
            @endcan
        </form>
    </x-simple-card>

    <x-form-card titulo="RESUMEN COMPRA" subtitulo="Resumen de productos adquiridos en la compra.">
        <div class="w-full relative flex flex-col gap-2">
            @if ($compra->sucursal->empresa->usarLista())
                <div class="w-full">
                    @if (count($pricetypes) > 1)
                        <div class="w-full md:w-64 lg:w-80">
                            <x-label value="Lista precios :" />
                            <div id="parentpricetype_id" class="relative" x-init="Pricetype">
                                <x-select class="block w-full" id="pricetype_id" x-ref="selectprice">
                                    <x-slot name="options">
                                        @foreach ($pricetypes as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->name }}</option>
                                        @endforeach
                                    </x-slot>
                                </x-select>
                                <x-icon-select />
                            </div>
                            <x-jet-input-error for="pricetype_id" />
                        </div>
                    @endif
                </div>
            @endif

            @if (count($compra->compraitems) > 0)
                <div
                    class="w-full grid grid-cols-[repeat(auto-fill,minmax(170px,1fr))] sm:grid-cols-[repeat(auto-fill,minmax(200px,1fr))] gap-1">
                    @foreach ($compra->compraitems as $item)
                        @php
                            $image =
                                count($item->producto->images) > 0
                                    ? pathURLProductImage($item->producto->images->first()->url)
                                    : null;
                            $promocion = verifyPromocion($item->producto->promocions->first());
                            $descuento = getDscto($promocion);
                            $combo = getAmountCombo($promocion, $pricetype);
                            $pricesale = $item->producto->getPrecioVenta($pricetype);
                        @endphp

                        <x-card-producto :image="$image" :name="$item->producto->name" :promocion="$promocion" class="overflow-hidden">
                            <div
                                class="w-full p-1 rounded-xl shadow-md shadow-shadowminicard border border-borderminicard my-2">
                                @foreach ($item->almacencompras as $almac)
                                    <div
                                        class="text-lg font-semibold mt-1 text-colorlabel text-center leading-4  @if (!$loop->first) pt-2 border-t border-borderminicard @endif">
                                        {{ decimalOrInteger($almac->cantidad) }}
                                        <small class="text-[10px] font-medium">
                                            {{ $item->producto->unit->name }} \
                                            {{ $almac->almacen->name }}</small>
                                    </div>
                                    @if (count($almac->series) > 0)
                                        <div class="w-full flex flex-wrap gap-1 items-start">
                                            @foreach ($almac->series as $ser)
                                                <x-span-text :text="$ser->serie" />
                                            @endforeach
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            <table class="w-full table text-[10px] text-colorsubtitleform">
                                <tr>
                                    <td class="align-middle">P. U. C. </td>
                                    <td class="text-end">
                                        <span class="text-sm font-medium">
                                            {{ decimalOrInteger($item->price + $item->igv, 2, ', ') }}</span>
                                        <small>{{ $compra->moneda->currency }}</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align-middle">SUBTOTAL</td>
                                    <td class="text-end">
                                        <span class="text-sm font-medium">
                                            {{ decimalOrInteger($item->subtotal, 2, ', ') }}</span>
                                        <small>{{ $compra->moneda->currency }}</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align-middle">DESCUENTOS</td>
                                    <td class="text-end">
                                        <span class="text-sm font-medium">
                                            {{ decimalOrInteger($item->subtotaldescuento, 2, ', ') }}</span>
                                        <small>{{ $compra->moneda->currency }}</small>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="align-middle">TOTAL </td>
                                    <td class="text-end">
                                        <span class="text-sm font-medium">
                                            {{ decimalOrInteger($item->total, 2, ', ') }}</span>
                                        <small>{{ $compra->moneda->currency }}</small>
                                    </td>
                                </tr>

                                @if ($compra->moneda->isDolar())
                                    <tr class="text-colorlabel">
                                        <td class="align-middle">P. U. C.</td>
                                        <td class="text-end text-sm font-semibold">
                                            {{ convertMoneda($item->price + $item->igv, 'PEN', $compra->tipocambio, 2, ', ') }}
                                            <small class="text-[10px]">SOLES</small>
                                        </td>
                                    </tr>
                                @endif
                                <tr class="text-colorlabel">
                                    <td class="align-middle"> P. U. V. </td>
                                    <td class="text-end text-sm font-semibold">
                                        {{ decimalOrInteger($pricesale, $pricetype->decimals ?? 2, ', ') }}
                                        <small class="text-[10px]">SOLES</small>
                                    </td>
                                </tr>
                            </table>

                            {{-- @if ($promocion)
                                @if ($promocion->isDescuento() || $promocion->isLiquidacion())
                                    @if ($descuento > 0)
                                        <span class="block w-full line-through text-red-600 text-center">
                                            S/.
                                            {{ decimalOrInteger(getPriceAntes($pricesale, $descuento), $pricetype->decimals ?? 2, ', ') }}
                                        </span>
                                    @endif
                                @endif
                            @endif --}}

                            @can('admin.almacen.compras.create')
                                <x-slot name="footer">
                                    <x-button-edit wire:click="edit({{ $item->id }})" wire:loading.attr="disabled" />
                                    <x-button-delete onclick="confirmDeleteItemCompra({{ $item->id }})"
                                        wire:loading.attr="disabled" />
                                </x-slot>
                            @endcan
                        </x-card-producto>
                    @endforeach
                </div>
            @else
                <x-span-text text="NO EXISTEN REGISTROS DE PRODUCTOS..." class="mt-3 bg-transparent" />
            @endif

            <div class="w-full flex justify-end">
                <x-button wire:click="$toggle('openadd')">AGREGAR PRODUCTO</x-button>
            </div>
        </div>
    </x-form-card>

    @if ($compra->typepayment->isContado())
        <x-form-card titulo="PAGOS" subtitulo="Control de pagos de su compra.">
            @if (count($compra->cajamovimientos) > 0)
                <div
                    class="w-full grid grid-cols-[repeat(auto-fill,minmax(12rem,1fr))] sm:grid-cols-[repeat(auto-fill,minmax(15rem,1fr))] gap-2">
                    @foreach ($compra->cajamovimientos as $item)
                        <x-card-payment-box :cajamovimiento="$item" :moneda="$compra->moneda">
                            <x-slot name="footer">
                                <x-button-print class="mr-auto" href="{{ route('admin.payments.print', $item) }}" />

                                @can('admin.almacen.compras.pagos')
                                    <x-button-delete onclick="confirmDeletepaycompra({{ $item->id }})"
                                        wire:loading.attr="disabled" />
                                @endcan
                            </x-slot>
                        </x-card-payment-box>
                    @endforeach
                </div>
            @else
                <x-span-text text="NO EXISTEN REGISTROS DE PAGOS..." class="mt-3 bg-transparent" />
            @endif

            @can('admin.almacen.compras.pagos')
                @if ($compra->sucursal_id == auth()->user()->sucursal_id)
                    @if ($compra->cajamovimientos()->sum('amount') < $compra->total)
                        <div class="w-full flex gap-2 pt-4 justify-end">
                            <x-button type="button" wire:loading.attr="disabled" wire:click="openmodal" @click="reset">
                                {{ __('REALIZAR PAGO') }}</x-button>
                        </div>
                    @endif
                @endif
            @endcan
        </x-form-card>
    @endif


    <x-jet-dialog-modal wire:model="openproducto" maxWidth="3xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Editar producto de compra') }}
        </x-slot>

        <x-slot name="content">
            @if (!empty($compraitem->producto))
                <form wire:submit.prevent="updateitem" class="w-full text-colorlabel flex flex-col gap-1"
                    x-data="{
                        requireserie: @entangle('requireserie').defer,
                        priceunitario: @entangle('priceunitario').defer,
                        igvunitario: @entangle('igvunitario').defer,
                        descuentounitario: @entangle('descuentounitario').defer,
                        pricebuy: `{{ $compraitem->price + $compraitem->igv }}`,
                        priceventa: @entangle('priceventa').defer,
                        pricebuysoles: 0,
                        subtotalitem: 0,
                        subtotaldsctoitem: 0,
                        totalitem: 0,
                        almacens: @entangle('almacens'),
                        sumstock: `{{ $compraitem->almacencompras()->sum('cantidad') }}`,
                        init() {
                            this.$watch('almacens', (value) => {
                                const almacens = Object.values(value);
                                if (almacens.length > 0) {
                                    const cantidad = almacens.reduce((sum, item) =>
                                        parseFloat(sum) + Number(item.cantidad), 0);
                                    this.sumstock = cantidad;
                                } else {
                                    this.sumstock = 0;
                                }
                                this.calcular()
                            });
                            this.$watch('typedescuento', (value) => {
                                if (value == '0') {
                                    this.descuentounitario = 0;
                                    this.subtotaldsctoitem = 0;
                                }
                                this.calcular();
                            })
                            this.calcular();
                        },
                        calcularsoles() {
                            let codemoneda = `{{ $compra->moneda->code }}`;
                            if (codemoneda == 'USD') {
                                this.pricebuysoles = toDecimal(parseFloat(this.pricebuy) * parseFloat(this
                                    .tipocambio), 2);
                            } else {
                                this.pricebuysoles = 0;
                            }
                        },
                        calcular() {
                            if (this.afectacion == 'S') {
                                this.igvunitario = toDecimal(parseFloat(this.priceunitario * 18) / 100, 2)
                            } else {
                                this.igvunitario = 0;
                            }
                    
                            this.pricebuy = toDecimal(parseFloat(this.priceunitario) + parseFloat(this
                                .igvunitario), 2);
                    
                            if (this.typedescuento > 0 && this.sumstock > 0) {
                                if (this.typedescuento == '1') {
                                    this.subtotaldsctoitem = toDecimal(parseFloat(this.descuentounitario) *
                                        parseFloat(this.sumstock), 2);
                                } else if (this.typedescuento == '2') {
                                    this.pricebuy = toDecimal((parseFloat(this.priceunitario) - parseFloat(this
                                        .descuentounitario)) + parseFloat(this.igvunitario), 2);
                                    this.subtotaldsctoitem = toDecimal(parseFloat(this.descuentounitario) *
                                        parseFloat(this.sumstock), 2);
                                } else if (this.typedescuento == '3') {
                                    this.subtotaldsctoitem = this.descuentounitario;
                                }
                            }
                    
                    
                            this.totalitem = toDecimal(parseFloat(this.pricebuy) * parseFloat(this.sumstock),
                                2);
                            this.subtotaligvitem = toDecimal(parseFloat(this.igvunitario) * parseFloat(this
                                .sumstock), 2);
                            this.subtotalitem = toDecimal(parseFloat(this.subtotaldsctoitem) + parseFloat(this
                                .totalitem), 2);
                            this.calcularsoles();
                        },
                    }">
                    <h1 class="text-xs sm:text-sm text-colorlabel md:text-sm !leading-normal font-medium pb-3">
                        {{ $compraitem->producto->name }}</h1>

                    <div class="w-full grid grid-cols-2 md:grid-cols-3 gap-2">
                        <div class="w-full">
                            <x-label value="Precio unitario sin IGV :" />
                            <x-input class="block w-full" x-mask:dynamic="$money($input, '.', '', 2)"
                                x-model="priceunitario" @input="calcular" placeholder="0.00"
                                onkeypress="return validarDecimal(event, 12)" />
                            <x-jet-input-error for="priceunitario" />
                        </div>
                        <div class="w-full" x-cloak x-show="afectacion == 'S'" style="display: none;">
                            <x-label value="IGV :" />
                            <x-input class="block w-full" x-mask:dynamic="$money($input, '.', '', 2)"
                                x-model="igvunitario" @input="calcular" placeholder="0.00"
                                onkeypress="return validarDecimal(event, 12)" />
                            <x-jet-input-error for="igvunitario" />
                        </div>

                        <div class="w-full">
                            <x-label value="Aplicar descuento :" />
                            <div id="parenttypedscto" class="relative" x-init="select2Typedescto">
                                <x-select class="block w-full" x-ref="selectypedscto" id="typedscto">
                                    <x-slot name="options">
                                        <option value="0">NO APLICAR DSCTO</option>
                                        <option value="1">PRECIO UNITARIO CON DESCUENTO APLICADO</option>
                                        <option value="2">PRECIO UNITARIO SIN APLICAR DESCUENTO</option>
                                        <option value="3">APLICAR DSCTO AL TOTAL DEL ITEM</option>
                                    </x-slot>
                                </x-select>
                                <x-icon-select />
                            </div>
                            <x-jet-input-error for="typedescuento" />
                        </div>

                        <div class="w-full" style="display: none" x-cloak x-show="typedescuento > 0" x-transition>
                            <x-label value="Descuento:" />
                            <x-input class="block w-full" x-model="descuentounitario"
                                x-mask:dynamic="$money($input, '.', '', 2)" @input="calcular" placeholder="0.00"
                                onkeypress="return validarDecimal(event, 12)" />
                            <x-jet-input-error for="descuentounitario" />
                        </div>

                        @if (!$compra->sucursal->empresa->usarLista())
                            <div class="w-full">
                                <x-label value="Precio venta unitario (SOLES):" />
                                <x-input class="block w-full" x-model="priceventa" placeholder="0.00"
                                    x-mask:dynamic="$money($input, '.', '', 2)"
                                    onkeypress="return validarDecimal(event, 12)" />
                                <x-jet-input-error for="priceventa" />
                            </div>
                        @endif
                    </div>

                    <x-jet-input-error for="sumstock" />

                    <div class="w-full flex gap-2 justify-end">
                        <x-button type="submit" wire:loading.attr="disabled" wire:loading.remove>
                            {{ __('Save') }}</x-button>
                    </div>

                    @if (count($almacens) > 0)
                        <div class="w-full flex flex-wrap gap-2">
                            @foreach ($almacens as $key => $item)
                                <x-simple-card wire:key="almacencompraitem{{ $compraitem->id }}_{{ $item['id'] }}"
                                    class="w-full xs:w-52 rounded-lg p-2 flex flex-col gap-3 justify-start">
                                    <div class="text-colorsubtitleform text-center">
                                        <small class="w-full block text-center text-[8px] leading-3">
                                            STOCK ACTUAL</small>
                                        <span class="inline-block text-2xl text-center font-semibold">
                                            {{ decimalOrInteger($item['stock_actual']) }}</span>
                                        <small class="inline-block text-center text-[10px] leading-3">
                                            {{ $compraitem->producto->unit->name }}</small>
                                    </div>

                                    <h1 class="text-colortitleform text-[10px] text-center font-semibold">
                                        {{ $item['name'] }}</h1>
                                    <div class="w-full">
                                        <x-label value="STOCK ENTRANTE :" class="!text-[10px]" />
                                        <x-input class="block w-full"
                                            wire:model.debounce.500ms="almacens.{{ $key }}.cantidad"
                                            x-mask:dynamic="$money($input, '.', '', 0)" placeholder="0"
                                            onkeypress="return validarDecimal(event, 9)"
                                            wire:key="cantidad_{{ $item['id'] }}" wire:loading.attr="disabled" />
                                    </div>

                                    <div x-cloak x-show="requireserie" style="display: none;" x-transition>
                                        <x-label value="SERIE :" textSize="[10px]" />
                                        <x-input class="block w-full"
                                            wire:keydown.enter.prevent="addserie('{{ $key }}')"
                                            onkeypress="return validarSerie(event)" placeholder="Ingresar serie..."
                                            wire:model.defer="almacens.{{ $key }}.newserie" />
                                        <x-jet-input-error for="almacens.{{ $key }}.newserie" />

                                        <div
                                            class="w-full flex gap-1 justify-between items-center font-medium text-colorlabel">
                                            <span class="text-[9px]">ENTER PARA AGREGAR SERIE</span>

                                            <p class="text-end text-[9px] font-semibold">
                                                <span>{{ count($item['series']) + 1 }}</span>
                                                / <span>{{ decimalOrInteger($item['cantidad']) }}</span>
                                            </p>
                                        </div>

                                        @if (count($item['series']) > 0)
                                            <ul class="w-full flex flex-wrap gap-1 items-start mt-2">
                                                @foreach ($item['series'] as $k => $ser)
                                                    <li>
                                                        <div
                                                            class="rounded-lg p-1 bg-fondospancardproduct text-textspancardproduct flex gap-1 items-center">
                                                            <small class="text-[10px] leading-3 tracking-wider">
                                                                {{ $ser['serie'] }}</small>
                                                            <x-button-delete
                                                                wire:click="removeserie({{ $key }}, {{ $k }})"
                                                                wire:loading.attr="disabled" />
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                </x-simple-card>
                            @endforeach
                        </div>
                    @endif

                    <table class="w-full table text-xs text-colorsubtitleform">
                        <tr>
                            <td class="align-middle text-end">
                                <small>PRECIO UNIT. COMPRA : </small>
                            </td>
                            <td class="text-end w-40">
                                <span x-text="pricebuy" class="text-sm font-medium"></span>
                                <small>{{ $compra->moneda->currency }}</small>
                            </td>
                        </tr>
                        {{-- <tr>
                            <td class="align-middle text-end">
                                <small>SUBTOTAL IGV : </small>
                            </td>
                            <td class="text-end">
                                <span x-text="subtotaligvitem" class="text-sm font-semibold"></span>
                                <small>{{ $compra->moneda->currency }}</small>
                            </td>
                        </tr> --}}
                        <tr>
                            <td class="align-middle text-end">
                                <small>SUBTOTAL : </small>
                            </td>
                            <td class="text-end">
                                <span x-text="subtotalitem" class="text-sm font-medium"></span>
                                <small>{{ $compra->moneda->currency }}</small>
                            </td>
                        </tr>
                        <tr>
                            <td class="align-middle text-end">
                                <small>DESCUENTOS : </small>
                            </td>
                            <td class="text-end">
                                <span x-text="subtotaldsctoitem" class="text-sm font-medium"></span>
                                <small>{{ $compra->moneda->currency }}</small>
                            </td>
                        </tr>
                        <tr>
                            <td class="align-middle text-end">
                                <small>TOTAL : </small>
                            </td>
                            <td class="text-end">
                                <span x-text="totalitem" class="text-sm font-medium"></span>
                                <small>{{ $compra->moneda->currency }}</small>
                            </td>
                        </tr>

                        <template x-if="pricebuysoles>0">
                            <tr class="text-colorlabel">
                                <td class="align-top text-end">
                                    <small>PRECIO UNIT. COMPRA : </small>
                                </td>
                                <td class="text-end">
                                    <span x-text="pricebuysoles" class="text-sm font-semibold"></span>
                                    <small>SOLES <br> (INCL. IGV)</small>
                                </td>
                            </tr>
                        </template>
                    </table>
                </form>
            @endif
        </x-slot>
    </x-jet-dialog-modal>


    <x-jet-dialog-modal wire:model="openadd" maxWidth="3xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Agregar producto a compra') }}
        </x-slot>

        <x-slot name="content">
            <form class="w-full flex flex-col gap-2 min-h-[400px]" @submit.prevent="addproducto(false)"
                x-data="addproducto">
                <div class="flex w-full flex-col gap-1">
                    <x-label value="Seleccionar producto :" />
                    <div class="w-full relative" x-init="select2Producto" id="parentproducto_id">
                        <x-select class="block w-full uppercase" x-ref="selectprod"
                            data-minimum-results-for-search="3" id="producto_id">
                            <x-slot name="options">
                                @if (count($productos) > 0)
                                    @foreach ($productos as $item)
                                        <option data-marca="{{ $item->name_marca }}"
                                            data-category="{{ $item->name_category }}"
                                            data-subcategory="{{ $item->name_subcategory }}"
                                            data-requireserie="{{ $item->isRequiredserie() }}"
                                            data-image="{{ !empty($item->image) ? pathURLProductImage($item->image) : null }}"
                                            value="{{ $item->id }}">
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="producto_id" />
                </div>

                <div class="w-full grid grid-cols-2 sm:grid-cols-3 gap-2">
                    <div class="w-full">
                        <x-label value="Precio unitario sin IGV :" />
                        <x-input class="block w-full" x-mask:dynamic="$money($input, '.', '', 2)"
                            x-model="priceunitario" @input="calcular" placeholder="0.00"
                            onkeypress="return validarDecimal(event, 12)" />
                        <x-jet-input-error for="priceunitario" />
                    </div>
                    <div class="w-full" x-cloak x-show="afectacion == 'S'" style="display: none;">
                        <x-label value="IGV :" />
                        <x-input class="block w-full" x-mask:dynamic="$money($input, '.', '', 2)"
                            x-model="igvunitario" @input="calcular" placeholder="0.00"
                            onkeypress="return validarDecimal(event, 12)" />
                        <x-jet-input-error for="igvunitario" />
                    </div>

                    <div class="w-full">
                        <x-label value="Aplicar descuento :" />
                        <div id="parenttypedescuento" class="relative" x-init="typedescto">
                            <x-select class="block w-full" x-ref="selectypedescuento" id="typedescuento">
                                <x-slot name="options">
                                    <option value="0">NO APLICAR DSCTO</option>
                                    <option value="1">PRECIO UNITARIO CON DESCUENTO APLICADO</option>
                                    <option value="2">PRECIO UNITARIO SIN APLICAR DESCUENTO</option>
                                    <option value="3">APLICAR DSCTO AL TOTAL DEL ITEM</option>
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="typedescuento" />
                    </div>

                    <div class="w-full" style="display: none" x-cloak x-show="typedescuento > 0" x-transition>
                        <x-label value="Descuento:" />
                        <x-input class="block w-full" x-model="descuentounitario"
                            x-mask:dynamic="$money($input, '.', '', 2)" @input="calcular" placeholder="0.00"
                            onkeypress="return validarDecimal(event, 12)" />
                        <x-jet-input-error for="typedescuento" />
                    </div>

                    @if (!$compra->sucursal->empresa->usarLista())
                        <div class="w-full">
                            <x-label value="Precio venta unitario (SOLES):" />
                            <x-input class="block w-full" x-model="priceventa" placeholder="0.00"
                                x-mask:dynamic="$money($input, '.', '', 2)"
                                onkeypress="return validarDecimal(event, 12)" />
                            <x-jet-input-error for="priceventa" />
                        </div>
                    @endif
                </div>

                @if (count($almacens) > 0)
                    <div class="w-full flex flex-wrap gap-2">
                        @foreach ($almacens as $key => $item)
                            <x-simple-card wire:key="{{ $key }}" wire:loading.class="opacity-25"
                                class="w-full xs:w-52 rounded-lg p-2 flex flex-col gap-3 justify-start">
                                <h1 class="text-colortitleform text-[10px] text-center font-semibold">
                                    {{ $item['name'] }}</h1>
                                <div class="text-colorsubtitleform text-center">
                                    <small class="w-full block text-center text-[8px] leading-3">STOCK
                                        ACTUAL</small>
                                    <span class="inline-block text-2xl text-center font-semibold">
                                        {{ $item['stock_actual'] }}</span>
                                    <small class="inline-block text-center text-[10px] leading-3">
                                        {{ $item['unit'] }}</small>
                                </div>

                                <div class="w-full">
                                    <x-label value="STOCK ENTRANTE :" class="!text-[10px]" />
                                    <x-input class="block w-full"
                                        wire:model.debounce.500ms="almacens.{{ $key }}.cantidad"
                                        x-mask:dynamic="$money($input, '.', '', 0)" placeholder="0"
                                        onkeypress="return validarDecimal(event, 9)"
                                        wire:key="cantidad_{{ $item['id'] }}" wire:loading.attr="disabled" />
                                    <x-jet-input-error for="almacens.{{ $key }}.cantidad" />
                                </div>

                                <div x-cloak x-show="requireserie" style="display: none;" x-transition>
                                    <x-input class="block w-full"
                                        wire:keydown.enter.prevent="addserie('{{ $key }}')"
                                        onkeypress="return validarSerie(event)" placeholder="Ingresar serie..."
                                        wire:model.defer="almacens.{{ $key }}.newserie" />
                                    <x-jet-input-error for="almacens.{{ $key }}.newserie" />

                                    <div
                                        class="w-full flex gap-1 justify-between items-center font-medium text-colorlabel">
                                        <span class="text-[9px]">ENTER PARA AGREGAR SERIE</span>

                                        <p class="text-end text-[9px] font-semibold">
                                            <span>{{ count($item['series']) + 1 }}</span>
                                            <span> / {{ $almacens[$key]['cantidad'] }}</span>
                                        </p>
                                    </div>

                                    @if (count($item['series']) > 0)
                                        <ul class="w-full flex flex-wrap gap-1 items-start mt-2">
                                            @foreach ($item['series'] as $k => $ser)
                                                <li>
                                                    <div
                                                        class="rounded-lg p-1 bg-fondospancardproduct text-textspancardproduct flex gap-1 items-center">
                                                        <small
                                                            class="text-[10px] leading-3 tracking-wider">{{ $ser['serie'] }}</small>
                                                        <x-button-delete
                                                            wire:click="removeserie({{ $key }}, {{ $k }})"
                                                            wire:loading.attr="disabled" />
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </x-simple-card>
                        @endforeach
                    </div>
                @endif

                <div class="w-full">
                    <x-jet-input-error for="sumstock" />
                    <x-jet-input-error for="almacens" />
                    <x-jet-input-error for="almacens.*.series" />
                </div>

                <div class="flex flex-col gap-2">
                    <div class="w-full flex gap-1 justify-end">
                        <x-button type="button" wire:loading.attr="disabled" wire:loading.attr="disabled"
                            @click="clearproducto">{{ __('Limpiar') }}</x-button>
                        <x-button type="submit" wire:loading.attr="disabled" wire:loading.attr="disabled">
                            {{ __('Save') }}</x-button>
                        <x-button type="button" wire:loading.attr="disabled" wire:loading.attr="disabled"
                            @click="addproducto(true)">{{ __('Save and close') }}</x-button>
                    </div>

                    <div class="w-full" x-cloak x-show="priceunitario > 0" style="display: none;">
                        <table class="w-full table text-xs text-colorsubtitleform">
                            <tr>
                                <td class="align-middle text-end">
                                    <small>PRECIO UNIT. COMPRA : </small>
                                </td>
                                <td class="text-end w-40">
                                    <span x-text="pricebuy" class="text-sm font-medium"></span>
                                    <small>{{ $compra->moneda->currency }}</small>
                                </td>
                            </tr>

                            <tr>
                                <td class="align-middle text-end">
                                    <small>SUBTOTAL : </small>
                                </td>
                                <td class="text-end">
                                    <span x-text="subtotalitem" class="text-sm font-medium"></span>
                                    <small>{{ $compra->moneda->currency }}</small>
                                </td>
                            </tr>
                            <tr>
                                <td class="align-middle text-end">
                                    <small>DESCUENTOS : </small>
                                </td>
                                <td class="text-end">
                                    <span x-text="subtotaldsctoitem" class="text-sm font-medium"></span>
                                    <small>{{ $compra->moneda->currency }}</small>
                                </td>
                            </tr>
                            <tr>
                                <td class="align-middle text-end">
                                    <small>TOTAL : </small>
                                </td>
                                <td class="text-end">
                                    <span x-text="totalitem" class="text-sm font-medium"></span>
                                    <small>{{ $compra->moneda->currency }}</small>
                                </td>
                            </tr>

                            <template x-if="pricebuysoles>0">
                                <tr class="text-colorlabel">
                                    <td class="align-top text-end">
                                        <small>PRECIO UNIT. COMPRA : </small>
                                    </td>
                                    <td class="text-end">
                                        <span x-text="pricebuysoles" class="text-sm font-semibold"></span>
                                        <small>SOLES <br> (INCL. IGV)</small>
                                    </td>
                                </tr>
                            </template>
                        </table>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>


    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Realizar pago compra') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="savepayment" class="w-full flex flex-col gap-1" x-data="paycompra">
                @if ($monthbox)
                    <x-card-box :openbox="$openbox" :monthbox="$monthbox" />
                @else
                    <p class="text-colorerror text-[10px] text-end">APERTURA DE CAJA MENSUAL NO DISPONIBLE...</p>
                @endif

                <div class="w-full">
                    <p class="text-colorlabel text-3xl font-semibold leading-3">
                        <small class="text-[10px] font-medium">{{ $compra->moneda->simbolo }}</small>
                        {{ number_format($compra->total, 2, '.', ', ') }}
                        <small class="text-[10px] font-medium">{{ $compra->moneda->currency }}</small>
                    </p>

                    @if ($pendiente < $compra->total)
                        <p class="text-colorerror text-sm font-semibold">
                            <small class="text-[10px] font-medium">SALDO </small>
                            {{ number_format($pendiente, 2, '.', ', ') }}
                            <small class="text-[10px] font-medium">{{ $compra->moneda->currency }}</small>
                        </p>
                    @endif
                </div>

                <div>
                    <x-label value="Seleccionar moneda :" />
                    @if (count($diferencias) > 0)
                        <div class="w-full flex flex-wrap gap-2 justify-start items-center">
                            @foreach ($diferencias as $item)
                                <div class="inline-flex">
                                    <input class="sr-only peer" x-model="moneda_id" type="radio" name="monedas"
                                        id="moneda_{{ $item->moneda_id }}" value="{{ $item->moneda_id }}"
                                        @change="getCodeMoneda('{{ $item->moneda }}')" />
                                    <x-label-check-moneda for="moneda_{{ $item->moneda_id }}" :simbolo="$item->moneda->simbolo"
                                        :saldo="$item->diferencia" :diferenciasbytype="$diferenciasbytype->where('moneda_id', $item->moneda_id)" />
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <x-jet-input-error for="moneda_id" />
                </div>

                <div class="w-full">
                    <x-label value="Monto pagar :" />
                    <x-input class="block w-full input-number-none" x-model="amount" @input="calcular"
                        type="number" min="0.001" step="0.001"
                        onkeypress="return validarDecimal(event, 12)" />
                    <x-jet-input-error for="paymentactual" />
                </div>

                <div class="w-full" x-show="showtipocambio">
                    <div class="w-full">
                        <x-label value="Tipo cambio :" />
                        <x-input class="block w-full input-number-none" x-model="tipocambio" @input="calcular"
                            type="number" onkeypress="return validarDecimal(event, 7)" step="0.001"
                            min="0.001" wire:key="tipo_cambio" />
                        <x-jet-input-error for="tipocambio" />
                    </div>

                    <div class="w-full text-xs text-end text-colorsubtitleform font-semibold"
                        x-show="totalamount > 0">
                        <small class="inline-block" x-text="simbolo"></small>
                        <template x-if="totalamount > 0">
                            <h1 x-text="totalamount" class="text-2xl inline-block"></h1>
                        </template>
                        <template x-if="totalamount == null">
                            <small class="inline-block text-colorerror">SELECCIONAR TIPO DE MONEDA...</small>
                        </template>
                        <small class="inline-block" x-text="currency"></small>
                    </div>
                </div>

                <div class="w-full">
                    <x-label value="Método pago :" />
                    <div class="relative" x-init="select2Methodpayment" id="parentqwerty" wire:ignore>
                        <x-select class="block w-full" x-ref="selectmp" id="qwerty" data-dropdown-parent="null">
                            <x-slot name="options">
                                @if (count($methodpayments))
                                    @foreach ($methodpayments as $item)
                                        <option value="{{ $item->id }}"
                                            data-transferencia="{{ $item->isTransferencia() }}">{{ $item->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="methodpayment_id" />
                </div>

                <div class="w-full" x-show="istransferencia" x-cloak style="display: none;" x-transition>
                    <x-label value="Observaciones / N° operación, etc :" />
                    <x-text-area class="block w-full" wire:model.defer="detalle"></x-text-area>
                    <x-jet-input-error for="detalle" />
                </div>

                <div class="w-full">
                    <x-jet-input-error for="concept_id" />
                    <x-jet-input-error for="openbox.id" />
                    <x-jet-input-error for="monthbox.id" />
                </div>

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('Save') }}</x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('addproducto', () => ({
                producto_id: @entangle('producto_id'),
                almacens: @entangle('almacens').defer,
                sumstock: @entangle('sumstock').defer,

                requireserie: @entangle('requireserie').defer,
                priceunitario: @entangle('priceunitario').defer,
                igvunitario: @entangle('igvunitario').defer,
                subtotaligvitem: @entangle('subtotaligvitem').defer,
                descuentounitario: @entangle('descuentounitario').defer,
                pricebuy: @entangle('pricebuy').defer,
                subtotalitem: @entangle('subtotalitem').defer,
                subtotaldsctoitem: @entangle('subtotaldsctoitem').defer,
                priceventa: @entangle('priceventa').defer,
                pricebuysoles: null,
                tipocambio: @entangle('tipocambio').defer,
                totalitem: @entangle('totalitem').defer,
                codemoneda: "{{ $compra->moneda->code }}",

                init() {
                    this.$watch("almacens", (value) => {
                        const almacens = Object.values(value);
                        if (almacens.length > 0) {
                            const cantidad = almacens.reduce((sum, item) =>
                                parseFloat(sum) + Number(item.cantidad), 0);
                            this.sumstock = cantidad;
                        } else {
                            this.sumstock = 0;
                        }
                        this.calcular()
                    });
                },

                numeric() {
                    this.exonerado = toDecimal(this.exonerado > 0 ? this.exonerado : 0);
                    this.gravado = toDecimal(this.gravado > 0 ? this.gravado : 0);
                    this.igv = toDecimal(this.igv > 0 ? this.igv : 0);
                    this.otros = toDecimal(this.otros > 0 ? this.otros : 0);
                    this.descuento = toDecimal(this.descuento > 0 ? this.descuento : 0);
                },
                sumar() {
                    let total = '0.00';

                    let exonerado = this.exonerado > 0 ? toDecimal(this.exonerado) : 0;
                    let gravado = this.gravado > 0 ? toDecimal(this.gravado) : 0;
                    let igv = this.igv > 0 ? toDecimal(this.igv) : 0;
                    let otros = this.otros > 0 ? toDecimal(this.otros) : 0;
                    let descuento = this.descuento > 0 ? toDecimal(this.descuento) : 0;

                    total = parseFloat(exonerado) + parseFloat(gravado) + parseFloat(igv) + parseFloat(
                        otros);
                    this.subtotal = toDecimal(parseFloat(total) + parseFloat(this.descuento));
                    this.total = toDecimal(total);
                },
                calcular() {
                    if (this.afectacion == 'S') {
                        this.igvunitario = toDecimal(parseFloat(this.priceunitario * 18) / 100, 2)
                    } else {
                        this.igvunitario = 0;
                    }

                    this.pricebuy = toDecimal(parseFloat(this.priceunitario) + parseFloat(this
                        .igvunitario), 2);

                    if (this.typedescuento > 0 && this.sumstock > 0) {
                        if (this.typedescuento == '1') {
                            this.subtotaldsctoitem = toDecimal(parseFloat(this.descuentounitario) *
                                parseFloat(this.sumstock), 2);
                        } else if (this.typedescuento == '2') {
                            this.pricebuy = toDecimal((parseFloat(this.priceunitario) - parseFloat(this
                                .descuentounitario)) + parseFloat(this.igvunitario), 2);
                            this.subtotaldsctoitem = toDecimal(parseFloat(this.descuentounitario) *
                                parseFloat(this.sumstock), 2);
                        } else if (this.typedescuento == '3') {
                            this.subtotaldsctoitem = this.descuentounitario;
                        }
                    }


                    this.totalitem = toDecimal(parseFloat(this.pricebuy) * parseFloat(this.sumstock),
                        2);
                    this.subtotaligvitem = toDecimal(parseFloat(this.igvunitario) * parseFloat(this
                        .sumstock), 2);
                    this.subtotalitem = toDecimal(parseFloat(this.subtotaldsctoitem) + parseFloat(this
                        .totalitem), 2);
                    this.calcularsoles();
                },
                calcularsoles() {
                    // console.log('CODE MONEDA :', this.codemoneda);
                    if (this.codemoneda == 'USD') {
                        this.pricebuysoles = toDecimal(parseFloat(this.pricebuy) * parseFloat(this
                            .tipocambio), 2)
                    } else {
                        this.pricebuysoles = null
                    }
                },
                addproducto(closemodal) {
                    this.$wire.call('addproducto', closemodal).then(result => console.log(result));
                },
                clearproducto() {
                    this.product = null
                    this.almacens = []
                    this.sumstock = 0
                    this.producto_id = null
                    this.requireserie = false
                    this.typedescuento = '0'
                    this.priceunitario = 0
                    this.igvunitario = 0
                    this.subtotaligvitem = 0
                    this.descuentounitario = 0
                    this.pricebuy = 0
                    this.subtotalitem = 0
                    this.subtotaldsctoitem = 0
                    this.priceventa = 0
                    this.pricebuysoles = null
                    this.totalitem = 0
                    this.$wire.$refresh()
                },
            }))
        })


        document.addEventListener('alpine:init', () => {
            Alpine.data('paycompra', () => ({
                showtipocambio: @entangle('showtipocambio').defer,
                amount: @entangle('paymentactual').defer,
                tipocambio: @entangle('tipocambio').defer,
                totalamount: @entangle('totalamount').defer,
                methodpayment_id: @entangle('methodpayment_id').defer,
                moneda_id: @entangle('moneda_id').defer,
                code_moneda_compra: @json($compra->moneda->code),
                simbolo: null,
                code: null,
                currency: null,

                init() {
                    this.$watch("showtipocambio", (value) => {
                        this.tipocambio = null;
                        this.totalamount = '0.00';
                    });
                },
                getCodeMoneda(moneda) {
                    const monedaJSON = JSON.parse(moneda);
                    this.code = monedaJSON.code;
                    if (this.code == this.code_moneda_compra) {
                        this.showtipocambio = false;
                    } else {
                        if (this.code == 'PEN') {
                            this.simbolo = '$.';
                            this.currency = 'DÓLARES';
                        } else if (this.code == 'USD') {
                            this.simbolo = 'S/.';
                            this.currency = 'SOLES';
                        }
                        this.calcular();
                        this.currency = monedaJSON.currency;
                        this.simbolo = monedaJSON.simbolo;
                        this.showtipocambio = true;
                    }
                },
                calcular() {
                    if (this.code == 'PEN') {
                        if (toDecimal(this.amount) > 0 && toDecimal(this.tipocambio) > 0) {
                            this.totalamount = toDecimal(this.amount * this.tipocambio, 2);
                        } else {
                            this.totalamount = '0.00'
                        }
                    } else if (this.code == 'USD') {
                        if (toDecimal(this.amount) > 0 && toDecimal(this.tipocambio) > 0) {
                            this.totalamount = toDecimal(this.amount / this.tipocambio, 2);
                        } else {
                            this.totalamount = '0.00'
                        }
                    } else {
                        this.totalamount = null
                    }
                }
            }))
        })


        function select2Producto() {
            this.selectP = $(this.$refs.selectprod).select2({
                templateResult: function(data) {
                    if (!data.id) {
                        return data.text;
                    }
                    const image = $(data.element).data('image') ?? '';
                    const marca = $(data.element).data('marca') ?? '';
                    const category = $(data.element).data('category') ?? '';
                    const subcategory = $(data.element).data('subcategory') ?? '';

                    let html = `<div class="custom-list-select">
                        <div class="image-custom-select">`;
                    if (image) {
                        html +=
                            `<img src="${image}" class="w-full h-full object-scale-down block" alt="${data.text}">`;
                    } else {
                        html += `<x-icon-image-unknown class="w-full h-full" />`;
                    }
                    html += `</div>
                            <div class="content-custom-select">
                                <p class="title-custom-select">
                                    ${data.text}</p>
                                <p class="marca-custom-select">
                                    ${marca}</p>  
                                <div class="category-custom-select">
                                    <span class="inline-block">${category}</span>
                                    <span class="inline-block">${subcategory}</span>
                                </div>  
                            </div>
                      </div>`;
                    return $(html);
                }
            });
            this.selectP.val(this.producto_id).trigger("change");
            this.selectP.on("select2:select", (event) => {
                this.producto_id = event.target.value;
                const selected = event.target.options[event.target.selectedIndex];
                if (selected.dataset) {
                    this.requireserie = selected.dataset.requireserie;
                } else {
                    this.requireserie = false;
                }
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("producto_id", (value) => {
                this.selectP.val(value).trigger("change");
                if (value == null) {
                    this.selectP.empty();
                }
            });
            Livewire.hook('message.processed', () => {
                this.selectP.select2('destroy');
                this.selectP.select2({
                    templateResult: function(data) {
                        if (!data.id) {
                            return data.text;
                        }
                        const image = $(data.element).data('image') ?? '';
                        const marca = $(data.element).data('marca') ?? '';
                        const category = $(data.element).data('category') ?? '';
                        const subcategory = $(data.element).data('subcategory') ?? '';

                        let html = `<div class="custom-list-select">
                        <div class="image-custom-select">`;
                        if (image) {
                            html +=
                                `<img src="${image}" class="w-full h-full object-scale-down block" alt="${data.text}">`;
                        } else {
                            html += `<x-icon-image-unknown class="w-full h-full" />`;
                        }
                        html += `</div>
                            <div class="content-custom-select">
                                <p class="title-custom-select">
                                    ${data.text}</p>
                                <p class="marca-custom-select">
                                    ${marca}</p>  
                                <div class="category-custom-select">
                                    <span class="inline-block">${category}</span>
                                    <span class="inline-block">${subcategory}</span>
                                </div>  
                            </div>
                      </div>`;
                        return $(html);
                    }
                }).val(this.producto_id).trigger('change');
            });
        }

        function select2Methodpayment() {
            this.selectF = $(this.$refs.selectmp).select2();
            this.selectF.val(this.methodpayment_id).trigger("change");
            this.selectF.on("select2:select", (event) => {
                this.methodpayment_id = event.target.value;
                var selectedOption = event.params.data.element;
                this.istransferencia = Boolean($(selectedOption).data('transferencia'));
                if (!this.istransferencia) {
                    this.detalle = '';
                }
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("methodpayment_id", (value) => {
                this.selectF.val(value).trigger("change");
            });
        }

        function comfirmDelete() {
            swal.fire({
                title: 'Desea eliminar el registro de la compra ?',
                text: "Se eliminará un registro de pago de la base de datos.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.delete();
                }
            })
        }

        function confirmDeleteItemCompra(itemcompra_id) {
            swal.fire({
                title: 'ELIMINAR ITEM DE LA COMPRA ?',
                text: "Se eliminará un registro de pago de la base de datos.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.deleteitemcompra(itemcompra_id);
                }
            })
        }

        function confirmDeletepaycompra(cajamovimiento_id) {
            swal.fire({
                title: 'ELIMINAR PAGO DE COMPRA ?',
                text: "Se eliminará un registro de pago de la base de datos.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.deletepay(cajamovimiento_id);
                }
            })
        }

        function select2Typedescto() {
            this.selectTD = $(this.$refs.selectypedscto).select2();
            this.selectTD.val(this.typedescuento).trigger("change");
            this.selectTD.on("select2:select", (event) => {
                this.typedescuento = event.target.value;
                this.calcular();
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("typedescuento", (value) => {
                this.selectTD.val(value).trigger("change");
            });

            Livewire.hook('message.processed', () => {
                this.selectTD.select2().val(this.typedescuento).trigger('change');
            });
        }

        function typedescto() {
            this.selectTDE = $(this.$refs.selectypedescuento).select2();
            this.selectTDE.val(this.typedescuento).trigger("change");
            this.selectTDE.on("select2:select", (event) => {
                this.typedescuento = event.target.value;
                this.calcular();
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("typedescuento", (value) => {
                this.selectTDE.val(value).trigger("change");
            });

            Livewire.hook('message.processed', () => {
                this.selectTDE.select2().val(this.typedescuento).trigger('change');
            });
        }

        function selec2Proveedor() {
            this.selectPRV = $(this.$refs.selectproveedoredit).select2();
            this.selectPRV.val(this.proveedor_id).trigger("change");
            this.selectPRV.on("select2:select", (event) => {
                this.proveedor_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("proveedor_id", (value) => {
                this.selectPRV.val(value).trigger("change");
            });

            Livewire.hook('message.processed', () => {
                this.selectPRV.select2().val(this.proveedor_id).trigger('change');
            });
        }

        function selec2Sucursal() {
            this.selectS = $(this.$refs.selectsucursal).select2();
            this.selectS.val(this.sucursal_id).trigger("change");
            this.selectS.on("select2:select", (event) => {
                this.sucursal_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("sucursal_id", (value) => {
                this.selectS.val(value).trigger("change");
            });
        }

        function select2Typepayment() {
            this.selectT = $(this.$refs.selecttp).select2();
            this.selectT.val(this.typepayment_id).trigger("change");
            this.selectT.on("select2:select", (event) => {
                this.typepayment_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("typepayment_id", (value) => {
                this.selectT.val(value).trigger("change");
            });

            Livewire.hook('message.processed', () => {
                this.selectT.select2().val(this.typepayment_id).trigger('change');
            });
        }

        function Pricetype() {
            this.selectPT = $(this.$refs.selectprice).select2();
            this.selectPT.val(this.pricetype_id).trigger("change");
            this.selectPT.on("select2:select", (event) => {
                this.pricetype_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("pricetype_id", (value) => {
                this.selectPT.val(value).trigger("change");
            });

            Livewire.hook('message.processed', () => {
                this.selectPT.select2().val(this.pricetype_id).trigger('change');
            });
        }
    </script>
</div>
