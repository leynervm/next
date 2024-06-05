<div
    {{ $attributes->merge([
        'class' =>
            'w-full xs:w-56 bg-fondominicard flex flex-col justify-between p-1 text-xs relative group rounded shadow shadow-shadowminicard hover:shadow-md hover:shadow-shadowminicard cursor-pointer',
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
            <div class="w-full h-40 rounded shadow shadow-shadowminicard border border-borderminicard overflow-hidden">
                @if (isset($image))
                    <img src="{{ $image }}" alt="" class="w-full h-full object-cover">
                @else
                    <x-icon-image-unknown class="w-full h-full text-neutral-500" />
                @endif
            </div>
        </div>

        <div class="w-full">
            @if (isset($name))
                <h1 class="text-colortitleform text-[10px] font-semibold leading-3 text-justify xs:text-center mt-1">
                    {{ $name }}</h1>
            @endif


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

    @if (isset($promocion))
        @php
            $fondopromocion = $promocion->isRemate() ? 'text-amber-400' : 'text-red-600';
            $colorpromocion = $promocion->isRemate() ? 'text-red-600' : 'text-white';
        @endphp
        <div class="absolute -top-1 -left-1">
            <span class="w-14 h-14 block relative">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                    class="w-full h-full {{ $fondopromocion }}" fill="currentColor" stroke="currentColor"
                    stroke-width="0">
                    <path fill="currentColor"
                        d="M18.9905 19H19M18.9905 19C18.3678 19.6175 17.2393 19.4637 16.4479 19.4637C15.4765 19.4637 15.0087 19.6537 14.3154 20.347C13.7251 20.9374 12.9337 22 12 22C11.0663 22 10.2749 20.9374 9.68457 20.347C8.99128 19.6537 8.52349 19.4637 7.55206 19.4637C6.76068 19.4637 5.63218 19.6175 5.00949 19C4.38181 18.3776 4.53628 17.2444 4.53628 16.4479C4.53628 15.4414 4.31616 14.9786 3.59938 14.2618C2.53314 13.1956 2.00002 12.6624 2 12C2.00001 11.3375 2.53312 10.8044 3.59935 9.73817C4.2392 9.09832 4.53628 8.46428 4.53628 7.55206C4.53628 6.76065 4.38249 5.63214 5 5.00944C5.62243 4.38178 6.7556 4.53626 7.55208 4.53626C8.46427 4.53626 9.09832 4.2392 9.73815 3.59937C10.8044 2.53312 11.3375 2 12 2C12.6625 2 13.1956 2.53312 14.2618 3.59937C14.9015 4.23907 15.5355 4.53626 16.4479 4.53626C17.2393 4.53626 18.3679 4.38247 18.9906 5C19.6182 5.62243 19.4637 6.75559 19.4637 7.55206C19.4637 8.55858 19.6839 9.02137 20.4006 9.73817C21.4669 10.8044 22 11.3375 22 12C22 12.6624 21.4669 13.1956 20.4006 14.2618C19.6838 14.9786 19.4637 15.4414 19.4637 16.4479C19.4637 17.2444 19.6182 18.3776 18.9905 19Z" />
                </svg>
                <h1
                    class="absolute {{ $colorpromocion }} top-0 left-0 w-full h-full flex flex-col text-center justify-center items-center font-semibold">
                    @if ($promocion->isDescuento())
                        <p class="text-xs leading-[0.5rem]">{{ formatDecimalOrInteger($promocion->descuento) }}
                            <small class="">%</small>
                        </p>
                        <small class="w-full text-[7px] leading-[0.5rem]">DSCT</small>
                    @elseif ($promocion->isCombo())
                        <small class="w-full text-[10px] leading-[0.6rem]">COM<br />BO</small>
                    @else
                        <small class="w-full text-[10px] leading-[0.5rem]">REM<br />ATE</small>
                    @endif
                </h1>
            </span>
        </div>

        {{-- <div class="absolute top-0 left-0">
            <span class="w-14 h-14 block relative">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                    class="w-full h-full text-red-600" fill="currentColor" stroke="currentColor" stroke-width="0">
                    <path fill="currentColor"
                        d="M18.9905 19H19M18.9905 19C18.3678 19.6175 17.2393 19.4637 16.4479 19.4637C15.4765 19.4637 15.0087 19.6537 14.3154 20.347C13.7251 20.9374 12.9337 22 12 22C11.0663 22 10.2749 20.9374 9.68457 20.347C8.99128 19.6537 8.52349 19.4637 7.55206 19.4637C6.76068 19.4637 5.63218 19.6175 5.00949 19C4.38181 18.3776 4.53628 17.2444 4.53628 16.4479C4.53628 15.4414 4.31616 14.9786 3.59938 14.2618C2.53314 13.1956 2.00002 12.6624 2 12C2.00001 11.3375 2.53312 10.8044 3.59935 9.73817C4.2392 9.09832 4.53628 8.46428 4.53628 7.55206C4.53628 6.76065 4.38249 5.63214 5 5.00944C5.62243 4.38178 6.7556 4.53626 7.55208 4.53626C8.46427 4.53626 9.09832 4.2392 9.73815 3.59937C10.8044 2.53312 11.3375 2 12 2C12.6625 2 13.1956 2.53312 14.2618 3.59937C14.9015 4.23907 15.5355 4.53626 16.4479 4.53626C17.2393 4.53626 18.3679 4.38247 18.9906 5C19.6182 5.62243 19.4637 6.75559 19.4637 7.55206C19.4637 8.55858 19.6839 9.02137 20.4006 9.73817C21.4669 10.8044 22 11.3375 22 12C22 12.6624 21.4669 13.1956 20.4006 14.2618C19.6838 14.9786 19.4637 15.4414 19.4637 16.4479C19.4637 17.2444 19.6182 18.3776 18.9905 19Z" />
                </svg>
                <h1
                    class="absolute text-colortitleform  top-0 left-0 w-full h-full flex flex-col text-center justify-center items-center font-semibold leading-3">
                    <p class="text-xs leading-3">{{ formatDecimalOrInteger($discount) }}
                        <small class="">%</small>
                    </p>
                    <small class="w-full text-[8px]">DSCT</small>
                </h1>
            </span>
        </div> --}}
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
                    {{ formatDecimalOrInteger($increment) }}
                    %
                </p>
            </h1>
        @endif
    @endif
</div>
