@props(['disabled' => false])

<button
    {{ $attributes->merge(['type' => 'button', 'class' => 'block border border-next-500 group relative font-semibold text-sm bg-transparent text-colorlinknav p-1.5 rounded-sm hover:bg-next-500 focus:bg-next-700 hover:border-next-500 focus:border-next-700 hover:ring-2 hover:text-white focus:ring-2 hover:ring-next-500 focus:ring-next-300 focus:text-white transition ease-in duration-150']) }}>

    <span>{{ $slot }}</span>
    @if (isset($icono))
        <span
            class="h-4 w-4 text-white absolute top-1/2 left-2/4 opacity-0 -translate-y-1/2 group-hover:left-3/4 group-focus:left-3/4 group-hover:opacity-100 group-focus:opacity-100 duration-150">
            {{ $icono }}
        </span>
    @endif

</button>
