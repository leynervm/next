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
        $promocion = verifyPromocion($producto->promocions->first());
        $descuento = getDscto($promocion);
        $combo = $producto->getAmountCombo($promocion, $pricetype ?? null);
        $pricesale = $producto->obtenerPrecioVenta($pricetype ?? null);
    @endphp

    <div class="contenedor w-full relative" x-data="showproducto">
        <div class="flex flex-col gap-5" x-data="{ currentImage: '{{ $image }}', showesp: true }">
            <div class="w-full md:flex">
                <div class="w-full md:flex-shrink-0 md:w-[50%] lg:w-[60%] md:px-3 py-2">
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
                        
                        @if ($promocion)
                            <div class="w-auto h-auto bg-red-600 absolute -left-10 top-7 -rotate-[35deg] leading-none">
                                <p class="text-white text-sm block font-medium py-2.5 px-20 tracking-widest">
                                    @if ($empresa->isTitlePromocion())
                                        PROMOCIÓN
                                    @elseif($empresa->isTitleLiquidacion())
                                        LIQUIDACIÓN
                                    @else
                                        @if ($promocion->isDescuento())
                                            - {{ decimalOrInteger($promocion->descuento) }}% DSCT
                                        @elseif ($promocion->isCombo())
                                            OFERTA
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
                    <div class="w-full border-b border-b-borderminicard pb-5">
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

                    <div class="w-full hidden sm:block">
                        <div class="w-full pt-3 flex items-center justify-between gap-2">
                            @if ($pricesale > 0)
                                <div class="w-full flex-1">
                                    <h1 class="font-semibold text-3xl text-center md:text-start text-colorlabel">
                                        <small class="text-lg"> {{ $moneda->simbolo }}</small>
                                        {{ decimalOrInteger($pricesale, 2, ', ') }}
                                    </h1>

                                    @if ($descuento > 0 && $empresa->verOldprice())
                                        <h1
                                            class="text-colorsubtitleform text-xs text-red-600 text-center md:text-start">
                                            {{ $moneda->simbolo }}
                                            <small class="text-lg inline-block line-through">
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
                                    <livewire:modules.marketplace.carrito.add-wishlist :producto="$producto"
                                        :empresa="$empresa" :moneda="$moneda" :pricetype="$pricetype" />
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
                            <span class="inline-block p-1 rounded-lg bg-red-600 text-white text-[10px] float-right">
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
                        @endif
                    </div>

                    @if ($combo)
                        @if (count($combo->products) > 0)
                            <div class="w-full flex flex-wrap gap-2 my-2">
                                @foreach ($combo->products as $itemcombo)
                                    <div class="block w-32 cursor-pointer">
                                        <div class="block rounded-lg w-full h-28 overflow-hidden relative">
                                            @if ($itemcombo->image)
                                                <img src="{{ $itemcombo->image }}" alt=""
                                                    class="w-full h-full object-scale-down rounded">
                                            @else
                                                <x-icon-image-unknown class="w-full h-full text-colorsubtitleform" />
                                            @endif
                                            <x-span-text text="GRATIS"
                                                class="absolute bottom-1 right-1 leading-3 !text-[9px] !py-0.5"
                                                type="green" />
                                        </div>
                                        <h1 class="text-[10px] leading-3 font-medium mt-2 text-colorsubtitleform">
                                            {{ $itemcombo->name }}</h1>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endif

                    @if ($promocion)
                        <p class="w-full mt-5 text-center text-xs leading-3 text-colorsubtitleform">
                            @if ($promocion->limit > 0)
                                @if ($promocion->expiredate)
                                    Promoción válida hasta el
                                    {{ formatDate($promocion->expiredate, 'DD MMMM Y') }}
                                    y/o agotar stock
                                @else
                                    Promoción válida hasta agotar unidades disponibles.
                                @endif

                                @if ($promocion->limit > 0)
                                    <br>
                                    [{{ decimalOrInteger($promocion->limit) }}
                                    {{ $promocion->producto->unit->name }}]
                                @endif
                            @else
                                @if ($promocion->expiredate)
                                    Promoción válida hasta el
                                    {{ formatDate($promocion->expiredate, 'DD MMMM Y') }}
                                    y/o agotar stock
                                @else
                                    Promoción válida hasta agotar stock.
                                @endif
                            @endif
                        </p>
                    @endif

                    @if (count($producto->especificacions) > 0)
                        <div class="w-full hidden sm:block py-3">
                            <ul class="w-full pt-3 text-colorsubtitleform text-[10px]">
                                @foreach ($producto->especificacions->take(3) as $item)
                                    <li class="py-1">
                                        <span class="font-semibold">
                                            {{ $item->caracteristica->name }} :
                                        </span>{{ $item->name }}
                                    </li>
                                @endforeach
                            </ul>

                            <a href="#especificacions" class="underline pt-3 text-colortitleform text-[10px]">
                                VER MÁS ESPECIFICACIONES</a>
                        </div>
                    @endif

                    @if (count($shipmenttypes) > 0)
                        <div class="w-full grid grid-cols-[repeat(auto-fill,minmax(240px,1fr))] gap-2 pt-3">
                            @foreach ($shipmenttypes as $item)
                                <div
                                    class="w-full border border-borderminicard rounded-lg xl:rounded-xl inline-flex gap-3 text-colorlabel p-2">
                                    <div class="w-8 h-8 flex-shrink-0">
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
                                    </div>
                                    <div class="w-full flex-1 text-xs" x-data="{ descripcion: false }">
                                        <h1 class="font-semibold">{{ $item->name }}</h1>
                                        <div class="inline-flex items-center gap-1">
                                            <button @click="descripcion = !descripcion"
                                                class="inline-flex gap-2 items-center p-1  underline">
                                                Ver descripción
                                                <span class="w-4 h-4 inline-block">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                        color="currentColor" fill="none" class="w-full h-full">
                                                        <path
                                                            d="M9.00005 6C9.00005 6 15 10.4189 15 12C15 13.5812 9 18 9 18"
                                                            stroke="currentColor" stroke-width="1.5"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                </span>
                                            </button>
                                        </div>
                                        <p x-cloak x-show="descripcion"
                                            class="w-full block text-[10px] leading-3 text-colorsubtitleform mt-1">
                                            {{ $item->descripcion }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if ($empresa->viewAlmacensDetalle())
                        @if (count($producto->almacens) > 0)
                            <div class="w-full flex flex-wrap gap-2 pt-5">
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
                                <div class="block w-4 h-4 text-colortitleform">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        color="currentColor" fill="none">
                                        <path
                                            d="M11.9982 2C8.99043 2 7.04018 4.01899 4.73371 4.7549C3.79589 5.05413 3.32697 5.20374 3.1372 5.41465C2.94743 5.62556 2.89186 5.93375 2.78072 6.55013C1.59143 13.146 4.1909 19.244 10.3903 21.6175C11.0564 21.8725 11.3894 22 12.0015 22C12.6135 22 12.9466 21.8725 13.6126 21.6175C19.8116 19.2439 22.4086 13.146 21.219 6.55013C21.1078 5.93364 21.0522 5.6254 20.8624 5.41449C20.6726 5.20358 20.2037 5.05405 19.2659 4.75499C16.9585 4.01915 15.0061 2 11.9982 2Z" />
                                        <path d="M9 13C9 13 10 13 11 15C11 15 14.1765 10 17 9" />
                                    </svg>
                                </div>
                                <h1 class="font-semibold text-colortitleform text-[10px]">
                                    GARANTÍAS</h1>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="block w-5 h-5 text-colortitleform duration-200"
                                    :class="!opengarantias ? ' -rotate-180' : ' rotate-0'">
                                    <path d="M5.99977 9.00005L11.9998 15L17.9998 9" />
                                </svg>
                            </button>
                            <div class="w-full pt-1 grid grid-cols-[repeat(auto-fill,minmax(170px,1fr))] gap-2" x-cloak
                                x-show="opengarantias" x-transition>
                                @foreach ($producto->garantiaproductos as $item)
                                    <div
                                        class="w-full text-[10px] text-colorsubtitleform text-center rounded-lg md:rounded-xl border border-borderminicard p-2 py-3 font-semibold">
                                        {{ $item->typegarantia->name }}
                                        /
                                        {{ $item->time }}
                                        @if ($item->typegarantia->datecode == 'YYYY')
                                            {{ $item->time > 1 ? 'AÑOS' : 'AÑO' }}
                                        @else
                                            {{ $item->time > 1 ? 'MESES' : 'MES' }}
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="w-full max-w-full py-3 mt-auto">
                        <img class="w-full h-auto object-scale-down object-center"
                            src="{{ asset('images/niubiz_header.png') }}" alt="">
                    </div>
                </div>
            </div>
        </div>

        @if (count($producto->especificacions) > 0)
            <div class="w-full sm:pt-16 xl:pt-20" id="especificacions">
                <h1 class="font-semibold text-lg sm:text-2xl py-3 text-colorsubtitleform">
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
                    </tbody>
                </table>
            </div>
        @endif

        @if ($producto->verDetalles())
            @if ($producto->detalleproducto)
                @if (!empty($producto->detalleproducto->descripcion))
                    <div class="w-full mt-8 overflow-x-auto">
                        <h1 class="font-semibold text-2xl py-3 text-colorsubtitleform">
                            Descripcion del producto</h1>

                        <div class="w-full block overflow-x-auto">
                            {!! $producto->detalleproducto->descripcion !!}
                        </div>
                    </div>
                @endif
            @endif
        @endif


        <div class="w-full sticky z-[99] bottom-0 left-0 p-1 bg-body sm:hidden">
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
        </div>


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
                <h1 class="font-medium uppercase text-xs py-3 text-colorsubtitleform">
                    Productos relacionados</h1>

                <div class="w-full relative px-6 md:px-0">
                    <div class="w-full flex overflow-x-hidden" id="relacionados">
                        @foreach ($relacionados as $item)
                            @php
                                // $image = $item->getImageURL();
                                $promocion = verifyPromocion($item->promocion);
                                $descuento = getDscto($promocion);
                                $combo = $item->getAmountCombo($promocion, $pricetype);
                                $pricesale = $item->obtenerPrecioVenta($pricetype ?? null);
                            @endphp

                            <x-card-producto-virtual :route="route('productos.show', $item)" :name="$item->name" :marca="$item->marca->name"
                                :partnumber="$item->partnumber" :image="$item->image ? pathURLProductImage($item->image) : null" :promocion="$promocion"
                                wire:key="cardproduct{{ $item->id }}"
                                class="card-sugerencias flex-shrink-0 overflow-hidden xs:w-[calc(100%/2)] sm:w-[calc(100%/3)] md:w-[calc(100%/4)] lg:w-[calc(100%/6)] xl:w-[calc(100%/7)] py-3 px-3 transition ease-in-out duration-150">

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
                                        <span
                                            class="inline-block font-semibold text-2xl">{{ decimalOrInteger($pricesale, 2, ', ') }}</span>
                                        {{-- <small class="text-[10px]">{{ $moneda->currency }}</small> --}}
                                    </h1>
                                    @if ($descuento > 0 && $empresa->verOldprice())
                                        <small class="block w-full line-through text-red-600 text-center text-xs">
                                            {{ $moneda->simbolo }}
                                            {{ getPriceAntes($pricesale, $descuento, null, ', ') }}
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
                <h1 class="font-medium uppercase text-xs py-3 text-colorsubtitleform">
                    También podría interesarte</h1>

                <div class="w-full relative px-6 md:px-0">
                    <div class="w-full flex overflow-x-hidden" id="interesantes">
                        @foreach ($interesantes as $item)
                            @php
                                // $image = $item->getImageURL();
                                $promocion = verifyPromocion($item->promocion);
                                $descuento = getDscto($promocion);
                                $combo = $item->getAmountCombo($promocion, $pricetype);
                                $pricesale = $item->obtenerPrecioVenta($pricetype ?? null);
                            @endphp

                            <x-card-producto-virtual :route="route('productos.show', $item)" :name="$item->name" :marca="$item->marca->name"
                                :partnumber="$item->partnumber" :image="$item->image ? pathURLProductImage($item->image) : null" :promocion="$promocion"
                                wire:key="cardproduct{{ $item->id }}"
                                class="card-similares flex-shrink-0 overflow-hidden xs:w-[calc(100%/2)] sm:w-[calc(100%/3)] md:w-[calc(100%/4)] lg:w-[calc(100%/6)] xl:w-[calc(100%/7)] py-3 px-3 transition ease-in-out duration-150">

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
                                        <span
                                            class="inline-block font-semibold text-2xl">{{ decimalOrInteger($pricesale, 2, ', ') }}</span>
                                        {{-- <small class="text-[10px]">{{ $moneda->currency }}</small> --}}
                                    </h1>
                                    @if ($descuento > 0 && $empresa->verOldprice())
                                        <small class="block w-full line-through text-red-600 text-center text-xs">
                                            {{ $moneda->simbolo }}
                                            {{ getPriceAntes($pricesale, $descuento, null, ', ') }}
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
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('showproducto', () => ({
                open: false,

                init() {},
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
