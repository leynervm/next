@props(['fontSize' => 'text-[10px]'])
<a
    {{ $attributes->merge(['class' => $fontSize . ' block group relative font-semibold tracking-widest bg-next-500 text-white p-2.5 rounded-lg disabled:opacity-25 hover:bg-next-700 focus:bg-next-700 hover:border-next-700 focus:border-next-700 hover:ring-2 hover:ring-next-300 focus:ring-2 focus:ring-next-300 cursor-pointer transition ease-in duration-150']) }}>

    <span>{{ $slot }}</span>

    @if (isset($icono))
        <span
            class="h-4 w-4 absolute top-1/2 left-2/4 opacity-0 -translate-y-1/2 group-hover:left-3/4 group-focus:left-3/4 group-hover:opacity-100 group-focus:opacity-100 duration-150">
            {{ $icono }}
        </span>
    @endif
</a>
