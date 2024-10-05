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
    'classFooter' => 'absolute',
])
<div
    {{ $attributes->merge(['class' => 'w-full group flex flex-col justify-between text-xs relative cursor-pointer']) }}>

    @if ($type == 'link')
        <a href="{{ $route }}" class="w-full flex flex-col xl:gap-2">
        @else
            <div class="w-full flex flex-col gap-2">
    @endif

    <div class="w-full h-32 sm:h-40 overflow-hidden rounded md:rounded-xl relative {{ isset($image) ? 'bg-white' : '' }}">
        @if (isset($image))
            <img src="{{ $image }}" alt=""
                class="w-full h-full object-scale-down group-hover:scale-105 {{ $secondimage ? 'group-hover:opacity-0 duration-700' : 'scale-90 duration-1000' }} transition-all ease-in">
            @if ($secondimage)
                <img src="{{ $secondimage }}" alt=""
                    class="absolute opacity-0 top-0 object-scale-down w-full h-full group-hover:scale-105 group-hover:opacity-100 transition-all ease-in duration-700">
            @endif
        @else
            <x-icon-image-unknown class="w-full h-full text-colorsubtitleform" />
        @endif
    </div>

    <div class="p-1">
        <div class="w-full flex flex-wrap gap-3 justify-between py-1">
            <span class="inline-block text-colorsubtitleform text-[10px] sm:text-xs">{{ $marca }}</span>
            @if (!empty($partnumber))
                <span class="inline-block text-[10px] text-colorsubtitleform">
                    N° PARTE: {{ $partnumber }}</span>
            @endif
        </div>

        <p
            class="text-colorlabel text-[9px] sm:text-[10px] tracking-wide xl:mt-2 xl:pb-2 text-center block font-semibold leading-3 xs:text-center group-hover:text-hoverlinktable transition ease-in-out duration-150">
            {{ $name }}</p>

        {{ $slot }}

    </div>

    @if ($type == 'link')
        </a>
    @else
</div>
@endif

@if (isset($footer))
    <div class="w-full md:mt-6 lg:mt-4">
        {{ $footer }}
    </div>
@endif

@if (isset($promocion))
    <p class="{{ $classFooter }} bottom-1 w-full p-1 -z-[0] text-center text-[10px] leading-3 text-colorsubtitleform">
        @if ($promocion->limit > 0)
            @if ($promocion->expiredate)
                Promoción válida hasta el {{ formatDate($promocion->expiredate, 'DD MMMM Y') }}
                y/o agotar stock
            @else
                Promoción válida hasta agotar unidades disponibles.
            @endif

            @if ($promocion->limit > 0)
                [{{ formatDecimalOrInteger($promocion->limit) }}
                {{ $promocion->producto->unit->name }}]
            @endif
        @else
            @if ($promocion->expiredate)
                Promoción válida hasta el {{ formatDate($promocion->expiredate, 'DD MMMM Y') }}
                y/o agotar stock
            @else
                Promoción válida hasta agotar stock.
            @endif
        @endif
    </p>
    <div class="w-auto h-auto bg-red-600 absolute -left-9 top-3 -rotate-[35deg] leading-3">
        <p class="text-white text-[9px] inline-block font-medium p-1 px-10">
            @php
                $empresa = mi_empresa();
            @endphp
            @if ($empresa->isTitlePromocion())
                PROMOCIÓN
            @elseif($empresa->isTitleLiquidacion())
                LIQUIDACIÓN
            @else
                @if ($promocion->isDescuento())
                    - {{ formatDecimalOrInteger($promocion->descuento) }}% DSCT
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
