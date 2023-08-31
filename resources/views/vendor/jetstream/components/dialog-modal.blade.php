@props(['id' => null, 'maxWidth' => null, 'footerAlign' => 'justify-end'])

<x-jet-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <div
        class="w-full font-medium flex justify-between items-center text-sm bg-fondoheadermodal text-colorheadermodal p-2">
        {{ $title }}
    </div>
    <div class="px-2 py-4">

        {{ $content }}

    </div>

    @if (isset($footer))
        <div class="flex flex-row px-2 py-4 gap-2 text-right {{ $footerAlign }}">
            {{ $footer }}
        </div>
    @endif

</x-jet-modal>
