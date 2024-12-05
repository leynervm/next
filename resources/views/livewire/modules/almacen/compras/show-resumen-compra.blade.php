<div x-data="addproductocompra">
    <div wire:loading.flex class="loading-overlay hidden fixed">
        <x-loading-next />
    </div>

    <x-form-card titulo="RESUMEN COMPRA" subtitulo="Resumen de productos adquiridos en la compra.">
        <div class="w-full relative flex flex-col gap-2">
            <div class="w-full">
                @if ($compra->sucursal->empresa->usarLista())
                    @if (count($pricetypes) > 1)
                        <div class="w-full md:w-64 lg:w-80">
                            <x-label value="Lista precios :" />
                            <div id="parentpricetype_id" class="relative" x-data="{ pricetype_id: @entangle('pricetype_id') }" x-init="Pricetype">
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
                @endif
            </div>
            @if (count($compra->compraitems) > 0)
                <div class="w-full flex gap-2 flex-wrap justify-around xl:justify-start">
                    @foreach ($compra->compraitems as $item)
                        @php
                            $image = !empty($item->producto->image)
                                ? pathURLProductImage($item->producto->image)
                                : null;
                            $promocion = $item->producto->getPromocionDisponible();
                            $descuento = $item->producto->getPorcentajeDescuento($promocion);
                            $pricesale = $item->producto->obtenerPrecioVenta($pricetype);
                        @endphp

                        <x-card-producto :image="$image" :name="$item->producto->name" :promocion="$promocion" x-data="{ showForm: false }">

                            <div class="text-sm font-semibold mt-1 text-colorlabel">
                                {{ decimalOrInteger($item->cantidad) }}
                                <small class="text-[10px] font-medium">{{ $item->producto->unit->name }} \
                                    {{ $item->almacen->name }}</small>
                            </div>


                            <div class="text-sm font-semibold mt-1 text-colorlabel leading-3">
                                <small class="text-[10px] font-medium">SUBTOTAL : </small>
                                {{ number_format($item->total + $item->igv, 2, '.', ', ') }}
                                <small class="text-[10px] font-medium">{{ $item->compra->moneda->currency }}</small>
                            </div>

                            <div class="w-full flex flex-wrap gap-1 items-start text-[10px]">
                                <x-span-text :text="'P.C UNIT: ' .
                                    $item->compra->moneda->simbolo .
                                    number_format($item->pricebuy, 2, '.', ', ')" class="leading-3" />

                                @if ($item->compra->moneda->code == 'USD')
                                    <x-span-text :text="'P.C UNIT: S/. ' .
                                        number_format($item->pricebuy * $item->compra->tipocambio, 2, '.', ', ') .
                                        ' SOLES'" class="leading-3" />
                                @endif

                                @if ($item->descuento > 0)
                                    <x-span-text :text="'DSCT ' .
                                        $item->compra->moneda->simbolo .
                                        number_format($item->descuento * $item->cantidad, 2, '.', ', ')" class="leading-3" type="green" />
                                @endif

                                @if (count($item->series) == 1)
                                    <span
                                        class="inline-flex items-center gap-1 text-[10px] bg-fondospancardproduct text-textspancardproduct p-1 py-0.5 rounded-lg leading-3 !tracking-widest">
                                        SERIE: {{ $item->series->first()->serie }}
                                        @can('admin.almacen.compras.create')
                                            <x-button-delete onclick="confirmDeleteSerie({{ $item->series->first() }})"
                                                wire:loading.attr="disabled" />
                                        @endcan
                                    </span>
                                @endif
                            </div>

                            @if ($promocion)
                                @if ($promocion->isDescuento() || $promocion->isRemate())
                                    @if ($descuento > 0)
                                        <span class="block w-full line-through text-red-600 text-center">
                                            S/.
                                            {{ decimalOrInteger(getPriceAntes($pricesale, $descuento), $pricetype->decimals ?? 2, ', ') }}
                                        </span>
                                    @endif
                                @endif
                            @endif

                            <h1 class="text-xl text-center font-semibold text-colortitleform">
                                <small class="text-[10px] font-medium">VENTA S/.</small>
                                {{ decimalOrInteger($pricesale, $pricetype->decimals ?? 2, ', ') }}
                                <small class="text-[10px] font-medium">SOLES</small>
                            </h1>


                            @can('admin.almacen.compras.create')
                                <div class="w-full">
                                    <x-label value="Series entrantes :" class="mt-3" />
                                    <div class="w-full inline-flex gap-1">
                                        <x-input class="block w-full flex-1 prevent"
                                            wire:model.defer="serie.{{ $item->id }}.serie"
                                            wire:keydown.enter="saveserie({{ $item }})"
                                            placeholder="Ingresar serie..." />
                                        <x-button-add class="px-2 flex-shrink-0"
                                            wire:click="saveserie({{ $item }})" wire:loading.attr="disabled">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M5 12l5 5l10 -10" />
                                            </svg>
                                        </x-button-add>
                                    </div>
                                    <x-jet-input-error for="serie.{{ $item->id }}.serie" />
                                    <x-jet-input-error for="serie.{{ $item->id }}.compraitem_id" />
                                </div>
                            @endcan

                            @if (count($item->series) > 1)
                                <div class="">
                                    <x-button @click="showForm = !showForm" class="whitespace-nowrap"
                                        wire:loading.attr="disabled">
                                        <span x-text="showForm ? 'OCULTAR SERIES' : 'VER SERIES'"></span>
                                    </x-button>
                                </div>
                            @endif

                            <div x-show="showForm" @click.away="showForm = false" x-transition
                                class="block w-full rounded mt-1">
                                <div class="w-full flex flex-wrap gap-1">
                                    @if (count($item->series) > 1)
                                        @foreach ($item->series as $itemserie)
                                            <span
                                                class="inline-flex items-center gap-1 text-[10px] bg-fondospancardproduct text-textspancardproduct p-1 rounded-lg">
                                                {{ $itemserie->serie }}

                                                @can('admin.almacen.compras.create')
                                                    <x-button-delete onclick="confirmDeleteSerie({{ $itemserie }})"
                                                        wire:loading.attr="disabled" />
                                                @endcan
                                            </span>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            @can('admin.almacen.compras.create')
                                <x-slot name="footer">
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

            <h3 class="font-semibold text-colortitleform text-3xl leading-normal text-end">
                <small class="font-medium text-[10px] w-full block">TOTAL ITEMS</small>
                <small class="text-[10px] font-medium">{{ $compra->moneda->simbolo }}</small>
                {{ number_format($compra->compraitems()->sum('total'), 2, '.', ', ') }}
            </h3>

            @can('admin.almacen.compras.create')
                {{-- @if ($compra->isOpen()) --}}
                @if ($compra->compraitems()->sum('total') < $compra->total)
                    <div class="w-full flex pt-4 justify-end">
                        <x-button wire:click="$toggle('open')">AGREGAR PRODUCTO</x-button>
                    </div>
                @endif
                {{-- @endif --}}
            @endcan
        </div>
    </x-form-card>


    <x-jet-dialog-modal wire:model="open" maxWidth="3xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Agregar producto compra') }}
        </x-slot>

        <x-slot name="content">
            <form @submit.prevent="verifyproducto" class="w-full relative flex flex-col gap-2">
                <div class="w-full grid grid-cols-1 sm:grid-cols-2 gap-2">
                    <div class="w-full sm:col-span-2">
                        <x-label value="Producto :" />
                        <div id="parenteditpc_id" class="relative" x-init="ProductoCompra">
                            <x-select class="block w-full" x-ref="selectpc" id="editpc_id"
                                data-minimum-results-for-search="3" data-dropdown-parent="null">
                                <x-slot name="options">
                                    @if (count($productos))
                                        @foreach ($productos as $item)
                                            <option value="{{ $item->id }}">
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


                    <div class="w-full sm:col-span-2 relative">
                        <div class="w-full flex flex-wrap gap-2">
                            <template x-for="(item, index) in almacens" :key="index">
                                <x-simple-card class="w-36 rounded-lg p-2 flex flex-col gap-3 justify-between">
                                    <h1 class="text-colortitleform text-[10px] text-center font-semibold"
                                        x-text="item.name"></h1>
                                    <div class="w-full">
                                        <x-label value="Cantidad :" />
                                        <x-input type="number" class="block w-full" min="0"
                                            x-model="item.cantidad" onkeypress="return validarDecimal(event, 9)" />
                                        {{-- <x-jet-input-error :for="'almacens.' + indice + '.cantidad'" /> --}}
                                    </div>
                                    {{-- <span x-text="item.index"></span> --}}
                                </x-simple-card>
                            </template>
                        </div>

                        @if ($producto_id && count($almacens) > 0)
                            {{-- <div class="w-full flex flex-wrap gap-2">
                                @foreach ($producto->almacens as $key => $item)
                                    <x-simple-card class="w-36 rounded-lg p-2 flex flex-col gap-3 justify-between">
                                        <h1 class="text-colortitleform text-[10px] text-center font-semibold">
                                            {{ $item['name'] }}</h1>

                                        <div class="w-full">
                                            <x-label value="Cantidad :" />
                                            <x-input type="number" class="block w-full" min="0"
                                                x-model.throttle.500ms="almacens[{{ $key }}].cantidad"
                                                onkeypress="return validarDecimal(event, 9)" />
                                            <x-jet-input-error for="almacens.{{ $key }}.cantidad" />
                                        </div>
                                    </x-simple-card>
                                @endforeach
                            </div> --}}
                        @else
                            <x-simple-card class="w-36 rounded-lg p-2 flex flex-col gap-3 justify-between">
                                <h1 class="text-colorerror text-[10px] py-5 text-center font-semibold">
                                    SELECCIONAR PRODUCTO PARA MOSTRAR ALMACENES</h1>
                            </x-simple-card>
                        @endif
                        <x-jet-input-error for="sumatoria_stock" />
                        <x-jet-input-error for="almacens" />
                    </div>

                    <div class="w-full">
                        <x-label value="Precio unitario ({{ $compra->moneda->currency }}) sin IGV :" />
                        <x-input class="block w-full" x-mask:dynamic="$money($input, '.', '', 2)"
                            x-model.number="pricebuy" @input="calcular" @change="numeric" placeholder="0.00"
                            onkeypress="return validarDecimal(event, 12)" />
                        <x-jet-input-error for="pricebuy" />
                        <x-label textSize="[10px]"
                            class="inline-flex items-end tracking-normal font-semibold gap-1 cursor-pointer text-textspancardproduct">
                            <x-input x-model="showigv" name="igvproducto" type="checkbox" id="showigv" />
                            AGREGAR IGV
                        </x-label>
                    </div>

                    <div class="w-full">
                        <x-label value="Descuento x unidad ({{ $compra->moneda->currency }}):" />
                        <x-input class="block w-full" x-model="descuento" x-mask:dynamic="$money($input, '.', '', 2)"
                            @input="calcular" @change="numeric" placeholder="0.00"
                            onkeypress="return validarDecimal(event, 12)" />
                        <x-jet-input-error for="descuento" />
                    </div>


                    <div class="w-full text-xs text-colorsubtitleform">
                        {{-- x-show="igv>0" --}}
                        <template x-if="igv > 0">
                            <div class="w-full">
                                <small class="w-full font-normal leading-3">IGV UNIT.</small>
                                <span class="text-xl inline-block font-semibold"
                                    x-text="moneda_compra.simbolo + ' '+ toDecimal(igv,2)"></span>
                            </div>
                        </template>

                        <template x-if="descuento > 0">
                            <div>
                                <div class="w-full">
                                    <small class="w-full font-normal leading-3">P. UNIT.</small>
                                    <span class="text-xl inline-block font-semibold"
                                        x-text="moneda_compra.simbolo + ' '+ toDecimal(parseFloat(pricebuy) + parseFloat(igv) + parseFloat(descuento),2)"></span>
                                </div>
                                <div class="w-full">
                                    <small class="w-full font-normal leading-3">DESC. UNIT.</small>
                                    <span class="text-xl inline-block font-semibold"
                                        x-text="moneda_compra.simbolo + ' '+ toDecimal(descuento,2)"></span>
                                </div>
                            </div>
                        </template>

                        <template x-if="pricebuy > 0">
                            <div class="w-full">
                                <small class="w-full font-normal leading-3">PRECIO UNIT.</small>
                                <p class="text-xl inline-block font-semibold"
                                    x-text="moneda_compra.simbolo + ' '+ toDecimal(parseFloat(pricebuyigv) - parseFloat(descuento),2)">
                                </p>
                            </div>
                        </template>

                        @if ($compra->moneda->code == 'USD')
                            <div class="w-full">
                                <small class="w-full font-normal leading-3">TIPO CAMBIO</small>
                                <span class="text-xl inline-block font-semibold">S/.
                                    {{ number_format($compra->tipocambio, 2) }}</span>
                            </div>

                            <template x-if="pricebuysoles>0">
                                <div class="w-full text-colorlabel" style="display: none;" x-clock
                                    x-show="pricebuysoles>0">
                                    <small class="w-full font-normal leading-3">PRECIO UNIT. </small>
                                    <p class="text-xl inline-block font-semibold"
                                        x-text="'S/. ' + toDecimal(pricebuysoles,2)">
                                    </p>
                                    <small class="w-full font-normal leading-3">SOLES INC. IGV</small>
                                </div>
                            </template>
                        @endif
                        <x-jet-input-error for="importe" />
                    </div>


                    @if (!$compra->sucursal->empresa->usarLista())
                        <div class="w-full">
                            <x-label value="Precio venta unitario (SOLES):" />
                            <x-input class="block w-full" wire:model.defer="pricesale" placeholder="0.00"
                                type="number" min="0" step="0.001"
                                onkeypress="return validarDecimal(event, 12)" />
                            <x-jet-input-error for="pricesale" />
                        </div>
                    @endif
                </div>

                <div class="w-full text-colorsubtitleform text-xs font-semibold">
                    <template x-if="pricebuyigv > 0">
                        <div class="w-full text-end">
                            <small class="w-full font-normal leading-3">IGV</small>
                            <span class="text-lg inline-block"
                                x-text="moneda_compra.simbolo + ' '+ toDecimal(parseFloat(igv) * parseFloat(cantidad),2)"></span>
                        </div>
                    </template>

                    <template x-if="pricebuyigv > 0">
                        <div class="w-full text-end">
                            <small class="w-full font-normal leading-3">SUBTOTAL</small>
                            <span class="text-lg inline-block"
                                x-text="moneda_compra.simbolo + ' '+ toDecimal((parseFloat(descuento) * parseFloat(cantidad)) + (parseFloat(pricebuy) * parseFloat(cantidad)),2)"></span>
                        </div>
                    </template>

                    <template x-if="descuento > 0">
                        <div class="w-full text-end text-colorlabel">
                            <small class="w-full font-normal leading-3">DESCUENTOS</small>
                            <span class="text-lg inline-block"
                                x-text="moneda_compra.simbolo + ' '+ toDecimal(parseFloat(descuento) * parseFloat(cantidad),2)"></span>
                        </div>
                    </template>

                    <template x-if="pricebuyigv > 0">
                        <div class="w-full text-end text-colorlabel">
                            <small class="w-full font-normal leading-3">TOTAL</small>
                            <span class="text-3xl inline-block"
                                x-text="moneda_compra.simbolo + ' '+ toDecimal((parseFloat(pricebuyigv) - parseFloat(descuento)) * parseFloat(cantidad),2)"></span>
                        </div>
                    </template>

                    {{-- <h3 class="font-semibold text-xs text-end leading-3">
                        <small class="font-medium">TOTAL DESCUENTO {{ $compra->moneda->simbolo }}</small>

                    </h3> --}}

                    {{-- <h3 class="font-semibold text-3xl leading-normal text-end">
                        <small class="text-[10px] font-medium">{{ $compra->moneda->simbolo }}</small>
                        {{ number_format($importe, 3) }}
                    </h3> --}}
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
            Alpine.data('addproductocompra', () => ({
                searchingalmacens: false,
                producto_id: @entangle('producto_id').defer,
                pricebuysoles: @entangle('pricebuysoles').defer,
                tipocambio: @this.get('tipocambio'),
                pricebuy: @entangle('pricebuy').defer,
                descuento: @entangle('descuento').defer,
                igv: @entangle('igv').defer,
                pricebuyigv: null,
                percent: @entangle('percent').defer,
                showigv: @entangle('showigv').defer,
                moneda_compra: @json($compra->moneda),
                cantidad: @entangle('sumatoria_stock').defer,
                total: @entangle('total').defer,
                almacens: @entangle('almacens').defer,
                // totaldescuentos : @entangle('importe').defer,

                init() {
                    this.$watch("cantidad", (value) => {
                        if (value > 0) {
                            this.total = toDecimal(parseFloat(this.pricebuyigv) * parseFloat(
                                this.cantidad));
                        } else {
                            this.total = toDecimal(0);
                        }
                    });
                    this.$watch("showigv", (value) => {
                        this.calcular()
                    });


                    this.$watch("almacens", (value) => {
                        // this.almacens = value;
                        const almacens = Object.values(value);
                        if (almacens.length > 0) {
                            const cantidad = almacens.reduce((sum, item) =>
                                parseFloat(sum) + Number(item.cantidad), 0);
                            this.cantidad = cantidad;
                        }
                    });
                },
                calcular() {
                    let descuento = this.descuento > 0 ? this.descuento : 0;
                    if (this.showigv) {
                        this.igv = toDecimal((parseFloat(this.pricebuy) + parseFloat(descuento)) * (
                            parseFloat(this.percent) / 100), 2);
                    } else {
                        this.igv = toDecimal(0, 2);
                    }
                    this.pricebuyigv = toDecimal(parseFloat(this.pricebuy) + parseFloat(descuento) +
                        parseFloat(this.igv))

                    this.pricebuysoles = toDecimal(parseFloat(this.pricebuyigv) * parseFloat(this
                        .tipocambio));
                    this.total = toDecimal(parseFloat(this.pricebuyigv) * parseFloat(this.cantidad));
                },
                numeric() {
                    this.pricebuy = toDecimal(this.pricebuy > 0 ? this.pricebuy : 0);
                    this.tipocambio = toDecimal(this.tipocambio > 0 ? this.tipocambio : 0);
                    this.descuento = toDecimal(this.descuento > 0 ? this.descuento : 0);
                },
                verifyproducto() {
                    this.$wire.call('verifyproducto').then((result) => {});
                }
            }))
        })

        function confirmDeleteItemCompra(itemcompra_id) {
            swal.fire({
                title: 'Desea eliminar item de compra seleccionado ?',
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

        function confirmDeleteSerie(serie) {
            swal.fire({
                title: 'Desea eliminar la serie ' + serie.serie + ' ?',
                text: "Se eliminará un registro de pago de la base de datos.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.deleteserie(serie.id);
                }
            })
        }

        function ProductoCompra() {
            this.selectPCI = $(this.$refs.selectpc).select2();
            this.selectPCI.val(this.producto_id).trigger("change");
            this.selectPCI.on("select2:select", (event) => {
                this.producto_id = event.target.value;
                this.$wire.call('loadproducto', this.producto_id).then(() => {});
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("producto_id", (value) => {
                this.selectPCI.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectPCI.select2().val(this.producto_id).trigger('change');
            });
        }

        function Pricetype() {
            this.selectP = $(this.$refs.selectprice).select2();
            this.selectP.val(this.pricetype_id).trigger("change");
            this.selectP.on("select2:select", (event) => {
                this.pricetype_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("pricetype_id", (value) => {
                this.selectP.val(value).trigger("change");
            });

            Livewire.hook('message.processed', () => {
                this.selectP.select2().val(this.pricetype_id).trigger('change');
            });
        }


        document.addEventListener("livewire:load", () => {
            document.addEventListener('confirm-agregate-compra', data => {
                swal.fire({
                    title: 'Producto ya se encuentra registrado en la compra, desea adicionar nuevas unidades ?',
                    text: "Se incremetará el stock adquirido y los datos del producto se actualizarán ",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.updatecompraitem(data.detail);
                    }
                })
            });
        })
    </script>
</div>
