<div
    class="relative w-64 cursor-pointer rounded-lg p-1 bg-fondominicard text-textcardnext shadow shadow-shadowminicard hover:shadow-md">

    @if (isset($imagen))
        <div class="w-full h-32">
            <img src="https://www.proshop.dk/Images/915x900/3125677_4437a7fd5160.jpg" alt=""
                class="w-full h-full object-scale-down object-center">
        </div>
    @endif

    <p class="font-bold text-sm text-textcardproduct text-center mt-1">{{ $name }}</p>
    <p class="font-bold text-sm text-textcardproduct text-center mt-1">{{ $price }}</p>

    {{-- {{ print_r($almacens) }} --}}



    <div class="flex justify-between gap-1">
        <div class="inline-block bg-fondospancardproduct rounded text-textcardproduct text-xs p-1 font-semibold">
            {{ $cantidad }}
        </div>

        @if (count($almacens))
            @foreach ($almacens as $almacen)
                <span class="inline-block bg-green-500 rounded text-white text-xs p-1 font-semibold">
                    {{ $almacen['name'] }}
                </span>
            @endforeach
        @endif

    </div>
    <p class="mt-1 bg-fondospancardproduct rounded text-textcardproduct text-xs p-1 font-semibold">Precio Unitario : S/.
        100.00
    </p>

    @if (count($series))
        <h1 class="mt-3 mb-1 text-sm font-semibold underline text-textcardproduct">Series Registradas</h1>

        <div class="flex flex-wrap justify-start gap-1">
            @foreach ($series as $serie)
                <span
                    class="p-1 px-2 bg-fondospancardproduct rounded font-semibold inline-flex text-[10px] items-center gap-2 text-textspancardproduct">
                    <p class="">{{ $serie['serie'] }}</p>
                    <form action="#">
                        <button type="button" class="text-red-600 ring-1 ring-red-300 rounded p-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 6h18" />
                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                <line x1="10" x2="10" y1="11" y2="17" />
                                <line x1="14" x2="14" y1="11" y2="17" />
                            </svg>
                        </button>
                    </form>
                </span>
            @endforeach
        </div>
    @endif


    @if (isset($increment))
        <div
            class="absolute left-0 top-1 rounded-full p-1 w-8 h-8 bg-green-200 text-green-600 text-center text-[9px] font-bold">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-4 mx-auto" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="22 7 13.5 15.5 8.5 10.5 2 17" />
                <polyline points="16 7 22 7 22 13" />
            </svg>
            <span>{{ $increment }}</span>
        </div>
    @endif

    @if (isset($cotizacion))
        <span
            class="absolute left-0 top-10 rounded-lg p-1 bg-amber-200 text-amber-600 text-center text-[9px] font-bold">
            {{ $cotizacion }}
        </span>
    @endif



</div>
