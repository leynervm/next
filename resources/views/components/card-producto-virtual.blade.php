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
            class="w-full h-60 max-w-full sm:h-40 overflow-hidden rounded md:rounded-xl relative {{ isset($image) ? 'bg-white' : '' }}">
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
                        @if (!empty($empresa->textnovedad))
                            <span
                                class="p-1 px-1.5 rounded-lg sm:rounded-xl text-[10px] bg-purple-700 text-white relative">
                                {{ $empresa->textnovedad }}</span>
                        @endif
                        <x-icon-novedad />
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
