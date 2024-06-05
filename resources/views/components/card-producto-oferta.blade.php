@props(['producto', 'empresa', 'moneda', 'pricetype' => null])

@php
    $image = $producto->getImageURL();
    $promocion = $producto->getPromocionDisponible();
    $descuento = $producto->getPorcentajeDescuento($promocion);
    $combo = $producto->getAmountCombo($promocion, $pricetype);
    $priceCombo = $combo ? $combo->total : 0;
    $mensajeprecios = null;

    if ($empresa->usarLista()) {
        if ($pricetype) {
            $price = $producto->calcularPrecioVentaLista($pricetype);
            $price = !is_null($promocion) && $promocion->isRemate() ? $producto->precio_real_compra : $price;

            $pricesale = $descuento > 0 ? $producto->getPrecioDescuento($price, $descuento, 0, $pricetype) : $price;
        } else {
            $mensajeprecios = '<p class="text-[10px] text-colorerror leading-3 py-2">
                    CONFIGURAR LISTA DE PRECIOS PARA TIENDA WEB...</p>';
        }
    } else {
        $price = $producto->pricesale;
        $price = !is_null($promocion) && $promocion->isRemate() ? $producto->pricebuy : $price;
        $pricesale = $descuento > 0 ? $producto->getPrecioDescuento($price, $descuento, 0) : $price;
    }
@endphp

<div class="w-52 card-not-unknown relative bg-fondominicard flex flex-col justify-center px-6 pt-3 pb-6 min-h-[24rem]">
    <div class="w-full relative">
        @if (isset($promocion))
            @php
                $fondopromocion = $promocion->isRemate() ? 'bg-amber-400' : 'bg-red-600';
                $colorpromocion = $promocion->isRemate() ? 'text-red-600' : 'text-white';
            @endphp
            <div
                class="absolute top-3 right-1 rounded-sm inline-flex items-center justify-center p-1 text-center h-6 w-14 whitespace-nowrap {{ $fondopromocion }} {{ $colorpromocion }}">
                @if ($promocion->isDescuento())
                    <span class="text-sm">-{{ formatDecimalOrInteger($promocion->descuento) }}%</span>
                @elseif ($promocion->isCombo())
                    <span class="text-sm">COMBO</span>
                @else
                    <span class="text-sm">REMATE</span>
                @endif
            </div>
        @endif

        <div class="w-full p-0">
            <div class="w-32 h-32 mx-auto mt-0 mb-4">
                @if ($image)
                    <img src="{{ $image }}" alt="product-case-iphone-11-transparente-image"
                        title="{{ $producto->slug }}" class="align-middle w-full h-full object-scale-down">
                @else
                    <x-icon-image-unknown class="w-full h-full text-neutral-500" />
                @endif
            </div>

            <div class="inline-flex flex-col">
                <div class="">
                    <div class="uppercase text-xs text-colorsubtitleform font font-semibold tracking-[.075rem]">
                        {{ $producto->marca->name }}</div>
                    <div class="w-full -tracking-normal overflow-hidden">
                        <h3 class="text-colorsubtitleform text-sm tracking-[-.019rem] overflow-hidden mb-0">
                            {{ $producto->name }}</h3>
                    </div>
                </div>
                <div class="mt-1">
                    @isset($price)
                        <div class="w-full text-colorsubtitleform whitespace-nowrap flex flex-col">
                            <div class="text-xl">
                                {{ $moneda->simbolo }}
                                {{ formatDecimalOrInteger($pricesale + $priceCombo, 2, ', ') }}
                            </div>
                        </div>

                        @if ($empresa->verDolar())
                            <h1 class="text-blue-700 font-medium text-xs">
                                $.
                                {{ convertMoneda($pricesale + $priceCombo, 'USD', $empresa->tipocambio, 2, ', ') }}
                                <small class="text-[10px]">USD</small>
                            </h1>
                        @endif

                        @if ($descuento > 0)
                            <div class="text-sm line-through text-red-600">
                                {{ $moneda->simbolo }}
                                {{ formatDecimalOrInteger($price + $priceCombo, 2, ', ') }}
                            </div>
                        @endif
                    @endisset
                </div>
            </div>
        </div>

        @if ($combo)
            @if (count($combo->products) > 0)
                <div class="w-full my-2">
                    @foreach ($combo->products as $itemcombo)
                        <div class="w-full flex gap-2 bg-body rounded relative">
                            <div
                                class="block rounded overflow-hidden flex-shrink-0 w-10 h-10 shadow relative hover:shadow-lg cursor-pointer">
                                @if ($itemcombo->image)
                                    <img src="{{ $itemcombo->image }}" alt=""
                                        class="w-full h-full object-scale-down">
                                @else
                                    <x-icon-image-unknown class="w-full h-full text-neutral-500" />
                                @endif
                            </div>
                            <h1 class="p-1 w-full flex-1 text-[10px] leading-3 text-left">
                                {{ $itemcombo->name }}</h1>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif

        {{-- <div class="mt-1 mb-7">
            <div class="relative inline-flex justify-center items-center" title="4.6">
                <svg class="star-grad"
                    style="position: absolute; z-index: 0; width: 0px; height: 0px; visibility: hidden;">
                    <defs>
                        <linearGradient id="starGrad450314912949914" x1="0%" y1="0%" x2="100%"
                            y2="0%">
                            <stop offset="0%" class="stop-color-first"
                                style="stop-color: rgb(247, 181, 0); stop-opacity: 1;">
                            </stop>
                            <stop offset="60%" class="stop-color-first"
                                style="stop-color: rgb(247, 181, 0); stop-opacity: 1;">
                            </stop>
                            <stop offset="60%" class="stop-color-final"
                                style="stop-color: rgb(203, 211, 227); stop-opacity: 1;">
                            </stop>
                            <stop offset="100%" class="stop-color-final"
                                style="stop-color: rgb(203, 211, 227); stop-opacity: 1;">
                            </stop>
                        </linearGradient>
                    </defs>
                </svg>
                <div class="relative inline-block align-middle">
                    <svg viewBox="0 0 51 48" class="w-3 h-3 mr-1">
                        <path class="star" d="m25,1 6,17h18l-14,11 5,17-15-10-15,10 5-17-14-11h18z"
                            style="fill: rgb(247, 181, 0); transition: fill 0.2s ease-in-out 0s;">
                        </path>
                    </svg>
                </div>
                <div class="relative inline-block align-middle">
                    <svg viewBox="0 0 51 48" class="w-3 h-3 mr-1">
                        <path class="star" d="m25,1 6,17h18l-14,11 5,17-15-10-15,10 5-17-14-11h18z"
                            style="fill: rgb(247, 181, 0); transition: fill 0.2s ease-in-out 0s;">
                        </path>
                    </svg>
                </div>
                <div class="relative inline-block align-middle">
                    <svg viewBox="0 0 51 48" class="w-3 h-3 mr-1">
                        <path class="star" d="m25,1 6,17h18l-14,11 5,17-15-10-15,10 5-17-14-11h18z"
                            style="fill: rgb(247, 181, 0); transition: fill 0.2s ease-in-out 0s;">
                        </path>
                    </svg>
                </div>
                <div class="relative inline-block align-middle">
                    <svg viewBox="0 0 51 48" class="w-3 h-3 mr-1">
                        <path class="star" d="m25,1 6,17h18l-14,11 5,17-15-10-15,10 5-17-14-11h18z"
                            style="fill: rgb(247, 181, 0); transition: fill 0.2s ease-in-out 0s;">
                        </path>
                    </svg>
                </div>
                <div class="relative inline-block align-middle">
                    <svg viewBox="0 0 51 48" class="w-3 h-3 mr-1">
                        <path class="star" d="m25,1 6,17h18l-14,11 5,17-15-10-15,10 5-17-14-11h18z"
                            style="fill: url(&quot;#starGrad450314912949914&quot;); transition: fill 0.2s ease-in-out 0s;">
                        </path>
                    </svg>
                </div>
                <span class="text-sm text-neutral-400 pt-0.5">4.6</span>
            </div>
        </div> --}}
    </div>
    <x-link-button href="{{ route('productos.show', $producto) }}" class="rounded-xl mt-auto text-center hover:scale-110">
        VER PRODUCTO</x-link-button>
</div>
