<div {{ $attributes->merge(['class' => $classes]) }}>
    <div class="w-full h-full flex flex-col gap-1 justify-center">

        @if (isset($imagen))
            <div class="w-full h-8 rounded">
                {{ $imagen }}
            </div>
        @endif

        <p class="relative text-[10px] leading-3 font-semibold text-center break-words">
            {{ $title }}
            @if (isset($spanColor))
                {{ $spanColor }}
            @endif
        </p>

        {{ $slot }}

        @if (isset($content))
            <p class="text-center">
                <span
                    class="bg-fondospancardproduct text-textspancardproduct p-1 text-[10px] rounded-lg text-center font-semibold">{{ $content }}</span>
            </p>
        @endif
    </div>

    @if (isset($buttons))
        <div class="w-full flex flex-wrap gap-1 items-center {{ $alignFooter }}">
            {{ $buttons }}
        </div>
    @endif
</div>
