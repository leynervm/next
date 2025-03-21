@props(['id' => null, 'maxWidth' => null, 'footerAlign' => 'justify-end'])

<x-jet-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <div class="flex gap-2 items-center justify-between p-2 py-3">
        <div class="text-left w-full flex-1">
            <h3 class="text-xs sm:text-lg !leading-none font-medium text-colorheadermodal">
                {{ $title }}</h3>
        </div>
        <span
            class="cursor-pointer flex-shrink-0 min-w-6 text-center font-semibold text-sm hover:text-red-600 disabled:opacity-25 transition ease-in duration-150"
            @click="show = false">✕</span>
    </div>

    <div class="px-2 py-3 text-colorlabel">
        {{ $content }}
    </div>

    @if (isset($footer))
        <div class="flex flex-row px-2 py-3 gap-2 text-right {{ $footerAlign }}">
            {{ $footer }}
        </div>
    @endif
</x-jet-modal>
