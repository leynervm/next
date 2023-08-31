<a {{ $attributes->merge(['class' => $classes]) }}>

    <span>{{ $slot }}</span>

    @if (isset($icono))
        <span
            class="h-4 w-4 absolute top-1/2 left-2/4 opacity-0 -translate-y-1/2 group-hover:left-3/4 group-focus:left-3/4 group-hover:opacity-100 group-focus:opacity-100 duration-150">
            {{ $icono }}
        </span>
    @endif

</a>
