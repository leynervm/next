@props(['size' => '[10px]', 'disabled' => false])

<button
    {{ $attributes->merge([
        'type' => 'button',
        'class' =>
            'text-[10px] bg-fondobutton rounded-sm text-colorbutton block group relative font-semibold tracking-widest p-2.5 rounded-xs disabled:opacity-25 hover:bg-fondohoverbutton focus:bg-fondohoverbutton hover:ring-2 hover:ring-ringbutton focus:ring-2 focus:ring-ringbutton transition ease-in duration-150',
    ]) }}>

    <span>{{ $slot }}</span>

    @if (isset($icono))
        <span
            class="h-4 w-4 absolute top-1/2 left-2/4 opacity-0 -translate-y-1/2 group-hover:left-3/4 group-focus:left-3/4 group-hover:opacity-100 group-focus:opacity-100 duration-150">
            {{ $icono }}
        </span>
    @endif
</button>

{{-- <button
    class="block group relative font-semibold text-sm bg-next-500 text-white p-2.5 px-7 rounded-sm hover:bg-next-700 focus:bg-next-700 hover:ring-2 hover:ring-next-300 focus:ring-2 focus:ring-next-300 transition ease-in duration-150">
    <span>{{ $slot }}</span>
    @if (isset($icono))
        <span
            class="h-4 w-4 absolute top-1/2 left-2/4 opacity-0 -translate-y-1/2 group-hover:left-3/4 group-hover:opacity-100 duration-150">
            {{ $icono }}
        </span>
    @endif
</button> --}}
