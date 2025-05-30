@props(['titulo', 'subtitulo' => null, 'classtitulo' => null])
<div
    {{ $attributes->merge(['class' => 'relative w-full flex flex-col border rounded-xl border-shadowform p-1 lg:p-2']) }}>
    <div class="w-full text-colortitleform mb-2">
        <h1 class="text-xs flex items-center gap-1 font-bold !leading-tight {{ $classtitulo }}">
            {{ $titulo }}</h1>
        <p class="text-xs text-colorsubtitleform">{{ $subtitulo }}</p>
    </div>

    {{ $slot }}

</div>
