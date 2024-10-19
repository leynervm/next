<div wire:init="loadProductos" x-data="carshoop">

    <x-loading-web-next class="!hidden" wire:loading.class.remove="!hidden" />

    <div class="w-full mb-2 xl:hidden">
        <button @click="sidebar = true, backdrop = true"
            class="p-2.5 tracking-wide inline-flex items-center justify-center gap-1 rounded-lg text-xs ring-1 ring-borderminicard shadow shadow-shadowminicard text-colorsubtitleform">
            FILTRAR
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3" />
            </svg>
        </button>
    </div>

    <div class="w-full lg:flex gap-2">
        <aside x-show="sidebar" class="sidebar-productos"
            :class="isSticky ? 'xl:sticky xl:top-24 xl:h-[calc(100vh-100px)]' : 'xl:h-[calc(100vh-150px)]'"
            x-transition:enter="opacity-0 transition ease-in-out duration-300"
            x-transition:enter-start="opacity-0 -translate-x-full" x-transition:enter-end="opacity-100 translate-x-0"
            x-transition:leave="opacity-100 transition ease-in-out duration-300"
            x-transition:leave-start="opacity-100 transition ease-in-out duration-300"
            x-transition:leave-end="opacity-100 -translate-x-full ease-in-out duration-300" id="sidebar-filter">
            <div class="w-full px-2 pb-2 xl:px-0 h-full max-h-full overflow-y-auto !overflow-x-hidden">
                <div class="w-full flex justify-end items-end xl:hidden">
                    <button class="p-2.5 font-semibold !leading-none text-colorsubtitleform"
                        @click="sidebar=false;backdrop = false;document.body.style.overflow = 'auto';">✕</button>
                </div>

                @if (count($productos) > 0)
                    <x-simple-card x-data="{ openfilter: true }" wire:key="dropdownprices"
                        class="w-full text-colorsubtitleform">
                        <button type="button"
                            class="w-full p-1 py-2 cursor-pointer flex gap-1 justify-between items-center"
                            @click="openfilter = !openfilter">
                            <h1 class="pl-2 font-semibold text-[11px]">ORDENAR</h1>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                class="block w-6 h-6">
                                <path d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                            </svg>
                        </button>
                        <div class="w-full flex flex-col text-xs" x-cloak x-transition>
                            @if (count($orderfilters) > 0)
                                <svg style="display:none;">
                                    <symbol id="precio_asc" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="m3 8 4-4 4 4" />
                                        <path d="M7 4v16" />
                                        <path d="M11 12h4" />
                                        <path d="M11 16h7" />
                                        <path d="M11 20h10" />
                                    </symbol>
                                    <symbol id="precio_desc" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="m3 16 4 4 4-4" />
                                        <path d="M7 20V4" />
                                        <path d="M11 4h4" />
                                        <path d="M11 8h7" />
                                        <path d="M11 12h10" />
                                    </symbol>
                                </svg>

                                @foreach ($orderfilters as $filter => $item)
                                    @if ($item['visible'])
                                        <div>
                                            <x-input type="radio" wire:model="filterselected"
                                                id="filter_{{ $filter }}" class="peer"
                                                value="{{ $filter }}" name="filterselected" style="display: none;"
                                                wire:key="key_{{ $filter }}" />
                                            <label for="filter_{{ $filter }}"
                                                class="w-full peer-checked:bg-fondospancardproduct peer-checked:text-textspancardproduct cursor-pointer block p-2.5 text-xs rounded text-left hover:bg-shadowminicard hover:text-textspancardproduct disabled:opacity-25 transition ease-in-out duration-150">
                                                <svg class="w-4 h-4 inline-block">
                                                    <use href="#{{ $filter }}"></use>
                                                </svg>
                                                {{ $item['text'] }}
                                            </label>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                            {{-- <button @click="if(!isXL){sidebar=false;backdrop=false}" wire:click="order('precio', 'asc')"
                                wire:loading.attr="disabled"
                                class="w-full p-2.5 text-xs rounded text-left hover:bg-shadowminicard hover:text-textspancardproduct disabled:opacity-25 transition ease-in-out duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="w-4 h-4 inline-block">
                                    <path d="m3 8 4-4 4 4" />
                                    <path d="M7 4v16" />
                                    <path d="M11 12h4" />
                                    <path d="M11 16h7" />
                                    <path d="M11 20h10" />
                                </svg>
                                DE MENOR A MAYOR PRECIO
                            </button> --}}
                            {{-- <button @click="if(!isXL){sidebar=false;backdrop=false}"
                                wire:click="order('precio', 'desc')" wire:loading.attr="disabled"
                                class="w-full p-2.5 text-xs rounded text-left hover:bg-shadowminicard hover:text-textspancardproduct disabled:opacity-25 transition ease-in-out duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="w-4 h-4 inline-block">
                                    <path d="m3 16 4 4 4-4" />
                                    <path d="M7 20V4" />
                                    <path d="M11 4h4" />
                                    <path d="M11 8h7" />
                                    <path d="M11 12h10" />
                                </svg>
                                DE MAYOR A MENOR PRECIO
                            </button>
                            <button @click="if(!isXL){sidebar=false;backdrop=false}" wire:click="order('name', 'asc')"
                                wire:loading.attr="disabled"
                                class="w-full p-2.5 text-xs rounded text-left hover:bg-shadowminicard hover:text-textspancardproduct disabled:opacity-25 transition ease-in-out duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="w-4 h-4 inline-block">
                                    <path d="m3 8 4-4 4 4" />
                                    <path d="M7 4v16" />
                                    <path d="M20 8h-5" />
                                    <path d="M15 10V6.5a2.5 2.5 0 0 1 5 0V10" />
                                    <path d="M15 14h5l-5 6h5" />
                                </svg>
                                NOMBRE ASCENDENTE
                            </button>
                            <button @click="if(!isXL){sidebar=false;backdrop=false}"
                                wire:click="order('name', 'desc')" wire:loading.attr="disabled"
                                class="w-full p-2.5 text-xs rounded text-left hover:bg-shadowminicard hover:text-textspancardproduct disabled:opacity-25 transition ease-in-out duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="w-4 h-4 inline-block">
                                    <path d="m3 16 4 4 4-4" />
                                    <path d="M7 4v16" />
                                    <path d="M15 4h5l-5 6h5" />
                                    <path d="M15 20v-3.5a2.5 2.5 0 0 1 5 0V20" />
                                    <path d="M20 18h-5" />
                                </svg>
                                NOMBRE DESCENDENTE
                            </button> --}}
                        </div>
                    </x-simple-card>
                @endif

                @if (count($categories) > 0)
                    <x-simple-card x-data="{ openfilter: {{ empty($searchcategorias) ? 'true' : 'true' }} }" wire:key="dropdowncategories"
                        class="w-full mt-3 text-colorsubtitleform">
                        <button type="button"
                            class="w-full p-1 py-2 cursor-pointer flex gap-1 justify-between items-center"
                            @click="openfilter = !openfilter">
                            <h1 class="pl-2 font-semibold text-[11px]">CATEGORÍAS</h1>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                class="block w-6 h-6">
                                <path d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                            </svg>
                        </button>
                        <div class="w-full flex flex-col {{-- max-h-60 overflow-y-auto --}}" x-cloak x-show="openfilter"
                            x-transition>
                            @foreach ($categories as $item)
                                <label for="category_id_{{ $item->id }}"
                                    class="text-xs font-medium hover:bg-shadowminicard hover:text-textspancardproduct rounded p-2 cursor-pointer inline-flex items-center gap-1 transition-colors ease-in-out duration-150">
                                    <input type="checkbox" wire:model.lazy="selectedcategorias" name="categorias[]"
                                        class="rounded p-2 outline-none cursor-pointer shadow-none shadow-0 border ring-0 focus:ring-0 hover:text-next-500 checked:text-next-500 focus:text-next-500"
                                        value="{{ $item->slug }}" id="category_id_{{ $item->id }}">
                                    {{ $item->name }}
                                </label>
                            @endforeach
                        </div>
                    </x-simple-card>
                @endif

                @if (count($subcategories) > 0)
                    <x-simple-card x-data="{ openfilter: {{ empty($searchsubcategorias) ? 'true' : 'true' }} }" wire:key="dropdownsubcategories"
                        class="w-full mt-3 text-colorsubtitleform">
                        <button type="button"
                            class="w-full p-1 py-2 cursor-pointer flex gap-1 justify-between items-center"
                            @click="openfilter = !openfilter">
                            <h1 class="pl-2 font-semibold text-[11px]">SUBCATEGORÍAS</h1>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" stroke-linecap="round"
                                stroke-linejoin="round" class="block w-6 h-6">
                                <path d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                            </svg>
                        </button>
                        <div class="w-full flex flex-col max-h-80 overflow-y-auto" x-cloak x-show="openfilter"
                            x-transition>
                            @foreach ($subcategories as $item)
                                <label for="subcategory_id_{{ $item->id }}"
                                    class="text-xs font-medium hover:bg-shadowminicard hover:text-textspancardproduct rounded p-2 cursor-pointer inline-flex items-center gap-1 transition-colors ease-in-out duration-150">
                                    <input type="checkbox" wire:model.lazy="selectedsubcategorias"
                                        name="subcategorias[]"
                                        class="rounded p-2 outline-none cursor-pointer shadow-none shadow-0 border ring-0 focus:ring-0 hover:text-next-500 checked:text-next-500 focus:text-next-500"
                                        value="{{ $item->slug }}" id="subcategory_id_{{ $item->id }}">
                                    {{ $item->name }}
                                </label>
                            @endforeach
                        </div>
                    </x-simple-card>
                @endif

                @if (count($marcas) > 0)
                    <x-simple-card x-data="{ openfilter: {{ empty($searchmarcas) ? 'true' : 'true' }} }" wire:key="dropdownmarcas"
                        class="w-full mt-3 text-colorsubtitleform">
                        <button type="button"
                            class="w-full p-1 py-2 cursor-pointer flex gap-1 justify-between items-center"
                            @click="openfilter = !openfilter">
                            <h1 class="pl-2 font-semibold text-[11px]">MARCAS</h1>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" stroke-linecap="round"
                                stroke-linejoin="round" class="block w-6 h-6">
                                <path d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                            </svg>
                        </button>
                        <div class="w-full flex flex-col max-h-80 overflow-y-auto" x-cloak x-show="openfilter"
                            x-transition>
                            @foreach ($marcas as $item)
                                <label for="marca_id_{{ $item->id }}"
                                    class="text-xs font-medium hover:bg-shadowminicard hover:text-textspancardproduct rounded p-2 cursor-pointer inline-flex items-center gap-1 transition-colors ease-in-out duration-150">
                                    <input type="checkbox" wire:model.lazy="selectedmarcas" name="marcas[]"
                                        class="rounded outline-none cursor-pointer shadow-none shadow-0 border ring-0 focus:ring-0 hover:text-next-500 checked:text-next-500 focus:text-next-500"
                                        value="{{ $item->slug }}" id="marca_id_{{ $item->id }}">
                                    {{ $item->name }}
                                </label>
                            @endforeach
                        </div>
                    </x-simple-card>
                @endif

                @if (count($especificacions) > 0)
                    @foreach ($especificacions as $caracteristica => $especificacion)
                        <x-simple-card x-data="{ openfilter: true }"
                            wire:key="dropdowncaracteristicas_{{ $caracteristica }}"
                            class="w-full mt-3 flex flex-col text-colorsubtitleform">
                            <button type="button"
                                class="w-full p-1 py-2 cursor-pointer flex gap-1 justify-between items-center"
                                @click="openfilter = !openfilter">
                                <h1 class="pl-2 font-semibold text-[11px]">{{ $caracteristica }}</h1>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" stroke-linecap="round"
                                    stroke-linejoin="round" class="block w-6 h-6">
                                    <path d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            </button>
                            <div class="w-full flex flex-col max-h-80 overflow-y-auto" x-cloak x-show="openfilter"
                                x-transition>
                                @foreach ($especificacion as $espec)
                                    <label for="especificacion_{{ $espec['slug'] }}"
                                        class="text-xs font-medium hover:bg-shadowminicard hover:text-textspancardproduct rounded p-2 cursor-pointer inline-flex items-center gap-1 transition-colors ease-in-out duration-150">
                                        <input type="checkbox" wire:model.lazy="selectedespecificacions"
                                            name="especificacions[]"
                                            class="rounded p-2 outline-none cursor-pointer shadow-none shadow-0 border ring-0 focus:ring-0 hover:text-next-500 checked:text-next-500 focus:text-next-500"
                                            value="{{ $espec['slug'] }}" id="especificacion_{{ $espec['slug'] }}">
                                        {{ $espec['name'] }}
                                    </label>
                                @endforeach
                            </div>
                        </x-simple-card>
                    @endforeach
                @endif
            </div>
        </aside>

        <div
            class="w-full flex-1 h-auto grid grid-cols-[repeat(auto-fill,minmax(170px,1fr))] sm:grid-cols-[repeat(auto-fill,minmax(200px,1fr))] gap-1 self-start">
            @foreach ($productos as $item)
                @php
                    $image = !empty($item->image) ? pathURLProductImage($item->image) : null;
                    $secondimage = !empty($item->image_2) ? pathURLProductImage($item->image_2) : null;
                    $promocion = verifyPromocion($item->promocion);
                    $descuento = getDscto($promocion);
                    $combo = $item->getAmountCombo($promocion, $pricetype);
                    $pricesale = $item->obtenerPrecioVenta($pricetype);
                    // $priceCombo = $combo ? $combo->total : 0;
                @endphp

                <x-card-producto-virtual :route="route('productos.show', $item)" :name="$item->name" :marca="$item->marca->name" :partnumber="$item->partnumber"
                    :image="$image" :secondimage="$secondimage" :promocion="$promocion" wire:key="cardproduct{{ $item->id }}"
                    x-data="{ addcart: isXL ? false : true }" @mouseover="addcart = true" @mouseleave="isXL && (addcart = false)"
                    class="w-full pt-0 xl:pb-14 rounded-md md:rounded-xl ring-1 ring-borderminicard hover:shadow-md hover:shadow-shadowminicard overflow-hidden transition ease-in-out duration-150"
                    x-init="$watch('isXL', value => { addcart = value ? false : true; })">
                    @if ($combo)
                        @if (count($combo->products) > 0)
                            <div class="w-full my-2">
                                @foreach ($combo->products as $itemcombo)
                                    <div class="w-full flex gap-1 rounded relative">
                                        <div class="block rounded overflow-hidden flex-shrink-0 w-10 h-10 relative">
                                            @if ($itemcombo->image)
                                                <img src="{{ $itemcombo->image }}" alt=""
                                                    class="w-full h-full object-scale-down">
                                            @else
                                                <x-icon-image-unknown class="!w-full !h-full text-colorsubtitleform" />
                                            @endif
                                        </div>
                                        <div class="p-1 w-full flex-1">
                                            <h1 class="text-[10px] leading-3 text-left text-colorsubtitleform">
                                                {{ $itemcombo->name }}</h1>
                                        </div>
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256"
                                            fill="currentColor" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="block opacity-60 absolute right-0 top-0 w-6 h-6 text-primary">
                                            <path
                                                d="M46.4,10.9C30.9,13.5,20,20.5,14.8,31.1c-3.8,7.6-4.8,13-4.8,25.1c0.1,11.1,1.4,20.4,4.8,32.9c2.6,9.8,7.2,23.2,10.3,29.7l2.6,5.5l60.1,60.1c52,51.9,60.5,60.1,62.8,60.7c3.6,1,6.1,0.9,9.8-0.3c2.9-1,6.9-4.8,42.8-40.6c30.3-30.2,39.9-40.3,41.2-42.7c1.9-3.8,2.2-8.9,0.7-12.6c-0.7-1.7-18.6-20-60.1-61.6c-65.7-65.8-59.7-60.5-75.4-66.4C87.1,12.4,61.7,8.4,46.4,10.9z M76,22.1c8.3,1.4,19.7,4.3,27.7,7.1c13.5,4.7,7.8-0.5,72.8,64.7c55.5,55.6,59.1,59.3,59.1,61.4c0,2.1-2.5,4.7-37.6,39.9c-20.7,20.7-38.5,38.3-39.5,39c-1,0.7-2.6,1.3-3.5,1.3c-2.4,0-119.5-116.9-121.4-121.3c-4.3-9.4-9.8-28.9-12.1-42c-1.5-8.9-1.6-24-0.1-29.4c2.1-7.9,5.8-13,12.2-16.9c2.9-1.8,9.6-4,14.3-4.8C53,20.3,68.6,20.8,76,22.1z" />
                                            <path
                                                d="M39.1,31.8c-5.3,2.5-8.3,7.2-8.3,13.1c-0.1,13.4,15.8,20,25.2,10.6c5.9-5.9,5.8-15.3-0.3-21.1C51.1,30,45,29.1,39.1,31.8z M48.8,41.8c1.4,1.3,1.6,5,0.1,6.3c-3.3,3.3-8.9,0.5-7.8-3.8C41.9,40.8,46.2,39.4,48.8,41.8z" />
                                            <path
                                                d="M83,82.9l-25.2,25.2l3.9,3.9l3.9,3.9L76,105.5L86.4,95l5.2,5.2l5.2,5.1l3.6-3.5l3.6-3.5l-4.9-5c-2.7-2.7-4.9-5.2-4.9-5.5c0-0.4,3.4-4,7.5-8.1l7.5-7.5l6.5,6.5l6.5,6.4l3.6-3.5l3.6-3.6l-10.2-10.2c-5.7-5.7-10.4-10.3-10.6-10.3S96.8,68.9,83,82.9z" />
                                            <path
                                                d="M108.2,107.7l-25.4,25.4l3.9,3.9l3.9,3.9l10.5-10.5l10.5-10.5l2.3,2.8c4.5,5.2,3.3,8.1-7.7,18.8c-2.7,2.7-5.4,5.7-5.9,6.6c-0.8,1.6-0.8,1.8,3,5.5l4,3.8l1-1.6c0.6-0.9,4.6-5.3,9-9.8c8.5-8.8,9.7-10.9,9.2-15.9l-0.2-2.9l3.6,0.1c4.9,0,7.9-1.8,14.7-8.9c4.3-4.5,5.5-6.2,6.3-9c1-3.2,1-3.8,0-7c-0.9-3.2-2-4.5-9.2-11.9l-8.3-8.4L108.2,107.7z M139.8,104.7c0,2.7-2,5.7-6.6,9.8c-5.8,5.3-7.9,5.3-13.1,0.5l-1.8-1.7l7.9-8l7.9-8l2.8,2.7C139,102.1,139.8,103.3,139.8,104.7z" />
                                            <path
                                                d="M137.5,137.1l-25.4,25.4l10.7,10.7l10.7,10.7l3.6-3.6l3.6-3.6L134,170l-6.8-6.8l7.5-7.5l7.5-7.5l5.5,5.5l5.6,5.5l3.5-3.6l3.5-3.6l-5.5-5.5l-5.5-5.5l7.2-7.2l7.2-7.2l6.8,6.8l6.9,6.8l3.5-3.6l3.6-3.6l-10.7-10.7L163,111.7L137.5,137.1z" />
                                            <path
                                                d="M164.3,163.8l-25.4,25.4l10.9,10.9l10.9,10.9l3.6-3.6l3.6-3.5l-7-7l-7-7l7.5-7.5l7.5-7.5l5.5,5.5l5.6,5.5l3.5-3.6l3.5-3.6l-5.5-5.5l-5.5-5.5l7.2-7.2l7.2-7.2l7,7l7,7l3.6-3.6l3.6-3.6l-10.9-10.9l-10.9-10.9L164.3,163.8z" />
                                        </svg>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endif

                    @if ($pricesale > 0)
                        @if ($empresa->verDolar())
                            <h1 class="text-blue-700 font-medium text-[1rem] text-center">
                                <small class="text-xs">$. </small>
                                {{ convertMoneda($pricesale, 'USD', $empresa->tipocambio, 2, ', ') }}
                            </h1>
                        @endif
                        <h1 class="text-colorlabel font-semibold text-sm sm:text-2xl text-center">
                            <small class="text-sm">{{ $moneda->simbolo }}</small>
                            {{ formatDecimalOrInteger($pricesale, 2, ', ') }}
                        </h1>
                        @if ($descuento > 0 && $empresa->verOldprice())
                            <h1 class="text-colorsubtitleform text-center text-[10px] text-red-600">
                                {{ $moneda->simbolo }}
                                <small class="text-sm inline-block line-through">
                                    {{ getPriceAntes($pricesale, $descuento, null, ', ') }}</small>
                            </h1>
                        @endif

                        @if (!empty($promocion))
                            <p class="w-full p-1 -z-[0] text-center text-[10px] leading-3 text-colorsubtitleform">
                                @if ($promocion->limit > 0)
                                    @if ($promocion->expiredate)
                                        Promoción válida hasta el {{ formatDate($promocion->expiredate, 'DD MMMM Y') }}
                                        y/o agotar stock
                                    @else
                                        Promoción válida hasta agotar unidades disponibles.
                                    @endif

                                    @if ($promocion->limit > 0)
                                        [{{ formatDecimalOrInteger($promocion->limit) }}
                                        {{ $promocion->producto->unit->name }}]
                                    @endif
                                @else
                                    @if ($promocion->expiredate)
                                        Promoción válida hasta el {{ formatDate($promocion->expiredate, 'DD MMMM Y') }}
                                        y/o agotar stock
                                    @else
                                        Promoción válida hasta agotar stock.
                                    @endif
                                @endif
                            </p>
                        @endif

                        @if ($item->stock > 0)
                            <x-slot name="footer">
                                <x-button-like class="absolute top-1 right-1" wire:loading.attr="disabled"
                                    wire:click="add_to_wishlist({{ $item->id }}, 1)" />

                                <div x-cloak x-show="addcart"
                                    class="w-full bg-body z-[2] p-1 flex flex-col {{-- xl:flex-row --}} items-end gap-1 justify-end xl:absolute xl:bottom-0"
                                    x-data="carshoop"
                                    x-transition:enter="opacity-0 transition ease-in-out duration-300"
                                    x-transition:enter-start="opacity-0 translate-y-full"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    x-transition:leave="transition translate-y-full ease-in-out duration-300"
                                    x-transition:leave-start="opacity-100 translate-y-0"
                                    x-transition:leave-end="opacity-0 translate-y-0">
                                    <div class="w-full flex-1 flex justify-center xl:justify-start gap-0.5 gap-x-3">
                                        <button type="button" wire:loading.attr="disabled"
                                            @click="parseFloat(qty--)" x-bind:disabled="qty == 1"
                                            class="font-medium hover:bg-neutral-400 hover:ring-2 hover:ring-neutral-300 text-xl w-9 h-9 bg-neutral-300 text-gray-500 p-2.5 pt-1.5 align-middle inline-flex items-center justify-center rounded-xl disabled:opacity-25 transition ease-in-out duration-150">-</button>
                                        <x-input x-model="qty"
                                            class="w-full rounded-xl flex-1 text-center text-colorlabel input-number-none"
                                            type="number" step="1" min="1"
                                            onkeypress="return validarNumero(event, 4)"
                                            @blur="if (!qty || qty === '0') qty = '1'" />
                                        <button type="button" wire:loading.attr="disabled"
                                            @click="parseFloat(qty++)"
                                            class="font-medium hover:bg-neutral-400 hover:ring-2 hover:ring-neutral-300 text-xl w-9 h-9 bg-neutral-300 text-gray-500 p-2.5 pt-1.5 align-middle inline-flex items-center justify-center rounded-xl disabled:opacity-25 transition ease-in-out duration-150">+</button>
                                    </div>

                                    <x-button-add-car type="button" wire:loading.attr="disabled"
                                        class="!rounded-xl w-full !flex gap-0.5 items-center justify-center text-[10px]"
                                        @click="add_to_cart({{ $item->id }})">AGREGAR</x-button-add-car>
                                </div>
                            </x-slot>
                        @else
                            <span
                                class="inline-block p-1 rounded-lg bg-red-600 text-white text-[10px] float-right xl:absolute xl:bottom-1 xl:right-1">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" fill="currentColor"
                                    stroke="currentColor" stroke-width="0.7" stroke-linecap="round"
                                    stroke-linejoin="round" class="inline-block w-4 h-4">
                                    <path
                                        d="m13.58,16.14c-2.68-5.62-.35-11.07,3.31-13.8,3.93-2.94,9.38-3.15,13.53-.47,1.56,1.01,2.8,2.31,3.71,3.9.92,1.6,1.4,3.33,1.44,5.16.05,1.81-.37,3.53-1.19,5.2,1.38.67,2.69,1.38,4.05,1.95,1.82.76,3.34,1.86,4.69,3.26,1.41,1.48,2.94,2.87,4.41,4.29.7.68.61,1.19-.28,1.63-1.81.88-3.62,1.77-5.45,2.63-.32.15-.44.32-.44.68.02,2.79,0,5.57.02,8.36,0,.56-.2.89-.72,1.14-5.35,2.58-10.69,5.16-16.02,7.76-.46.23-.86.2-1.31-.02-5.34-2.6-10.68-5.18-16.02-7.76-.48-.23-.69-.54-.68-1.06.01-2.79,0-5.57.02-8.36,0-.38-.1-.59-.47-.76-1.83-.86-3.64-1.75-5.45-2.63-.85-.41-.94-.93-.26-1.6,2.06-2,4.12-3.99,6.18-5.98.2-.2.45-.36.71-.49,1.9-.93,3.81-1.85,5.72-2.78.17-.08.33-.17.52-.27Zm-5.26,14.82c-.02.09-.04.13-.04.18,0,2.43,0,4.87.01,7.3,0,.16.18.38.34.46,5.01,2.44,10.02,4.87,15.04,7.28.18.08.47.08.65,0,5.01-2.41,10.01-4.83,15.01-7.24.29-.14.38-.31.38-.62-.01-2.32,0-4.64,0-6.95,0-.12-.02-.25-.03-.41-.21.1-.36.16-.52.24-2.65,1.28-5.29,2.56-7.94,3.84-.66.32-.89.28-1.41-.23-1.8-1.75-3.6-3.49-5.41-5.24-.13-.13-.27-.24-.47-.43-.14.17-.25.32-.38.45-1.79,1.74-3.58,3.47-5.37,5.2-.55.53-.77.56-1.46.23-2.63-1.27-5.26-2.55-7.89-3.82-.16-.08-.33-.15-.52-.23ZM24.01,1.6c-5.41-.03-9.9,4.26-9.94,9.49-.04,5.33,4.37,9.7,9.81,9.72,5.56.03,10.02-4.24,10.04-9.59.02-5.29-4.42-9.6-9.91-9.62Zm-1.5,26.63c-.06-.04-.14-.09-.23-.14-4.78-2.32-9.57-4.63-14.34-6.95-.29-.14-.42-.05-.61.13-1.61,1.57-3.24,3.14-4.85,4.71-.07.06-.12.14-.21.24,5.01,2.42,9.97,4.82,14.88,7.2,1.81-1.75,3.58-3.47,5.37-5.19Zm8.35,5.19c4.91-2.38,9.88-4.78,14.9-7.21-1.71-1.66-3.38-3.25-5.02-4.87-.27-.27-.47-.31-.82-.14-3.87,1.89-7.74,3.76-11.62,5.63-.95.46-1.9.92-2.83,1.38,1.8,1.74,3.57,3.45,5.39,5.21Zm7.82-13.4c-1.78-.86-3.45-1.67-5.11-2.47-4.8,6.58-14.71,6.38-19.17,0-1.66.8-3.33,1.61-5.07,2.45.17.1.25.15.34.19,4.68,2.26,9.35,4.53,14.03,6.78.17.08.47.05.65-.03,3.01-1.44,6.01-2.89,9.01-4.34,1.74-.84,3.47-1.68,5.32-2.58Z" />
                                    <path
                                        d="m24.04,12.31c-1.42,1.37-2.75,2.65-4.07,3.94-.11.11-.21.22-.33.32-.4.33-.88.32-1.2-.01-.31-.32-.31-.77.03-1.13.51-.52,1.05-1.02,1.57-1.52.91-.89,1.82-1.77,2.77-2.7-.13-.13-.25-.27-.38-.39-1.29-1.25-2.59-2.5-3.87-3.75-.43-.42-.46-.89-.1-1.23.36-.34.84-.32,1.27.1,1.28,1.23,2.56,2.48,3.83,3.72.13.13.24.28.38.45.22-.2.37-.33.51-.47,1.25-1.21,2.51-2.43,3.76-3.65.47-.45.94-.51,1.31-.17.39.35.34.84-.14,1.31-1.39,1.35-2.77,2.69-4.2,4.07.14.14.26.27.39.4,1.22,1.18,2.44,2.36,3.66,3.54.09.08.17.16.25.25.38.41.41.87.06,1.2-.35.33-.83.32-1.24-.07-1.15-1.1-2.29-2.21-3.43-3.33-.28-.27-.54-.56-.84-.87Z" />
                                </svg>
                                AGOTADO
                            </span>
                        @endif
                    @else
                        <p class="text-colorerror text-[10px] font-semibold text-center">
                            PRECIO DE VENTA NO ENCONTRADO</p>
                    @endif
                </x-card-producto-virtual>
            @endforeach
        </div>
    </div>

    @if (count($productos) > 0)
        @if ($productos->hasPages())
            <div class="w-full py-2">
                {{ $productos->onEachSide(0)->links('livewire::pagination-default') }}
            </div>
        @endif
    @endif

    @if (count($productos) == 0)
        <p class="text-xs py-12 block w-full text-colorlabel">NO SE ENCONTRARON REGISTROS DE PRODUCTOS...</p>
    @endif

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('carshoop', () => ({
                ordenfilter: false,
                qty: 1,
                isSticky: false,
                init() {
                    if (this.isXL) {
                        this.sidebar = true;
                    }
                    const footer = document.querySelector('.footer-marketplace');
                    const sidebarHeight = document.querySelector('.sidebar-productos').offsetHeight;

                    const footerTop = footer.getBoundingClientRect().top;
                    const windowHeight = window.innerHeight;

                    if (footerTop <= windowHeight) {
                        this.isSticky = false;
                    } else {
                        if (this.isXL) {
                            this.isSticky = true;
                        }
                    }

                    window.addEventListener('scroll', () => {
                        this.isSticky = window.scrollY > 47;
                        // console.log(window.scrollY);
                    });
                },
                add_to_cart(producto_id) {
                    // console.log(producto_id, this.qty);
                    @this.add_to_cart(producto_id, this.qty);
                },
            }))
        })

        document.addEventListener('DOMContentLoaded', function() {
            // Checkboxes for categories
            const checkboxes = document.querySelectorAll('input[type="checkbox"][name="categorias[]"]');
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const selectedOptions = Array.from(checkboxes)
                        .filter(checkbox => checkbox.checked)
                        .map(checkbox => checkbox.value);
                    // console.log(selectedOptions.join(','));
                    // @this.set('selectedcategorias', selectedOptions.join(','));
                    // @this.set('selectedcategorias', selectedOptions);
                });
            });




            // Inputs for price range
            // const priceRangeMin = document.querySelector('input[wire\\:model="priceRange.0"]');
            // const priceRangeMax = document.querySelector('input[wire\\:model="priceRange.1"]');

            // priceRangeMin.addEventListener('input', function() {
            //     @this.set('priceRange', `${priceRangeMin.value},${priceRangeMax.value}`);
            // });

            // priceRangeMax.addEventListener('input', function() {
            //     @this.set('priceRange', `${priceRangeMin.value},${priceRangeMax.value}`);
            // });
        });
    </script>
</div>
