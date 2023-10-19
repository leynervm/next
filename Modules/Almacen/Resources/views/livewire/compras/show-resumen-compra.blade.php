<div>
    <x-form-card titulo="RESUMEN COMPRA" widthBefore="before:w-28"
        subtitulo="Resumen de productos adquiridos en la compra.">
        <div class="w-full flex flex-wrap xl:flex-nowrap gap-2 xl:items-start">
            <form wire:submit.prevent="verifyproducto" x-data="{ searchingalmacens: false }"
                class="w-full xl:w-1/3 relative flex flex-col gap-2 bg-body p-3 rounded">

                <div class="w-full flex flex-col xs:grid xs:grid-cols-2 xl:flex gap-2">
                    <div class="w-full xs:col-span-2">
                        <x-label value="Producto :" />
                        <div id="parenteditproductocompra_id">
                            <x-select class="block w-full" wire:model.defer="producto_id" id="editproductocompra_id"
                                data-minimum-results-for-search="3">
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
                        </div>
                        <x-jet-input-error for="producto_id" />
                    </div>

                    @if ($producto_id && count($producto->almacens))
                        <div class="w-full">
                            <x-label value="Almacén :" />
                            <div class="w-full flex gap-1 flex-wrap animate__animated animate__fadeIn animate__faster">
                                @foreach ($producto->almacens as $item)
                                    <x-input-radio class="py-2" :for="'almacen_' . $item->id" :text="$item->name" textSize="xs">
                                        <input wire:model.defer="almacen_id"
                                            class="sr-only peer peer-disabled:opacity-25" type="radio"
                                            id="almacen_{{ $item->id }}" name="almacen_id"
                                            value="{{ $item->id }}" />
                                    </x-input-radio>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <x-jet-input-error for="almacen_id" />

                    <div class="w-full">
                        <x-label value="Cantidad :" />
                        <x-input class="block w-full" wire:model.defer="cantidad" placeholder="0" type="number"
                            min="0" step="1" />
                        <x-jet-input-error for="cantidad" />
                    </div>

                    <div class="w-full">
                        <x-label value="P.C Unitario ({{ $compra->moneda->currency }}) :" />
                        <x-input class="block w-full numeric" wire:model.lazy="pricebuy" placeholder="0.00"
                            type="number" min="0" step="0.0001"
                            wire:key="pricebuy-{{ count($compra->compraitems) }}" />
                        <x-jet-input-error for="pricebuy" />
                    </div>

                    @if ($compra->moneda->code == 'USD')
                        <div class="w-full">
                            <x-label value="P.C Unitario (SOLES) :" />
                            <x-disabled-text :text="number_format($compra->tipocambio * $pricebuy, 4)" />
                            <x-jet-input-error for="pricebuy" />
                        </div>
                    @endif

                    @if (!$empresa->uselistprice)
                        <div class="w-full">
                            <x-label value="Precio venta unitario (SOLES):" />
                            <x-input class="block w-full" wire:model.defer="pricesale" placeholder="0.00" type="number"
                                min="0" step="0.0001" />
                            <x-jet-input-error for="pricesale" />
                        </div>
                    @endif
                </div>
                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled" wire:target="verifyproducto">
                        {{ __('AGREGAR') }}
                    </x-button>
                </div>

                @foreach ($errors->all() as $key)
                    {{ $key }}
                @endforeach

                <div x-show="searchingalmacens" wire:loading wire:loading.flex
                    wire:target="loadproducto, verifyproducto, addproducto, updatecompraitem"
                    class="loading-overlay rounded">
                    <x-loading-next />
                </div>
            </form>
            <div class="w-full xl:w-2/3 bg-body relative" x-data="{ loadingitems: false }">
                @if (count($compra->compraitems))
                    <div class="w-full flex gap-2 flex-wrap justify-around">
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

                                $discount = count($item->producto->ofertasdisponibles) ? $item->producto->ofertasdisponibles()->first()->descuento : null;
                            @endphp

                            <x-card-producto :image="$image" :name="$item->producto->name" :discount="$discount ?? null"
                                x-data="{ loadingproducto: false }">
                                <div class="w-full flex flex-wrap gap-1 justify-center mt-1">

                                    <x-label-price>
                                        <span>
                                            {{ $item->compra->moneda->simbolo }}
                                            {{ number_format($item->subtotal + $item->igv, 4, '.', ', ') }}
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
                                    <span
                                        class="leading-3 p-1 bg-fondospancardproduct text-textspancardproduct rounded uppercase">
                                        P.C UNIT: {{ $item->compra->moneda->simbolo }}
                                        {{ number_format($item->pricebuy, 4, '.', ', ') }}
                                    </span>

                                    @if ($empresa->viewpricedolar)
                                        @if ($item->compra->moneda->code == 'USD')
                                            <span
                                                class="leading-3 p-1 bg-fondospancardproduct text-textspancardproduct rounded uppercase">
                                                P.C UNIT: S/.
                                                {{ number_format($item->pricebuy * $item->compra->tipocambio, 4, '.', ', ') }}
                                                SOLES
                                            </span>
                                        @endif
                                    @endif

                                    <span
                                        class="leading-3 p-1 bg-fondospancardproduct text-textspancardproduct rounded uppercase">
                                        {{ \App\Helpers\FormatoPersonalizado::getValue($item->cantidad) }}
                                        {{ $item->producto->unit->name }}
                                    </span>
                                    <span
                                        class="leading-3 p-1 bg-fondospancardproduct text-textspancardproduct rounded uppercase">
                                        {{ $item->almacen->name }}
                                    </span>

                                    @if (count($item->series) == 1)
                                        <span
                                            class="leading-3 p-1 bg-fondospancardproduct text-textspancardproduct rounded uppercase">
                                            SERIE: {{ $item->series->first()->serie }}
                                        </span>
                                    @endif


                                </div>

                                <div class="w-full">
                                    <x-label value="Series entrantes :" class="mt-3" />
                                    <div class="w-full inline-flex gap-1">
                                        <x-input class="block w-full prevent"
                                            wire:model.defer="serie.{{ $item->id }}.serie"
                                            wire:keydown.enter="saveserie({{ $item }})" />
                                        <x-button-add class="px-2" wire:click="saveserie({{ $item }})"
                                            wire:loading.attr="disabled" wire:target="saveserie">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M5 12l5 5l10 -10" />
                                            </svg>
                                        </x-button-add>
                                    </div>
                                    <x-jet-input-error for="serie.{{ $item->id }}.serie" />
                                    <x-jet-input-error for="serie.{{ $item->id }}.compraitem_id" />
                                </div>

                                <div class="w-full" x-data="{ showForm: false, showPrices: false }">
                                    <div class="mt-1 flex gap-1 flex-wrap justify-between items-start">

                                        <x-button @click="showPrices = !showPrices" class="whitespace-nowrap">
                                            {{ __('VER PRECIOS') }}
                                        </x-button>

                                        @if (count($item->series) > 1)
                                            <x-button @click="showForm = !showForm" class="whitespace-nowrap">
                                                {{ __('VER SERIES') }}
                                            </x-button>
                                        @endif
                                    </div>

                                    <div x-show="showForm" @click.away="showForm = false"
                                        x-transition:enter="transition ease-out duration-300 transform"
                                        x-transition:enter-start="opacity-0 translate-y-[-10%]"
                                        x-transition:enter-end="opacity-100 translate-y-0"
                                        x-transition:leave="transition ease-in duration-300 transform"
                                        x-transition:leave-start="opacity-100 translate-y-0"
                                        x-transition:leave-end="opacity-0 translate-y-[-10%]"
                                        class="block w-full rounded mt-1">
                                        <div class="w-full flex flex-wrap gap-1">
                                            @if (count($item->series) > 1)
                                                @foreach ($item->series as $itemserie)
                                                    <span
                                                        class="inline-flex items-center gap-1 text-[10px] bg-fondospancardproduct text-textspancardproduct p-1 rounded-lg">
                                                        {{ $itemserie->serie }}
                                                        <x-button-delete
                                                            wire:click="$emit('compra.confirmDeleteSerie',{{ $itemserie }})"
                                                            wire:loading.attr="disabled" />
                                                    </span>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>

                                    <div x-show="showPrices"
                                        x-transition:enter="transition ease-out duration-300 transform"
                                        x-transition:enter-start="opacity-0 translate-y-[-10%]"
                                        x-transition:enter-end="opacity-100 translate-y-0"
                                        x-transition:leave="transition ease-in duration-300 transform"
                                        x-transition:leave-start="opacity-100 translate-y-0"
                                        x-transition:leave-end="opacity-0 translate-y-[-10%]"
                                        class="block w-full rounded mt-1">
                                        @if ($empresa->uselistprice)
                                            @if (count($pricetypes))
                                                <div class="w-full grid xs:grid-cols-2 lg:grid-cols-1 gap-1">
                                                    @foreach ($pricetypes as $lista)
                                                        @php
                                                            $precios = \App\Helpers\GetPrice::getPriceProducto($item->producto, $empresa->uselistprice ? $lista->id : null, $empresa->usepricedolar, $empresa->tipocambio)->getData();
                                                        @endphp

                                                        <x-prices-card-product :name="$lista->name">
                                                            <x-slot name="buttonpricemanual">
                                                                <x-button-edit
                                                                    wire:click="openmodalprice({{ $item->producto_id }},{{ $lista->id }} )"
                                                                    wire:loading.attr="disabled" />
                                                            </x-slot>

                                                            @if (count($item->producto->ofertasdisponibles))
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

                                                            @if ($empresa->usepricedolar && $empresa->viewpricedolar)
                                                                <div
                                                                    class="w-full flex font-semibold gap-1 items-center justify-between mt-1">
                                                                    <x-label-price>
                                                                        $.
                                                                        {{-- {{ number_format($precios->pricewithdescountDolar, $precios->decimal, '.', ', ') }} --}}
                                                                        {{ number_format($precios->priceDolar, $precios->decimal, '.', ', ') }}
                                                                        <small> DÓLARES</small>
                                                                    </x-label-price>

                                                                    {{-- @if (count($item->producto->ofertasdisponibles))
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
                                                    $precios = \App\Helpers\GetPrice::getPriceProducto($item->producto, null, $empresa->usepricedolar, $empresa->tipocambio)->getData();
                                                @endphp

                                                <h1
                                                    class="text-colortitleform text-[10px] font-semibold leading-3 mt-3">
                                                    PRECIO VENTA</h1>

                                                <x-prices-card-product>
                                                    @if ($empresa->usepricedolar && $empresa->viewpricedolar)
                                                        <div
                                                            class="w-full flex font-semibold gap-1 items-center justify-between mt-1">
                                                            <h1 class="text-xs font-semibold leading-3 text-green-500">
                                                                $.
                                                                {{-- {{ number_format($precios->pricewithdescountDolar, $precios->decimal, '.', ', ') }} --}}
                                                                {{ number_format($precios->priceDolar, $precios->decimal, '.', ', ') }}
                                                                <small> DÓLARES</small>
                                                            </h1>

                                                            {{-- @if (count($item->producto->ofertasdisponibles))
                                                        <p
                                                            class="text-[10px] inline-block leading-3 bg-red-100 p-0.5 rounded text-red-500">
                                                            <small>ANTES : </small>$.
                                                            {{ number_format($precios->priceDolar, $precios->decimal, '.', ', ') }}
                                                        </p>
                                                    @endif --}}
                                                        </div>
                                                    @endif

                                                    @if (count($item->producto->ofertasdisponibles))
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
                                                        <h1
                                                            class="text-xs font-semibold leading-3 text-green-500 mt-1">
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

                                <x-slot name="footer">
                                    <x-button-delete
                                        wire:click="$emit('compra.confirmDeleteItemCompra',({{ $item->id }}))"
                                        wire:loading.attr="disabled"
                                        wire:target="compra.confirmDeleteItemCompra, deleteitemcompra" />
                                </x-slot>

                                <div x-show="loadingproducto" wire:loading.flex class="loading-overlay rounded">
                                    <x-loading-next />
                                </div>
                            </x-card-producto>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </x-form-card>

    <x-jet-dialog-modal wire:model="openprice" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Cambiar precio venta') }}
            <x-button-add wire:click="$toggle('openprice')" wire:loading.attr="disabled">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </x-button-add>
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
                        wire:loading.attr="disabled" wire:target="deletepricemanual, saveprecioventa">
                        REGISTRAR
                    </x-button>
                </div>
            </div>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        document.addEventListener("livewire:load", () => {
            $('#editproductocompra_id').select2()
                .on("change", function(e) {
                    $(this).attr("disabled", true);
                    @this.loadproducto(e.target.value);
                }).on('select2:open', function(e) {
                    const evt = "scroll.select2";
                    $(e.target).parents().off(evt);
                    $(window).off(evt);
                });

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
                        Livewire.emitTo('almacen::compras.show-resumen-compra', 'updatecompraitem',
                            data
                            .detail);
                    }
                })
            });

            Livewire.on('compra.confirmDeleteItemCompra', data => {
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
                        Livewire.emitTo('almacen::compras.show-resumen-compra', 'deleteitemcompra',
                            data);
                    }
                })
            });

            Livewire.on('compra.confirmDeleteSerie', data => {
                swal.fire({
                    title: 'Desea eliminar la serie ' + data.serie + ' ?',
                    text: "Se eliminará un registro de pago de la base de datos.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.emitTo('almacen::compras.show-resumen-compra', 'deleteserie', data
                            .id);
                    }
                })
            });

            document.addEventListener('render-show-resumen-compra', () => {
                $('#editproductocompra_id').select2();
            });

        })
    </script>
</div>
