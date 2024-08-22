@props(['size' => 'sm', 'size', 'titulo' => null])

@php
    if ($size == 'xs') {
        $classesSize = 'w-20 h-20';
        $classesIcon = 'w-4 h-4';
    } elseif ($size == 'md') {
        $classesSize = 'w-28 h-28';
        $classesIcon = 'w-8 h-8';
    } elseif ($size == 'lg') {
        $classesSize = 'w-32 h-32';
        $classesIcon = 'w-10 h-10';
    } elseif ($size == 'xl') {
        $classesSize = 'w-40 h-40 p-5 ';
        $classesIcon = 'w-12 h-12';
    } else {
        $classesSize = 'w-20 sm:w-24 h-20 sm:h-24';
        $classesIcon = 'w-6 sm:w-6 h-6 sm:h-6';
    }
@endphp
<a
    {{ $attributes->merge(['class' => "inline-flex flex-col items-center justify-center relative bg-fondominicard text-colorlinknav shadow shadow-shadowminicard p-1 rounded-xl hover:shadow-md hover:shadow-shadowminicard $classesSize transition-shadow ease-out duration-150"]) }}>
    <span class="block mx-auto {{ $classesIcon }}">
        {{ $slot }}
    </span>

    @if (isset($titulo))
        <p class="leading-3 text-center mt-1 text-[10px] sm:text-xs font-bold">
            {{ $titulo }}
        </p>
    @endif
</a>
