<div>
    <x-form-card titulo="RESUMEN COMPRA" subtitulo="Resumen de productos adquiridos en la compra.">
        <div class="w-full relative flex flex-col gap-2">
            <div class="w-full">
                @if ($compra->sucursal->empresa->usarLista())
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
                @endif
            </div>
            @if (count($compra->compraitems) > 0)
                <div class="w-full flex gap-2 flex-wrap justify-around xl:justify-start">
                    @foreach ($compra->compraitems as $item)
                        @php
                            $image = null;
                            $promocion = null;
                            if (count($item->producto->images) > 0) {
                                if ($item->producto->images()->default()->exists()) {
                                    $image = asset(
                                        'storage/productos/' . $item->producto->images()->default()->first()->url,
                                    );
                                } else {
                                    $image = asset('storage/productos/' . $item->producto->images->first()->url);
                                }
                            }

                            $firstpromocion =
                                count($item->producto->promocions) > 0 ? $item->producto->promocions->first() : null;
                            if ($firstpromocion) {
                                $promocion =
                                    $firstpromocion->isDisponible() && $firstpromocion->isAvailable()
                                        ? $firstpromocion
                                        : null;
                            }

                            $precios = getPrecio(
                                $item->producto,
                                $compra->sucursal->empresa->usarLista() ? $pricetype_id : null,
                                $compra->sucursal->empresa->tipocambio,
                            )->getData();
                            $precioProducto = precio_producto(
                                $item->producto,
                                $precios,
                                $compra->sucursal->empresa->tipocambio,
                            );
                        @endphp

                        <x-card-producto :image="$image" :name="$item->producto->name" :promocion="$promocion" x-data="{ showForm: false }">
                            <div class="w-full flex flex-wrap gap-1 justify-center mt-1">
                                <x-label-price>
                                    <span>
                                        {{ $item->compra->moneda->simbolo }}
                                        {{ number_format($item->total + $item->igv, 3, '.', ', ') }}
                                        {{ $item->compra->moneda->currency }}
                                    </span>
                                </x-label-price>
                            </div>

                            <div class="w-full flex flex-wrap gap-1 items-start mt-2 text-[10px]">
                                <x-span-text :text="'P.C UNIT: ' .
                                    $item->compra->moneda->simbolo .
                                    number_format($item->pricebuy, 3, '.', ', ')" class="leading-3 !tracking-normal" />

                                @if ($item->compra->moneda->code == 'USD')
                                    <x-span-text :text="'P.C UNIT: S/. ' .
                                        number_format($item->pricebuy * $item->compra->tipocambio, 3, '.', ', ') .
                                        ' SOLES'" class="leading-3 !tracking-normal" />
                                @endif

                                @if ($item->descuento > 0)
                                    <x-span-text :text="'DSCT ' .
                                        $item->compra->moneda->simbolo .
                                        number_format($item->descuento * $item->cantidad, 2, '.', ', ')" class="leading-3 !tracking-normal" type="green" />
                                @endif

                                <x-span-text :text="formatDecimalOrInteger($item->cantidad) . ' ' . $item->producto->unit->name" class="leading-3 !tracking-normal" />
                                <x-span-text :text="$item->almacen->name" class="leading-3 !tracking-normal" />

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

                            <x-prices-card-product :name="$compra->sucursal->empresa->usarLista() ? $pricetype->name : null">
                                <x-slot name="buttonpricemanual">
                                    @if ($promocion)
                                        @if ($promocion->isDescuento() || $promocion->isRemate())
                                            <p
                                                class="inline-block font-semibold text-[9px] leading-3 bg-red-100 p-1 rounded text-red-500">
                                                ANTES : S/.
                                                {{ number_format($precioProducto->priceAntesPEN, $precios->decimal, '.', ', ') }}
                                                {{-- {{ number_format($moneda->code == 'USD' ? $precioProducto->priceAntesUSD : $precioProducto->priceAntesPEN, $precios->decimal, '.', ', ') }} --}}
                                            </p>
                                        @endif
                                    @endif
                                </x-slot>

                                @if ($precioProducto->pricePEN > 0)
                                    <x-label-price>
                                        S/.
                                        {{ number_format($precioProducto->pricePEN, $precios->decimal, '.', '') }}
                                        <small> SOLES</small>
                                    </x-label-price>
                                @else
                                    <p>
                                        @if ($compra->sucursal->empresa->usarLista())
                                            <x-span-text text="RANGO DE PRECIO NO DISPONIBLE"
                                                class="!tracking-normal inline-block leading-3" type="red" />
                                        @else
                                            <x-span-text text="NO SE PUDO OBTENER PRECIO DE VENTA DEL PRODUCTO"
                                                class="!tracking-normal inline-block leading-3" type="red" />
                                        @endif
                                    </p>
                                @endif
                            </x-prices-card-product>

                            @can('admin.almacen.compras.create')
                                <div class="w-full">
                                    <x-label value="Series entrantes :" class="mt-3" />
                                    <div class="w-full inline-flex gap-1">
                                        <x-input class="block w-full prevent"
                                            wire:model.defer="serie.{{ $item->id }}.serie"
                                            wire:keydown.enter="saveserie({{ $item }})" />
                                        <x-button-add class="px-2" wire:click="saveserie({{ $item }})"
                                            wire:loading.attr="disabled">
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
                {{ number_format($compra->compraitems()->sum('total'), 3, '.', ', ') }}
            </h3>

            @can('admin.almacen.compras.create')
                @if ($compra->isOpen())
                    @if ($compra->compraitems()->sum('total') < $compra->total)
                        <div class="w-full flex pt-4 justify-end">
                            <x-button wire:click="$toggle('open')">AGREGAR PRODUCTO</x-button>
                        </div>
                    @endif
                @endif
            @endcan
        </div>
    </x-form-card>


    <x-jet-dialog-modal wire:model="open" maxWidth="3xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Agregar producto compra') }}
            <x-button-close-modal wire:click="$toggle('open')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="verifyproducto" class="w-full relative flex flex-col gap-2"
                x-data="addproductocompra">
                @if ($compra->moneda->code == 'USD')
                    <h1 class="text-[10px] text-colortitleform text-end ">
                        TIPO CAMBIO COMPRA
                        <p class="font-semibold text-lg !leading-3">
                            {{ number_format($compra->tipocambio, 3) }}</p>
                    </h1>

                    <h1 class="text-[10px] text-colortitleform text-end ">
                        PRECIO COMPRA UNITARIO SOLES
                        <p class="font-semibold text-lg !leading-3" x-text="pricebuysoles"></p>
                    </h1>
                @endif

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
                        @if ($producto_id && count($producto->almacens))
                            <div class="w-full flex flex-wrap gap-2">
                                @foreach ($producto->almacens as $item)
                                    <x-simple-card class="w-36 rounded-lg p-2 flex flex-col gap-3 justify-between">
                                        <h1 class="text-colortitleform text-[10px] text-center font-semibold">
                                            {{ $item->name }}</h1>
                                        <div class="w-full">
                                            <x-label value="Cantidad :" />
                                            <x-input type="number" class="block w-full" min="0"
                                                wire:model.lazy="almacens.{{ $item->id }}.cantidad"
                                                onkeypress="return validarDecimal(event, 9)" />
                                            <x-jet-input-error for="almacens.{{ $item->id }}.cantidad" />
                                        </div>
                                    </x-simple-card>
                                @endforeach
                            </div>
                        @else
                            <x-simple-card class="w-36 rounded-lg p-2 flex flex-col gap-3 justify-between">
                                <h1 class="text-colorerror text-[10px] py-5 text-center font-semibold">
                                    SELECCIONAR PRODUCTO PARA MOSTRAR ALMACENES</h1>
                            </x-simple-card>
                        @endif
                        <div wire:loading.flex wire:target="loadproducto" class="loading-overlay rounded hidden">
                            <x-loading-next />
                        </div>
                        <x-jet-input-error for="almacens" />
                    </div>

                    <div class="w-full">
                        <x-label value="Precio compra unitario ({{ $compra->moneda->currency }}) :" />
                        <x-input class="block w-full" x-model="pricebuy" @input="calcular" @change="numeric"
                            placeholder="0.00" type="number" min="0" step="0.001"
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
                        <x-input class="block w-full" x-model="descuento" @input="calcular" @change="numeric"
                            placeholder="0.00" type="number" min="0" step="0.001"
                            onkeypress="return validarDecimal(event, 12)" />
                        <x-jet-input-error for="descuento" />
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

                <div class="w-full text-xs text-colorsubtitleform font-semibold">
                    <x-jet-input-error for="importe" />

                    {{-- x-show="igv>0" --}}
                    <template x-if="igv > 0">
                        <div class="w-full">
                            <small class="w-full font-normal leading-3">IGV UNIT.</small>
                            <span class="text-sm inline-block" x-text="igv"></span>
                        </div>
                    </template>
                    <template x-if="descuento > 0">
                        <div>
                            <div class="w-full">
                                <small class="w-full font-normal leading-3">P. UNIT.</small>
                                <span class="text-sm inline-block"
                                    x-text="toDecimal(parseFloat(pricebuy) + parseFloat(igv) + parseFloat(descuento))"></span>
                            </div>
                            <div class="w-full text-colorlabel">
                                <small class="w-full font-normal leading-3">DESC. UNIT.</small>
                                <span class="text-sm inline-block" x-text="descuento"></span>
                            </div>
                        </div>
                    </template>
                    <template x-if="pricebuy > 0">
                        <div class="w-full">
                            <small class="w-full font-normal leading-3">P. COMPRA UNIT.</small>
                            <p class="text-xl inline-block"
                                x-text="toDecimal(parseFloat(pricebuyigv) - parseFloat(descuento))"></p>
                        </div>
                    </template>
                </div>

                <div class="w-full text-colorsubtitleform text-xs font-semibold">
                    <template x-if="pricebuyigv > 0">
                        <div class="w-full text-end">
                            <small class="w-full font-normal leading-3">IGV
                                {{ $compra->moneda->simbolo }}</small>
                            <span class="text-lg inline-block"
                                x-text="toDecimal(parseFloat(igv) * parseFloat(cantidad))"></span>
                            {{-- <small class="w-full font-normal leading-3">{{ $compra->moneda->currency }}</small> --}}
                        </div>
                    </template>

                    <template x-if="pricebuyigv > 0">
                        <div class="w-full text-end">
                            <small class="w-full font-normal leading-3">SUBTOTAL
                                {{ $compra->moneda->simbolo }}</small>
                            <span class="text-lg inline-block"
                                x-text="toDecimal((parseFloat(descuento) * parseFloat(cantidad)) + (parseFloat(pricebuy) * parseFloat(cantidad)))"></span>
                            {{-- <small class="w-full font-normal leading-3">{{ $compra->moneda->currency }}</small> --}}
                        </div>
                    </template>

                    <template x-if="descuento > 0">
                        <div class="w-full text-end text-colorlabel">
                            <small class="w-full font-normal leading-3">DESCUENTOS
                                {{ $compra->moneda->simbolo }}</small>
                            <span class="text-lg inline-block"
                                x-text="toDecimal(parseFloat(descuento) * parseFloat(cantidad))"></span>
                            {{-- <small class="w-full font-normal leading-3">{{ $compra->moneda->currency }}</small> --}}
                        </div>
                    </template>

                    <template x-if="pricebuyigv > 0">
                        <div class="w-full text-end text-colorlabel">
                            <small class="w-full font-normal leading-3">TOTAL {{ $compra->moneda->simbolo }}</small>
                            <span class="text-3xl inline-block"
                                x-text="toDecimal((parseFloat(pricebuyigv) - parseFloat(descuento)) * parseFloat(cantidad))"></span>
                            {{-- <small class="w-full font-normal leading-3">{{ $compra->moneda->currency }}</small> --}}
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

                {{-- <div>
                    {{ var_dump($almacens) }}
                </div> --}}

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('REGISTRAR') }}
                    </x-button>
                </div>

                {{-- {{ print_r($errors->all()) }} --}}
            </form>
        </x-slot>
    </x-jet-dialog-modal>


    <x-jet-dialog-modal wire:model="openprice" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Cambiar precio venta') }}
            <x-button-close-modal wire:click="$toggle('openprice')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <div>
                <x-label value="Lista precio :" />
                <x-disabled-text :text="$pricetype->name" />
                <x-jet-input-error for="pricetype_id" />

                <x-label value="Precio venta sugerido :" class="mt-2" />
                <x-disabled-text :text="$priceold" />

                <x-label value="Precio venta manual :" class="mt-2" />
                <x-input class="block w-full" wire:model.defer="newprice" type="number" min="0"
                    step="0.01" wire:keydown.enter="saveprecioventa" />
                <x-jet-input-error for="newprice" />
                <x-jet-input-error for="producto.id" />

                <div class="mt-3 flex flex-wrap gap-1 justify-end">
                    @if ($pricemanual)
                        <x-button wire:click="deletepricemanual" wire:key="deletepricemanual{{ $producto->id }}"
                            wire:loading.attr="disabled">
                            ELIMINAR PRECIO MANUAL</x-button>
                    @endif
                    <x-button wire:click="saveprecioventa" wire:key="saveprecioventa{{ $producto->id }}"
                        wire:loading.attr="disabled">
                        REGISTRAR</x-button>
                </div>
            </div>
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
                pricetype_id: @entangle('pricetype_id'),
                igv: @entangle('igv').defer,
                pricebuyigv: null,
                percent: @entangle('percent').defer,
                showigv: @entangle('showigv').defer,
                codemoneda_compra: @json($compra->moneda->code),
                cantidad: @entangle('stock'),
                total: @entangle('total').defer,
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
                },
                calcular() {
                    let descuento = this.descuento > 0 ? this.descuento : 0;
                    if (this.showigv) {
                        this.igv = toDecimal((parseFloat(this.pricebuy) + parseFloat(descuento)) * (
                            parseFloat(this.percent) /
                            100));
                    } else {
                        this.igv = toDecimal(0);
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
                @this.loadproducto(this.producto_id);
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
                // @this.setPricetypeId(event.target.value);
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
