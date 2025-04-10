@props([
    'type' => 'link',
    'route' => null,
    'name',
    'marca',
    'category' => null,
    'subcategory' => null,
    'partnumber' => null,
    'sku' => null,
    'image' => null,
    'secondimage' => null,
    'promocion' => null,
    'classFooter' => 'xl:absolute',
    'id' => rand(),
    'novedad' => false,
])
<div {{ $attributes->merge(['class' => 'w-full group flex flex-col relative']) }}>
    <a class="w-full group flex flex-1 flex-col justify-between text-xs relative cursor-pointer"
        href="{{ $route }}">
        <div class="w-full">
            <div x-data="{ imageLoaded: false }" x-init="const img = document.getElementById('{{ $id }}');
            if (img) {
                img.src = img.dataset.src;
            }"
                class="w-full h-36 max-w-full sm:h-40 overflow-hidden rounded md:rounded-xl relative {{ isset($image) ? 'bg-white' : '' }}">
                @if (isset($image))
                    <x-loading-lazy-image x-show="imageLoaded == false" x-cloak />

                    <img id="{{ $id }}" data-src="{{ $image }}" alt="{{ $image }}"
                        x-on:load="imageLoaded = true" x-show="imageLoaded" style="display: none;"
                        class="w-full h-full object-scale-down group-hover:scale-105 {{ $secondimage ? 'group-hover:opacity-0 duration-700' : 'scale-90 duration-1000' }} transition-all ease-in">
                    @if ($secondimage)
                        <img src="{{ $secondimage }}" alt="{{ $secondimage }}" fetchpriority="low" loading="lazy"
                            class="absolute opacity-0 top-0 object-scale-down w-full h-full group-hover:scale-105 group-hover:opacity-100 transition-all ease-in duration-700">
                    @endif
                @else
                    <x-icon-image-unknown
                        class="w-full h-full text-colorsubtitleform group-hover:text-hoverlinktable transition ease-in-out duration-150" />
                @endif
            </div>

            <div class="flex-1 flex flex-col">
                <div class="w-full flex flex-wrap gap-3 justify-between p-1">
                    <span class="inline-block text-colorsubtitleform text-[11px] sm:text-xs font-medium">
                        {{ $marca }}</span>

                    @if ($subcategory)
                        <x-span-text :text="$subcategory" class="!py-0.5" />
                    @endif
                </div>

                @if ($novedad)
                    <div class="px-1 pb-1">
                        @if (!empty($empresa->textnovedad))
                            <span class="span-novedad">
                                {{ $empresa->textnovedad }}</span>
                        @endif
                        <x-icon-novedad />
                    </div>
                @endif

                @if (!empty($sku))
                    <p class="px-1 inline-block text-[10px] text-colorsubtitleform leading-none">
                        SKU: {{ $sku }}</p>
                @endif

                <p
                    class="px-1 text-colorlabel text-[11px] font-semibold !leading-tight tracking-wide xl:mt-2 pb-2 text-center block xs:text-center group-hover:text-hoverlinktable transition ease-in-out duration-150">
                    {{ $name }}</p>

                {{ $slot }}

            </div>
        </div>

        @if (isset($footer))
            {{-- {{ isset($promocion) ? '' : 'xl:pt-9' }} --}}
            <div class="w-full">
                {{ $footer }}
            </div>
        @endif

        @if (isset($promocion))
            <div class="w-auto h-auto bg-red-600 absolute -left-9 top-3 -rotate-[35deg] leading-3">
                <p class="text-white text-[9px] inline-block font-medium p-1 px-10">
                    @if ($empresa->isTitlePromocion())
                        PROMOCIÓN
                    @elseif($empresa->isTitleOferta())
                        OFERTA
                    @elseif($empresa->isTitleLiquidacion())
                        LIQUIDACIÓN
                    @else
                        @if ($promocion->isDescuento())
                            - {{ decimalOrInteger($promocion->descuento) }}%
                        @endif
                        {{ getTextPromocion($promocion->type) }}
                    @endif
                </p>
            </div>
        @endif
    </a>

    @if (isset($buttonscart))
        {{ $buttonscart }}
    @endif
</div>
