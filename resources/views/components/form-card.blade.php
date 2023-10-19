<div
    {{ $attributes->merge(['class' => 'relative bg-fondoform w-full flex flex-col sm:flex-row xl:flex-col border rounded border-shadowform p-3 shadow-md shadow-shadowform']) }}>
    <div class="sm:w-40 xl:w-full sm:flex-shrink-0 text-colortitleform mb-5 sm:mb-0 xl:mb-5 pr-0 sm:pr-3 xl:pr-0">
        <h1
            class="text-xs font-semibold relative before:absolute before:h-[2px] before:bg-colortitleform before:-bottom-1 {{ $widthBefore }}">
            {{ $titulo }}</h1>
        <p class="text-xs mt-2 text-colorsubtitleform">{{ $subtitulo }}</p>
    </div>

    {{ $slot }}

</div>
