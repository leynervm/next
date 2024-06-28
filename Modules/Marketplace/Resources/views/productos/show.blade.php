<x-app-layout>
    <x-slot name="breadcrumb">
        <x-link-breadcrumb text="TIENDA WEB" active>
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="8" cy="21" r="1" />
                    <circle cx="19" cy="21" r="1" />
                    <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                </svg>
            </x-slot>
        </x-link-breadcrumb>
    </x-slot>

    @php
        $image = $producto->getImageURL();
        $promocion = $producto->getPromocionDisponible();
        $descuento = $producto->getPorcentajeDescuento($promocion);
        $combo = $producto->getAmountCombo($promocion, $pricetype ?? null);
        $pricesale = $producto->obtenerPrecioVenta($pricetype ?? null);
    @endphp

    <div x-data="showproducto">
        <div class="flex flex-col gap-5" x-data="{ currentImage: '{{ $image }}' }">
            <div class="w-full md:flex bg-fondominicard">
                <div class="w-full md:w-[42%] md:px-3 py-2">
                    <style>
                        figure.zoom img:hover {
                            opacity: 0;
                        }

                        figure.zoom img:not(:hover) {
                            background: #fff;
                        }
                    </style>

                    <div class="w-full max-w-full h-96 rounded overflow-hidden relative">
                        @if ($image)
                            <template x-if="currentImage">
                                <figure id="imageproducto" x-ref="figure"
                                    class="zoom relative w-full h-full bg-no-repeat object-scale-down overflow-hidden"
                                    :style="'background-image: url(\'' + currentImage + '\');'" @mousemove="zoom"
                                    @touchmove="zoom">
                                    <img :src="currentImage" x-ref="image"
                                        class="zoom-hover block w-full h-full object-scale-down transition ease-linear duration-300">
                                </figure>
                            </template>

                            <template x-if="!currentImage">
                                <x-icon-image-unknown class="w-full h-full text-neutral-500" />
                            </template>
                        @else
                            <x-icon-image-unknown class="w-full h-full text-neutral-500" />
                        @endif

                        @if ($producto->marca)
                            @if ($producto->marca->image && $empresa->verLogomarca())
                                <div class="absolute top-1 right-0 w-24 h-12">
                                    <img src="{{ $producto->marca->image->getMarcaURL() }}"
                                        class="block w-full h-full object-scale-down">
                                </div>
                            @endif
                        @endif
                        @if (count($producto->especificacions) > 0)
                            <ul
                                class="text-white py-3 text-[10px] absolute left-0 top-20 flex flex-col gap-1 justify-start items-start">
                                @foreach ($producto->especificacions()->take(5)->get() as $item)
                                    <li class="p-1 px-1.5 bg-next-500 rounded-xl tracking-[0.0625em] text-[10px]">
                                        <span class="font-medium">
                                            {{ $item->caracteristica->name }} : </span>
                                        {{ $item->name }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    @if ($image)
                        <div class="w-full relative px-10 mt-2">
                            <button id="previusthumbnail" @click="changeImage('prev')"
                                class="absolute text-colorsubtitleform top-1/2 left-0 -translate-y-1/2 h-10 w-6 shadow flex items-center justify-center disabled:opacity-25">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" class="w-6 h-6 block">
                                    <path d="M15 7L10 12L15 17" />
                                </svg>
                            </button>
                            <div class="w-full flex gap-1 overflow-hidden p-1" id="imagethumbs">
                                @foreach ($producto->images as $item)
                                    <div class="w-20 h-20 flex-shrink-0 border-2 border-white thumbnail cursor-pointer"
                                        :class="{
                                            'ring-1 ring-next-500 active': currentImage ==
                                                '{{ $item->getImageURL() }}',
                                            'hover:ring-1 ring-borderminicard opacity-70': currentImage !=
                                                '{{ $item->getImageURL() }}'
                                        }">
                                        <img class="w-full h-full object-cover object-center"
                                            src="{{ $item->getImageURL() }}"
                                            @click="currentImage = '{{ $item->getImageURL() }}'">
                                    </div>
                                @endforeach
                            </div>
                            <button id="nextthumbnail" @click="changeImage('next')"
                                class="absolute text-colorsubtitleform top-1/2 right-0 -translate-y-1/2 h-10 w-6 shadow flex items-center justify-center disabled:opacity-25">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" class="w-6 h-6 block">
                                    <path d="M10 7L15 12L10 17" />
                                </svg>
                            </button>
                        </div>
                    @endif
                </div>

                <div class="w-full flex-1 sm:py-2 sm:px-8">
                    <div class="w-full border-b border-b-borderminicard pb-5">
                        <div class="w-full flex gap-2 justify-between items-center flex-wrap">
                            <div>
                                <p href="#" class="text-colorlabel font-semibold">
                                    {{ $producto->marca->name }}</p>
                            </div>
                            <div class="text-[10px] text-colorsubtitleform flex gap-3">
                                @if (!empty($producto->partnumber))
                                    <span>N° Parte: {{ $producto->partnumber }}</span>
                                @endif

                                @if (!empty($producto->sku))
                                    <span>SKU: {{ $producto->sku }}</span>
                                @endif
                            </div>
                        </div>
                        <p class="text-neutral-500 leading-5">{{ $producto->name }}</p>
                    </div>

                    <div class="w-full grid xl:grid-cols-2 gap-5 mt-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-1 lg:grid-cols-1 gap-2">
                            <div class="w-full p-5 bg-neutral-100 text-xs">
                                @if (count($producto->especificacions) > 0)
                                    <h1 class="font-semibold">Especificaciones principales</h1>
                                    <ul class="text-neutral-500 py-3 text-[10px]">
                                        @foreach ($producto->especificacions()->take(2)->get() as $item)
                                            <li class="py-1">
                                                <span class="font-semibold">{{ $item->caracteristica->name }} :
                                                </span>{{ $item->name }}
                                            </li>
                                        @endforeach
                                    </ul>

                                    <a href="#especificacions" class="underline py-5 text-colorsubtitleform text-xs">Ver
                                        más especificaciones</a>
                                @else
                                    <p class="text-[10px]">PRODUCTO SIN ESPECIFICACIONES</p>
                                @endif
                            </div>


                            @if (count($shipmenttypes) > 0)
                                <div class="w-full flex flex-wrap gap-2 lg:pt-5 lg:border-t border-t-borderminicard">
                                    @foreach ($shipmenttypes as $item)
                                        <div
                                            class="w-full inline-flex max-w-full lg:max-w-[220px] xl:max-w-full gap-3 text-neutral-700 p-2 bg-fondominicard shadow">
                                            <div class="w-8 h-8 flex-shrink-0">
                                                @if ($item->isEnviodomicilio())
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 33"
                                                        fill-rule="evenodd" clip-rule="evenodd" fill="currentColor"
                                                        class="w-full h-full">
                                                        <path
                                                            d="M3 7.5C3 6.39543 3.89543 5.5 5 5.5H17C18.1046 5.5 19 6.39543 19 7.5V10.5H24.4338C25.1363 10.5 25.7873 10.8686 26.1488 11.471L28.715 15.748C28.9015 16.0588 29 16.4145 29 16.777V22.5C29 23.6046 28.1046 24.5 27 24.5H25.874C25.4299 26.2252 23.8638 27.5 22 27.5C20.0283 27.5 18.3898 26.0734 18.0604 24.1961C17.753 24.3887 17.3895 24.5 17 24.5H12.874C12.4299 26.2252 10.8638 27.5 9 27.5C7.12577 27.5 5.55261 26.211 5.1187 24.4711C3.91896 24.2875 3 23.2511 3 22V21.5C3 20.9477 3.44772 20.5 4 20.5C4.55228 20.5 5 20.9477 5 21.5V22C5 22.1459 5.06252 22.2773 5.16224 22.3687C5.65028 20.7105 7.18378 19.5 9 19.5C10.8638 19.5 12.4299 20.7748 12.874 22.5H17V16.5V7.5H5V8.5C5 9.05228 4.55228 9.5 4 9.5C3.44772 9.5 3 9.05228 3 8.5V7.5ZM19 15.5V12.5H24.4338L26.2338 15.5H19ZM19 17.5H27V22.5H25.874C25.4299 20.7748 23.8638 19.5 22 19.5C20.8053 19.5 19.7329 20.0238 19 20.8542V17.5ZM22 21.5C23.1046 21.5 24 22.3954 24 23.5C24 24.6046 23.1046 25.5 22 25.5C20.8954 25.5 20 24.6046 20 23.5C20 22.3954 20.8954 21.5 22 21.5ZM7 23.5C7 24.6046 7.89543 25.5 9 25.5C10.1046 25.5 11 24.6046 11 23.5C11 22.3954 10.1046 21.5 9 21.5C7.89543 21.5 7 22.3954 7 23.5ZM2 10.5C1.44772 10.5 1 10.9477 1 11.5C1 12.0523 1.44772 12.5 2 12.5H7C7.55228 12.5 8 12.0523 8 11.5C8 10.9477 7.55228 10.5 7 10.5H2ZM3 13.5C2.44772 13.5 2 13.9477 2 14.5C2 15.0523 2.44772 15.5 3 15.5H7C7.55228 15.5 8 15.0523 8 14.5C8 13.9477 7.55228 13.5 7 13.5H3ZM3 17.5C3 16.9477 3.44772 16.5 4 16.5H7C7.55229 16.5 8 16.9477 8 17.5C8 18.0523 7.55229 18.5 7 18.5H4C3.44772 18.5 3 18.0523 3 17.5Z" />
                                                    </svg>
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 33"
                                                        fill-rule="evenodd" clip-rule="evenodd" fill="currentColor"
                                                        class="w-full h-full">
                                                        <path
                                                            d="M18.4449 14.2024C19.4296 12.8623 20 11.5761 20 10.5C20 8.29086 18.2091 6.5 16 6.5C13.7909 6.5 12 8.29086 12 10.5C12 11.5761 12.5704 12.8623 13.5551 14.2024C14.3393 15.2698 15.2651 16.2081 16 16.8815C16.7349 16.2081 17.6607 15.2698 18.4449 14.2024ZM16.8669 18.7881C18.5289 17.3455 22 13.9227 22 10.5C22 7.18629 19.3137 4.5 16 4.5C12.6863 4.5 10 7.18629 10 10.5C10 13.9227 13.4712 17.3455 15.1331 18.7881C15.6365 19.2251 16.3635 19.2251 16.8669 18.7881ZM5 11.5H8.27078C8.45724 12.202 8.72804 12.8724 9.04509 13.5H5V26.5H10.5V22C10.5 21.4477 10.9477 21 11.5 21H20.5C21.0523 21 21.5 21.4477 21.5 22V26.5H27V13.5H22.9549C23.272 12.8724 23.5428 12.202 23.7292 11.5H27C28.1046 11.5 29 12.3954 29 13.5V26.5C29.5523 26.5 30 26.9477 30 27.5C30 28.0523 29.5523 28.5 29 28.5H3C2.44772 28.5 2 28.0523 2 27.5C2 26.9477 2.44772 26.5 3 26.5V13.5C3 12.3954 3.89543 11.5 5 11.5ZM19.5 23V26.5H12.5V23H19.5ZM17 10.5C17 11.0523 16.5523 11.5 16 11.5C15.4477 11.5 15 11.0523 15 10.5C15 9.94772 15.4477 9.5 16 9.5C16.5523 9.5 17 9.94772 17 10.5ZM19 10.5C19 12.1569 17.6569 13.5 16 13.5C14.3431 13.5 13 12.1569 13 10.5C13 8.84315 14.3431 7.5 16 7.5C17.6569 7.5 19 8.84315 19 10.5Z" />
                                                    </svg>
                                                @endif
                                            </div>
                                            <div class="flex-1 text-xs" x-data="{ descripcion: false }">
                                                <h1 class="font-semibold">{{ $item->name }}</h1>
                                                <div class="inline-flex items-center gap-1">
                                                    <button @click="descripcion = !descripcion"
                                                        class="inline-flex gap-2 items-center p-1  underline">
                                                        Ver descripción
                                                        <span class="w-4 h-4 inline-block">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                viewBox="0 0 24 24" color="currentColor"
                                                                fill="none" class="w-full h-full">
                                                                <path
                                                                    d="M9.00005 6C9.00005 6 15 10.4189 15 12C15 13.5812 9 18 9 18"
                                                                    stroke="currentColor" stroke-width="1.5"
                                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                            </svg>
                                                        </span>
                                                    </button>
                                                </div>
                                                <p x-cloak x-show="descripcion"
                                                    class="w-full block text-[10px] leading-3 text-colorsubtitleform mt-1">
                                                    {{ $item->descripcion }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif


                            <div class="w-full mt-5 text-center">
                                <button @click="openModal()"
                                    class="text-[10px] text-neutral-700 inline-flex gap-1 font-semibold items-center underline">
                                    <span class="w-6 h-6 block ">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            color="currentColor" fill="none" stroke="currentColor"
                                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                            class="w-full h-full">
                                            <path
                                                d="M11 22C10.1818 22 9.40019 21.6698 7.83693 21.0095C3.94564 19.3657 2 18.5438 2 17.1613C2 16.7742 2 10.0645 2 7M11 22L11 11.3548M11 22C11.3404 22 11.6463 21.9428 12 21.8285M20 7V11.5" />
                                            <path
                                                d="M18 18.0005L18.9056 17.0949M22 18C22 15.7909 20.2091 14 18 14C15.7909 14 14 15.7909 14 18C14 20.2091 15.7909 22 18 22C20.2091 22 22 20.2091 22 18Z" />
                                            <path
                                                d="M7.32592 9.69138L4.40472 8.27785C2.80157 7.5021 2 7.11423 2 6.5C2 5.88577 2.80157 5.4979 4.40472 4.72215L7.32592 3.30862C9.12883 2.43621 10.0303 2 11 2C11.9697 2 12.8712 2.4362 14.6741 3.30862L17.5953 4.72215C19.1984 5.4979 20 5.88577 20 6.5C20 7.11423 19.1984 7.5021 17.5953 8.27785L14.6741 9.69138C12.8712 10.5638 11.9697 11 11 11C10.0303 11 9.12883 10.5638 7.32592 9.69138Z" />
                                            <path d="M5 12L7 13" />
                                            <path d="M16 4L6 9" />
                                        </svg>
                                    </span>
                                    CONSULTAR STOCK
                                    <span class="w-4 h-4 inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            color="currentColor" fill="none" stroke="currentColor"
                                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                            class="w-full h-full">
                                            <path d="M9.00005 6C9.00005 6 15 10.4189 15 12C15 13.5812 9 18 9 18" />
                                        </svg>
                                    </span>
                                </button>
                            </div>
                        </div>

                        <div class="text-neutral-700">
                            <div class="w-full flex items-center justify-between gap-2">
                                <div class="w-full flex-1">
                                    @if ($pricesale > 0)
                                        <h1 class="font-semibold text-3xl">
                                            {{ $moneda->simbolo }}
                                            {{ formatDecimalOrInteger($pricesale, 2, ', ') }}
                                        </h1>

                                        @if ($descuento > 0 && $empresa->verOldprice())
                                            <span class="text-colorsubtitleform text-xs line-through text-red-600">
                                                {{ $moneda->simbolo }}
                                                {{ getPriceAntes($pricesale, $descuento, null, ', ') }}
                                            </span>
                                        @endif
                                        @if ($empresa->verDolar())
                                            <h1 class="text-blue-700 font-medium text-xs">
                                                <small class="text-[10px]">$. </small>
                                                {{ convertMoneda($pricesale, 'USD', $empresa->tipocambio, 2, ', ') }}
                                                <small class="text-[10px]">USD</small>
                                            </h1>
                                        @endif

                                        <livewire:modules.marketplace.carrito.add-wishlist :producto="$producto"
                                            :empresa="$empresa" :moneda="$moneda" :pricetype="$pricetype" />
                                    @else
                                        <p class="text-colorerror text-[10px] font-semibold text-center">
                                            PRECIO DE VENTA NO ENCONTRADO</p>
                                    @endif
                                </div>
                            </div>

                            @if ($combo)
                                @if (count($combo->products) > 0)
                                    <div class="w-full flex flex-wrap gap-2 my-2">
                                        @foreach ($combo->products as $itemcombo)
                                            <div class="block w-28 cursor-pointer">
                                                <div class="block rounded w-full h-28 overflow-hidden shadow relative">
                                                    @if ($itemcombo->image)
                                                        <img src="{{ $itemcombo->image }}" alt=""
                                                            class="w-full h-full object-scale-down rounded">
                                                    @else
                                                        <x-icon-image-unknown class="w-full h-full text-neutral-500" />
                                                    @endif
                                                    <x-span-text text="GRATIS"
                                                        class="absolute bottom-1 right-1 leading-3 !tracking-normal !text-[9px] !py-0.5"
                                                        type="green" />
                                                </div>
                                                <h1 class="text-[10px] leading-3 font-medium mt-2">
                                                    {{ $itemcombo->name }}
                                                </h1>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            @endif


                            @if ($pricesale > 0)
                                <livewire:modules.marketplace.carrito.add-carrito :producto="$producto" :empresa="$empresa"
                                    :moneda="$moneda" :pricetype="$pricetype" />
                            @endif

                            @if ($promocion)
                                <p class="w-full p-1 mt-5 text-center text-xs leading-3 text-colorsubtitleform">
                                    @if ($promocion->limit > 0)
                                        @if ($promocion->expiredate)
                                            Promoción válida hasta el
                                            {{ formatDate($promocion->expiredate, 'DD MMMM Y') }}
                                            y/o agotar stock
                                        @else
                                            Promoción válida hasta agotar unidades disponibles.
                                        @endif

                                        @if ($promocion->limit > 0)
                                            <br>
                                            [{{ formatDecimalOrInteger($promocion->limit) }}
                                            {{ $promocion->producto->unit->name }}]
                                        @endif
                                    @else
                                        @if ($promocion->expiredate)
                                            Promoción válida hasta el
                                            {{ formatDate($promocion->expiredate, 'DD MMMM Y') }}
                                            y/o agotar stock
                                        @else
                                            Promoción válida hasta agotar stock.
                                        @endif
                                    @endif
                                </p>
                            @endif

                            {{-- <div class="w-full mt-5">
                            <x-button class="w-full block rounded-xl text-sm">AGREGAR AL CARRITO</x-button>
                        </div> --}}
                            @if (count($producto->garantiaproductos) > 0)
                                <div class="w-full border-t border-t-borderminicard mt-5">
                                    <div class="w-full" x-data="{ opengarantias: true }">
                                        <div class="flex gap-1 items-center text-xs py-3">
                                            <div class="w-6 h-6">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" color="currentColor" fill="none">
                                                    <path
                                                        d="M11.9982 2C8.99043 2 7.04018 4.01899 4.73371 4.7549C3.79589 5.05413 3.32697 5.20374 3.1372 5.41465C2.94743 5.62556 2.89186 5.93375 2.78072 6.55013C1.59143 13.146 4.1909 19.244 10.3903 21.6175C11.0564 21.8725 11.3894 22 12.0015 22C12.6135 22 12.9466 21.8725 13.6126 21.6175C19.8116 19.2439 22.4086 13.146 21.219 6.55013C21.1078 5.93364 21.0522 5.6254 20.8624 5.41449C20.6726 5.20358 20.2037 5.05405 19.2659 4.75499C16.9585 4.01915 15.0061 2 11.9982 2Z" />
                                                    <path d="M9 13C9 13 10 13 11 15C11 15 14.1765 10 17 9" />
                                                </svg>
                                            </div>
                                            <h1 class="font-semibold">Garantías del producto</h1>
                                            <button @click="opengarantias = !opengarantias"
                                                class="w-6 h-6 text-colorsubtitleform">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" color="currentColor" fill="none"
                                                    class="duration-200"
                                                    :class="!opengarantias ? ' -rotate-180' : ' rotate-0'">
                                                    <path
                                                        d="M18 9.00005C18 9.00005 13.5811 15 12 15C10.4188 15 6 9 6 9" />
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="w-full flex flex-col gap-2" x-cloak x-show="opengarantias"
                                            x-transition:enter="transition ease-in-out duration-300"
                                            x-transition:enter-start="opacity-0 transform scale-y-0 -translate-y-1/2"
                                            x-transition:enter-end="opacity-100 transform scale-y-100 translate-y-0"
                                            x-transition:leave="transition ease-in-out duration-300"
                                            x-transition:leave-start="opacity-100 transform scale-y-100 translate-y-0"
                                            x-transition:leave-end="opacity-0 transform scale-y-0 -translate-y-1/2">
                                            @foreach ($producto->garantiaproductos as $item)
                                                <x-simple-card
                                                    class="text-[10px] text-center border border-borderminicard !shadow-none p-2 font-semibold">
                                                    {{ $item->typegarantia->name }}
                                                    /
                                                    {{ $item->time }}
                                                    @if ($item->typegarantia->datecode == 'YYYY')
                                                        {{ $item->time > 1 ? 'AÑOS' : 'AÑO' }}
                                                    @else
                                                        {{ $item->time > 1 ? 'MESES' : 'MES' }}
                                                    @endif
                                                </x-simple-card>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full flex flex-col gap-2 lg:gap-0 lg:flex-wrap bg-fondominicard overflow-x-hidden">
            @if (count($producto->especificacions) > 0)
                <div class="w-full lg:w-[42%] lg:px-3 flex-shrink-0" id="especificacions">
                    <h1 class="font-bold py-3 border-b-2 border-b-borderminicard">Especificaciones</h1>
                    <table class="w-full text-[10px] mt-5">
                        <tbody>
                            @foreach ($producto->especificacions as $item)
                                <tr class="{{ $loop->index % 2 == 0 ? 'bg-neutral-100' : '' }}">
                                    <th class="p-2 py-5 text-left">{{ $item->caracteristica->name }}</th>
                                    <td class="p-2 py-5 text-left">{{ $item->name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
            <div class="w-full flex-1 overflow-x-hidden">
                <h1 class="font-medium p-3 text-colorsubtitleform border-b border-b-borderminicard">
                    Información adicional</h1>
                <div class="w-full block mt-5 ">
                    {{-- @if ($producto->detalleproducto)
                        {!! $producto->detalleproducto->descripcion !!}
                    @endif --}}
                </div>
            </div>
        </div>


        <div @keydown.escape.window="closeModal()"
            class="fixed z-20 h-screen inset-0 bg-neutral-700 bg-opacity-75 transition-opacity" x-show="open" x-cloak
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none">
            <!-- Modal -->
            <div class="w-full flex items-center justify-center min-h-screen">
                <div class="bg-fondobodymodal p-3 rounded-lg overflow-hidden shadow-xl w-full max-w-2xl transform transition-all"
                    @click.away="closeModal()">
                    <!-- Modal header -->
                    <div class="w-full flex items-start justify-between">
                        <h3 class="flex-1 w-full text-sm leading-4 font-medium text-colorsubtitleform">
                            {{ $producto->name }}</h3>
                        <span class="flex-shrink-0 cursor-pointer text-colorsubtitleform ml-2" @click="closeModal()">✕</span>
                    </div>

                    <div class="w-full flex flex-col gap-2 text-left mt-5 p-5">
                        @if (count($stocksucursals) > 0)
                            @foreach ($stocksucursals as $item)
                                <x-simple-card class="w-full p-3 rounded-xl flex justify-between items-center">
                                    <div class="text-xs">
                                        <p class="w-full flex gap-1 items-center font-medium text-colorlabel">
                                            <span class="inline-block w-4 h-4">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    color="currentColor" fill="none" class="w-full h-full">
                                                    <path
                                                        d="M2.97656 10.5146V15.009C2.97656 17.8339 2.97656 19.2463 3.85621 20.1239C4.73585 21.0015 6.15162 21.0015 8.98315 21.0015H12.9875" />
                                                    <path d="M6.98145 17.0066H10.9858" />
                                                    <path
                                                        d="M18.4941 13.5107C20.4292 13.5107 21.9979 15.0464 21.9979 16.9408C21.9979 19.0836 19.8799 20.1371 18.8695 21.7433C18.6542 22.0857 18.3495 22.0857 18.1187 21.7433C17.0767 20.1981 14.9902 19.0389 14.9902 16.9408C14.9902 15.0464 16.559 13.5107 18.4941 13.5107Z" />
                                                    <path d="M18.4941 17.0066H18.5031" />
                                                    <path
                                                        d="M17.7957 2.00254L6.14983 2.03002C4.41166 1.94542 3.966 3.2116 3.966 3.83056C3.966 4.38414 3.89055 5.19117 2.82524 6.70798C1.75993 8.22478 1.83998 8.67537 2.44071 9.72544C2.93928 10.5969 4.20741 10.9374 4.86862 10.9946C6.96883 11.0398 7.99065 9.32381 7.99065 8.1178C9.03251 11.1481 11.9955 11.1481 13.3158 10.8016C14.6385 10.4545 15.7717 9.2118 16.0391 8.1178C16.195 9.47735 16.6682 10.2707 18.0663 10.8158C19.5145 11.3805 20.7599 10.5174 21.3848 9.9642C22.0096 9.41096 22.4107 8.18278 21.2967 6.83288C20.5285 5.90195 20.2084 5.02494 20.1032 4.11599C20.0423 3.58931 19.9888 3.02336 19.5971 2.66323C19.0247 2.13691 18.2035 1.97722 17.7957 2.00254Z" />
                                                </svg>
                                            </span>
                                            {{ $item->name }}
                                        </p>
                                        <p class="text-[10px] text-colorsubtitleform">
                                            DIRECCIÓN: {{ $item->direccion }}</p>
                                        <p class="text-[10px] text-colorsubtitleform">
                                            LUGAR: {{ $item->lugar }}</p>
                                    </div>

                                    <x-span-text :text="formatDecimalOrInteger($item->total) . ' UND'"
                                        class="!text-colorsubtitleform text-xs rounded-xl font-medium" />
                                </x-simple-card>
                            @endforeach

                            <div class="w-full rounded-xl bg-next-50 p-2 border border-next-300">
                                <p class="text-next-500 text-sm">
                                    <span class="font-semibold">Importante:</span> Los puntos de recojo disponibles
                                    podrían variar en función
                                    del número de unidades adquiridas.
                                </p>
                            </div>
                        @endif
                    </div>

                    <!-- Modal footer -->
                    <div class="flex items-center justify-end mt-4">
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (count($recents) > 0)
        <div class="w-full bg-fondominicard mt-10">
            <h1 class="font-medium p-3 text-colorsubtitleform border-b border-b-borderminicard">
                Clientes que vieron este producto también vieron</h1>

            <div class="w-full relative xl:p-10">
                <div class="w-full flex overflow-x-hidden transition-transform ease-in-out duration-700"
                    id="recents">
                    @foreach ($recents as $item)
                        @php
                            $image = $item->getImageURL();
                            $promocion = $item->getPromocionDisponible();
                            $descuento = $item->getPorcentajeDescuento($promocion);
                            $combo = $item->getAmountCombo($promocion, $pricetype);
                            $pricesale = $item->obtenerPrecioVenta($pricetype ?? null);
                        @endphp

                        <x-card-producto-virtual :route="route('productos.show', $item)" :name="$item->name" :marca="$item->marca->name"
                            :partnumber="$item->partnumber" :image="$image" :promocion="$promocion"
                            wire:key="cardproduct{{ $item->id }}"
                            class="card-recents flex-shrink-0 overflow-hidden xs:w-[calc(100%/2)] sm:w-[calc(100%/3)] md:w-[calc(100%/4)] lg:w-[calc(100%/6)] xl:w-[calc(100%/7)] py-3 pb-7 px-3 transition ease-in-out duration-150">

                            @if ($pricesale > 0)
                                @if ($empresa->verDolar())
                                    <h1 class="text-blue-700 font-medium text-xs text-center text-xs">
                                        <small class="text-[10px]">$. </small>
                                        {{ convertMoneda($pricesale, 'USD', $empresa->tipocambio, 2, ', ') }}
                                        <small class="text-[10px]">USD</small>
                                    </h1>
                                @endif
                                <h1 class="text-neutral-700 text-center">
                                    <small class="text-[10px]">{{ $moneda->simbolo }}</small>
                                    <span
                                        class="inline-block font-semibold text-2xl">{{ formatDecimalOrInteger($pricesale, 2, ', ') }}</span>
                                    <small class="text-[10px]">{{ $moneda->currency }}</small>
                                </h1>
                                @if ($descuento > 0 && $empresa->verOldprice())
                                    <small class="block w-full line-through text-red-600 text-center text-xs">
                                        {{ $moneda->simbolo }}
                                        {{ getPriceAntes($pricesale, $descuento, null, ', ') }}
                                    </small>
                                @endif
                            @else
                                <p class="text-colorerror text-[10px] font-semibold text-center leading-3">
                                    PRECIO DE VENTA NO ENCONTRADO</p>
                            @endif
                        </x-card-producto-virtual>
                    @endforeach
                </div>
                <button id="leftrecents"
                    class="absolute bg-fondominicard text-colorsubtitleform top-1/2 left-0 -translate-y-1/2 h-16 w-8 shadow flex items-center justify-center disabled:opacity-25">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 block">
                        <path d="M15 7L10 12L15 17" />
                    </svg>
                </button>
                <button id="rightrecents"
                    class="absolute bg-fondominicard text-colorsubtitleform top-1/2 right-0 -translate-y-1/2 h-16 w-8 shadow flex items-center justify-center disabled:opacity-25">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 block">
                        <path d="M10 7L15 12L10 17" />
                    </svg>
                </button>
            </div>
        </div>
    @endif


    @if (count($sugerencias) > 0)
        <div class="w-full bg-fondominicard mt-10">
            <h1 class="font-medium text-colorsubtitleform p-3 border-b border-b-borderminicard">
                Tenemos más productos similares para ti</h1>

            <div class="w-full relative xl:p-10">
                <div class="w-full flex overflow-x-hidden" id="sugerencias">
                    @foreach ($sugerencias as $item)
                        @php
                            $image = $item->getImageURL();
                            $promocion = $item->getPromocionDisponible();
                            $descuento = $item->getPorcentajeDescuento($promocion);
                            $combo = $item->getAmountCombo($promocion, $pricetype);
                            $pricesale = $item->obtenerPrecioVenta($pricetype ?? null);
                        @endphp

                        <x-card-producto-virtual :route="route('productos.show', $item)" :name="$item->name" :marca="$item->marca->name"
                            :partnumber="$item->partnumber" :image="$image" :promocion="$promocion"
                            wire:key="cardproduct{{ $item->id }}"
                            class="card-sugerencias flex-shrink-0 overflow-hidden xs:w-[calc(100%/2)] sm:w-[calc(100%/3)] md:w-[calc(100%/4)] lg:w-[calc(100%/6)] xl:w-[calc(100%/7)] py-3 pb-7 px-3 transition ease-in-out duration-150">

                            @if ($pricesale > 0)
                                @if ($empresa->verDolar())
                                    <h1 class="text-blue-700 font-medium text-xs text-center text-xs">
                                        <small class="text-[10px]">$. </small>
                                        {{ convertMoneda($pricesale, 'USD', $empresa->tipocambio, 2, ', ') }}
                                        <small class="text-[10px]">USD</small>
                                    </h1>
                                @endif
                                <h1 class="text-neutral-700 text-center">
                                    <small class="text-[10px]">{{ $moneda->simbolo }}</small>
                                    <span
                                        class="inline-block font-semibold text-2xl">{{ formatDecimalOrInteger($pricesale, 2, ', ') }}</span>
                                    <small class="text-[10px]">{{ $moneda->currency }}</small>
                                </h1>
                                @if ($descuento > 0 && $empresa->verOldprice())
                                    <small class="block w-full line-through text-red-600 text-center text-xs">
                                        {{ $moneda->simbolo }}
                                        {{ getPriceAntes($pricesale, $descuento, null, ', ') }}
                                    </small>
                                @endif
                            @else
                                <p class="text-colorerror text-[10px] font-semibold text-center leading-3">
                                    PRECIO DE VENTA NO ENCONTRADO</p>
                            @endif
                        </x-card-producto-virtual>
                    @endforeach
                </div>
                <button id="leftsugerencias"
                    class="absolute text-colorsubtitleform top-1/2 left-0 -translate-y-1/2 h-16 w-8 shadow flex items-center justify-center disabled:opacity-25">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 block">
                        <path d="M15 7L10 12L15 17" />
                    </svg>
                </button>
                <button id="rightrecents"
                    class="absolute text-colorsubtitleform top-1/2 right-0 -translate-y-1/2 h-16 w-8 shadow flex items-center justify-center disabled:opacity-25">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 block">
                        <path d="M10 7L15 12L10 17" />
                    </svg>
                </button>
            </div>
        </div>
    @endif


    @if (count($similares) > 0)
        <div class="w-full bg-fondominicard mt-10">
            <h1 class="font-medium p-3 text-colorsubtitleform border-b border-b-borderminicard">
                También podría interesarte</h1>

            <div class="w-full relative xl:p-10">
                <button id="leftsimilares"
                    class="absolute text-colorsubtitleform top-1/2 left-0 -translate-y-1/2 h-16 w-8 shadow flex items-center justify-center disabled:opacity-25">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 block">
                        <path d="M15 7L10 12L15 17" />
                    </svg>
                </button>
                <div class="w-full flex overflow-x-hidden" id="similares">
                    @foreach ($similares as $item)
                        @php
                            $image = $item->getImageURL();
                            $promocion = $item->getPromocionDisponible();
                            $descuento = $item->getPorcentajeDescuento($promocion);
                            $combo = $item->getAmountCombo($promocion, $pricetype);
                            $pricesale = $item->obtenerPrecioVenta($pricetype ?? null);
                        @endphp

                        <x-card-producto-virtual :route="route('productos.show', $item)" :name="$item->name" :marca="$item->marca->name"
                            :partnumber="$item->partnumber" :image="$image" :promocion="$promocion"
                            wire:key="cardproduct{{ $item->id }}"
                            class="card-similares flex-shrink-0 overflow-hidden xs:w-[calc(100%/2)] sm:w-[calc(100%/3)] md:w-[calc(100%/4)] lg:w-[calc(100%/6)] xl:w-[calc(100%/7)] py-3 pb-7 px-3 transition ease-in-out duration-150">

                            @if ($pricesale > 0)
                                @if ($empresa->verDolar())
                                    <h1 class="text-blue-700 font-medium text-xs text-center text-xs">
                                        <small class="text-[10px]">$. </small>
                                        {{ convertMoneda($pricesale, 'USD', $empresa->tipocambio, 2, ', ') }}
                                        <small class="text-[10px]">USD</small>
                                    </h1>
                                @endif
                                <h1 class="text-neutral-700 text-center">
                                    <small class="text-[10px]">{{ $moneda->simbolo }}</small>
                                    <span
                                        class="inline-block font-semibold text-2xl">{{ formatDecimalOrInteger($pricesale, 2, ', ') }}</span>
                                    <small class="text-[10px]">{{ $moneda->currency }}</small>
                                </h1>
                                @if ($descuento > 0 && $empresa->verOldprice())
                                    <small class="block w-full line-through text-red-600 text-center text-xs">
                                        {{ $moneda->simbolo }}
                                        {{ getPriceAntes($pricesale, $descuento, null, ', ') }}
                                    </small>
                                @endif
                            @else
                                <p class="text-colorerror text-[10px] font-semibold text-center leading-3">
                                    PRECIO DE VENTA NO ENCONTRADO</p>
                            @endif
                        </x-card-producto-virtual>
                    @endforeach
                </div>
                <button id="rightsimilares"
                    class="absolute text-colorsubtitleform top-1/2 right-0 -translate-y-1/2 h-16 w-8 shadow flex items-center justify-center disabled:opacity-25">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 block">
                        <path d="M10 7L15 12L10 17" />
                    </svg>
                </button>
            </div>
        </div>
    @endif

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('showproducto', () => ({
                open: false,

                init() {},
                openModal() {
                    this.open = true;
                    document.body.style.overflow = 'hidden';
                },
                closeModal() {
                    this.open = false;
                    document.body.style.overflow = 'auto';
                },
                changeImage(direction) {
                    let imagethumbs = $("#imagethumbs");
                    let thumbnail = $(".thumbnail");
                    var index = $('.thumbnail.active').index();
                    let left = $('#previusthumbnail');
                    let right = $('#nextthumbnail');
                    let moveX = Math.round($(imagethumbs).get(0).scrollWidth / $('.thumbnail').length);

                    if (direction === 'next') {
                        index = index < $('.thumbnail').length - 1 ? index + 1 : index;
                        thumbnail = thumbnail.get(index);

                        if (thumbnail.offsetLeft >= Math.round($(imagethumbs).outerWidth(true))) {
                            $(imagethumbs).animate({
                                scrollLeft: '+=' + moveX
                            }, 'fast');

                            if (thumbnail.offsetLeft + moveX >= $(imagethumbs).get(0).scrollWidth) {
                                $(left).prop('disabled', false);
                                $(right).prop('disabled', true);
                            }
                        }
                        $(left).prop('disabled', false);

                    } else if (direction === 'prev') {
                        index = index > 0 ? index - 1 : index;
                        thumbnail = thumbnail.get(index);

                        $(imagethumbs).animate({
                            scrollLeft: '-=' + moveX
                        }, 'fast');

                        if (thumbnail.offsetLeft < $(imagethumbs).get(0).scrollWidth) {
                            $(right).prop('disabled', false);
                        }

                        if (thumbnail.offsetLeft <= $(".thumbnail")[0].offsetLeft) {
                            $(left).prop('disabled', true);
                        }
                    }

                    this.currentImage = $(".thumbnail")[index].querySelector('img').getAttribute(
                        'src');
                }
            }))
        })

        document.addEventListener('DOMContentLoaded', function() {
            // $("#leftrecents, #leftsugerencias, #leftsimilares").prop('disabled', true);

            let recents = $("#recents");
            $("#rightrecents").click(() => hacerScroll(recents));
            $("#leftrecents").click(() => hacerScroll(recents, '-'));
            // disabledButtons(recents, '#leftrecents', '#rightrecents');

            let sugerencias = $("#sugerencias");
            $("#rightsugerencias").click(() => hacerScroll(sugerencias));
            $("#leftsugerencias").click(() => hacerScroll(sugerencias, '-'));
            disabledButtons(sugerencias, '#leftsugerencias', '#rightsugerencias');

            let similares = $("#similares");
            $("#rightsimilares").click(() => hacerScroll(similares));
            $("#leftsimilares").click(() => hacerScroll(similares, '-'));
            disabledButtons(similares, '#leftsimilares', '#rightsimilares');


            function hacerScroll(contenedor, type = "+") {
                $(contenedor).animate({
                    scrollLeft: type + '=' + Math.round($(contenedor).width())
                }, 'slow');
            }

            function disabledButtons(contenedor, left, right) {
                $(contenedor).scroll(function() {
                    if ($(contenedor).scrollLeft() <= 0) {
                        $(left).prop('disabled', true);
                        $(right).prop('disabled', false);
                    }

                    if ($(contenedor).scrollLeft() >= $(contenedor).get(0).scrollWidth - $(contenedor)
                        .width()) {
                        $(right).prop('disabled', true);
                        $(left).prop('disabled', false);
                    }
                });
            }

            function changeSlide(e) {
                let left = $('#previusthumbnail');
                let right = $('#nextthumbnail');
                let start = $(".thumbnail")[0].offsetLeft;
                let end = $(".thumbnail")[$('.thumbnail').length - 1].offsetLeft;
                let offset = $(".thumbnail")[e.index()].offsetLeft;

                if (offset == start) {
                    $(left).prop('disabled', true);
                    $(right).prop('disabled', false);
                } else {
                    $(left).prop('disabled', false);
                }

                if (offset == end) {
                    $(left).prop('disabled', false);
                    $(right).prop('disabled', true);
                } else {
                    $(right).prop('disabled', false);
                }

                // if (offset == start || offset == end) {
                //     $(left).prop('disabled', false);
                //     $(right).prop('disabled', false);
                // }

            }

            $(".thumbnail").click(function() {
                changeSlide($(this));
            });
        })

        function zoom(e) {
            var zoomer = e.currentTarget;
            e.offsetX ? offsetX = e.offsetX : offsetX = e.touches ? e.touches[0].pageX : 0
            e.offsetY ? offsetY = e.offsetY : offsetX = e.touches ? e.touches[0].pageX : 0
            x = offsetX / zoomer.offsetWidth * 100
            y = offsetY / zoomer.offsetHeight * 100
            zoomer.style.backgroundPosition = x + '% ' + y + '%';
            // console.log(e.offsetX);
            // console.log(e.touches);
        }
    </script>
</x-app-layout>
