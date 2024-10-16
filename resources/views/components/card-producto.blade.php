@props(['id' => rand()])
<div
    {{ $attributes->merge([
        'class' =>
            'w-full border border-borderminicard flex flex-col justify-between text-xs relative group rounded-lg hover:shadow-md hover:shadow-shadowminicard cursor-pointer',
    ]) }}>

    <div class="w-full flex flex-col gap-2">
        <div class="w-full relative">
            @if (isset($category) || isset($almacen))
                <div class="w-full absolute bottom-0 left-0 flex flex-wrap gap-1 justify-between p-1">
                    @if (isset($category))
                        <p class="font-medium leading-3 text-[10px] bg-next-500 text-white p-0.5 inline-block rounded">
                            {{ $category }}</p>
                    @endif
                    @if (isset($almacen))
                        <p class="font-medium leading-3 text-[10px] bg-orange-500 text-white p-0.5 inline-block rounded">
                            {{ $almacen }}</p>
                    @endif
                </div>
            @endif
            <div class="w-full h-40 rounded overflow-hidden" x-data="{ loadImage: false }" x-init="loadimagen('{{ $id }}')">
                @if (isset($image))
                    <x-loading-lazy-image x-show="loadImage == false" x-cloak />

                    <img id="{{ $id }}" data-src="{{ $image }}" alt="{{ $image }}"
                        x-on:load="loadImage = true" x-show="loadImage" style="display: none;"
                        class="w-full h-full object-scale-down">
                @else
                    <x-icon-image-unknown class="!w-full !h-full text-colorsubtitleform" />
                @endif
            </div>
        </div>

        <div class="w-full p-1">
            @if (isset($name))
                <h1
                    class="text-colorlabel text-[10px] font-semibold tracking-wide leading-3 text-justify xs:text-center mt-1">
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
        <div class="w-full flex flex-col gap-0.5 p-1">
            <div class="w-full flex items-end gap-1 justify-end mt-1">
                {{ $footer }}

            </div>
            @if (isset($messages))
                {{ $messages }}
            @endif
        </div>
    @endif

    @if (isset($promocion))
        <div class="w-auto h-auto bg-red-600 absolute -left-9 top-3 -rotate-[35deg] leading-3">
            <p class=" text-white text-[8px] inline-block font-semibold p-1 px-10">
                PROMOCIÓN</p>
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
                    {{ formatDecimalOrInteger($increment) }}
                    %
                </p>
            </h1>
        @endif
    @endif

    <script>
        function loadimagen(id) {
            const img = document.getElementById(id);
            if (img) {
                img.src = img.dataset.src;
            };
        }
    </script>
</div>
