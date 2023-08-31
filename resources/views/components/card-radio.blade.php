<div class="relative">
    <input {{ $attributes->wire('model') }} class="sr-only peer" type="radio" value="{{ $value }}" name="{{ $name }}"
        id="{{ $id }}" />
    <label for="{{ $id }}"
        {{ $attributes->merge(['class' => 'w-48 block shadow shadow-shadowminicard hover:shadow-md hover:shadow-shadowminicard font-semibold ring-1 ring-transparent text-next-500 text-xs bg-fondominicard border border-gray-300 rounded-lg cursor-pointer  hover:border-next-500 peer-checked:border-next-500 peer-checked:shadow-md peer-checked:shadow-shadowminicard transition ease-in-out duration-150']) }}>
        <p class="w-full rounded-t-lg p-1 text-center bg-next-500 text-white">{{ $text }}</p>
        <div class="w-full p-1">
            <h1 class="text-center py-1">S/. {{ $price }}</h1>
            @if (isset($especificaciones))
                <div class="inline-flex justify-around gap-1 flex-wrap w-full">
                    @foreach ($especificaciones as $item)
                        <span
                            class="bg-fondospancardproduct text-textspancardproduct text-[10px] p-1 rounded-lg">{{ $item['descripcion'] }}</span>
                    @endforeach
                </div>
            @endif
        </div>
    </label>
    <span
        class="bg-white rounded-full p-0.5 absolute opacity-0 w-4 h-4 peer-checked:opacity-100 top-1 right-1 text-next-500 duration-150">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="20 6 9 17 4 12" />
        </svg>
    </span>
</div>
