<div {{ $attributes->merge(['class' => $classes]) }}>

    <div class="">
        @if (isset($titulo))
            <div class="w-full p-1 px-2 bg-fondotitlecardnext text-colortitlecardnext rounded-t-md">
                <h1 class="font-semibold text-[10px]">{{ $titulo }}</h1>
            </div>
        @endif

        <div class="p-2 relative">
            {{ $slot }}
        </div>
    </div>

    @if (isset($footer))
        <div class="flex flex-wrap gap-1 px-2 py-1 items-center {{ $alignFooter }}">
            {{ $footer }}
        </div>
    @endif

</div>
