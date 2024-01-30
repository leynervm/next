<div class="">

    @if (count($ofertas))
        <div class="pb-2">
            {{ $ofertas->links() }}
        </div>
    @endif

    <div class="w-full flex gap-2 flex-wrap justify-start">
        @if (count($ofertas))
            @foreach ($ofertas as $item)
                @php
                    $image = null;
                    if (count($item->producto->images)) {
                        if (count($item->producto->defaultImage)) {
                            $image = asset('storage/productos/' . $item->producto->defaultImage->first()->url);
                        } else {
                            $image = asset('storage/productos/' . $item->producto->images->first()->url);
                        }
                    }

                @endphp
                <x-card-producto :image="$image" :name="$item->producto->name" :discount="$item->descuento" x-data="{ loadingproducto: false }">
                    <div class="w-full flex flex-wrap gap-1 items-start mt-2 text-[10px]">
                        @php
                            $priceCompra = number_format($moneda->code == 'USD' ? $item->producto->pricebuy / $empresa->tipocambio : $item->producto->pricebuy, 4, '.', ', ');
                            $unitsMax = \App\Helpers\FormatoPersonalizado::getValue($item->limit);
                            $unitsDisponibles = \App\Helpers\FormatoPersonalizado::getValue($item->disponible);
                            $datestart = \Carbon\Carbon::parse($item->datestart)
                                ->locale('es')
                                ->isoFormat('D MMMM YYYY h:mm A');
                            $dateexpire = \Carbon\Carbon::parse($item->dateexpire)
                                ->locale('es')
                                ->isoFormat('D MMMM YYYY h:mm A');

                        @endphp

                        <x-span-text :text="'P.C UNIT : ' . $moneda->simbolo . ' ' . $priceCompra . ' ' . $moneda->currency" class="leading-3" />
                        <x-span-text :text="'STOCK MÁXIMO : ' . $unitsMax . ' ' . $item->producto->unit->name" class="leading-3" />
                        <x-span-text :text="'STOCK DISPONIBLES : ' . $unitsDisponibles . ' ' . $item->producto->unit->name" class="leading-3" />
                        <x-span-text :text="$item->almacen->name" class="leading-3" />
                        <x-span-text :text="'INICIO : ' . $datestart" class="leading-3 uppercase" />
                        <x-span-text :text="'FIN : ' . $dateexpire" class="leading-3 uppercase" />

                        @if ($item->status)
                            <small class="text-[10px] font-medium leading-3 p-1 text-white bg-red-500 rounded">
                                FINALIZADO</small>
                        @else
                            <small class="text-[10px] font-medium leading-3 p-1 text-white bg-green-500 rounded">
                                ACTIVO</small>
                        @endif

                    </div>

                    <div class="w-full mt-2" x-data="{ showForm: false, showPrices: false }">
                        <x-button @click="showPrices = !showPrices" class="whitespace-nowrap">
                            {{ __('VER PRECIOS') }}
                        </x-button>

                        <div x-show="showPrices" x-transition:enter="transition ease-out duration-300 transform"
                            x-transition:enter-start="opacity-0 translate-y-[-10%]"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-300 transform"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-[-10%]" class="block w-full rounded mt-1">
                            @if ($empresa->uselistprice)
                                @if (count($pricetypes))
                                    <div class="w-full grid xs:grid-cols-2 lg:grid-cols-1 gap-1">
                                        @foreach ($pricetypes as $lista)
                                            @php
                                                $precios = \App\Helpers\GetPrice::getPriceProducto($item->producto, $empresa->uselistprice ? $lista->id : null, $empresa->usepricedolar, $empresa->tipocambio)->getData();
                                            @endphp

                                            <x-prices-card-product :name="$lista->name">
                                                <x-slot name="buttonpricemanual">
                                                    <p
                                                        class="inline-block font-semibold text-[9px] leading-3 bg-red-100 p-1 rounded text-red-500">
                                                        ANTES : {{ $moneda->simbolo }}
                                                        {{ number_format($moneda->code == 'USD' ? $precios->priceDolar : $precios->pricesale, $precios->decimal, '.', ', ') }}
                                                    </p>
                                                </x-slot>

                                                <x-label-price>
                                                    {{ $moneda->simbolo }}
                                                    {{ number_format($moneda->code == 'USD' ? $precios->pricewithdescountDolar : $precios->pricewithdescount, $precios->decimal, '.', ', ') }}
                                                    {{ $moneda->currency }}
                                                </x-label-price>

                                                @if ($empresa->uselistprice)
                                                    @if (!$precios->existsrango)
                                                        <small
                                                            class="text-red-500 bg-red-50 p-0.5 rounded font-semibold inline-block mt-1">
                                                            Rango de precio no disponible <a class="underline px-1"
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

                                    <x-prices-card-product name="PRECIO VENTA">
                                        <x-slot name="buttonpricemanual">
                                            <p
                                                class="inline-block font-semibold text-[9px] leading-3 bg-red-100 p-1 rounded text-red-500">
                                                ANTES : {{ $moneda->simbolo }}
                                                {{ number_format($moneda->code == 'USD' ? $precios->priceDolar : $precios->pricesale, $precios->decimal, '.', ', ') }}
                                            </p>
                                        </x-slot>

                                        <x-label-price>
                                            {{ $moneda->simbolo }}
                                            {{ number_format($moneda->code == 'USD' ? $precios->pricewithdescountDolar : $precios->pricewithdescount, $precios->decimal, '.', ', ') }}
                                            {{ $moneda->currency }}
                                        </x-label-price>
                                    </x-prices-card-product>
                                </div>
                            @endif
                        </div>
                    </div>

                    <x-slot name="footer">
                        <x-button-edit wire:click="edit({{ $item->id }})" wire:loading.attr="disabled" />
                        <x-button-delete wire:click="$emit('ofertas.confirmDelete',{{ $item }})"
                            wire:loading.attr="disabled" />
                    </x-slot>

                    <div x-show="loadingproducto" wire:loading.flex class="loading-overlay rounded">
                        <x-loading-next />
                    </div>
                </x-card-producto>
            @endforeach
        @endif
    </div>

    <x-jet-dialog-modal wire:model="open" maxWidth="xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar oferta') }}
            <x-button-close-modal wire:click="$toggle('open')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update" class="w-full grid grid-cols-1 xs:grid-cols-2 gap-2">
                <div class="w-full xs:max-w-xs mx-auto xs:col-span-2">
                    @if ($oferta->producto)
                        @if (count($oferta->producto->images))
                            @if ($oferta->producto->defaultImage)
                                <div
                                    class="w-full h-60 shadow-md shadow-shadowminicard border rounded-lg border-borderminicard overflow-hidden mb-1 duration-300 relative">
                                    @if ($oferta->producto->defaultImage)
                                        <img src="{{ asset('storage/productos/' . $oferta->producto->defaultImage->first()->url) }}"
                                            alt="" class="w-full h-full object-scale-down">
                                    @else
                                        <img src="{{ asset('storage/productos/' . $oferta->producto->images->first()->url) }}"
                                            alt="" class="w-full h-full object-scale-down">
                                    @endif
                                </div>
                            @endif
                        @endif
                    @else
                        <div
                            class="w-full flex items-center justify-center h-60 shadow-md shadow-shadowminicard border rounded-lg border-borderminicard mb-1">
                            <svg class="text-neutral-500 w-24 h-24" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path
                                    d="M13 3.00231C12.5299 3 12.0307 3 11.5 3C7.02166 3 4.78249 3 3.39124 4.39124C2 5.78249 2 8.02166 2 12.5C2 16.9783 2 19.2175 3.39124 20.6088C4.78249 22 7.02166 22 11.5 22C15.9783 22 18.2175 22 19.6088 20.6088C20.9472 19.2703 20.998 17.147 20.9999 13" />
                                <path
                                    d="M2 14.1354C2.61902 14.0455 3.24484 14.0011 3.87171 14.0027C6.52365 13.9466 9.11064 14.7729 11.1711 16.3342C13.082 17.7821 14.4247 19.7749 15 22" />
                                <path
                                    d="M21 16.8962C19.8246 16.3009 18.6088 15.9988 17.3862 16.0001C15.5345 15.9928 13.7015 16.6733 12 18" />
                                <path
                                    d="M17 4.5C17.4915 3.9943 18.7998 2 19.5 2M22 4.5C21.5085 3.9943 20.2002 2 19.5 2M19.5 2V10" />
                            </svg>
                        </div>
                    @endif
                </div>

                <div class="xs:col-span-2">
                    <x-label value="Producto :" />
                    <x-disabled-text :text="$oferta->producto->name ?? '-'" />
                    <x-jet-input-error for="oferta.producto_id" />
                </div>

                <div>
                    <x-label value="Almacén :" />
                    <x-disabled-text :text="$oferta->almacen->name ?? '-'" />
                    <x-jet-input-error for="oferta.almacen_id" />
                </div>

                <div class="w-full">
                    <x-label value="Descuento (%) :" />
                    <x-input class="block w-full" wire:model.defer="oferta.descuento" type="number" min="0"
                        step="0.1" />
                    <x-jet-input-error for="oferta.descuento" />
                </div>

                <div class="w-full">
                    <x-label value="Fecha inicio :" />
                    <x-disabled-text :text="\Carbon\Carbon::parse($oferta->datestart)->format('d/m/Y') ?? '-'" />
                    <x-jet-input-error for="oferta.datestart" />
                </div>

                <div class="w-full">
                    <x-label value="Fecha finalización :" />
                    <x-input class="block w-full" wire:model.defer="oferta.dateexpire" type="date" />
                    <x-jet-input-error for="oferta.dateexpire" />
                </div>

                <div class="w-full">
                    <x-label value="Vendidos :" />
                    <x-disabled-text :text="$oferta->vendidos" />
                </div>

                <div class="w-full">
                    <x-label value="Máximo stock :" />
                    <x-input class="block w-full" wire:model.defer="oferta.limit" type="number" min="0"
                        step="1" :disabled="$max == 1 ? true : false" />
                    <x-jet-input-error for="oferta.limit" />
                </div>

                <div class="w-full ">
                    <x-label value="Stock disponible :" />
                    <x-disabled-text :text="$oferta->disponible" />
                </div>

                <div class="xs:col-span-2">
                    <x-label-check for="edit_max">
                        <x-input wire:model.lazy="max" name="max" value="1" type="checkbox"
                            id="edit_max" />
                        SELECCIONAR STOCK MÁXIMO DISPONIBLE
                    </x-label-check>
                    <x-jet-input-error for="max" />
                </div>

                <div class="w-full xs:col-span-2 flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('ACTUALIZAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        document.addEventListener("livewire:load", () => {
            Livewire.on("ofertas.confirmDelete", data => {
                swal.fire({
                    title: 'Eliminar oferta del producto con nombre: ' + data.producto.name,
                    text: "Se eliminará un registro de la base de datos.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.emitTo('almacen::ofertas.show-ofertas', 'delete', data.id);
                    }
                })
            });
        })
    </script>
</div>
