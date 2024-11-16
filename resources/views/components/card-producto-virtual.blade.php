@props([
    'type' => 'link',
    'route' => null,
    'name',
    'marca',
    'partnumber' => null,
    'sku' => null,
    'image' => null,
    'secondimage' => null,
    'promocion' => null,
    'classFooter' => 'xl:absolute',
    'id' => rand(),
    'novedad' => false,
])
<div
    {{ $attributes->merge(['class' => 'w-full group flex flex-col justify-between text-xs relative cursor-pointer']) }}>
    <a href="{{ $route }}" class="w-full flex flex-col flex-1 xl:gap-2">
        <div x-data="{ imageLoaded: false }" x-init="const img = document.getElementById('{{ $id }}');
        if (img) {
            img.src = img.dataset.src;
        }"
            class="w-full h-32 sm:h-40 overflow-hidden rounded md:rounded-xl relative {{ isset($image) ? 'bg-white' : '' }}">
            @if (isset($image))
                <x-loading-lazy-image x-show="imageLoaded == false" x-cloak />

                <img id="{{ $id }}" data-src="{{ $image }}" alt="{{ $image }}"
                    x-on:load="imageLoaded = true" x-show="imageLoaded" style="display: none;"
                    class="w-full h-full object-scale-down group-hover:scale-105 {{ $secondimage ? 'group-hover:opacity-0 duration-700' : 'scale-90 duration-1000' }} transition-all ease-in">
                @if ($secondimage)
                    <img src="{{ $secondimage }}" alt="{{ $secondimage }}"
                        class="absolute opacity-0 top-0 object-scale-down w-full h-full group-hover:scale-105 group-hover:opacity-100 transition-all ease-in duration-700">
                @endif
            @else
                <x-icon-image-unknown class="w-full h-full text-colorsubtitleform" />
            @endif
        </div>

        <div class="p-1 flex-1">
            <div class="w-full flex flex-wrap gap-3 justify-between py-1">
                <span class="inline-block text-colorsubtitleform text-[11px] sm:text-xs font-medium">
                    {{ $marca }}</span>

                @if ($novedad)
                    <div class="inline-block">
                        <span class="p-1 px-1.5 rounded-lg sm:rounded-xl text-[10px] bg-purple-700 text-white relative">
                            NUEVO</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 128 128"
                            class="text-center w-5 h-5 inline-block">
                            <radialGradient id="IconifyId17ecdb2904d178eab8626" cx="68.884" cy="124.296" r="70.587"
                                gradientTransform="matrix(-1 -.00434 -.00713 1.6408 131.986 -79.345)"
                                gradientUnits="userSpaceOnUse">
                                <stop offset=".314" stop-color="#ff9800" />
                                <stop offset=".662" stop-color="#ff6d00" />
                                <stop offset=".972" stop-color="#f44336" />
                            </radialGradient>
                            <path
                                d="M35.56 40.73c-.57 6.08-.97 16.84 2.62 21.42c0 0-1.69-11.82 13.46-26.65c6.1-5.97 7.51-14.09 5.38-20.18c-1.21-3.45-3.42-6.3-5.34-8.29c-1.12-1.17-.26-3.1 1.37-3.03c9.86.44 25.84 3.18 32.63 20.22c2.98 7.48 3.2 15.21 1.78 23.07c-.9 5.02-4.1 16.18 3.2 17.55c5.21.98 7.73-3.16 8.86-6.14c.47-1.24 2.1-1.55 2.98-.56c8.8 10.01 9.55 21.8 7.73 31.95c-3.52 19.62-23.39 33.9-43.13 33.9c-24.66 0-44.29-14.11-49.38-39.65c-2.05-10.31-1.01-30.71 14.89-45.11c1.18-1.08 3.11-.12 2.95 1.5z"
                                fill="url(#IconifyId17ecdb2904d178eab8626)" />
                            <radialGradient id="IconifyId17ecdb2904d178eab8627" cx="64.921" cy="54.062" r="73.86"
                                gradientTransform="matrix(-.0101 .9999 .7525 .0076 26.154 -11.267)"
                                gradientUnits="userSpaceOnUse">
                                <stop offset=".214" stop-color="#fff176" />
                                <stop offset=".328" stop-color="#fff27d" />
                                <stop offset=".487" stop-color="#fff48f" />
                                <stop offset=".672" stop-color="#fff7ad" />
                                <stop offset=".793" stop-color="#fff9c4" />
                                <stop offset=".822" stop-color="#fff8bd" stop-opacity=".804" />
                                <stop offset=".863" stop-color="#fff6ab" stop-opacity=".529" />
                                <stop offset=".91" stop-color="#fff38d" stop-opacity=".209" />
                                <stop offset=".941" stop-color="#fff176" stop-opacity="0" />
                            </radialGradient>
                            <path
                                d="M76.11 77.42c-9.09-11.7-5.02-25.05-2.79-30.37c.3-.7-.5-1.36-1.13-.93c-3.91 2.66-11.92 8.92-15.65 17.73c-5.05 11.91-4.69 17.74-1.7 24.86c1.8 4.29-.29 5.2-1.34 5.36c-1.02.16-1.96-.52-2.71-1.23a16.09 16.09 0 0 1-4.44-7.6c-.16-.62-.97-.79-1.34-.28c-2.8 3.87-4.25 10.08-4.32 14.47C40.47 113 51.68 124 65.24 124c17.09 0 29.54-18.9 19.72-34.7c-2.85-4.6-5.53-7.61-8.85-11.88z"
                                fill="url(#IconifyId17ecdb2904d178eab8627)" />
                        </svg>
                    </div>
                @endif
            </div>

            @if (!empty($sku))
                <p class="inline-block text-[10px] text-colorsubtitleform leading-none">
                    SKU: {{ $sku }}</p>
            @endif

            <p
                class="text-colorlabel text-[9px] sm:text-[10px] tracking-wide xl:mt-2 xl:pb-2 text-center block font-semibold leading-3 xs:text-center group-hover:text-hoverlinktable transition ease-in-out duration-150">
                {{ $name }}</p>

            {{ $slot }}

        </div>
    </a>

    @if (isset($footer))
        <div class="w-full {{-- md:mt-6 lg:mt-4 --}}">
            {{ $footer }}
        </div>
    @endif

    @if (isset($promocion))
        <div class="w-auto h-auto bg-red-600 absolute -left-9 top-3 -rotate-[35deg] leading-3">
            <p class="text-white text-[9px] inline-block font-medium p-1 px-10">
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
</div>
