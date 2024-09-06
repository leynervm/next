<div x-data="carshoop">
    <div wire:loading.flex class="fixed loading-overlay rounded hidden overflow-hidden z-[99999]">
        <x-loading-next />
    </div>

    <div class="w-full flex items-start gap-3 bg-body">
        <aside
            class="w-full max-w-[80%] xs:w-60 xs:max-w-full lg:w-52 flex-shrink-0 absolute z-[199] lg:z-[90] top-0 mt-[108px] lg:mt-0 left-0 lg:top-0 h-[calc(100vh-110px)] lg:h-auto lg:relative lg:block rounded-r-xl transition-all duration-300"
            :class="sidebar ? 'translate-x-0 bg-inherit lg:bg-inherit' : '-translate-x-full bg-inherit lg:translate-x-0',
                openSidebar ? '' : 'z-[199]'">
            <div class="w-full p-2 pt-10 lg:p-0 relative overflow-y-auto overflow-x-hidden h-full">
                <button x-cloak
                    class="absolute flex font-semibold items-center justify-center leading-3 top-1 right-1 lg:hidden cursor-pointer rounded-lg text-lg text-textspancardproduct p-2"
                    :class="sidebar ? '' : ''" @click="sidebar=false,backdrop = false">✕</button>

                {{-- <h1 class="text-colorsubtitleform text-[10px] py-2">FILTRAR</h1> --}}

                @if (count($productos) > 0)
                    <x-simple-card x-data="{ openfilter: {{ empty($searchmarcas) ? 'false' : 'true' }} }" class="w-full text-colorsubtitleform">
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
                        <div class="w-full flex flex-col text-xs" x-cloak x-show="openfilter" x-transition>
                            <button @click="sidebar=false,backdrop = false" wire:click="order('precio', 'asc')"
                                wire:loading.attr="disabled"
                                class="w-full p-2 rounded text-left hover:bg-shadowminicard hover:text-textspancardproduct disabled:opacity-25 transition ease-in-out duration-150">
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
                            </button>
                            <button @click="sidebar=false,backdrop = false" wire:click="order('precio', 'desc')"
                                wire:loading.attr="disabled"
                                class="w-full p-2 rounded text-left hover:bg-shadowminicard hover:text-textspancardproduct disabled:opacity-25 transition ease-in-out duration-150">
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
                            <button @click="sidebar=false,backdrop = false" wire:click="order('name', 'asc')"
                                wire:loading.attr="disabled"
                                class="w-full p-2 rounded text-left hover:bg-shadowminicard hover:text-textspancardproduct disabled:opacity-25 transition ease-in-out duration-150">
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
                            <button @click="sidebar=false,backdrop = false" wire:click="order('name', 'desc')"
                                wire:loading.attr="disabled"
                                class="w-full p-2 rounded text-left hover:bg-shadowminicard hover:text-textspancardproduct disabled:opacity-25 transition ease-in-out duration-150">
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
                            </button>
                        </div>
                    </x-simple-card>
                @endif

                @if (count($marcas) > 0)
                    <x-simple-card x-data="{ openfilter: {{ empty($searchmarcas) ? 'false' : 'true' }} }" class="w-full mt-3 text-colorsubtitleform">
                        <button type="button"
                            class="w-full p-1 py-2 cursor-pointer flex gap-1 justify-between items-center"
                            @click="openfilter = !openfilter">
                            <h1 class="pl-2 font-semibold text-[11px]">MARCAS</h1>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                class="block w-6 h-6">
                                <path d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                            </svg>
                        </button>
                        <div class="w-full flex flex-col" x-cloak x-show="openfilter" x-transition>
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

                @if (count($categories) > 0)
                    <x-simple-card x-data="{ openfilter: {{ empty($searchcategorias) ? 'false' : 'true' }} }" class="w-full mt-3 text-colorsubtitleform">
                        <button type="button"
                            class="w-full p-1 py-2 cursor-pointer flex gap-1 justify-between items-center"
                            @click="openfilter = !openfilter">
                            <h1 class="pl-2 font-semibold text-[11px]">CATEGORÍAS</h1>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" stroke-linecap="round"
                                stroke-linejoin="round" class="block w-6 h-6">
                                <path d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                            </svg>
                        </button>
                        <div class="w-full flex flex-col" x-cloak x-show="openfilter" x-transition>
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
                    <x-simple-card x-data="{ openfilter: {{ empty($searchsubcategorias) ? 'false' : 'true' }} }" class="w-full mt-3 text-colorsubtitleform">
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
                        <div class="w-full flex flex-col" x-cloak x-show="openfilter" x-transition>
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

                @if (count($caracteristicas) > 0)
                    @foreach ($caracteristicas as $item)
                        <x-simple-card x-data="{ openfilter: false }"
                            class="w-full mt-3 flex flex-col text-colorsubtitleform">
                            <button type="button"
                                class="w-full p-1 py-2 cursor-pointer flex gap-1 justify-between items-center"
                                @click="openfilter = !openfilter">
                                <h1 class="pl-2 font-semibold text-[11px]">{{ $item->name }}</h1>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" stroke-linecap="round"
                                    stroke-linejoin="round" class="block w-6 h-6">
                                    <path d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            </button>
                            <div class="w-full flex flex-col" x-cloak x-show="openfilter" x-transition>
                                @foreach ($item->especificacions as $espec)
                                    <label for="especificacion_{{ $item->id }}{{ $espec->id }}"
                                        class="text-xs font-medium hover:bg-shadowminicard hover:text-textspancardproduct rounded p-2 cursor-pointer inline-flex items-center gap-1 transition-colors ease-in-out duration-150">
                                        <input type="checkbox" wire:model.lazy="especificacions"
                                            name="especificacions[]"
                                            class="rounded p-2 outline-none cursor-pointer shadow-none shadow-0 border ring-0 focus:ring-0 hover:text-next-500 checked:text-next-500 focus:text-next-500"
                                            value="{{ $espec->slug }}"
                                            id="especificacion_{{ $item->id }}{{ $espec->id }}">
                                        {{ $espec->name }}
                                    </label>
                                @endforeach
                            </div>
                        </x-simple-card>
                    @endforeach
                @endif
            </div>
        </aside>

        <div class="w-full flex-1">
            <div class="w-full flex justify-end">
                <button @click="sidebar = true, backdrop = true"
                    class="lg:hidden p-2.5 mb-3 tracking-wide inline-flex items-center justify-center gap-1 rounded-lg text-xs bg-fondospancardproduct text-textspancardproduct">
                    FILTRAR
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3" />
                    </svg>
                </button>
            </div>
            <div
                class="w-full grid grid-cols-[repeat(auto-fill,minmax(170px,1fr))] sm:grid-cols-[repeat(auto-fill,minmax(200px,1fr))] gap-1">
                @foreach ($productos as $item)
                    @php
                        $image = $item->getImageURL();
                        $secondimage = $item->getSecondImageURL();
                        $promocion = $item->getPromocionDisponible();
                        $descuento = $item->getPorcentajeDescuento($promocion);
                        $combo = $item->getAmountCombo($promocion, $pricetype);
                        $pricesale = $item->obtenerPrecioVenta($pricetype);
                        // $priceCombo = $combo ? $combo->total : 0;
                    @endphp

                    <x-card-producto-virtual :route="route('productos.show', $item)" :name="$item->name" :marca="$item->marca->name" :partnumber="$item->partnumber"
                        :image="$image" :secondimage="$secondimage" :promocion="$promocion"
                        wire:key="cardproduct{{ $item->id }}" x-data="{ addcart: isXL ? false : true }"
                        @mouseover="addcart = true" @mouseleave="isXL && (addcart = false)"
                        class="w-full pt-0 pb-5 rounded-xl border border-borderminicard hover:shadow-md hover:shadow-shadowminicard overflow-hidden transition ease-in-out duration-150"
                        x-init="$watch('isXL', value => { addcart = value ? false : true; })">
                        @if ($combo)
                            @if (count($combo->products) > 0)
                                <div class="w-full my-2">
                                    @foreach ($combo->products as $itemcombo)
                                        <div class="w-full flex gap-2 bg-body rounded relative">
                                            <div
                                                class="block rounded overflow-hidden flex-shrink-0 w-10 h-10 shadow relative hover:shadow-lg cursor-pointer">
                                                @if ($itemcombo->image)
                                                    <img src="{{ $itemcombo->image }}" alt=""
                                                        class="w-full h-full object-scale-down">
                                                @else
                                                    <x-icon-image-unknown
                                                        class="w-full h-full text-colorsubtitleform" />
                                                @endif
                                            </div>
                                            <div class="p-1">
                                                <h1 class="text-[10px] leading-3 text-left text-colorlabel">
                                                    {{ $itemcombo->name }}</h1>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        @endif

                        @if ($pricesale > 0)
                            @if ($empresa->verDolar())
                                <h1 class="text-blue-700 font-medium text-[1rem] text-center">
                                    <small class="text-[10px]">$. </small>
                                    {{ convertMoneda($pricesale, 'USD', $empresa->tipocambio, 2, ', ') }}
                                    <small class="text-[10px]">USD</small>
                                </h1>
                            @endif
                            <h1 class="text-colorlabel font-semibold text-sm sm:text-2xl text-center">
                                <small class="text-[10px]">{{ $moneda->simbolo }}</small>
                                {{ formatDecimalOrInteger($pricesale, 2, ', ') }}
                                <small class="text-[10px]">{{ $moneda->currency }}</small>
                            </h1>
                            @if ($descuento > 0 && $empresa->verOldprice())
                                <small class="block text-[1rem] w-full line-through text-red-600 text-center">
                                    {{ $moneda->simbolo }}
                                    {{ getPriceAntes($pricesale, $descuento, null, ', ') }}
                                </small>
                            @endif

                            <x-slot name="footer">
                                <x-button-like class="absolute top-1 right-1" wire:loading.attr="disabled"
                                    wire:click="add_to_wishlist({{ $item->id }}, 1)" />

                                <div x-cloak x-show="addcart"
                                    class="w-full bg-fondominicard z-[2] p-1 flex items-end gap-1 justify-end mt-1 absolute bottom-0"
                                    x-data="carshoop"
                                    x-transition:enter="opacity-0 transition ease-in-out duration-300"
                                    x-transition:enter-start="opacity-0 translate-y-full"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    x-transition:leave="transition translate-y-full ease-in-out duration-300"
                                    x-transition:leave-start="opacity-100 translate-y-0"
                                    x-transition:leave-end="opacity-0 translate-y-0">
                                    <div class="w-full flex-1 flex gap-1">
                                        <button type="button" wire:loading.attr="disabled" @click="qty = qty-1"
                                            x-bind:disabled="qty == 1"
                                            class="font-medium hover:bg-neutral-400 hover:ring-2 hover:ring-neutral-300 text-xl w-9 h-9 bg-neutral-300 text-gray-500 p-2.5 pt-1.5 align-middle inline-flex items-center justify-center rounded-xl disabled:opacity-25 transition ease-in-out duration-150">-</button>
                                        <x-input x-model="qty"
                                            class="w-10 text-center text-colorlabel input-number-none" type="number"
                                            step="1" min="1" onkeypress="return validarNumero(event, 4)"
                                            @blur="if (!qty || qty === '0') qty = '1'" />
                                        <button type="button" wire:loading.attr="disabled" @click="qty = qty+1"
                                            class="font-medium hover:bg-neutral-400 hover:ring-2 hover:ring-neutral-300 text-xl w-9 h-9 bg-neutral-300 text-gray-500 p-2.5 pt-1.5 align-middle inline-flex items-center justify-center rounded-xl disabled:opacity-25 transition ease-in-out duration-150">+</button>
                                    </div>

                                    <x-button-add-car type="button" wire:loading.attr="disabled" class="!rounded-xl"
                                        @click="add_to_cart({{ $item->id }})" />
                                </div>
                            </x-slot>
                        @else
                            <p class="text-colorerror text-[10px] font-semibold text-center">
                                PRECIO DE VENTA NO ENCONTRADO</p>
                        @endif
                    </x-card-producto-virtual>
                @endforeach
            </div>

            @if ($productos->hasPages())
                <div class="w-full py-2">
                    {{ $productos->onEachSide(0)->links('livewire::pagination-default') }}
                </div>
            @endif

            @if (count($productos) == 0)
                <p class="text-xs py-12 w-full text-colorlabel">NO SE ENCONTRARON REGISTROS DE PRODUCTOS...</p>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('carshoop', () => ({
                qty: 1,

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
                    console.log(selectedOptions.join(','));
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
