<x-app-layout>
    <x-slot name="breadcrumb">
        <x-link-breadcrumb text="TIENDA WEB" route="productos">
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                    <path
                        d="M3.06164 15.1933L3.42688 13.1219C3.85856 10.6736 4.0744 9.44952 4.92914 8.72476C5.78389 8 7.01171 8 9.46734 8H14.5327C16.9883 8 18.2161 8 19.0709 8.72476C19.9256 9.44952 20.1414 10.6736 20.5731 13.1219L20.9384 15.1933C21.5357 18.5811 21.8344 20.275 20.9147 21.3875C19.995 22.5 18.2959 22.5 14.8979 22.5H9.1021C5.70406 22.5 4.00504 22.5 3.08533 21.3875C2.16562 20.275 2.4643 18.5811 3.06164 15.1933Z" />
                    <path
                        d="M7.5 8L7.66782 5.98618C7.85558 3.73306 9.73907 2 12 2C14.2609 2 16.1444 3.73306 16.3322 5.98618L16.5 8" />
                    <path d="M15 11C14.87 12.4131 13.5657 13.5 12 13.5C10.4343 13.5 9.13002 12.4131 9 11" />
                </svg>
            </x-slot>
        </x-link-breadcrumb>
        <x-link-breadcrumb text="ESPECIFICACIÓN DEL PRODUCTO" active>
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                    <path
                        d="M13 22C12.1818 22 11.4002 21.6588 9.83691 20.9764C8.01233 20.18 6.61554 19.5703 5.64648 19H2M13 22C13.8182 22 14.5998 21.6588 16.1631 20.9764C20.0544 19.2779 22 18.4286 22 17V6.5M13 22L13 11M4 6.5L4 9.5" />
                    <path
                        d="M9.32592 9.69138L6.40472 8.27785C4.80157 7.5021 4 7.11423 4 6.5C4 5.88577 4.80157 5.4979 6.40472 4.72215L9.32592 3.30862C11.1288 2.43621 12.0303 2 13 2C13.9697 2 14.8712 2.4362 16.6741 3.30862L19.5953 4.72215C21.1984 5.4979 22 5.88577 22 6.5C22 7.11423 21.1984 7.5021 19.5953 8.27785L16.6741 9.69138C14.8712 10.5638 13.9697 11 13 11C12.0303 11 11.1288 10.5638 9.32592 9.69138Z" />
                    <path d="M18.1366 4.01563L7.86719 8.98485" />
                    <path d="M2 13H5" />
                    <path d="M2 16H5" />
                </svg>
            </x-slot>
        </x-link-breadcrumb>
    </x-slot>

    <style>
        figure.zoom img:hover {
            opacity: 0;
        }

        figure.zoom img:not(:hover) {
            background: #fff;
        }
    </style>

    @php
        $image = count($producto->images) ? pathURLProductImage($producto->images->first()->url) : null;
        $pricesale = $producto->getPrecioVenta($pricetype);
        $priceold = $producto->getPrecioVentaDefault($pricetype);
        $promocion_producto = null;

        if ($producto->descuento > 0 || $producto->liquidacion) {
            $promocion_producto = $producto->promocions
                ->where('type', '<>', \App\Enums\PromocionesEnum::COMBO->value)
                ->first();
        }
    @endphp

    <div class="contenedor w-full relative" x-data="showproducto">
        <div class="flex flex-col gap-5" x-data="{ currentImage: '{{ $image }}', showesp: true }">
            <div class="w-full md:flex">
                <div class="w-full md:flex-shrink-0 md:w-[50%] lg:w-[55%] md:px-3 py-2">
                    <div class="w-full max-w-full h-64 xs:h-80 lg:h-[30rem] rounded-lg md:rounded-xl overflow-hidden relative {{ !empty($image) ? 'bg-white' : '' }}"
                        @mouseover="showesp = false" @mouseleave="showesp = true">
                        @if ($image)
                            <template x-if="currentImage">
                                <figure id="imageproducto" x-ref="figure"
                                    class="zoom relative w-full h-full bg-no-repeat object-scale-down overflow-hidden"
                                    :style="'background-image: url(\'' + currentImage + '\');'" @mousemove="zoom"
                                    @mouseover="showesp = false" @mouseleave="showesp = true" @touchmove="zoom">
                                    <img :src="currentImage" x-ref="image"
                                        class="zoom-hover block w-full h-full object-scale-down transition ease-linear duration-300">
                                </figure>
                            </template>

                            <template x-if="!currentImage">
                                <x-icon-image-unknown class="w-full h-full text-colorsubtitleform" />
                            </template>
                        @else
                            <x-icon-image-unknown class="w-full h-full text-colorsubtitleform" />
                        @endif

                        @if ($producto->marca)
                            @if ($producto->marca->image && $empresa->verLogomarca())
                                <div class="absolute top-1 right-1 w-28 h-12 rounded overflow-hidden">
                                    <img src="{{ getMarcaURL($producto->marca->image->url) }}"
                                        class="block w-full h-full object-scale-down">
                                </div>
                            @endif
                        @endif

                        @if ($producto->descuento > 0 || $producto->liquidacion)
                            <div class="w-auto h-auto bg-red-600 absolute -left-10 top-7 -rotate-[35deg] leading-none">
                                <p class="text-white text-sm block font-medium py-2.5 px-20 tracking-widest">
                                    @if ($empresa->isTitlePromocion())
                                        PROMOCIÓN
                                    @elseif($empresa->isTitleLiquidacion())
                                        LIQUIDACIÓN
                                    @else
                                        @if ($producto->descuento > 0)
                                            - {{ decimalOrInteger($producto->descuento) }}% DSCT
                                        @else
                                            LIQUIDACIÓN
                                        @endif
                                    @endif
                                </p>
                            </div>
                        @endif

                        @if ($producto->verEspecificaciones())
                            @if (count($producto->especificacions) > 0)
                                <ul class="text-white py-3 text-[10px] absolute left-1 top-20 hidden md:flex flex-col gap-2 justify-start items-start"
                                    x-cloak x-show="showesp">
                                    @foreach ($producto->especificacions->take(5) as $item)
                                        <li class="badge-especificacion p-1 bg-gray-500 rounded-xl text-[9px] max-w-36">
                                            <span class="font-medium inline-block">
                                                {{ $item->caracteristica->name }} : </span>
                                            {{ $item->name }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        @endif
                    </div>

                    @if ($image)
                        <div class="w-full relative px-10 mt-2">
                            <button id="previusthumbnail" @click="changeImage('prev')"
                                class="absolute text-colorsubtitleform top-1/2 left-0 -translate-y-1/2 h-10 w-6 shadow flex items-center justify-center disabled:opacity-25">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" class="w-6 h-6 block">
                                    <path d="M15 7L10 12L15 17" />
                                </svg>
                            </button>
                            <div class="w-full flex gap-1 overflow-hidden p-1" id="imagethumbs">
                                @foreach ($producto->images as $item)
                                    <div class="w-20 h-20 flex-shrink-0 border-2 thumbnail cursor-pointer bg-white rounded-md"
                                        :class="{
                                            'ring-1 border-primary ring-primary active': currentImage ==
                                                '{{ pathURLProductImage($item->url) }}',
                                            'hover:ring-1 border-borderminicard ring-borderminicard opacity-70': currentImage !=
                                                '{{ pathURLProductImage($item->url) }}'
                                        }">
                                        <img class="w-full h-full object-scale-down object-center"
                                            src="{{ pathURLProductImage($item->url) }}"
                                            @click="currentImage = '{{ pathURLProductImage($item->url) }}'">
                                    </div>
                                @endforeach
                            </div>
                            <button id="nextthumbnail" @click="changeImage('next')"
                                class="absolute text-colorsubtitleform top-1/2 right-0 -translate-y-1/2 h-10 w-6 shadow flex items-center justify-center disabled:opacity-25">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" class="w-6 h-6 block">
                                    <path d="M10 7L15 12L10 17" />
                                </svg>
                            </button>
                        </div>
                    @endif
                </div>

                <div class="w-full flex-1 sm:py-2 px-1 md:pl-8">
                    <div class="w-full md:border-b border-b-borderminicard pb-2 md:pb-5">
                        @if ($producto->isNovedad())
                            <div class="inline-block">
                                @if (!empty($empresa->textnovedad))
                                    <span class="span-novedad p-1.5 px-2">
                                        {{ $empresa->textnovedad }}</span>
                                @endif
                                <x-icon-novedad class="size-6" />
                            </div>
                        @endif


                        <p class="text-[10px] text-colorsubtitleform font-medium">
                            {{ $producto->category->name }} | {{ $producto->subcategory->name }}</p>
                        <div class="w-full flex gap-2 justify-between items-center flex-wrap">
                            <div>
                                <p class="text-colorsubtitleform font-semibold">
                                    {{ $producto->marca->name }}</p>
                            </div>
                            <div class="text-[10px] text-colorsubtitleform flex gap-3">
                                @if (!empty($producto->partnumber))
                                    <span>N° PARTE: {{ $producto->partnumber }}</span>
                                @endif

                                @if (!empty($producto->sku))
                                    <span>SKU: {{ $producto->sku }}</span>
                                @endif
                            </div>
                        </div>
                        <p class="text-colorlabel text-[10px] leading-3 xs:text-sm xs:leading-5">
                            {{ $producto->name }}</p>
                        @if (!empty($producto->modelo))
                            <span class="text-[10px] text-colorsubtitleform inline-block">
                                MODELO : {{ $producto->modelo }}</span>
                        @endif
                    </div>

                    <div class="w-full p-1 sm:p-0 fixed sm:relative bottom-0 left-0 bg-body z-[299] sm:z-0 sm:block">
                        <div class="w-full sm:pt-3 flex items-center justify-between gap-2">
                            @if ($pricesale > 0)
                                <div class="w-full flex-1">
                                    <h1
                                        class="font-semibold text-2xl sm:text-3xl text-center md:text-start text-colorlabel">
                                        <small class="text-lg"> {{ $moneda->simbolo }}</small>
                                        {{ decimalOrInteger($pricesale, 2, ', ') }}
                                    </h1>

                                    @if ($priceold > $pricesale && $empresa->verOldprice())
                                        <h1
                                            class="text-colorsubtitleform text-[10px] sm:text-xs text-red-600 text-center md:text-start !leading-none">
                                            {{ $moneda->simbolo }}
                                            <small class="text-sm sm:text-lg inline-block line-through">
                                                {{ decimalOrInteger($priceold, 2, ', ') }}</small>
                                        </h1>
                                    @endif

                                    @if ($empresa->verDolar())
                                        <h1 class="text-blue-700 font-medium text-xs text-center md:text-start">
                                            <small class="text-[10px]">$. </small>
                                            {{ convertMoneda($pricesale, 'USD', $empresa->tipocambio, 2, ', ') }}
                                            <small class="text-[10px]">USD</small>
                                        </h1>
                                    @endif
                                </div>
                                @auth
                                    <div class="flex-shrink-0">
                                        @php
                                            $favoritos = Cart::instance('wishlist')->content()->pluck('id')->toArray();
                                            // $favoritos = array_column($wishlist, 'id');
                                        @endphp
                                        <x-button-like
                                            class="{{ in_array($producto->id, $favoritos) ? 'activo' : 'bg-body' }}"
                                            wire:loading.attr="disabled"
                                            onclick="addfavoritos(this, '{{ encryptText($producto->id) }}')" />
                                    </div>
                                @endauth
                            @else
                                <p class="w-full flex-1 text-colorerror text-[10px] font-semibold text-center">
                                    PRECIO DE VENTA NO ENCONTRADO</p>
                            @endif
                        </div>

                        @if ($producto->stock > 0)
                            @if ($pricesale > 0)
                                <div class="w-full flex items-center gap-1 justify-end mt-1 sm:mt-5"
                                    x-data="{ qty: 1 }">
                                    <div class="w-full flex-1 flex justify-center xl:justify-start gap-0.5 gap-x-1">
                                        <template x-if="parseFloat(qty)>1">
                                            <button x-on:click="parseFloat(qty--)" class="btn-increment-cart"
                                                type="button" wire:loading.attr="disabled"
                                                :key="{{ $item->id }}">-</button>
                                        </template>
                                        <template x-if="parseFloat(qty)==1">
                                            <span class="btn-increment-cart disabled"
                                                :key="{{ rand() }}">-</span>
                                        </template>

                                        <x-input x-model="qty"
                                            class="w-20 rounded-xl text-center text-colorlabel input-number-none"
                                            type="number" step="1" min="1"
                                            onpaste="return validarPasteNumero(event, 12)"
                                            onkeypress="return validarNumero(event, 5)"
                                            x-on:blur="if (!qty || qty === '0') qty = '1'" />

                                        <button class="btn-increment-cart" x-on:click="parseFloat(qty++)"
                                            type="button" wire:loading.attr="disabled">+</button>
                                    </div>

                                    <x-button-add-car type="button"
                                        x-on:click="addproductocart('{{ encryptText($producto->id) }}', '{{ $promocion_producto ? encryptText($promocion_producto->id) : null }}', qty)"
                                        class="px-2.5 !flex gap-1 items-center text-xs flex-shrink-0"
                                        classIcon="!w-6 !h-6">AGREGAR</x-button-add-car>
                                </div>

                                @if ($promocion_producto)
                                    <p
                                        class="w-full text-center pt-1 xs:pt-0 xs:text-justify text-xs leading-none text-colorsubtitleform">
                                        Promoción válida hasta agotar stock.
                                        <br>
                                        [{{ decimalOrInteger($promocion_producto->limit - $promocion_producto->outs) }}
                                        {{ $producto->unit->name }}] disponibles

                                        @if (!empty($promocion_producto->expiredate))
                                            <br>
                                            @if ($promocion_producto->expiredate)
                                                Promoción válida hasta el
                                                {{ formatDate($promocion_producto->expiredate, 'DD MMMM Y') }}
                                            @endif
                                        @endif
                                    </p>
                                @endif
                            @endif
                        @else
                            <div class="flex justify-end">
                                <span class="inline-block p-1 rounded-lg bg-red-600 text-white text-[10px]">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" fill="currentColor"
                                        stroke="currentColor" stroke-width="0.7" stroke-linecap="round"
                                        stroke-linejoin="round" class="inline-block w-4 h-4">
                                        <path
                                            d="m13.58,16.14c-2.68-5.62-.35-11.07,3.31-13.8,3.93-2.94,9.38-3.15,13.53-.47,1.56,1.01,2.8,2.31,3.71,3.9.92,1.6,1.4,3.33,1.44,5.16.05,1.81-.37,3.53-1.19,5.2,1.38.67,2.69,1.38,4.05,1.95,1.82.76,3.34,1.86,4.69,3.26,1.41,1.48,2.94,2.87,4.41,4.29.7.68.61,1.19-.28,1.63-1.81.88-3.62,1.77-5.45,2.63-.32.15-.44.32-.44.68.02,2.79,0,5.57.02,8.36,0,.56-.2.89-.72,1.14-5.35,2.58-10.69,5.16-16.02,7.76-.46.23-.86.2-1.31-.02-5.34-2.6-10.68-5.18-16.02-7.76-.48-.23-.69-.54-.68-1.06.01-2.79,0-5.57.02-8.36,0-.38-.1-.59-.47-.76-1.83-.86-3.64-1.75-5.45-2.63-.85-.41-.94-.93-.26-1.6,2.06-2,4.12-3.99,6.18-5.98.2-.2.45-.36.71-.49,1.9-.93,3.81-1.85,5.72-2.78.17-.08.33-.17.52-.27Zm-5.26,14.82c-.02.09-.04.13-.04.18,0,2.43,0,4.87.01,7.3,0,.16.18.38.34.46,5.01,2.44,10.02,4.87,15.04,7.28.18.08.47.08.65,0,5.01-2.41,10.01-4.83,15.01-7.24.29-.14.38-.31.38-.62-.01-2.32,0-4.64,0-6.95,0-.12-.02-.25-.03-.41-.21.1-.36.16-.52.24-2.65,1.28-5.29,2.56-7.94,3.84-.66.32-.89.28-1.41-.23-1.8-1.75-3.6-3.49-5.41-5.24-.13-.13-.27-.24-.47-.43-.14.17-.25.32-.38.45-1.79,1.74-3.58,3.47-5.37,5.2-.55.53-.77.56-1.46.23-2.63-1.27-5.26-2.55-7.89-3.82-.16-.08-.33-.15-.52-.23ZM24.01,1.6c-5.41-.03-9.9,4.26-9.94,9.49-.04,5.33,4.37,9.7,9.81,9.72,5.56.03,10.02-4.24,10.04-9.59.02-5.29-4.42-9.6-9.91-9.62Zm-1.5,26.63c-.06-.04-.14-.09-.23-.14-4.78-2.32-9.57-4.63-14.34-6.95-.29-.14-.42-.05-.61.13-1.61,1.57-3.24,3.14-4.85,4.71-.07.06-.12.14-.21.24,5.01,2.42,9.97,4.82,14.88,7.2,1.81-1.75,3.58-3.47,5.37-5.19Zm8.35,5.19c4.91-2.38,9.88-4.78,14.9-7.21-1.71-1.66-3.38-3.25-5.02-4.87-.27-.27-.47-.31-.82-.14-3.87,1.89-7.74,3.76-11.62,5.63-.95.46-1.9.92-2.83,1.38,1.8,1.74,3.57,3.45,5.39,5.21Zm7.82-13.4c-1.78-.86-3.45-1.67-5.11-2.47-4.8,6.58-14.71,6.38-19.17,0-1.66.8-3.33,1.61-5.07,2.45.17.1.25.15.34.19,4.68,2.26,9.35,4.53,14.03,6.78.17.08.47.05.65-.03,3.01-1.44,6.01-2.89,9.01-4.34,1.74-.84,3.47-1.68,5.32-2.58Z" />
                                        <path
                                            d="m24.04,12.31c-1.42,1.37-2.75,2.65-4.07,3.94-.11.11-.21.22-.33.32-.4.33-.88.32-1.2-.01-.31-.32-.31-.77.03-1.13.51-.52,1.05-1.02,1.57-1.52.91-.89,1.82-1.77,2.77-2.7-.13-.13-.25-.27-.38-.39-1.29-1.25-2.59-2.5-3.87-3.75-.43-.42-.46-.89-.1-1.23.36-.34.84-.32,1.27.1,1.28,1.23,2.56,2.48,3.83,3.72.13.13.24.28.38.45.22-.2.37-.33.51-.47,1.25-1.21,2.51-2.43,3.76-3.65.47-.45.94-.51,1.31-.17.39.35.34.84-.14,1.31-1.39,1.35-2.77,2.69-4.2,4.07.14.14.26.27.39.4,1.22,1.18,2.44,2.36,3.66,3.54.09.08.17.16.25.25.38.41.41.87.06,1.2-.35.33-.83.32-1.24-.07-1.15-1.1-2.29-2.21-3.43-3.33-.28-.27-.54-.56-.84-.87Z" />
                                    </svg>
                                    AGOTADO
                                </span>
                            </div>
                        @endif
                    </div>

                    @if ($producto->promocions->where('type', \App\Enums\PromocionesEnum::COMBO->value)->count() > 0)
                        <div class="w-full flex justify-center xl:justify-end mt-2">
                            <a class="text-primary inline-flex gap-3 md:gap-6 items-center text-xs font-medium p-3 rounded-xl shadow-md shadow-shadowminicard"
                                href="#combos">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" fill="currentColor"
                                    stroke="currentColor" stroke-width=".5" class="block w-6 h-6 text-primary">
                                    <path
                                        d="m19.534 11.063.88-1.253L24 4.709c.21-.297.418-.599.64-.887a1 1 0 0 1 .576-.411 1.28 1.28 0 0 1 1.065.224l2.336 1.717 3.694 2.715 4.071 2.994q1.371 1.006 2.738 2.016a.9.9 0 0 0 .562.178c.464-.002.93-.025 1.39.025 1.611.183 2.798 1.481 2.896 2.891q.082 1.207.174 2.414c.114 1.513.238 3.022.343 4.533.082 1.131.142 2.261.219 3.39.069 1.01.151 2.021.226 3.031l.217 2.944q.091 1.211.171 2.43.119 1.81.215 3.625c.041.791.053 1.582.089 2.373.021.443.069.882.091 1.326q.009.336-.034.672a7 7 0 0 1-.142.768 2.22 2.22 0 0 1-.974 1.342c-.64.434-1.351.686-2.135.688q-2.011.005-4.025 0l-10.745.005H15.223c-3.129 0-6.256-.009-9.385.005a3.7 3.7 0 0 1-2.528-.901c-.759-.654-1.074-1.495-1.017-2.482q.062-1.031.133-2.062c.053-.754.114-1.511.167-2.267q.039-.613.069-1.223l.133-2.24q.064-1.243.137-2.489.069-1.193.153-2.386l.169-2.443q.105-1.495.199-2.99c.057-.928.103-1.856.16-2.784q.126-2.011.258-4.023c.03-.45.03-.907.096-1.353.185-1.255.894-2.11 2.066-2.59q.64-.256 1.328-.235c.439.009.88-.002 1.319.005.133 0 .174-.037.174-.174q-.009-2.56-.009-5.125c0-1.509.016-3.022-.002-4.533-.009-.663.443-1.12 1.109-1.129q1.063-.01 2.13-.007l6.245.007c.279 0 .539.053.766.224a.91.91 0 0 1 .389.667q.023.183.025.366v7.504zm4.537 32.482h18.087c.233 0 .469 0 .693-.041.491-.094.882-.633.843-1.131l-.032-.265q-.151-2.002-.293-4.002c-.075-1.125-.139-2.249-.21-3.374q-.089-1.474-.185-2.949c-.069-.976-.155-1.95-.222-2.926-.096-1.429-.181-2.857-.274-4.286l-.261-3.961-.304-4.418c-.034-.48-.423-.93-.907-.965-.539-.037-1.083-.016-1.623-.011-.263 0-.523.032-.784.03-.24-.002-.48-.046-.72-.046q-9.029-.007-18.057 0c-.315 0-.629.046-.944.064-.215.011-.43.023-.645.011-.295-.016-.587-.075-.882-.075q-3.271-.007-6.54-.005a2 2 0 0 0-.279.018 4.6 4.6 0 0 1-1.131.023 18 18 0 0 0-2.039-.027 1.2 1.2 0 0 0-1.143 1.127q-.082 1.287-.176 2.576l-.265 3.845-.265 4.034-.208 3.093q-.133 1.874-.261 3.755c-.085 1.287-.16 2.571-.245 3.858l-.315 4.699a1.37 1.37 0 0 0 .094.722c.229.455.608.626 1.099.624l7.623.002h10.775ZM10.997 13.236q.069.009.13.011h6.069c.107 0 .13-.034.13-.137l.005-2.805q0-2.841.009-5.685c0-.139-.037-.183-.183-.183q-3.003.007-6.009.005l-.151.007zm9.653 0h15.054l-9.897-7.278z" />
                                    <path
                                        d="m19.534 11.063.88-1.253L24 4.709c.21-.297.418-.599.64-.887a1 1 0 0 1 .576-.411 1.28 1.28 0 0 1 1.065.224l2.336 1.717 3.694 2.715 4.071 2.994q1.371 1.006 2.738 2.016a.9.9 0 0 0 .562.178c.464-.002.93-.025 1.39.025 1.611.183 2.798 1.481 2.896 2.891q.082 1.207.174 2.414c.114 1.513.238 3.022.343 4.533.082 1.131.142 2.261.219 3.39.069 1.01.151 2.021.226 3.031l.217 2.944q.091 1.211.171 2.43.119 1.81.215 3.625c.041.791.053 1.582.089 2.373.021.443.069.882.091 1.326q.009.336-.034.672a7 7 0 0 1-.142.768 2.22 2.22 0 0 1-.974 1.342c-.64.434-1.351.686-2.135.688q-2.011.005-4.025 0l-10.745.005H15.223c-3.129 0-6.256-.009-9.385.005a3.7 3.7 0 0 1-2.528-.901c-.759-.654-1.074-1.495-1.017-2.482q.062-1.031.133-2.062c.053-.754.114-1.511.167-2.267q.039-.613.069-1.223l.133-2.24q.064-1.243.137-2.489.069-1.193.153-2.386l.169-2.443q.105-1.495.199-2.99c.057-.928.103-1.856.16-2.784q.126-2.011.258-4.023c.03-.45.03-.907.096-1.353.185-1.255.894-2.11 2.066-2.59q.64-.256 1.328-.235c.439.009.88-.002 1.319.005.133 0 .174-.037.174-.174q-.009-2.56-.009-5.125c0-1.509.016-3.022-.002-4.533-.009-.663.443-1.12 1.109-1.129q1.063-.01 2.13-.007l6.245.007c.279 0 .539.053.766.224a.91.91 0 0 1 .389.667q.023.183.025.366v7.504zm4.537 32.482h18.087c.233 0 .469 0 .693-.041.491-.094.882-.633.843-1.131l-.032-.265q-.151-2.002-.293-4.002c-.075-1.125-.139-2.249-.21-3.374q-.089-1.474-.185-2.949c-.069-.976-.155-1.95-.222-2.926-.096-1.429-.181-2.857-.274-4.286l-.261-3.961-.304-4.418c-.034-.48-.423-.93-.907-.965-.539-.037-1.083-.016-1.623-.011-.263 0-.523.032-.784.03-.24-.002-.48-.046-.72-.046q-9.029-.007-18.057 0c-.315 0-.629.046-.944.064-.215.011-.43.023-.645.011-.295-.016-.587-.075-.882-.075q-3.271-.007-6.54-.005a2 2 0 0 0-.279.018 4.6 4.6 0 0 1-1.131.023 18 18 0 0 0-2.039-.027 1.2 1.2 0 0 0-1.143 1.127q-.082 1.287-.176 2.576l-.265 3.845-.265 4.034-.208 3.093q-.133 1.874-.261 3.755c-.085 1.287-.16 2.571-.245 3.858l-.315 4.699a1.37 1.37 0 0 0 .094.722c.229.455.608.626 1.099.624l7.623.002h10.775ZM10.997 13.236q.069.009.13.011h6.069c.107 0 .13-.034.13-.137l.005-2.805q0-2.841.009-5.685c0-.139-.037-.183-.183-.183q-3.003.007-6.009.005l-.151.007zm9.653 0h15.054l-9.897-7.278z" />
                                    <path
                                        d="M32.923 22.329c.19-.274.432-.48.773-.521l-19.177-.261a1.44 1.44 0 0 0-.841.046 1.2 1.2 0 0 0-.608.558 1.44 1.44 0 0 0 .023 1.506c.672 1.111 1.586 1.982 2.619 2.706 1.559 1.09 3.31 1.696 5.134 2.041a16 16 0 0 0 2.846.265v.078l.37-.032q.386-.03.777-.055a29 29 0 0 0 1.831-.169 13.7 13.7 0 0 0 3.671-.992c1.552-.67 2.914-1.6 4.007-2.923q.496-.603.791-1.326a1.05 1.05 0 0 0-.037-.937 1.65 1.65 0 0 0-.869-.777 1.4 1.4 0 0 0-.576-.069 1.42 1.42 0 0 0-1.015.667m.281.194-.281-.194m.281.194-.144.215a7 7 0 0 1-.389.544zm-.281-.194-.16.235a5 5 0 0 1-.354.496m.514-.731-.514.731m0 0c-.754.896-1.705 1.531-2.789 1.995zm-10.665 2.762a16.7 16.7 0 0 0 3.801.16 12.1 12.1 0 0 0 4.071-.926zm0 0c-1.378-.231-2.681-.645-3.854-1.378zm-3.854-1.378a7.3 7.3 0 0 1-2.233-2.128zm-2.233-2.13a1.33 1.33 0 0 0-.859-.571z" />
                                </svg>
                                <span class="flex-1 block tracking-tighter">VER COMBOS</span>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-miterlimit="16"
                                    stroke-linecap="round" stroke-linejoin="round" class="block w-6 h-6">
                                    <path d="M5.99977 9.00005L11.9998 15L17.9998 9" />
                                </svg>
                            </a>
                        </div>
                    @endif

                    {{-- @if (count($producto->especificacions) > 0)
                        <div class="w-full hidden sm:block mt-2">
                            <ul class="w-full pt-3 text-colorsubtitleform text-[10px]">
                                @foreach ($producto->especificacions->take(3) as $item)
                                    <li class="py-0.5">
                                        <span class="font-semibold">
                                            {{ $item->caracteristica->name }} :
                                        </span>{{ $item->name }}
                                    </li>
                                @endforeach
                            </ul>

                            <a href="#especificacions" class="underline pt-3 text-colortitleform text-[10px]">
                                VER MÁS ESPECIFICACIONES</a>
                        </div>
                    @endif --}}

                    @if (count($shipmenttypes) > 0)
                        <div class="w-full grid grid-cols-[repeat(auto-fill,minmax(220px,1fr))] gap-2 mt-2">
                            @foreach ($shipmenttypes as $item)
                                <div
                                    class="w-full border border-borderminicard rounded-lg xl:rounded-xl text-colorlabel p-2">
                                    <div class="w-full flex items-end gap-2">
                                        <span class="block w-6 md:w-10 h-6 md:h-10 flex-shrink-0">
                                            @if ($item->isEnviodomicilio())
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 33"
                                                    fill-rule="evenodd" clip-rule="evenodd" fill="currentColor"
                                                    class="w-full h-full">
                                                    <path
                                                        d="M3 7.5C3 6.39543 3.89543 5.5 5 5.5H17C18.1046 5.5 19 6.39543 19 7.5V10.5H24.4338C25.1363 10.5 25.7873 10.8686 26.1488 11.471L28.715 15.748C28.9015 16.0588 29 16.4145 29 16.777V22.5C29 23.6046 28.1046 24.5 27 24.5H25.874C25.4299 26.2252 23.8638 27.5 22 27.5C20.0283 27.5 18.3898 26.0734 18.0604 24.1961C17.753 24.3887 17.3895 24.5 17 24.5H12.874C12.4299 26.2252 10.8638 27.5 9 27.5C7.12577 27.5 5.55261 26.211 5.1187 24.4711C3.91896 24.2875 3 23.2511 3 22V21.5C3 20.9477 3.44772 20.5 4 20.5C4.55228 20.5 5 20.9477 5 21.5V22C5 22.1459 5.06252 22.2773 5.16224 22.3687C5.65028 20.7105 7.18378 19.5 9 19.5C10.8638 19.5 12.4299 20.7748 12.874 22.5H17V16.5V7.5H5V8.5C5 9.05228 4.55228 9.5 4 9.5C3.44772 9.5 3 9.05228 3 8.5V7.5ZM19 15.5V12.5H24.4338L26.2338 15.5H19ZM19 17.5H27V22.5H25.874C25.4299 20.7748 23.8638 19.5 22 19.5C20.8053 19.5 19.7329 20.0238 19 20.8542V17.5ZM22 21.5C23.1046 21.5 24 22.3954 24 23.5C24 24.6046 23.1046 25.5 22 25.5C20.8954 25.5 20 24.6046 20 23.5C20 22.3954 20.8954 21.5 22 21.5ZM7 23.5C7 24.6046 7.89543 25.5 9 25.5C10.1046 25.5 11 24.6046 11 23.5C11 22.3954 10.1046 21.5 9 21.5C7.89543 21.5 7 22.3954 7 23.5ZM2 10.5C1.44772 10.5 1 10.9477 1 11.5C1 12.0523 1.44772 12.5 2 12.5H7C7.55228 12.5 8 12.0523 8 11.5C8 10.9477 7.55228 10.5 7 10.5H2ZM3 13.5C2.44772 13.5 2 13.9477 2 14.5C2 15.0523 2.44772 15.5 3 15.5H7C7.55228 15.5 8 15.0523 8 14.5C8 13.9477 7.55228 13.5 7 13.5H3ZM3 17.5C3 16.9477 3.44772 16.5 4 16.5H7C7.55229 16.5 8 16.9477 8 17.5C8 18.0523 7.55229 18.5 7 18.5H4C3.44772 18.5 3 18.0523 3 17.5Z" />
                                                </svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 33"
                                                    fill-rule="evenodd" clip-rule="evenodd" fill="currentColor"
                                                    class="w-full h-full">
                                                    <path
                                                        d="M18.4449 14.2024C19.4296 12.8623 20 11.5761 20 10.5C20 8.29086 18.2091 6.5 16 6.5C13.7909 6.5 12 8.29086 12 10.5C12 11.5761 12.5704 12.8623 13.5551 14.2024C14.3393 15.2698 15.2651 16.2081 16 16.8815C16.7349 16.2081 17.6607 15.2698 18.4449 14.2024ZM16.8669 18.7881C18.5289 17.3455 22 13.9227 22 10.5C22 7.18629 19.3137 4.5 16 4.5C12.6863 4.5 10 7.18629 10 10.5C10 13.9227 13.4712 17.3455 15.1331 18.7881C15.6365 19.2251 16.3635 19.2251 16.8669 18.7881ZM5 11.5H8.27078C8.45724 12.202 8.72804 12.8724 9.04509 13.5H5V26.5H10.5V22C10.5 21.4477 10.9477 21 11.5 21H20.5C21.0523 21 21.5 21.4477 21.5 22V26.5H27V13.5H22.9549C23.272 12.8724 23.5428 12.202 23.7292 11.5H27C28.1046 11.5 29 12.3954 29 13.5V26.5C29.5523 26.5 30 26.9477 30 27.5C30 28.0523 29.5523 28.5 29 28.5H3C2.44772 28.5 2 28.0523 2 27.5C2 26.9477 2.44772 26.5 3 26.5V13.5C3 12.3954 3.89543 11.5 5 11.5ZM19.5 23V26.5H12.5V23H19.5ZM17 10.5C17 11.0523 16.5523 11.5 16 11.5C15.4477 11.5 15 11.0523 15 10.5C15 9.94772 15.4477 9.5 16 9.5C16.5523 9.5 17 9.94772 17 10.5ZM19 10.5C19 12.1569 17.6569 13.5 16 13.5C14.3431 13.5 13 12.1569 13 10.5C13 8.84315 14.3431 7.5 16 7.5C17.6569 7.5 19 8.84315 19 10.5Z" />
                                                </svg>
                                            @endif
                                        </span>
                                        <h1 class="w-full flex-1 font-semibold text-xs md:text-sm">{{ $item->name }}
                                        </h1>
                                    </div>
                                    <p class="w-full block text-[10px] leading-tight text-colorsubtitleform mt-1">
                                        {{ $item->descripcion }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if ($empresa->viewAlmacensDetalle())
                        @if (count($producto->almacens) > 0)
                            <div class="w-full flex flex-wrap gap-2 pt-2 md:pt-5">
                                @foreach ($producto->almacens as $item)
                                    <x-minicard :title="null" size="sm">
                                        <small class="text-[9px]">STOCK</small>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 inline-block mx-auto"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                                            <path
                                                d="M13 22C12.1818 22 11.4002 21.6588 9.83691 20.9764C8.01233 20.18 6.61554 19.5703 5.64648 19H2M13 22C13.8182 22 14.5998 21.6588 16.1631 20.9764C20.0544 19.2779 22 18.4286 22 17V6.5M13 22L13 11M4 6.5L4 9.5" />
                                            <path
                                                d="M9.32592 9.69138L6.40472 8.27785C4.80157 7.5021 4 7.11423 4 6.5C4 5.88577 4.80157 5.4979 6.40472 4.72215L9.32592 3.30862C11.1288 2.43621 12.0303 2 13 2C13.9697 2 14.8712 2.4362 16.6741 3.30862L19.5953 4.72215C21.1984 5.4979 22 5.88577 22 6.5C22 7.11423 21.1984 7.5021 19.5953 8.27785L16.6741 9.69138C14.8712 10.5638 13.9697 11 13 11C12.0303 11 11.1288 10.5638 9.32592 9.69138Z" />
                                            <path d="M18.1366 4.01563L7.86719 8.98485" />
                                            <path d="M2 13H5" />
                                            <path d="M2 16H5" />
                                        </svg>
                                        <span class="text-[10px] text-center font-bold">
                                            {{ $item->name }}</span>
                                    </x-minicard>
                                @endforeach
                            </div>
                        @endif
                    @endif

                    @if (count($producto->garantiaproductos) > 0)
                        <div class="w-full block pt-3" x-data="{ opengarantias: true }">
                            <button class="flex gap-1 items-center text-xs py-2"
                                @click="opengarantias = !opengarantias">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    color="currentColor" fill="none"
                                    class="block w-6 h-6 text-colortitleform flex-shrink-0">
                                    <path
                                        d="M11.9982 2C8.99043 2 7.04018 4.01899 4.73371 4.7549C3.79589 5.05413 3.32697 5.20374 3.1372 5.41465C2.94743 5.62556 2.89186 5.93375 2.78072 6.55013C1.59143 13.146 4.1909 19.244 10.3903 21.6175C11.0564 21.8725 11.3894 22 12.0015 22C12.6135 22 12.9466 21.8725 13.6126 21.6175C19.8116 19.2439 22.4086 13.146 21.219 6.55013C21.1078 5.93364 21.0522 5.6254 20.8624 5.41449C20.6726 5.20358 20.2037 5.05405 19.2659 4.75499C16.9585 4.01915 15.0061 2 11.9982 2Z" />
                                    <path d="M9 13C9 13 10 13 11 15C11 15 14.1765 10 17 9" />
                                </svg>
                                <h1 class="font-medium text-colortitleform text-xs sm:text-sm flex-1 w-full">
                                    GARANTÍA DEL PRODUCTO</h1>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="block flex-shrink-0 w-6 h-6 text-colortitleform duration-200"
                                    :class="!opengarantias ? ' -rotate-180' : ' rotate-0'">
                                    <path d="M5.99977 9.00005L11.9998 15L17.9998 9" />
                                </svg>
                            </button>
                            <div class="w-full pt-1 flex flex-wrap gap-2" x-cloak x-show="opengarantias" x-transition>
                                @foreach ($producto->garantiaproductos as $item)
                                    <div
                                        class="size-24 rounded-xl md:rounded-2xl border border-green-500 p-2 flex flex-col justify-center items-center gap-1">
                                        <p class="text-xs text-green-600 text-center leading-tight">
                                            {{ $item->typegarantia->name }}</p>
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                            stroke-width="1" stroke-linecap="round" stroke="currentColor"
                                            class="block w-8 sm:w-10 h-8 sm:h-10 mx-auto text-green-600">
                                            <path opacity="0.5" fill-rule="evenodd" clip-rule="evenodd"
                                                d="M21 8.28029V11.1833C21 16.8085 15.9372 20.1835 13.406 21.5194C12.7989 21.8398 12.4954 22 12 22C11.5046 22 11.2011 21.8398 10.594 21.5194C8.06277 20.1835 3 16.8085 3 11.1833V8.28029C3 6.64029 3 5.82028 3.40411 5.28529C3.80822 4.75029 4.72192 4.49056 6.54932 3.9711C7.79783 3.6162 8.89839 3.18863 9.77771 2.79829C10.9766 2.2661 11.576 2 12 2C12.424 2 13.0234 2.2661 14.2223 2.79829C15.1016 3.18863 16.2022 3.6162 17.4507 3.9711C19.2781 4.49056 20.1918 4.75029 20.5959 5.28529C21 5.82028 21 6.64029 21 8.28029ZM13.126 10.1279H11.376C10.5606 10.1279 10.1529 10.1279 9.83128 10.2706C9.40248 10.4609 9.06181 10.8259 8.88419 11.2854C8.75098 11.6299 8.75098 12.0668 8.75098 12.9404C8.75098 13.814 8.75098 14.2509 8.88419 14.5954C9.06181 15.0549 9.40248 15.4199 9.83128 15.6102C10.1529 15.7529 10.5606 15.7529 11.376 15.7529H13.126C13.9414 15.7529 14.3491 15.7529 14.6707 15.6102C15.0995 15.4199 15.4402 15.0549 15.6178 14.5954C15.751 14.2509 15.751 13.814 15.751 12.9404C15.751 12.0669 15.751 11.6299 15.6178 11.2854C15.4402 10.8259 15.0995 10.4609 14.6707 10.2706C14.3491 10.1279 13.9414 10.1279 13.126 10.1279Z"
                                                class="" />
                                            <path
                                                d="M10.5871 10.1309C9.50714 10.1309 8.96714 10.9109 8.84714 11.3909C8.72714 11.8709 8.72714 13.6109 8.79914 14.3309C9.03914 15.2309 9.63914 15.6029 10.2271 15.7229C10.7671 15.7709 13.0471 15.7529 13.7071 15.7529C14.6671 15.7709 15.3871 15.4109 15.6871 14.3309C15.7471 13.9709 15.8071 11.9909 15.6571 11.3909C15.3391 10.4309 14.5471 10.1309 13.9471 10.1309H10.5871Z" />
                                            <path
                                                d="M10.5 9.7085C10.5 9.6485 10.5082 9.3031 10.5096 8.86854C10.5108 8.47145 10.476 8.08854 10.6656 7.73814C11.376 6.32454 13.416 6.46854 13.92 7.90854C14.0073 8.14562 14.0125 8.52146 14.01 8.86854C14.0067 9.312 14.016 9.7085 14.016 9.7085" />
                                            <path
                                                d="M21 11.1833V8.28029C21 6.64029 21 5.82028 20.5959 5.28529C20.1918 4.75029 19.2781 4.49056 17.4507 3.9711C16.2022 3.6162 15.1016 3.18863 14.2223 2.79829C13.0234 2.2661 12.424 2 12 2C11.576 2 10.9766 2.2661 9.77771 2.79829C8.89839 3.18863 7.79784 3.61619 6.54933 3.9711C4.72193 4.49056 3.80822 4.75029 3.40411 5.28529C3 5.82028 3 6.64029 3 8.28029V11.1833C3 16.8085 8.06277 20.1835 10.594 21.5194C11.2011 21.8398 11.5046 22 12 22C12.4954 22 12.7989 21.8398 13.406 21.5194C15.9372 20.1835 21 16.8085 21 11.1833Z" />
                                        </svg>
                                        <p class="text-[10px] text-colorsubtitleform text-center leading-tight">
                                            {{ $item->time }}
                                            @if ($item->typegarantia->datecode == 'YYYY')
                                                {{ $item->time > 1 ? 'AÑOS' : 'AÑO' }}
                                            @else
                                                {{ $item->time > 1 ? 'MESES' : 'MES' }}
                                            @endif
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @if ($producto->promocions->where('type', \App\Enums\PromocionesEnum::COMBO->value)->count() > 0)
            <div class="w-full" id="combos" style="scroll-margin-top: 80px;">
                <h1 class="font-semibold text-lg sm:text-2xl py-3 text-colorsubtitleform">
                    Combos sugeridos para tí</h1>

                <div class="w-full grid grid-cols-[repeat(auto-fill,minmax(340px,1fr))] gap-2">
                    @foreach ($producto->promocions->where('type', \App\Enums\PromocionesEnum::COMBO->value)->all() as $item)
                        @php
                            $combo = getAmountCombo($item, $pricetype);
                        @endphp
                        @if ($combo->is_disponible && $combo->stock_disponible)
                            <div
                                class="w-full flex flex-col justify-between border border-borderminicard shadow-md shadow-shadowminicard p-1 md:p-2 rounded-lg md:rounded-2xl">
                                <div class="w-full">
                                    <h1 class="text-colorlabel font-medium text-xs md:text-[1rem] !leading-none pb-2">
                                        {{ $item->titulo }}</h1>

                                    <div class="w-full flex items-center flex-wrap gap-1">
                                        <div class="w-20 flex flex-col justify-center items-center opacity-50">
                                            <div class="w-full block rounded-lg relative">
                                                @if ($image)
                                                    <img src="{{ $image }}" alt="{{ $image }}"
                                                        class="block w-full h-auto max-h-16 object-scale-down overflow-hidden rounded-lg">
                                                @else
                                                    <x-icon-image-unknown
                                                        class="w-full h-full max-h-16 text-colorsubtitleform" />
                                                @endif
                                            </div>
                                        </div>

                                        @foreach ($combo->products as $itemcombo)
                                            @php
                                                $opacidad = $itemcombo->stock > 0 ? '' : 'opacity-50 saturate-0';
                                            @endphp

                                            <span class="block w-5 h-5 text-colorsubtitleform">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    color="currentColor" fill="none" stroke="currentColor"
                                                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                                                    class="w-full h-full block">
                                                    <path d="M12 4V20M20 12H4" />
                                                </svg>
                                            </span>

                                            <div class="w-20 flex flex-col justify-center items-center">
                                                <a class="w-full block rounded-lg relative"
                                                    href="{{ route('productos.show', $itemcombo->producto_slug) }}">
                                                    @if ($itemcombo->image)
                                                        <img src="{{ $itemcombo->image }}"
                                                            alt="{{ $itemcombo->image }}"
                                                            class="{{ $opacidad }} block w-full h-auto max-h-16 object-scale-down overflow-hidden rounded-lg">
                                                    @else
                                                        <x-icon-image-unknown
                                                            class="w-full h-full max-h-16 text-colorsubtitleform {{ $opacidad }}" />
                                                    @endif

                                                    @if ($itemcombo->stock > 0)
                                                        @if ($itemcombo->price <= 0)
                                                            <x-span-text text="GRATIS" type="green"
                                                                class="text-nowrap absolute bottom-0 left-[50%] -translate-x-[50%] !text-[9px] py-0.5" />
                                                        @else
                                                            {{-- <x-span-text :text="'s/. ' .
                                                            decimalOrInteger(
                                                                $itemcombo->price,
                                                                $pricetype->decimals ?? 2,
                                                                ', ',
                                                            )"
                                                            class="text-nowrap absolute bottom-0 !text-[10px] py-0.5 left-[50%] -translate-x-[50%]" /> --}}
                                                        @endif
                                                    @else
                                                        <x-span-text text="AGOTADO"
                                                            class="text-nowrap absolute bottom-0 left-[50%] -translate-x-[50%] !text-[9px] py-0.5" />
                                                    @endif

                                                </a>

                                                {{-- @if ($itemcombo->price > 0)
                                                    <h1
                                                        class="text-sm font-semibold text-colorlabel mt-1 leading-none">
                                                        <small class="text-[10px] font-medium">S/.</small>
                                                        {{ decimalOrInteger($itemcombo->price, $pricetype->decimals ?? 2, ', ') }}
                                                    </h1>
                                                @endif --}}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="w-full pt-2 flex flex-wrap gap-3 items-end justify-end">
                                    <div class="w-full flex-1">
                                        <h1
                                            class="text-colorlabel text-lg md:text-xl font-semibold text-center !leading-tight">
                                            <small class="text-[10px] font-medium">S/.</small>
                                            {{ number_format($pricesale + $combo->total, 2, '.', ', ') }}

                                            <span
                                                class="text-[10px] md:text-xs p-0.5 rounded text-colorerror font-medium line-through">
                                                {{ number_format($priceold + $combo->total_normal, 2, '.', ', ') }}</span>
                                        </h1>

                                        <p class="w-full text-center text-[10px] leading-none text-colorsubtitleform">
                                            Promoción válida hasta agotar stock.
                                            <br>
                                            [{{ decimalOrInteger($item->limit - $item->outs) }}
                                            {{ $combo->unit }}] disponibles

                                            @if (!empty($item->expiredate))
                                                <br>
                                                @if ($item->expiredate)
                                                    Promoción válida hasta el
                                                    {{ formatDate($item->expiredate, 'DD MMMM Y') }}
                                                @endif
                                            @endif
                                        </p>
                                    </div>

                                    <x-button-add-car type="button"
                                        class="button-add-to-cart px-2.5 !flex gap-1 items-center text-xs flex-shrink-0"
                                        data-promocion-id="{{ encryptText($item->id) }}">
                                        AGREGAR</x-button-add-car>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif


        @if (count($producto->especificacions) > 0 || !empty(trim($producto->comentario)))
            <div class="w-full sm:mt-8 xl:mt-10" id="especificacions">
                <h1 class="font-semibold text-lg sm:text-2xl text-colorsubtitleform">
                    Especificaciones</h1>
                <table class="w-full text-[10px] sm:mt-5">
                    <tbody class="odd:bg-fondohovertable">
                        @foreach ($producto->especificacions as $item)
                            <tr
                                class="text-textbodytable {{ $loop->index % 2 == 0 ? 'bg-body' : 'bg-fondobodytable' }}">
                                <th class="py-1.5 sm:py-3 px-2 text-left max-w-xs md:w-80">
                                    {{ $item->caracteristica->name }}
                                </th>
                                <td class="p-2 py-3 text-left">{{ $item->name }}</td>
                            </tr>
                        @endforeach
                        @if (!empty(trim($producto->comentario)))
                            <tr
                                class="text-textbodytable {{ count($producto->especificacions) % 2 == 0 ? 'bg-body' : 'bg-fondobodytable' }}">
                                <th class="py-1.5 sm:py-3 px-2 text-left max-w-xs md:w-80">
                                    COMENTARIO
                                </th>
                                <td class="p-2 py-3 text-left">{!! nl2br($producto->comentario) !!}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        @endif

        @if ($producto->verDetalles())
            @if ($producto->detalleproducto)
                @if (!empty($producto->detalleproducto->descripcion))
                    <div class="w-full mt-8 overflow-x-auto">
                        <h1 class="font-semibold text-lg sm:text-2xl py-3 text-colorsubtitleform">
                            Descripcion del producto</h1>

                        <div class="w-full block overflow-x-auto">
                            {!! $producto->detalleproducto->descripcion !!}
                        </div>
                    </div>
                @endif
            @endif
        @endif

        {{-- <div @keydown.escape.window="closeModal()"
            class="fixed z-20 h-screen inset-0 bg-neutral-700 bg-opacity-75 transition-opacity" x-show="open" x-cloak
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none">
            <!-- Modal -->
            <div class="w-full flex items-center justify-center min-h-screen">
                <div class="bg-fondobodymodal p-3 rounded-lg overflow-hidden shadow-xl w-full max-w-2xl transform transition-all"
                    @click.away="closeModal()">
                    <!-- Modal header -->
                    <div class="w-full flex items-start justify-between">
                        <h3 class="flex-1 w-full text-sm leading-4 font-medium text-colorsubtitleform">
                            {{ $producto->name }}</h3>
                        <span class="flex-shrink-0 cursor-pointer text-colorsubtitleform ml-2"
                            @click="closeModal()">✕</span>
                    </div>

                    <div class="w-full flex flex-col gap-2 text-left mt-5 md:p-5">
                        @if (count($stocksucursals) > 0)
                            @foreach ($stocksucursals as $item)
                                <x-simple-card class="w-full p-3 rounded-xl flex justify-between items-end">
                                    <div class="w-full flex-1 text-xs">
                                        <p class="w-full font-semibold text-colorlabel">
                                            <span class="inline-block w-4 h-4">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    color="currentColor" fill="none" class="w-full h-full">
                                                    <path
                                                        d="M2.97656 10.5146V15.009C2.97656 17.8339 2.97656 19.2463 3.85621 20.1239C4.73585 21.0015 6.15162 21.0015 8.98315 21.0015H12.9875" />
                                                    <path d="M6.98145 17.0066H10.9858" />
                                                    <path
                                                        d="M18.4941 13.5107C20.4292 13.5107 21.9979 15.0464 21.9979 16.9408C21.9979 19.0836 19.8799 20.1371 18.8695 21.7433C18.6542 22.0857 18.3495 22.0857 18.1187 21.7433C17.0767 20.1981 14.9902 19.0389 14.9902 16.9408C14.9902 15.0464 16.559 13.5107 18.4941 13.5107Z" />
                                                    <path d="M18.4941 17.0066H18.5031" />
                                                    <path
                                                        d="M17.7957 2.00254L6.14983 2.03002C4.41166 1.94542 3.966 3.2116 3.966 3.83056C3.966 4.38414 3.89055 5.19117 2.82524 6.70798C1.75993 8.22478 1.83998 8.67537 2.44071 9.72544C2.93928 10.5969 4.20741 10.9374 4.86862 10.9946C6.96883 11.0398 7.99065 9.32381 7.99065 8.1178C9.03251 11.1481 11.9955 11.1481 13.3158 10.8016C14.6385 10.4545 15.7717 9.2118 16.0391 8.1178C16.195 9.47735 16.6682 10.2707 18.0663 10.8158C19.5145 11.3805 20.7599 10.5174 21.3848 9.9642C22.0096 9.41096 22.4107 8.18278 21.2967 6.83288C20.5285 5.90195 20.2084 5.02494 20.1032 4.11599C20.0423 3.58931 19.9888 3.02336 19.5971 2.66323C19.0247 2.13691 18.2035 1.97722 17.7957 2.00254Z" />
                                                </svg>
                                            </span>
                                            {{ $item->name }}
                                        </p>
                                        <p class="text-[10px] text-colorsubtitleform">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" class="w-4 h-4 text-colorlabel inline-block">
                                                <path
                                                    d="M14.5 10C14.0697 8.55426 12.5855 7.5 11 7.5C9.067 7.5 7.5 9.067 7.5 11C7.5 12.7632 8.80385 14.2574 10.5 14.5" />
                                                <path
                                                    d="M19.9504 10C19.4697 5.53446 15.5596 2 11 2C6.12944 2 2 6.03298 2 10.9258C2 15.9137 6.2039 19.3616 10.073 21.7567C10.3555 21.9162 10.675 22 11 22C11.325 22 11.6445 21.9162 11.927 21.7567C12.1816 21.6009 12.4376 21.4403 12.6937 21.2748" />
                                                <path
                                                    d="M17.5 12C19.9353 12 22 14.0165 22 16.4629C22 18.9482 19.9017 20.6924 17.9635 21.8783C17.8223 21.9581 17.6625 22 17.5 22C17.3375 22 17.1777 21.9581 17.0365 21.8783C15.1019 20.6808 13 18.9568 13 16.4629C13 14.0165 15.0647 12 17.5 12Z" />
                                                <path d="M17.5 16.5H17.509" />
                                            </svg>

                                            {{ $item->direccion }}
                                            <br>
                                            {{ $item->lugar }}
                                        </p>
                                    </div>

                                    <x-span-text :text="decimalOrInteger($item->total) . ' UND'"
                                        class="!text-colorsubtitleform text-xs rounded-lg font-medium" />
                                </x-simple-card>
                            @endforeach

                            <div class="w-full rounded-xl bg-next-50 p-2 border border-next-300">
                                <p class="text-primary text-xs">
                                    <b>Importante:</b>
                                    Los puntos de recojo disponibles
                                    podrían variar en función
                                    del número de unidades adquiridas.
                                </p>
                            </div>
                        @endif
                    </div>

                    <!-- Modal footer -->
                    <div class="flex items-center justify-end mt-4">
                    </div>
                </div>
            </div>
        </div> --}}

        @if (count($relacionados) > 0)
            <div class="w-full mt-10">
                <h1 class="font-semibold text-lg sm:text-2xl py-3 text-colorsubtitleform">
                    Productos relacionados</h1>

                <div class="w-full relative px-6 md:px-0">
                    <div class="w-full flex overflow-x-hidden" id="relacionados">
                        @foreach ($relacionados as $item)
                            @php
                                $pricesale = $item->getPrecioVenta($pricetype);
                                $promocion_relacionados = null;

                                if ($item->descuento > 0 || $item->liquidacion) {
                                    $promocion_relacionados = $item->promocions
                                        ->where('type', '<>', \App\Enums\PromocionesEnum::COMBO->value)
                                        ->first();
                                }
                            @endphp

                            <x-card-producto-virtual :route="route('productos.show', $item)" :name="$item->name" :marca="$item->marca->name"
                                :partnumber="$item->partnumber" :image="$item->image ? pathURLProductImage($item->image) : null" :promocion="$promocion_relacionados"
                                wire:key="cardproduct{{ $item->id }}"
                                class="card-sugerencias flex-shrink-0 overflow-hidden xs:w-[calc(100%/2)] sm:w-[calc(100%/3)] md:w-[calc(100%/4)] lg:w-[calc(100%/6)] xl:w-[calc(100%/7)] xs:p-1 sm:p-3 transition ease-in-out duration-150">

                                @if ($pricesale > 0)
                                    @if ($empresa->verDolar())
                                        <h1 class="text-blue-700 font-medium text-xs text-center">
                                            <small class="text-[10px]">$. </small>
                                            {{ convertMoneda($pricesale, 'USD', $empresa->tipocambio, 2, ', ') }}
                                            {{-- <small class="text-[10px]">USD</small> --}}
                                        </h1>
                                    @endif
                                    <h1 class="text-colorlabel text-center">
                                        <small class="text-sm">{{ $moneda->simbolo }}</small>
                                        <span class="inline-block font-semibold text-2xl">
                                            {{ decimalOrInteger($pricesale, 2, ', ') }}</span>
                                        {{-- <small class="text-[10px]">{{ $moneda->currency }}</small> --}}
                                    </h1>
                                    @if (!empty($promocion_relacionados) && $empresa->verOldprice())
                                        <small class="block w-full line-through text-red-600 text-center text-xs">
                                            {{ $moneda->simbolo }}
                                            {{ decimalOrInteger($item->getPrecioVentaDefault($pricetype), 2, ', ') }}
                                        </small>
                                    @endif
                                @else
                                    <p class="text-colorerror text-[10px] font-semibold text-center leading-3">
                                        PRECIO DE VENTA NO ENCONTRADO</p>
                                @endif
                            </x-card-producto-virtual>
                        @endforeach
                    </div>
                    <button id="leftrelacionados"
                        class="bg-fondominicard opacity-60 absolute text-colortitleform top-1/2 left-0 -translate-y-1/2 h-12 w-6 shadow shadow-shadowminicard rounded-sm flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"
                            class="w-6 h-6 block">
                            <path d="M15 7L10 12L15 17" />
                        </svg>
                    </button>
                    <button id="rightrelacionados"
                        class="bg-fondominicard opacity-60 absolute text-colortitleform top-1/2 right-0 -translate-y-1/2 h-12 w-6 shadow shadow-shadowminicard rounded-sm flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"
                            class="w-6 h-6 block">
                            <path d="M10 7L15 12L10 17" />
                        </svg>
                    </button>
                </div>
            </div>
        @endif

        @if (count($interesantes) > 0)
            <div class="w-full mt-10 pb-10">
                <h1 class="font-semibold text-lg sm:text-2xl py-3 text-colorsubtitleform">
                    También podría interesarte</h1>

                <div class="w-full relative px-6 md:px-0">
                    <div class="w-full flex overflow-x-hidden" id="interesantes">
                        @foreach ($interesantes as $item)
                            @php
                                $pricesale = $item->getPrecioVenta($pricetype);
                                $promocion_interesantes = null;
                                if ($item->descuento > 0 || $item->liquidacion) {
                                    $promocion_interesantes = $item->promocions
                                        ->where('type', '<>', \App\Enums\PromocionesEnum::COMBO->value)
                                        ->first();
                                }
                            @endphp

                            <x-card-producto-virtual :route="route('productos.show', $item)" :name="$item->name" :marca="$item->marca->name"
                                :partnumber="$item->partnumber" :image="$item->image ? pathURLProductImage($item->image) : null" :promocion="$promocion_interesantes"
                                wire:key="cardproduct{{ $item->id }}"
                                class="card-similares flex-shrink-0 overflow-hidden xs:w-[calc(100%/2)] sm:w-[calc(100%/3)] md:w-[calc(100%/4)] lg:w-[calc(100%/6)] xl:w-[calc(100%/7)] xs:p-1 sm:p-3 transition ease-in-out duration-150">

                                @if ($pricesale > 0)
                                    @if ($empresa->verDolar())
                                        <h1 class="text-blue-700 font-medium text-xs text-center">
                                            <small class="text-[10px]">$. </small>
                                            {{ convertMoneda($pricesale, 'USD', $empresa->tipocambio, 2, ', ') }}
                                        </h1>
                                    @endif
                                    <h1 class="text-colorlabel text-center">
                                        <small class="text-sm">{{ $moneda->simbolo }}</small>
                                        <span class="inline-block font-semibold text-2xl">
                                            {{ decimalOrInteger($pricesale, 2, ', ') }}</span>
                                    </h1>
                                    @if (!empty($promocion_interesantes) && $empresa->verOldprice())
                                        <small class="block w-full line-through text-red-600 text-center text-xs">
                                            {{ $moneda->simbolo }}
                                            {{ decimalOrInteger($item->getPrecioVentaDefault($pricetype), 2, ', ') }}
                                        </small>
                                    @endif
                                @else
                                    <p class="text-colorerror text-[10px] font-semibold text-center leading-3">
                                        PRECIO DE VENTA NO ENCONTRADO</p>
                                @endif
                            </x-card-producto-virtual>
                        @endforeach
                    </div>
                    <button id="leftinteresantes"
                        class="bg-fondominicard opacity-60 absolute text-colortitleform top-1/2 left-0 -translate-y-1/2 h-12 w-6 shadow flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"
                            class="w-6 h-6 block">
                            <path d="M15 7L10 12L15 17" />
                        </svg>
                    </button>
                    <button id="rightinteresantes"
                        class="bg-fondominicard opacity-60 absolute text-colortitleform top-1/2 right-0 -translate-y-1/2 h-12 w-6 shadow flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"
                            class="w-6 h-6 block">
                            <path d="M10 7L15 12L10 17" />
                        </svg>
                    </button>
                </div>
            </div>
        @endif

        {{-- <div class="w-full sticky z-[99] bottom-0 left-0 p-1 bg-body sm:hidden">
            <div class="w-full sm:pt-3 flex items-center justify-between gap-2">
                @if ($pricesale > 0)
                    <div class="w-full flex-1">
                        <h1 class="font-semibold text-2xl text-center md:text-start text-colorlabel">
                            <small class="text-lg"> {{ $moneda->simbolo }}</small>
                            {{ decimalOrInteger($pricesale, 2, ', ') }}
                        </h1>

                        @if ($descuento > 0 && $empresa->verOldprice())
                            <h1 class="text-colorsubtitleform text-[10px] text-red-600 text-center md:text-start">
                                {{ $moneda->simbolo }}
                                <small class="text-xs inline-block line-through">
                                    {{ getPriceAntes($pricesale, $descuento, null, ', ') }}</small>
                            </h1>
                        @endif
                        @if ($empresa->verDolar())
                            <h1 class="text-blue-700 font-medium text-xs text-center md:text-start">
                                <small class="text-[10px]">$. </small>
                                {{ convertMoneda($pricesale, 'USD', $empresa->tipocambio, 2, ', ') }}
                                <small class="text-[10px]">USD</small>
                            </h1>
                        @endif
                    </div>
                    <div class="flex-shrink-0">
                        <livewire:modules.marketplace.carrito.add-wishlist :producto="$producto" :empresa="$empresa"
                            :moneda="$moneda" :pricetype="$pricetype" />
                    </div>
                @else
                    <p class="w-full flex-1 text-colorerror text-[10px] font-semibold text-center">
                        PRECIO DE VENTA NO ENCONTRADO</p>
                @endif
            </div>

            @if ($producto->stock > 0)
                @if ($pricesale > 0)
                    <livewire:modules.marketplace.carrito.add-carrito :producto="$producto" :empresa="$empresa"
                        :moneda="$moneda" :pricetype="$pricetype" />
                @endif
            @else
                <div class="w-full flex items-center justify-end">
                    <span class="inline-block justify-center p-1 rounded-lg bg-red-600 text-white text-[10px]">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" fill="currentColor"
                            stroke="currentColor" stroke-width="0.7" stroke-linecap="round" stroke-linejoin="round"
                            class="inline-block w-4 h-4">
                            <path
                                d="m13.58,16.14c-2.68-5.62-.35-11.07,3.31-13.8,3.93-2.94,9.38-3.15,13.53-.47,1.56,1.01,2.8,2.31,3.71,3.9.92,1.6,1.4,3.33,1.44,5.16.05,1.81-.37,3.53-1.19,5.2,1.38.67,2.69,1.38,4.05,1.95,1.82.76,3.34,1.86,4.69,3.26,1.41,1.48,2.94,2.87,4.41,4.29.7.68.61,1.19-.28,1.63-1.81.88-3.62,1.77-5.45,2.63-.32.15-.44.32-.44.68.02,2.79,0,5.57.02,8.36,0,.56-.2.89-.72,1.14-5.35,2.58-10.69,5.16-16.02,7.76-.46.23-.86.2-1.31-.02-5.34-2.6-10.68-5.18-16.02-7.76-.48-.23-.69-.54-.68-1.06.01-2.79,0-5.57.02-8.36,0-.38-.1-.59-.47-.76-1.83-.86-3.64-1.75-5.45-2.63-.85-.41-.94-.93-.26-1.6,2.06-2,4.12-3.99,6.18-5.98.2-.2.45-.36.71-.49,1.9-.93,3.81-1.85,5.72-2.78.17-.08.33-.17.52-.27Zm-5.26,14.82c-.02.09-.04.13-.04.18,0,2.43,0,4.87.01,7.3,0,.16.18.38.34.46,5.01,2.44,10.02,4.87,15.04,7.28.18.08.47.08.65,0,5.01-2.41,10.01-4.83,15.01-7.24.29-.14.38-.31.38-.62-.01-2.32,0-4.64,0-6.95,0-.12-.02-.25-.03-.41-.21.1-.36.16-.52.24-2.65,1.28-5.29,2.56-7.94,3.84-.66.32-.89.28-1.41-.23-1.8-1.75-3.6-3.49-5.41-5.24-.13-.13-.27-.24-.47-.43-.14.17-.25.32-.38.45-1.79,1.74-3.58,3.47-5.37,5.2-.55.53-.77.56-1.46.23-2.63-1.27-5.26-2.55-7.89-3.82-.16-.08-.33-.15-.52-.23ZM24.01,1.6c-5.41-.03-9.9,4.26-9.94,9.49-.04,5.33,4.37,9.7,9.81,9.72,5.56.03,10.02-4.24,10.04-9.59.02-5.29-4.42-9.6-9.91-9.62Zm-1.5,26.63c-.06-.04-.14-.09-.23-.14-4.78-2.32-9.57-4.63-14.34-6.95-.29-.14-.42-.05-.61.13-1.61,1.57-3.24,3.14-4.85,4.71-.07.06-.12.14-.21.24,5.01,2.42,9.97,4.82,14.88,7.2,1.81-1.75,3.58-3.47,5.37-5.19Zm8.35,5.19c4.91-2.38,9.88-4.78,14.9-7.21-1.71-1.66-3.38-3.25-5.02-4.87-.27-.27-.47-.31-.82-.14-3.87,1.89-7.74,3.76-11.62,5.63-.95.46-1.9.92-2.83,1.38,1.8,1.74,3.57,3.45,5.39,5.21Zm7.82-13.4c-1.78-.86-3.45-1.67-5.11-2.47-4.8,6.58-14.71,6.38-19.17,0-1.66.8-3.33,1.61-5.07,2.45.17.1.25.15.34.19,4.68,2.26,9.35,4.53,14.03,6.78.17.08.47.05.65-.03,3.01-1.44,6.01-2.89,9.01-4.34,1.74-.84,3.47-1.68,5.32-2.58Z" />
                            <path
                                d="m24.04,12.31c-1.42,1.37-2.75,2.65-4.07,3.94-.11.11-.21.22-.33.32-.4.33-.88.32-1.2-.01-.31-.32-.31-.77.03-1.13.51-.52,1.05-1.02,1.57-1.52.91-.89,1.82-1.77,2.77-2.7-.13-.13-.25-.27-.38-.39-1.29-1.25-2.59-2.5-3.87-3.75-.43-.42-.46-.89-.1-1.23.36-.34.84-.32,1.27.1,1.28,1.23,2.56,2.48,3.83,3.72.13.13.24.28.38.45.22-.2.37-.33.51-.47,1.25-1.21,2.51-2.43,3.76-3.65.47-.45.94-.51,1.31-.17.39.35.34.84-.14,1.31-1.39,1.35-2.77,2.69-4.2,4.07.14.14.26.27.39.4,1.22,1.18,2.44,2.36,3.66,3.54.09.08.17.16.25.25.38.41.41.87.06,1.2-.35.33-.83.32-1.24-.07-1.15-1.1-2.29-2.21-3.43-3.33-.28-.27-.54-.56-.84-.87Z" />
                        </svg>
                        AGOTADO
                    </span>
                </div>
            @endif
        </div> --}}
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('showproducto', () => ({
                open: false,
                init() {

                },
                openModal() {
                    this.open = true;
                    document.body.style.overflow = 'hidden';
                },
                closeModal() {
                    this.open = false;
                    document.body.style.overflow = 'auto';
                },
                changeImage(direction) {
                    let imagethumbs = $("#imagethumbs");
                    let thumbnail = $(".thumbnail");
                    var index = $('.thumbnail.active').index();
                    let left = $('#previusthumbnail');
                    let right = $('#nextthumbnail');
                    let moveX = Math.round($(imagethumbs).get(0).scrollWidth / $('.thumbnail').length);

                    if (direction === 'next') {
                        index = index < $('.thumbnail').length - 1 ? index + 1 : index;
                        thumbnail = thumbnail.get(index);

                        if (thumbnail.offsetLeft >= Math.round($(imagethumbs).outerWidth(true))) {
                            $(imagethumbs).animate({
                                scrollLeft: '+=' + moveX
                            }, 'fast');

                            if (thumbnail.offsetLeft + moveX >= $(imagethumbs).get(0).scrollWidth) {
                                $(left).prop('disabled', false);
                                $(right).prop('disabled', true);
                            }
                        }
                        $(left).prop('disabled', false);

                    } else if (direction === 'prev') {
                        index = index > 0 ? index - 1 : index;
                        thumbnail = thumbnail.get(index);

                        $(imagethumbs).animate({
                            scrollLeft: '-=' + moveX
                        }, 'fast');

                        if (thumbnail.offsetLeft < $(imagethumbs).get(0).scrollWidth) {
                            $(right).prop('disabled', false);
                        }

                        if (thumbnail.offsetLeft <= $(".thumbnail")[0].offsetLeft) {
                            $(left).prop('disabled', true);
                        }
                    }

                    this.currentImage = $(".thumbnail")[index].querySelector('img').getAttribute(
                        'src');
                }
            }))
        })

        document.addEventListener('DOMContentLoaded', function() {

            let relacionados = $("#relacionados");
            $("#rightrelacionados").click(() => hacerScroll(relacionados));
            $("#leftrelacionados").click(() => hacerScroll(relacionados, '-'));
            // disabledButtons(relacionados, '#leftrelacionados', '#rightrelacionados');

            let interesantes = $("#interesantes");
            $("#rightinteresantes").click(() => hacerScroll(interesantes));
            $("#leftinteresantes").click(() => hacerScroll(interesantes, '-'));
            // disabledButtons(interesantes, '#leftinteresantes', '#rightinteresantes');


            function hacerScroll(contenedor, type = "+") {
                $(contenedor).animate({
                    scrollLeft: type + '=' + Math.round($(contenedor).width())
                }, 'slow');
            }

            function disabledButtons(contenedor, left, right) {
                $(contenedor).scroll(function() {
                    if ($(contenedor).scrollLeft() <= 0) {
                        $(left).prop('disabled', true);
                        $(right).prop('disabled', false);
                    }

                    if ($(contenedor).scrollLeft() >= $(contenedor).get(0).scrollWidth - $(contenedor)
                        .width()) {
                        $(right).prop('disabled', true);
                        $(left).prop('disabled', false);
                    }
                });
            }

            function changeSlide(e) {
                let left = $('#previusthumbnail');
                let right = $('#nextthumbnail');
                let start = $(".thumbnail")[0].offsetLeft;
                let end = $(".thumbnail")[$('.thumbnail').length - 1].offsetLeft;
                let offset = $(".thumbnail")[e.index()].offsetLeft;

                if (offset == start) {
                    $(left).prop('disabled', true);
                    $(right).prop('disabled', false);
                } else {
                    $(left).prop('disabled', false);
                }

                if (offset == end) {
                    $(left).prop('disabled', false);
                    $(right).prop('disabled', true);
                } else {
                    $(right).prop('disabled', false);
                }

                // if (offset == start || offset == end) {
                //     $(left).prop('disabled', false);
                //     $(right).prop('disabled', false);
                // }

            }

            $(".thumbnail").click(function() {
                changeSlide($(this));
            });
        })

        function zoom(e, zoomLevel = 130) {
            var zoomer = e.currentTarget;
            e.offsetX ? offsetX = e.offsetX : offsetX = e.touches ? e.touches[0].pageX : 0
            e.offsetY ? offsetY = e.offsetY : offsetX = e.touches ? e.touches[0].pageX : 0
            x = offsetX / zoomer.offsetWidth * 100
            y = offsetY / zoomer.offsetHeight * 100
            // zoomer.style.backgroundPosition = x + '% ' + y + '%';
            zoomer.style.backgroundSize = zoomLevel + "%";
            zoomer.style.backgroundPosition = x + '% ' + y + '%';
            // console.log(e.offsetX);
            // console.log(e.touches);
        }
    </script>
</x-app-layout>
