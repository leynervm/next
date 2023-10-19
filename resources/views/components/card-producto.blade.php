<div
    {{ $attributes->merge([
        'class' =>
            'w-full lg:w-52 bg-fondominicard flex flex-col justify-between p-1 text-xs relative group rounded shadow shadow-shadowminicard hover:shadow-md hover:shadow-shadowminicard cursor-pointer',
    ]) }}>

    <div class="w-full flex flex-col xs:flex-row lg:flex-col gap-2">
        <div class="w-full xs:w-36 flex-shrink-0 lg:w-full relative">

            @if (isset($category))
                <div
                    class="absolute bottom-1 left-1 bg-next-500 text-white p-1 rounded transition-all ease-in-out duration-150">
                    <p class="font-semibold leading-3 text-[9px]">
                        {{ $category }}</p>
                </div>
            @endif

            @if (isset($image))
                <div
                    class="w-full h-36 xs:h-full lg:h-32 rounded shadow shadow-shadowminicard border border-borderminicard">
                    <img src="{{ $image }}" alt="" class="w-full h-full object-scale-down">
                </div>
            @else
                <div class="w-full h-24 lg:h-32 rounded shadow shadow-shadowminicard border border-borderminicard">
                    <img src="" alt="" class="w-full h-full object-scale-down">
                </div>
            @endif
        </div>

        <div class="w-full">
            <h1 class="text-colortitleform text-[10px] font-semibold leading-3 text-justify lg:text-center mt-1">
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
            <div class="w-full flex items-end gap-1 justify-end mt-2">
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
        <h1
            class="absolute top-1 left-1 w-8 h-8 flex flex-col items-center justify-center p-1 rounded-full bg-green-500 text-white transition-all ease-in-out duration-150">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-2 h-2 block" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 11l6-6 6 6M6 19l6-6 6 6" />
            </svg>
            <p class="text-[9px]">
                {{ \App\Helpers\FormatoPersonalizado::getValue($increment) }} %
            </p>
        </h1>
    @endif
</div>
