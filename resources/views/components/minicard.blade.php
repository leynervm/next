<div {{ $attributes->merge(['class' => $classes]) }}>
    <div class="w-full h-full flex flex-col gap-1 justify-center">

        @if (isset($imagen))
            <div class="w-full h-12 rounded">
                {{ $imagen }}
            </div>
        @endif

        @if (isset($title) || isset($spanColor))
            <p class="relative text-[10px] leading-3 font-semibold text-center break-words">
                {{ $title }}
                @if (isset($spanColor))
                    {{ $spanColor }}
                @endif
            </p>
        @endif

        {{ $slot }}

        @if (isset($content))
            <p class="text-center"><x-span-text :text="$content" class="!tracking-normal" /></p>
        @endif
    </div>

    @if (isset($buttons))
        <div class="w-full flex flex-wrap gap-1 items-center {{ $alignFooter }}">
            {{ $buttons }}
        </div>
    @endif
</div>
