<div {{ $attributes->merge(['class' => 'w-full flex flex-col gap-1 mt-2']) }}>

    @if (isset($name) || isset($buttonpricemanual))
        <div class="w-full flex flex-wrap gap-1 items-start justify-between">
            @if (isset($name))
                <x-span-text :text="$name" class="leading-3 !tracking-normal" />
            @endif
            @if (isset($buttonpricemanual))
                {{ $buttonpricemanual }}
            @endif
        </div>
    @endif

    {{ $slot }}
</div>
