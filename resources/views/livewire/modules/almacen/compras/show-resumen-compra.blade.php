<div>
    <x-form-card titulo="RESUMEN COMPRA" subtitulo="Resumen de productos adquiridos en la compra.">
        <div class="w-full relative flex flex-col gap-2">
            @if (count($compra->compraitems))
                <div class="w-full flex gap-2 flex-wrap justify-around xl:justify-start">
                    @foreach ($compra->compraitems as $item)
                        @php
                            $image = null;
                            if (count($item->producto->images)) {
                                if (count($item->producto->defaultImage)) {
                                    $image = asset('storage/productos/' . $item->producto->defaultImage->first()->url);
                                } else {
                                    $image = asset('storage/productos/' . $item->producto->images->first()->url);
                                }
                            }

                            $discount = count($item->producto->descuentosactivos)
                                ? $item->producto->descuentosactivos()->first()->descuento
                                : null;
                        @endphp

                        <x-card-producto :image="$image" :name="$item->producto->name" :discount="$discount ?? null" x-data="{ loadingproducto: false }">
                            <div class="w-full flex flex-wrap gap-1 justify-center mt-1">

                                <x-label-price>
                                    <span>
                                        {{ $item->compra->moneda->simbolo }}
                                        {{ number_format($item->total + $item->igv, 3, '.', ', ') }}
                                        {{ $item->compra->moneda->currency }}
                                    </span>
                                </x-label-price>

                                {{-- @if ($empresa->viewpricedolar)
                                        @if ($item->compra->moneda->code == 'USD')
                                            <h1 class="text-xs font-semibold leading-3 text-green-500">
                                                <span>
                                                    S/.
                                                    {{ number_format(($item->subtotal + $item->igv) * $item->compra->tipocambio, 4, '.', ', ') }}
                                                    SOLES
                                                </span>
                                            </h1>
                                        @endif
                                    @endif --}}
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

                                    {{-- <x-span-text :text="'SERIE: ' . $item->series->first()->serie" class="leading-3" /> --}}
                                @endif
                            </div>

                            @can('admin.almacen.compras.create')
                                <div class="w-full">
                                    <x-label value="Series entrantes :" class="mt-3" />
                                    <div class="w-full inline-flex gap-1">
                                        <x-input class="block w-full prevent"
                                            wire:model.defer="serie.{{ $item->id }}.serie"
                                            wire:keydown.enter="saveserie({{ $item }})" />
                                        <x-button-add class="px-2" wire:click="saveserie({{ $item }})"
                                            wire:loading.attr="disabled" wire:target="saveserie">
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

                            <div class="w-full" x-data="{ showForm: false, showPrices: false }">
                                <div class="mt-1 flex gap-1 flex-wrap justify-between items-start">

                                    <x-button @click="showPrices = !showPrices" class="whitespace-nowrap"
                                        wire:loading.attr="disabled">
                                        {{-- {{ __('VER PRECIOS') }} --}}
                                        <span x-text="showPrices ? 'OCULTAR PRECIOS' : 'VER PRECIOS'"></span>
                                    </x-button>

                                    @if (count($item->series) > 1)
                                        <x-button @click="showForm = !showForm" class="whitespace-nowrap"
                                            wire:loading.attr="disabled">
                                            {{-- {{ __('VER SERIES') }} --}}
                                            <span x-text="showPrices ? 'OCULTAR SERIES' : 'VER SERIES'"></span>
                                        </x-button>
                                    @endif
                                </div>

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

                                <div x-show="showPrices" x-transition class="block w-full rounded mt-1">

                                    @php
                                        $empresa = $compra->sucursal->empresa;
                                    @endphp
                                    @if ($empresa->usarLista())
                                        @if (count($pricetypes))
                                            <div class="w-full grid xs:grid-cols-2 lg:grid-cols-1 gap-1">
                                                @foreach ($pricetypes as $lista)
                                                    @php
                                                        $precios = getPrecio(
                                                            $item->producto,
                                                            $lista->id,
                                                            $empresa->tipocambio,
                                                        )->getData();
                                                        // var_dump($precios);
                                                    @endphp

                                                    <x-prices-card-product :name="$lista->name">
                                                        <x-slot name="buttonpricemanual">
                                                            <x-button-edit
                                                                wire:click="openmodalprice({{ $item->producto_id }},{{ $lista->id }} )"
                                                                wire:loading.attr="disabled" />
                                                        </x-slot>

                                                        @if (count($item->producto->descuentosactivos))
                                                            <div
                                                                class="w-full flex font-semibold gap-1 items-center justify-between mt-1">
                                                                <x-label-price>
                                                                    S/.
                                                                    {{-- {{ number_format($precios->pricewithdescount, $precios->decimal, '.', ', ') }} --}}
                                                                    {{ number_format($precios->pricesale, $precios->decimal, '.', ', ') }}
                                                                    <small> SOLES</small>
                                                                </x-label-price>

                                                                {{-- <p
                                                                            class="text-[10px] inline-block leading-3 bg-red-100 p-0.5 rounded text-red-500">
                                                                            <small>ANTES : </small>S/.
                                                                            {{ number_format($precios->pricesale, $precios->decimal, '.', ', ') }}
                                                                        </p> --}}
                                                            </div>
                                                        @else
                                                            <x-label-price>
                                                                S/.
                                                                {{ number_format($precios->pricemanual ?? $precios->pricesale, $precios->decimal, '.', ', ') }}
                                                                <small> SOLES</small>
                                                            </x-label-price>
                                                        @endif

                                                        @if ($empresa->usarDolar() && $empresa->verDolar())
                                                            <div
                                                                class="w-full flex font-semibold gap-1 items-center justify-between mt-1">
                                                                <x-label-price>
                                                                    $.
                                                                    {{-- {{ number_format($precios->pricewithdescountDolar, $precios->decimal, '.', ', ') }} --}}
                                                                    {{ number_format($precios->pricewithdescountDolar ?? $precios->priceDolar, $precios->decimal, '.', ', ') }}
                                                                    <small>DÓLARES</small>
                                                                </x-label-price>

                                                                {{-- @if (count($item->producto->descuentosactivos))
                                                                            <p
                                                                                class="text-[10px] inline-block leading-3 bg-red-100 p-0.5 rounded text-red-500">
                                                                                <small>ANTES : </small>$.
                                                                                {{ number_format($precios->priceDolar, $precios->decimal, '.', ', ') }}
                                                                            </p>
                                                                        @endif --}}
                                                            </div>
                                                        @endif

                                                        @if ($empresa->uselistprice)
                                                            @if (!$precios->existsrango)
                                                                <small
                                                                    class="text-red-500 bg-red-50 p-0.5 rounded font-semibold inline-block mt-1">
                                                                    Rango de precio no disponible <a
                                                                        class="underline px-1"
                                                                        href="#">REGISTRAR</a></small>
                                                            @endif
                                                        @endif
                                                    </x-prices-card-product>
                                                @endforeach
                                            </div>
                                        @else
                                            <small
                                                class="text-red-500 bg-red-50 text-xs p-0.5 rounded font-semibold inline-block mt-1">
                                                Configurar lista de precios
                                                <a class="underline px-1" href="#">REGISTRAR</a>
                                            </small>
                                        @endif
                                    @else
                                        <div class="w-full flex flex-col">
                                            @php
                                                $precios = getPrecio(
                                                    $item->producto,
                                                    null,
                                                    $empresa->usarDolar(),
                                                    $empresa->tipocambio,
                                                )->getData();
                                            @endphp

                                            <h1 class="text-colortitleform text-[10px] font-semibold leading-3 mt-3">
                                                PRECIO VENTA</h1>

                                            <x-prices-card-product>
                                                @if ($empresa->usarDolar() && $empresa->verDolar())
                                                    <div
                                                        class="w-full flex font-semibold gap-1 items-center justify-between mt-1">
                                                        <h1 class="text-xs font-semibold leading-3 text-green-500">
                                                            $.
                                                            {{-- {{ number_format($precios->pricewithdescountDolar, $precios->decimal, '.', ', ') }} --}}
                                                            {{ number_format($precios->priceDolar, $precios->decimal, '.', ', ') }}
                                                            <small> DÓLARES</small>
                                                        </h1>

                                                        {{-- @if (count($item->producto->descuentosactivos))
                                                        <p
                                                            class="text-[10px] inline-block leading-3 bg-red-100 p-0.5 rounded text-red-500">
                                                            <small>ANTES : </small>$.
                                                            {{ number_format($precios->priceDolar, $precios->decimal, '.', ', ') }}
                                                        </p>
                                                    @endif --}}
                                                    </div>
                                                @endif

                                                @if (count($item->producto->descuentosactivos))
                                                    <div
                                                        class="w-full flex font-semibold gap-1 items-center justify-between mt-1">
                                                        <h1 class="text-xs leading-3 text-green-500">
                                                            S/.
                                                            {{ number_format($precios->pricesale, $precios->decimal, '.', ', ') }}
                                                            {{-- {{ number_format($precios->pricewithdescount, $precios->decimal, '.', ', ') }} --}}
                                                            <small> SOLES</small>
                                                        </h1>

                                                        {{-- <p
                                                        class="text-[10px] inline-block leading-3 bg-red-100 p-0.5 rounded text-red-500">
                                                        <small>ANTES : </small>S/.
                                                        {{ number_format($precios->pricesale, $precios->decimal, '.', ', ') }}
                                                    </p> --}}
                                                    </div>
                                                @else
                                                    <h1 class="text-xs font-semibold leading-3 text-green-500 mt-1">
                                                        S/.
                                                        {{ number_format($precios->pricesale, $precios->decimal, '.', ', ') }}
                                                        <small> SOLES</small>
                                                    </h1>
                                                @endif
                                            </x-prices-card-product>
                                        </div>
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
            {{ __('Agregar producto almacén') }}
            <x-button-close-modal wire:click="$toggle('open')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="verifyproducto" x-data="converter"
                class="w-full relative flex flex-col gap-2">
                @if ($compra->moneda->code == 'USD')
                    <h1 class="text-[10px] text-colortitleform text-end ">
                        TIPO CAMBIO COMPRA
                        <p class="font-semibold text-lg !leading-3">{{ number_format($compra->tipocambio, 3) }}</p>
                    </h1>

                    <h1 class="text-[10px] text-colortitleform text-end ">
                        PRECIO COMPRA UNITARIO SOLES
                        <p class="font-semibold text-lg !leading-3" x-text="pricebuysoles"></p>
                    </h1>
                @endif

                <div class="w-full grid grid-cols-1 xs:grid-cols-2 gap-2">
                    <div class="w-full xs:col-span-2">
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
                    <div class="w-full">
                        <x-label value="Precio compra unitario ({{ $compra->moneda->currency }}) :" />
                        <x-input class="block w-full numeric" wire:model.lazy="pricebuy" x-model="pricebuy"
                            @change="calcular" placeholder="0.00" type="number" min="0" step="0.001"
                            wire:key="pricebuy-{{ count($compra->compraitems) }}" />
                        <x-jet-input-error for="pricebuy" />
                    </div>

                    <div class="w-full">
                        <x-label value="Descuento x unidad :" />
                        <x-input class="block w-full numeric" wire:model.lazy="descuento" x-model="descuento"
                            @change="calcular" placeholder="0.00" type="number" min="0" step="0.001"
                            wire:key="{{ rand() }}" />
                        <x-jet-input-error for="descuento" />
                    </div>

                    @if ($compra->sucursal->empresa->uselistprice == 0)
                        <div class="w-full">
                            <x-label value="Precio venta unitario (SOLES):" />
                            <x-input class="block w-full" wire:model.defer="pricesale" placeholder="0.00"
                                type="number" min="0" step="0.001" />
                            <x-jet-input-error for="pricesale" />
                        </div>
                    @endif

                    <div class="w-full xs:col-span-2">
                        @if ($producto_id && count($producto->almacens))
                            <div class="w-full flex flex-wrap gap-2 animate__animated animate__fadeIn animate__faster">
                                @foreach ($producto->almacens as $item)
                                    <x-simple-card class="w-36 rounded-lg p-2 flex flex-col gap-3 justify-between">
                                        <h1 class="text-colortitleform text-[10px] text-center font-semibold">
                                            {{ $item->name }}</h1>
                                        <div class="w-full">
                                            <x-label value="Cantidad :" />
                                            <x-input type="number" class="block w-full" min="0"
                                                wire:model.lazy="almacens.{{ $item->id }}.cantidad" />
                                            <x-jet-input-error for="almacens.{{ $item->id }}.cantidad" />
                                        </div>
                                    </x-simple-card>
                                @endforeach
                            </div>
                        @endif
                        <x-jet-input-error for="almacens" />
                    </div>
                </div>

                <div class="w-full text-colortitleform">
                    <h3 class="font-semibold text-xs text-end leading-3">
                        <small class="font-medium">TOTAL DESCUENTO {{ $compra->moneda->simbolo }}</small>
                        {{ number_format($descuento * $stock, 3) }}
                    </h3>

                    <h3 class="font-semibold text-3xl leading-normal text-end">
                        <small class="text-[10px] font-medium">{{ $compra->moneda->simbolo }}</small>
                        {{ number_format($importe, 3) }}
                    </h3>
                </div>

                <div>
                    {{ var_dump($almacens) }}
                </div>

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled" wire:target="verifyproducto">
                        {{ __('REGISTRAR') }}
                    </x-button>
                </div>

                @foreach ($errors->all() as $key)
                    {{ $key }}
                @endforeach
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
                            wire:loading.attr="disabled" wire:target="deletepricemanual, saveprecioventa">
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

        function toDecimal(valor, decimals = 3) {
            let numero = parseFloat(valor);

            if (isNaN(numero)) {
                return 0;
            } else {
                return parseFloat(numero).toFixed(decimals);
            }
        }

        document.addEventListener('alpine:init', () => {
            Alpine.data('converter', () => ({
                searchingalmacens: false,
                producto_id: @entangle('producto_id'),
                pricebuysoles: @entangle('pricebuysoles').defer,
                tipocambio: @this.get('tipocambio'),
                pricebuy: @entangle('pricebuy').defer,
                descuento: @entangle('descuento').defer,

                init() {
                    this.pricebuysoles = toDecimal(this.pricebuysoles);
                    this.descuento = toDecimal(this.descuento);
                },
                calcular() {
                    this.pricebuy = toDecimal(this.pricebuy);
                    this.pricebuysoles = toDecimal(this.pricebuy * parseFloat(this.tipocambio));
                }
            }))
        })

        function ProductoCompra() {
            this.selectPCI = $(this.$refs.selectpc).select2();
            this.selectPCI.val(this.producto_id).trigger("change");
            this.selectPCI.on("select2:select", (event) => {
                this.producto_id = event.target.value;
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

            document.addEventListener('render-show-resumen-compra', () => {
                $('#editproductocompra_id').select2();
            });

        })
    </script>
</div>
