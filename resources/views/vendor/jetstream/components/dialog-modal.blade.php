@props(['id' => null, 'maxWidth' => null, 'footerAlign' => 'justify-end'])

<x-jet-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <div class="flex gap-2 items-center justify-between p-2 px-3 sm:p-4">
        <div class="text-left w-full flex-1">
            <h3 class="text-xs sm:text-lg leading-6 font-medium text-colorheadermodal">
                {{ $title }}</h3>
            {{-- <div class="mt-1">
                <p class="text-sm text-colorsubtitleform">
                    El archivo a importar debe tener la extencion <b>xlsx</b>
                </p>
            </div> --}}
        </div>
        <span
            class="cursor-pointer flex-shrink-0 font-semibold text-sm hover:text-red-600 disabled:opacity-25 transition ease-in duration-150"
            @click="show = false">✕</span>
    </div>

    {{-- <div
        class="w-full font-medium flex justify-between items-center text-sm bg-fondoheadermodal text-colorheadermodal p-2">
        {{ $title }}
    </div> --}}
    <div class="px-2 py-4 text-colorlabel">

        {{ $content }}

    </div>

    @if (isset($footer))
        <div class="flex flex-row px-2 py-4 gap-2 text-right {{ $footerAlign }}">
            {{ $footer }}
        </div>
    @endif
</x-jet-modal>
