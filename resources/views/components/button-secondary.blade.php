@props(['size' => 'text-[10px]'])

<button
    {{ $attributes->merge([
        'type' => 'button',
        'class' =>
            $size .
            ' inline-flex items-center group relative font-semibold tracking-widest bg-red-500 text-white p-2.5 rounded-sm hover:bg-red-700 focus:bg-red-700 hover:border-red-700 focus:border-red-700 hover:ring-2 hover:ring-red-300 focus:ring-2 focus:ring-red-300 disabled:opacity-25 transition ease-in duration-150',
    ]) }}>
    {{ $slot }}
    @if (isset($icono))
        <span
            class="h-4 w-4 absolute top-1/2 left-2/4 opacity-0 -translate-y-1/2 group-hover:left-3/4 group-focus:left-3/4 group-hover:opacity-100 group-focus:opacity-100 duration-150">
            {{ $icono }}
        </span>
    @endif
</button>
