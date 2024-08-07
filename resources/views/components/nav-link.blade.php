@props(['titulo' => null, 'active' => false])

@php
    $classes = $active
        ? 'inline-flex group p-1.5 justify-center md:justify-start items-center text-colorlinknav cursor-pointer font-medium rounded-lg shadow text-hovercolorlinknav bg-hoverlinknav transition-all ease-in-out duration-150'
        : 'inline-flex group p-1.5 justify-center md:justify-start items-center text-colorlinknav cursor-pointer font-medium rounded-lg shadow hover:text-hovercolorlinknav hover:bg-hoverlinknav focus:bg-hoverlinknav focus:text-hovercolorlinknav transition-all ease-in-out duration-150';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
    {{-- :class="sidebarOpen ? 'md:flex md:space-x-3' : ' group'" --}}
    @if (isset($titulo))
        <span
            class="hidden leading-3 text-xs group-hover:w-auto group-hover:p-1 group-hover:rounded group-hover:text-hovercolorlinknav group-hover:absolute group-hover:bg-colorlinknav group-hover:text-xs group-hover:left-full group-hover:block">
            {{-- :class="sidebarOpen ? 'md:block' : 'hidden'" --}}
            {{ $titulo }}
        </span>
    @endif
    {{-- QUIT md:block --}}
</a>
