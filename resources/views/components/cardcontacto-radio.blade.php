<div class="relative w-full">
    <input {{ $attributes->wire('model') }} class="sr-only peer" type="radio" value="{{ $value }}"
        name="{{ $name }}" id="{{ $id }}" />
    <label for="{{ $id }}"
        class="w-full shadow shadow-shadowminicard h-full hover:shadow-md hover:shadow-shadowminicard p-1 flex-wrap inline-flex items-end font-semibold text-center ring-1 ring-transparent text-textcardnext text-[10px] bg-fondominicard border border-gray-300 rounded-lg cursor-pointer hover:border-next-500 peer-checked:border-next-500 peer-checked:shadow-md peer-checked:shadow-shadowminicard transition ease-in-out duration-150">
        <div class="inline-block w-1/2">
            <p class="inline-block bg-fondospancardproduct text-textspancardproduct text-center p-1 rounded-lg">
                {{ $document }}</p>
            <p class="rounded-t-lg p-1 text-center">{{ $text }}</p>
        </div>
        <div class="inline-block w-1/2 border-l">
            @if (isset($phones))
                <div class="inline-flex justify-around gap-1 flex-wrap w-full">
                    @foreach ($phones as $item)
                        <span
                            class="bg-fondospancardproduct text-textspancardproduct inline-flex gap-1 items-center text-[10px] p-1 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                            </svg>
                            {{ $item['phone'] }}</span>
                    @endforeach
                </div>
            @endif
        </div>
        <div class="w-full block mt-1">
            <x-button size="xs" class="ml-auto"> Añadir Teléfono </x-button>
        </div>
    </label>
    <span
        class="bg-next-500 rounded-full p-0.5 absolute opacity-0 w-4 h-4 peer-checked:opacity-100 top-1 right-1 text-white duration-150">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="20 6 9 17 4 12" />
        </svg>
    </span>
</div>
