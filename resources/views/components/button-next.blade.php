@props(['size' => 'sm', 'titulo' => null])

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
        $classesSize = 'px-5 py-12';
        $classesIcon = 'w-12 h-12';
    } else {
        $classesSize = 'w-20 sm:w-24 h-20 sm:h-24';
        $classesIcon = 'w-6 sm:w-6 h-6 sm:h-6';
    }
@endphp
<button
    {{ $attributes->merge(['class' => "$classesSize relative bg-fondominicard text-colorlinknav shadow shadow-shadowminicard p-3 rounded-xl block hover:shadow-md hover:shadow-shadowminicard transition-shadow ease-out duration-150", 'type' => 'button']) }}>
    <span class="block mx-auto {{ $classesIcon }}">
        {{ $slot }}
    </span>

    @if (isset($titulo))
        <p class="leading-3 text-center mt-1 text-[11px] font-bold">
            {{ $titulo }}</p>
    @endif
</button>
