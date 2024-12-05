@props([
    'id' => rand(),
    'name' => null,
    'image' => null,
    'category' => null,
    'marca' => null,
    'increment' => null,
    'promocion' => null,
])

<div
    {{ $attributes->merge(['class' => 'w-full border border-borderminicard flex flex-col justify-between text-xs relative group rounded-lg md:rounded-xl']) }}>

    <div class="w-full flex flex-col gap-2">
        <div class="w-full px-1 relative h-40 rounded overflow-hidden" x-data="{ loadImage: false }" x-init="$nextTick(() => { lazy('{{ $id }}') });">
            @if (isset($image))
                <x-loading-lazy-image x-show="loadImage == false" x-cloak />

                <img id="{{ $id }}" data-src="{{ $image }}" alt="{{ $image }}"
                    x-on:load="loadImage = true" x-show="loadImage" style="display: none;"
                    class="w-full h-full object-scale-down">
            @else
                <x-icon-image-unknown class="!w-full !h-full text-colorsubtitleform" />
            @endif

            @if (isset($promocion))
                <div class="w-auto h-auto bg-red-600 absolute -left-9 top-3 -rotate-[35deg] leading-3">
                    <p class=" text-white text-[8px] inline-block font-semibold p-1 px-10">
                        PROMOCIÓN</p>
                </div>
            @endif
        </div>

        <div class="w-full p-1">
            @if (isset($marca) || isset($category))
                <div class="w-full flex flex-wrap gap-1 p-1 @if (!empty($marca) && !empty($category)) justify-between @endif">
                    @if (isset($marca))
                        <p class="inline-block text-colorsubtitleform font-semibold leading-3 text-[10px]">
                            {{ $marca }}</p>
                    @endif
                    @if (isset($category))
                        <p class="inline-block text-colortitle font-semibold leading-3 text-[10px]">
                            {{ $category }}</p>
                    @endif
                </div>
            @endif

            @if (isset($name))
                <h1 class="text-colorlabel text-[10px] font-semibold leading-[.8rem] text-center mt-1 mb-2">
                    {{ $name }}</h1>
            @endif

            {{ $slot }}

            @if (isset($series))
                <div class="w-full flex flex-wrap gap-1">
                    {{ $series }}
                </div>
            @endif
        </div>
    </div>

    @if (isset($footer))
        <div class="w-full flex flex-col gap-0.5 p-1 mt-1">
            <div class="w-full flex items-end gap-1 justify-between">
                {{ $footer }}
            </div>
            @if (isset($messages))
                {{ $messages }}
            @endif
        </div>
    @endif

    @if (isset($increment))
        @if ($increment > 0)
            <h1
                class="absolute top-1 left-1 w-8 h-8 flex flex-col items-center justify-center p-1 rounded-full bg-green-500 text-white transition-all ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-2 h-2 block" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 11l6-6 6 6M6 19l6-6 6 6" />
                </svg>
                <p class="text-[8px] text-center leading-3">
                    {{ decimalOrInteger($increment) }} %</p>
            </h1>
        @endif
    @endif
</div>
