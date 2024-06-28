@props(['title', 'content' => null, 'size' => 'sm', 'imagen' => null, 'alignFooter' => 'justify-end'])

@php
    switch ($size) {
        case 'md':
            $dimensions = 'w-28 h-28';
            break;
        case 'lg':
            $dimensions = 'w-32 h-32';
            break;
        case 'xl':
            $dimensions = 'w-36 h-36';
            break;
        default:
            $dimensions = 'w-24 h-24';
    }

@endphp
<div
    {{ $attributes->merge(['class' => $dimensions . ' inline-block relative bg-fondominicard text-colorlinknav shadow shadow-shadowminicard p-1 rounded-xl hover:shadow-md hover:shadow-shadowminicard transition-shadow ease-out duration-150']) }}>
    <div class="w-full h-full flex flex-col gap-1 justify-between items-center">
        <div class="flex-1 flex flex-col justify-center items-center">
            @if (!is_null($imagen))
                <div class="w-full h-16 rounded overflow-hidden">
                    <img class="w-full h-full object-scale-down" src="{{ $imagen }}" alt="">
                </div>
            @endif

            @if (isset($title))
                <p class="relative text-[10px] leading-3 font-semibold text-center break-words">
                    {{ $title }}
                </p>
            @endif

            {{ $slot }}

            @if (isset($content))
                <x-span-text :text="$content" class="!tracking-normal !leading-3" />
            @endif
        </div>

        @if (isset($buttons))
            <div class="w-full flex flex-wrap gap-1 items-center {{ $alignFooter }}">
                {{ $buttons }}
            </div>
        @endif
    </div>
</div>
