@props(['producto', 'pricetype' => null])

@php
    $mensajeprecios = null;
    $tipocambio = $empresa->usarDolar() ? $empresa->tipocambio : null;
    $promocion = verifyPromocion($producto->promocion);
    $descuento = getDscto($promocion);
    $combo = $producto->getAmountCombo($promocion, $pricetype);
    $pricesale = $producto->obtenerPrecioVenta($pricetype);
@endphp


<div
    class="w-full shadow-md shadow-shadowminicard xs:shadow-none rounded-xl xs:rounded-none card-not-unknown relative flex flex-col justify-between px-1 sm:px-3 pt-3 pb-3 min-h-[24rem]">
    <div class="w-full relative">
        @if ($promocion)
            <div
                class="absolute top-3 right-1 bg-red-600 text-white rounded-sm inline-flex items-center justify-center p-1 text-center h-6 w-auto whitespace-nowrap">
                <span class="text-sm">
                    @if ($empresa->isTitlePromocion())
                        PROMOCIÓN
                    @elseif($empresa->isTitleLiquidacion())
                        LIQUIDACIÓN
                    @else
                        @if ($promocion->isDescuento())
                            -{{ decimalOrInteger($promocion->descuento) }}%
                        @elseif ($promocion->isCombo())
                            OFERTA
                        @else
                            LIQUIDACIÓN
                        @endif
                    @endif
                </span>
            </div>
        @endif

        <div class="w-full p-0">
            <div class="w-full rounded-lg overflow-hidden mx-auto mb-2">
                @if ($producto->image)
                    <img src="{{ pathURLProductImage($producto->image) }}" alt="product-case-iphone-11-transparente-image"
                        title="{{ $producto->slug }}" class="align-middle w-full h-full max-h-44 object-scale-down">
                @else
                    <x-icon-image-unknown class="!w-full !h-full text-colorsubtitleform" />
                @endif
            </div>

            <div class="w-full inline-flex flex-col">
                <h3 class="uppercase text-xs text-colorsubtitleform font-semibold tracking-[.075rem]">
                    {{ $producto->marca->name }}</h3>
                <h1 class="text-colorsubtitleform text-xs tracking-[-.019rem]">
                    {{ $producto->name }}</h1>
                <div class="mt-1">
                    @if ($pricesale > 0)
                        <div class="w-full text-colorsubtitleform whitespace-nowrap flex flex-col">
                            @if ($empresa->verDolar())
                                <h1 class="text-blue-700 font-medium text-xs">
                                    $.
                                    {{ convertMoneda($pricesale, 'USD', $empresa->tipocambio, 2, ', ') }}
                                    <small class="text-[10px]">USD</small>
                                </h1>
                            @endif
                            <div class="text-xl font-semibold text-colorlabel">
                                <small class="text-sm">{{ $moneda->simbolo }}</small>
                                {{ decimalOrInteger($pricesale, 2, ', ') }}
                            </div>
                        </div>

                        @if ($descuento > 0 && $empresa->verOldprice())
                            <h1 class="text-colorsubtitleform text-[10px] text-red-600 text-center md:text-start">
                                {{ $moneda->simbolo }}
                                <small class="text-sm inline-block line-through">
                                    {{ getPriceAntes($pricesale, $descuento, null, ', ') }}</small>
                            </h1>
                        @endif
                    @else
                        <p class="text-colorerror text-[10px] font-semibold text-center leading-3">
                            PRECIO DE VENTA NO ENCONTRADO</p>
                    @endif
                </div>
            </div>
        </div>

        @if ($combo)
            @if (count($combo->products) > 0)
                <div class="w-full my-2">
                    @foreach ($combo->products as $itemcombo)
                        <div class="w-full flex gap-1 relative">
                            <div class="block rounded overflow-hidden flex-shrink-0 w-12 h-12 relative cursor-pointer">
                                @if ($itemcombo->image)
                                    <img src="{{ $itemcombo->image }}" alt=""
                                        class="w-full h-full object-scale-down">
                                @else
                                    <x-icon-image-unknown class="!w-full !h-full text-colorsubtitleform" />
                                @endif
                            </div>
                            <h1 class="p-1 w-full flex-1 text-[10px] text-colorsubtitleform leading-3 text-left">
                                {{ $itemcombo->name }}</h1>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" fill="currentColor"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="block opacity-60 absolute right-0 top-0 w-6 h-6 text-primary">
                                <path
                                    d="M46.4,10.9C30.9,13.5,20,20.5,14.8,31.1c-3.8,7.6-4.8,13-4.8,25.1c0.1,11.1,1.4,20.4,4.8,32.9c2.6,9.8,7.2,23.2,10.3,29.7l2.6,5.5l60.1,60.1c52,51.9,60.5,60.1,62.8,60.7c3.6,1,6.1,0.9,9.8-0.3c2.9-1,6.9-4.8,42.8-40.6c30.3-30.2,39.9-40.3,41.2-42.7c1.9-3.8,2.2-8.9,0.7-12.6c-0.7-1.7-18.6-20-60.1-61.6c-65.7-65.8-59.7-60.5-75.4-66.4C87.1,12.4,61.7,8.4,46.4,10.9z M76,22.1c8.3,1.4,19.7,4.3,27.7,7.1c13.5,4.7,7.8-0.5,72.8,64.7c55.5,55.6,59.1,59.3,59.1,61.4c0,2.1-2.5,4.7-37.6,39.9c-20.7,20.7-38.5,38.3-39.5,39c-1,0.7-2.6,1.3-3.5,1.3c-2.4,0-119.5-116.9-121.4-121.3c-4.3-9.4-9.8-28.9-12.1-42c-1.5-8.9-1.6-24-0.1-29.4c2.1-7.9,5.8-13,12.2-16.9c2.9-1.8,9.6-4,14.3-4.8C53,20.3,68.6,20.8,76,22.1z" />
                                <path
                                    d="M39.1,31.8c-5.3,2.5-8.3,7.2-8.3,13.1c-0.1,13.4,15.8,20,25.2,10.6c5.9-5.9,5.8-15.3-0.3-21.1C51.1,30,45,29.1,39.1,31.8z M48.8,41.8c1.4,1.3,1.6,5,0.1,6.3c-3.3,3.3-8.9,0.5-7.8-3.8C41.9,40.8,46.2,39.4,48.8,41.8z" />
                                <path
                                    d="M83,82.9l-25.2,25.2l3.9,3.9l3.9,3.9L76,105.5L86.4,95l5.2,5.2l5.2,5.1l3.6-3.5l3.6-3.5l-4.9-5c-2.7-2.7-4.9-5.2-4.9-5.5c0-0.4,3.4-4,7.5-8.1l7.5-7.5l6.5,6.5l6.5,6.4l3.6-3.5l3.6-3.6l-10.2-10.2c-5.7-5.7-10.4-10.3-10.6-10.3S96.8,68.9,83,82.9z" />
                                <path
                                    d="M108.2,107.7l-25.4,25.4l3.9,3.9l3.9,3.9l10.5-10.5l10.5-10.5l2.3,2.8c4.5,5.2,3.3,8.1-7.7,18.8c-2.7,2.7-5.4,5.7-5.9,6.6c-0.8,1.6-0.8,1.8,3,5.5l4,3.8l1-1.6c0.6-0.9,4.6-5.3,9-9.8c8.5-8.8,9.7-10.9,9.2-15.9l-0.2-2.9l3.6,0.1c4.9,0,7.9-1.8,14.7-8.9c4.3-4.5,5.5-6.2,6.3-9c1-3.2,1-3.8,0-7c-0.9-3.2-2-4.5-9.2-11.9l-8.3-8.4L108.2,107.7z M139.8,104.7c0,2.7-2,5.7-6.6,9.8c-5.8,5.3-7.9,5.3-13.1,0.5l-1.8-1.7l7.9-8l7.9-8l2.8,2.7C139,102.1,139.8,103.3,139.8,104.7z" />
                                <path
                                    d="M137.5,137.1l-25.4,25.4l10.7,10.7l10.7,10.7l3.6-3.6l3.6-3.6L134,170l-6.8-6.8l7.5-7.5l7.5-7.5l5.5,5.5l5.6,5.5l3.5-3.6l3.5-3.6l-5.5-5.5l-5.5-5.5l7.2-7.2l7.2-7.2l6.8,6.8l6.9,6.8l3.5-3.6l3.6-3.6l-10.7-10.7L163,111.7L137.5,137.1z" />
                                <path
                                    d="M164.3,163.8l-25.4,25.4l10.9,10.9l10.9,10.9l3.6-3.6l3.6-3.5l-7-7l-7-7l7.5-7.5l7.5-7.5l5.5,5.5l5.6,5.5l3.5-3.6l3.5-3.6l-5.5-5.5l-5.5-5.5l7.2-7.2l7.2-7.2l7,7l7,7l3.6-3.6l3.6-3.6l-10.9-10.9l-10.9-10.9L164.3,163.8z" />
                            </svg>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif
    </div>
    <x-link-button href="{{ route('productos.show', $producto) }}"
        class="rounded-xl xs:mt-auto text-center hover:scale-105">
        VER PRODUCTO</x-link-button>
</div>
