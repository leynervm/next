<div {{ $attributes->merge(['class' => 'w-full bg-body rounded shadow-md shadow-shadowform p-1 flex flex-col gap-1']) }}>

    @if (isset($name) || isset($buttonpricemanual))
        <div class="w-full flex flex-wrap gap-1 items-start justify-between">
            @if (isset($name))
                <span
                    class="text-[9px] font-semibold leading-3 p-1 bg-fondospancardproduct text-textspancardproduct rounded uppercase">
                    {{ $name }}</span>
            @endif
            @if (isset($buttonpricemanual))
                {{ $buttonpricemanual }}
            @endif
        </div>
    @endif

    {{ $slot }}
</div>
