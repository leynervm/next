<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
    {{-- :class="sidebarOpen ? 'md:flex md:space-x-3' : ' group'" --}}
    @if (isset($titulo))
        <span
            class="hidden text-xs group-hover:w-auto group-hover:p-1 group-hover:rounded group-hover:text-fondolinknav group-hover:absolute group-hover:bg-colorlinknav group-hover:text-xs group-hover:left-full group-hover:block">
            {{-- :class="sidebarOpen ? 'md:block' : 'hidden'" --}}
            {{ $titulo }}
        </span>
    @endif
    
</a>
{{-- QUIT md:block --}}
