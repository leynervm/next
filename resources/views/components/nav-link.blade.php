<a {{ $attributes->merge(['class' => $classes]) }}>
    {{-- :class="sidebarOpen ? 'md:flex md:space-x-3' : ' group'" --}}
    @if (isset($titulo))
        <span
            class="hidden group-hover:w-auto group-hover:p-1 group-hover:rounded group-hover:text-fondolinknav group-hover:absolute group-hover:bg-colorlinknav group-hover:text-xs group-hover:left-full group-hover:block">
            {{-- :class="sidebarOpen ? 'md:block' : 'hidden'" --}}
            {{ $titulo }}
        </span>
    @endif
    {{ $slot }}
</a>
{{-- QUIT md:block --}}
