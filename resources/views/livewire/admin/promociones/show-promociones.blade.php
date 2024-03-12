<div x-data="data">
    @if ($promociones->hasPages())
        <div class="pt-3 pb-1">
            {{ $promociones->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    @if (mi_empresa()->uselistprice)
        @if (count($pricetypes) > 0)
            <div class="w-full mb-3 max-w-sm">
                <x-label value="Lista precios :" />
                <div id="parentventapricetype_id" class="relative" x-init="selectPricetype" wire:ignore>
                    <x-select class="block w-full" id="ventapricetype_id" x-ref="selectp">
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


    @if (count($promociones) > 0)
        <div class="w-full flex flex-wrap gap-3 relative">
            @foreach ($promociones as $item)
                @php
                    $colortype = $item->isRemate() ? 'text-orange-600' : 'text-red-600';
                    $empresa = mi_empresa();
                    $tipocambio = $empresa->usarDolar() ? $empresa->tipocambio : null;
                @endphp
                <x-simple-card class="w-72 relative flex flex-col gap-2 justify-between">
                    <div class="w-full">
                        @if (count($item->producto->images))
                            <button
                                class="block rounded overflow-hidden w-full h-48 shadow relative hover:shadow-lg cursor-pointer">
                                @if (count($item->producto->defaultImage))
                                    <img src="{{ asset('storage/productos/' . $item->producto->defaultImage->first()->url) }}"
                                        alt="" class="w-full h-full object-cover">
                                @else
                                    <img src="{{ asset('storage/productos/' . $item->producto->images->first()->url) }}"
                                        alt="" class="w-full h-full object-cover">
                                @endif
                            </button>
                        @endif

                        <div class="p-1">
                            <h1 class="text-colorlabel text-[10px] text-center leading-3 mb-2">
                                {{ $item->producto->name }}</h1>

                            {{-- @php

                                if (mi_empresa()->uselistprice) {
                                    if ($precios->existsrango) {
                                        $priceDolar = $precios->pricewithdescountDolar ?? $precios->priceDolar;
                                        $price = !is_null($precios->pricemanual)
                                            ? $precios->pricemanual
                                            : $precios->pricewithdescount ?? $precios->pricesale;
                                    }
                                } else {
                                    $priceDolar = $precios->pricewithdescountDolar ?? $precios->priceDolar;
                                    $price = $precios->pricewithdescount ?? $precios->pricesale;
                                }
                            @endphp --}}

                            @if ($empresa->usarLista())
                                @if (!empty($pricetype_id))
                                    @php
                                        $precios = getPrecio($item->producto, $pricetype_id, $tipocambio)->getData();
                                        if ($item->isDescuento()) {
                                            $price = $precios->pricewithdescount;
                                        } elseif ($item->isRemate()) {
                                            $price = $precios->pricebuy;
                                        } else {
                                            $price = $precios->pricesale;
                                        }
                                        $priceDolar = $precios->pricewithdescountDolar ?? $precios->priceDolar;
                                    @endphp

                                    @if ($precios->existsrango)
                                        @if (count($item->producto->descuentosactivos))
                                            <x-label-price>
                                                S/.
                                                {{ number_format($precios->pricewithdescount, $precios->decimal, '.', ', ') }}
                                                <small>SOLES</small>
                                            </x-label-price>
                                        @else
                                            <x-label-price>
                                                S/.
                                                {{ number_format(!is_null($precios->pricemanual) ? $precios->pricemanual : $precios->pricesale, $precios->decimal, '.', ', ') }}
                                                <small>SOLES</small>
                                            </x-label-price>
                                        @endif
                                    @else
                                        <x-prices-card-product :name="$precios->name">
                                            <div>
                                                <x-span-text text="RANGO DE PRECIO NO DISPONIBLE"
                                                    class="leading-3 !tracking-normal !text-red-500 !bg-red-50 !text-[9px] font-semibold"
                                                    type="red" />
                                            </div>
                                        </x-prices-card-product>
                                    @endif
                                @else
                                    <x-span-text text="SELECCICONAR LISTA DE PRECIOS" class="leading-3 !tracking-normal"
                                        type="red" />
                                @endif
                            @else
                                @php
                                    $precios = getPrecio($item->producto, null, $tipocambio)->getData();
                                    if ($item->isDescuento()) {
                                        $price = $precios->pricewithdescount;
                                    } elseif ($item->isRemate()) {
                                        $price = $precios->pricebuy;
                                    } else {
                                        $price = $precios->pricesale;
                                    }
                                    $priceDolar = $precios->pricewithdescountDolar ?? $precios->priceDolar;
                                @endphp

                                {{-- <div class="w-full flex flex-wrap justify-between gap-2 items-center">
                                    <x-label-price>
                                        S/. {{ number_format($price ?? 0, $precios->decimal, '.', ', ') }}
                                        <small>SOLES</small>
                                    </x-label-price>
                                    @if (count($item->producto->descuentosactivos))
                                        <x-span-text :text="'ANTES : S/. ' .
                                            number_format($precios->pricesale ?? 0, $precios->decimal, '.', ', ')" class="leading-3 !tracking-normal"
                                            type="red" />
                                    @endif
                                </div> --}}
                            @endif

                            {{-- <p class="text-[9px] text-colorlabel">{{ var_dump($precios) }}</p> --}}

                            <div class="w-full">
                                <x-span-text :text="formatDecimalOrInteger($item->outs) . ' VENDIDOS'" class="leading-3 !tracking-normal" />

                                <x-span-text :text="$item->limit > 0
                                    ? 'STOCK MAXIMO : ' .
                                        formatDecimalOrInteger($item->limit) .
                                        ' ' .
                                        $item->producto->unit->name
                                    : 'HASTA AGOTAR STOCK'" class="leading-3 !tracking-normal" />

                                <x-span-text :text="$item->startdate
                                    ? 'FECHA INICIO : ' . formatDate($item->startdate, 'DD MMMM Y')
                                    : 'SIN FECHA INICIO'" class="leading-3 !tracking-normal" />

                                <x-span-text :text="$item->expiredate
                                    ? 'FECHA EXPIRACIÓN : ' . formatDate($item->expiredate, 'DD MMMM Y')
                                    : 'SIN FECHA LÍMITE'" class="leading-3 !tracking-normal" />
                            </div>
                        </div>

                        {{-- ITEMS SECUNDARIOS --}}
                        <div class="w-full flex flex-col gap-1 p-1">
                            @foreach ($item->itempromos as $itempromo)
                                @php
                                    if ($itempromo->isSinDescuento()) {
                                        $typecombo = 'SIN DESCUENTO';
                                        $color = 'orange';
                                    } elseif ($itempromo->isDescuento()) {
                                        $typecombo = formatDecimalOrInteger($itempromo->descuento) . '% DSCT';
                                        $color = 'green';
                                    } else {
                                        $typecombo = 'GRATIS';
                                        $color = 'green';
                                    }
                                @endphp

                                <div class="w-full flex gap-2 bg-body rounded relative">
                                    <div
                                        class="block rounded overflow-hidden flex-shrink-0 w-16 h-16 shadow relative hover:shadow-lg cursor-pointer">
                                        @if (count($itempromo->producto->defaultImage))
                                            <img src="{{ asset('storage/productos/' . $itempromo->producto->defaultImage->first()->url) }}"
                                                alt=""
                                                class="w-full h-full object-scale-down hover:scale-125 hover:object-cover transition duration-300">
                                        @else
                                            <img src="{{ asset('storage/productos/' . $itempromo->producto->images->first()->url) }}"
                                                alt=""
                                                class="w-full h-full object-scale-down hover:scale-125 hover:object-cover transition duration-300">
                                        @endif
                                    </div>
                                    <div class="p-1">
                                        <h1 class="text-colortitleform text-[10px] leading-3">
                                            {{ $itempromo->producto->name }}</h1>

                                        @if ($empresa->usarLista())
                                            @php
                                                $precioscombo = getPrecio(
                                                    $itempromo->producto,
                                                    $pricetype_id,
                                                    $tipocambio,
                                                )->getData();
                                                $priceitem = $precioscombo->pricesale;
                                                $priceDolar = $precioscombo->priceDolar;

                                                if ($itempromo->isDescuento()) {
                                                    $descuentoitem = number_format(
                                                        (($priceitem - $precioscombo->pricebuy) *
                                                            $itempromo->descuento) /
                                                            100,
                                                        $precioscombo->decimal,
                                                        '.',
                                                        '',
                                                    );
                                                    $priceitem = number_format(
                                                        $priceitem - $descuentoitem,
                                                        $precioscombo->decimal,
                                                        '.',
                                                        '',
                                                    );
                                                }

                                                $price = $price + $priceitem;
                                            @endphp

                                            @if ($precioscombo->existsrango)
                                                @if ($itempromo->isDescuento())
                                                    <h1
                                                        class="text-[10px] font-medium text-red-500 mt-1 line-through leading-3">
                                                        S/.
                                                        {{ number_format($precioscombo->pricesale, $precios->decimal, '.', ', ') }}
                                                        <small>SOLES</small>
                                                    </h1>
                                                @endif

                                                <h1 class="text-xs font-semibold text-green-500 mt-1 leading-3">
                                                    S/.
                                                    {{ number_format($priceitem, $precios->decimal, '.', ', ') }}
                                                    <small>SOLES</small>
                                                </h1>
                                            @else
                                                <x-prices-card-product :name="$lista->name">
                                                    <div>
                                                        <x-span-text text="RANGO DE PRECIO NO DISPONIBLE"
                                                            class="leading-3 !tracking-normal !text-red-500 !bg-red-50 !text-[9px] font-semibold"
                                                            type="red" />
                                                    </div>
                                                </x-prices-card-product>
                                            @endif
                                        @else
                                            @php
                                                $precioscombo = getPrecio(
                                                    $itempromo->producto,
                                                    null,
                                                    $tipocambio,
                                                )->getData();
                                                $priceitem = $precioscombo->pricesale;
                                                $priceDolar =
                                                    $precioscombo->pricewithdescountDolar ?? $precioscombo->priceDolar;

                                                if ($itempromo->isDescuento()) {
                                                    $descuentoitem = number_format(
                                                        (($precioscombo->pricesale - $precioscombo->pricebuy) *
                                                            $itempromo->descuento) /
                                                            100,
                                                        $precioscombo->decimal,
                                                        '.',
                                                        '',
                                                    );
                                                    $priceitem = number_format(
                                                        $precioscombo->pricesale - $descuentoitem,
                                                        $precioscombo->decimal,
                                                        '.',
                                                        '',
                                                    );
                                                }

                                                $price = $price + $priceitem;
                                            @endphp

                                            @if ($itempromo->descuento > 0)
                                                <div>
                                                    <x-span-text :text="'ANTES : S/. ' .
                                                        number_format(
                                                            $precioscombo->pricesale ?? 0,
                                                            $precioscombo->decimal,
                                                            '.',
                                                            ', ',
                                                        )"
                                                        class="leading-3 !tracking-normal text-[8px]" type="red" />
                                                </div>
                                            @endif

                                            <x-label-price>
                                                S/.
                                                {{ number_format($priceitem ?? 0, $precioscombo->decimal, '.', ', ') }}
                                                <small>SOLES</small>
                                            </x-label-price>
                                        @endif

                                        {{-- <p>{{ $price = $price + $priceitem }}</p> --}}
                                        {{-- <p class="text-[9px] text-colorlabel">{{ var_dump($precioscombo) }}</p> --}}

                                        <x-span-text :text="$typecombo" :type="$color"
                                            class="leading-3 !tracking-normal absolute right-1 bottom-1" />

                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- @if (($empresa->usarLista() && $precios->existsrango) || ($empresa->usarLista() == false && $precios->pricesale > 0)) --}}
                        <div class="w-full flex flex-col justify-between  items-center">
                            @if (count($item->producto->descuentosactivos))
                                <x-span-text :text="'ANTES : S/. ' .
                                    number_format($precios->pricesale ?? 0, $precios->decimal, '.', ', ')" class="leading-3 !tracking-normal text-[8px]"
                                    type="red" />
                            @endif

                            <x-label-price class="!text-2xl">
                                <small class="text-[10px]">S/.</small>
                                {{ number_format($price ?? 0, $precios->decimal, '.', ', ') }}
                                <small class="text-[10px]">SOLES</small>
                            </x-label-price>
                        </div>
                        {{-- @endif --}}
                    </div>

                    <div
                        class="w-full flex gap-2 items-end {{ $item->isDisponible() ? 'justify-between' : 'justify-end' }}">
                        @if ($item->isDisponible())
                            <button wire:click="desactivar({{ $item->id }})" wire:loading.attr="disabled"
                                type="button"
                                class="block p-0.5 rounded-sm disabled:opacity-75 {{ $item->isActivo() ? 'text-green-500' : 'text-gray-300' }}">
                                <svg class="w-5 h-5 scale-125" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path fill="currentColor"
                                        d="M11 12C11 13.6569 9.65685 15 8 15C6.34315 15 5 13.6569 5 12C5 10.3431 6.34315 9 8 9C9.65685 9 11 10.3431 11 12Z" />
                                    <path
                                        d="M16 6H8C4.68629 6 2 8.68629 2 12C2 15.3137 4.68629 18 8 18H16C19.3137 18 22 15.3137 22 12C22 8.68629 19.3137 6 16 6Z" />
                                </svg>
                            </button>
                        @endif
                        <x-button-delete onclick="confirmDelete({{ $item->id }})" wire:loading.attr="disabled" />
                    </div>

                    <div class="absolute -top-1 -left-1">
                        <span class="w-14 h-14 block relative">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                class="w-full h-full {{ $colortype }}" fill="currentColor" stroke="currentColor"
                                stroke-width="0">
                                <path fill="currentColor"
                                    d="M18.9905 19H19M18.9905 19C18.3678 19.6175 17.2393 19.4637 16.4479 19.4637C15.4765 19.4637 15.0087 19.6537 14.3154 20.347C13.7251 20.9374 12.9337 22 12 22C11.0663 22 10.2749 20.9374 9.68457 20.347C8.99128 19.6537 8.52349 19.4637 7.55206 19.4637C6.76068 19.4637 5.63218 19.6175 5.00949 19C4.38181 18.3776 4.53628 17.2444 4.53628 16.4479C4.53628 15.4414 4.31616 14.9786 3.59938 14.2618C2.53314 13.1956 2.00002 12.6624 2 12C2.00001 11.3375 2.53312 10.8044 3.59935 9.73817C4.2392 9.09832 4.53628 8.46428 4.53628 7.55206C4.53628 6.76065 4.38249 5.63214 5 5.00944C5.62243 4.38178 6.7556 4.53626 7.55208 4.53626C8.46427 4.53626 9.09832 4.2392 9.73815 3.59937C10.8044 2.53312 11.3375 2 12 2C12.6625 2 13.1956 2.53312 14.2618 3.59937C14.9015 4.23907 15.5355 4.53626 16.4479 4.53626C17.2393 4.53626 18.3679 4.38247 18.9906 5C19.6182 5.62243 19.4637 6.75559 19.4637 7.55206C19.4637 8.55858 19.6839 9.02137 20.4006 9.73817C21.4669 10.8044 22 11.3375 22 12C22 12.6624 21.4669 13.1956 20.4006 14.2618C19.6838 14.9786 19.4637 15.4414 19.4637 16.4479C19.4637 17.2444 19.6182 18.3776 18.9905 19Z" />
                            </svg>
                            <h1
                                class="absolute text-colortitleform top-0 left-0 w-full h-full flex flex-col text-center justify-center items-center font-semibold">
                                @if ($item->isDescuento())
                                    <p class="text-xs leading-[0.5rem]">{{ formatDecimalOrInteger($item->descuento) }}
                                        <small class="">%</small>
                                    </p>
                                    <small class="w-full text-[7px]">DSCT</small>
                                @elseif ($item->isCombo())
                                    <small class="w-full text-[10px] leading-[0.6rem]">COM<br />BO</small>
                                @else
                                    <small class="w-full text-[10px] leading-[0.5rem]">REM<br />ATE</small>
                                @endif
                            </h1>
                        </span>
                    </div>

                    {{-- <div class="absolute top-0 left-0">
                        <span class="w-14 h-14 block relative">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                class="w-full h-full {{ $color }}" fill="currentColor" stroke="currentColor"
                                stroke-width="0">
                                <path fill="currentColor"
                                    d="M18.9905 19H19M18.9905 19C18.3678 19.6175 17.2393 19.4637 16.4479 19.4637C15.4765 19.4637 15.0087 19.6537 14.3154 20.347C13.7251 20.9374 12.9337 22 12 22C11.0663 22 10.2749 20.9374 9.68457 20.347C8.99128 19.6537 8.52349 19.4637 7.55206 19.4637C6.76068 19.4637 5.63218 19.6175 5.00949 19C4.38181 18.3776 4.53628 17.2444 4.53628 16.4479C4.53628 15.4414 4.31616 14.9786 3.59938 14.2618C2.53314 13.1956 2.00002 12.6624 2 12C2.00001 11.3375 2.53312 10.8044 3.59935 9.73817C4.2392 9.09832 4.53628 8.46428 4.53628 7.55206C4.53628 6.76065 4.38249 5.63214 5 5.00944C5.62243 4.38178 6.7556 4.53626 7.55208 4.53626C8.46427 4.53626 9.09832 4.2392 9.73815 3.59937C10.8044 2.53312 11.3375 2 12 2C12.6625 2 13.1956 2.53312 14.2618 3.59937C14.9015 4.23907 15.5355 4.53626 16.4479 4.53626C17.2393 4.53626 18.3679 4.38247 18.9906 5C19.6182 5.62243 19.4637 6.75559 19.4637 7.55206C19.4637 8.55858 19.6839 9.02137 20.4006 9.73817C21.4669 10.8044 22 11.3375 22 12C22 12.6624 21.4669 13.1956 20.4006 14.2618C19.6838 14.9786 19.4637 15.4414 19.4637 16.4479C19.4637 17.2444 19.6182 18.3776 18.9905 19Z" />
                            </svg>
                            <small
                                class="absolute text-colortitleform -top-1 left-0 w-full h-full flex text-center text-[10px] justify-center items-center font-semibold leading-3 whitespace-pre-line">

                                @if ($item->type == '0')
                                    {{ formatDecimalOrInteger($item->descuento) }}%<br />DSCT
                                @elseif ($item->type == '1')
                                    COM<br />BO
                                @else
                                    REM<br />ATE
                                @endif

                            </small>
                        </span>
                    </div> --}}
                </x-simple-card>
            @endforeach

            <div wire:loading.flex class="loading-overlay rounded hidden">
                <x-loading-next />
            </div>
        </div>
    @endif

    <script>
        function confirmDelete(promocion_id) {
            swal.fire({
                title: 'Eliminar promoción del producto seleccionado !',
                text: "Se eliminará un registro de la base de datos.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.delete(promocion_id);
                }
            })
        }

        document.addEventListener('alpine:init', () => {
            Alpine.data('data', () => ({
                pricetype_id: @entangle('pricetype_id'),
            }))
        })


        function selectPricetype() {
            this.selectP = $(this.$refs.selectp).select2();
            this.selectP.val(this.pricetype_id).trigger("change");
            this.selectP.on("select2:select", (event) => {
                this.pricetype_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch('pricetype_id', (value) => {
                this.selectP.val(value).trigger("change");
            });
        }
    </script>
</div>
