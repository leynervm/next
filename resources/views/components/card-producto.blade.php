<div
    {{ $attributes->merge([
        'class' =>
            'w-full xs:w-52 bg-fondominicard flex flex-col justify-between p-1 text-xs relative group rounded shadow shadow-shadowminicard hover:shadow-md hover:shadow-shadowminicard cursor-pointer',
    ]) }}>

    <div class="w-full flex flex-col gap-2">
        <div class="w-full relative">

            @if (isset($category) || isset($almacen))
                <div class="w-full absolute bottom-0 left-0 flex flex-wrap gap-1 justify-between p-1">
                    @if (isset($category))
                        <p class="font-medium leading-3 text-[10px] bg-next-500 text-white p-0.5 inline-block rounded">
                            {{ $category }}</p>
                    @endif
                    @if (isset($almacen))
                        <p class="font-medium leading-3 text-[10px] bg-orange-500 text-white p-0.5 inline-block rounded">
                            {{ $almacen }}</p>
                    @endif
                </div>
            @endif

            @if (isset($image))
                <div
                    class="w-full h-36 xs:h-32 rounded shadow shadow-shadowminicard border border-borderminicard">
                    <img src="{{ $image }}" alt="" class="w-full h-full object-scale-down">
                </div>
            @else
                <div class="w-full h-24 xs:h-32 rounded shadow shadow-shadowminicard border border-borderminicard">
                    <svg class="w-full h-full text-neutral-500 block" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="16.5" cy="7.5" r="1.5" />
                        <path
                            d="M2 14.1354C2.66663 14.0455 3.3406 14.0011 4.01569 14.0027C6.87163 13.9466 9.65761 14.7729 11.8765 16.3342C13.9345 17.7821 15.3805 19.7749 16 22" />
                        <path d="M13.5 17.5C14.5 16.5 15.1772 16.2768 16 16" />
                        <path
                            d="M20 20.2132C18.6012 21.5001 16.3635 21.5001 12 21.5001C7.52166 21.5001 5.28249 21.5001 3.89124 20.1089C2.5 18.7176 2.5 16.4785 2.5 12.0001C2.5 7.63653 2.5 5.39882 3.78701 4" />
                        <path
                            d="M20.0002 16C20.5427 16 21.048 16.2945 21.3967 16.5638C21.5 15.3693 21.5 13.8825 21.5 12C21.5 7.52166 21.5 5.28249 20.1088 3.89124C18.7175 2.5 16.4783 2.5 12 2.5C9.59086 2.5 7.82972 2.5 6.5 2.71659" />
                        <path d="M2 2L22 22" />
                    </svg>
                </div>
            @endif
        </div>

        <div class="w-full">
            <h1 class="text-colortitleform text-[10px] font-semibold leading-3 text-justify xs:text-center mt-1">
                {{ $name }}</h1>

            {{ $slot }}

            @if (isset($series))
                {{-- <h1 class="w-full block text-[10px] mt-2">SERIES</h1> --}}
                <div class="w-full flex flex-wrap gap-1">
                    {{ $series }}
                </div>
            @endif

            @if (isset($pricetypes))
                <div class="w-full">
                    {{ $pricetypes }}
                </div>
            @endif

        </div>
    </div>

    @if (isset($footer))
        <div class="w-full flex flex-col gap-0.5">
            <div class="w-full flex items-end gap-1 justify-end mt-1">
                {{ $footer }}

            </div>
            @if (isset($messages))
                {{ $messages }}
            @endif
        </div>
    @endif

    @if (isset($discount))
        <div
            class="absolute top-1 left-1 w-10 h-10 group-hover:shadow group-hover:shadow-red-500 flex flex-col items-center justify-center rounded-full bg-red-500 text-white bg-opacity-80 group-hover:bg-opacity-100 transition-all ease-in-out duration-150">
            <h1 class="font-semibold leading-3 text-[9px]">
                {{ \App\Helpers\FormatoPersonalizado::getValue($discount) }}%</h1>
            <p class="leading-3 text-[7px]">DSCT</p>
        </div>
        {{-- <h1
            class="absolute w-8 h-8 top-1 left-1 flex flex-col items-center justify-center p-1 rounded-full bg-green-500 text-white transition-all ease-in-out duration-150">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-2 h-2 block" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 11l6-6 6 6M6 19l6-6 6 6" />
            </svg>
            <p class="font-semibold text-[8px]">
                {{ \App\Helpers\FormatoPersonalizado::getValue($discount) }} %</p>
        </h1> --}}
    @endif

    @if (isset($increment))
        @if ($increment > 0)
            <h1
                class="absolute top-1 left-1 w-8 h-8 flex flex-col items-center justify-center p-1 rounded-full bg-green-500 text-white transition-all ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-2 h-2 block" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 11l6-6 6 6M6 19l6-6 6 6" />
                </svg>
                <p class="text-[8px] text-center leading-3">
                    {{ \App\Helpers\FormatoPersonalizado::getValue($increment) }}
                    %
                </p>
            </h1>
        @endif
    @endif
</div>
