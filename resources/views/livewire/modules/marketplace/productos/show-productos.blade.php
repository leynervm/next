<div x-data="carshoop">
    <div wire:loading.flex class="fixed loading-overlay rounded hidden overflow-hidden z-[99999]">
        <x-loading-next />
    </div>

    <div class="w-full flex items-start gap-3 bg-body">
        <aside
            class="w-full max-w-[80%] xs:w-60 xs:max-w-full lg:w-52 flex-shrink-0 absolute h-screen max-h-[calc(100vh-120px)] z-[199] lg:z-[100] top-0 mt-28 lg:mt-0 left-0 lg:top-0 overflow-hidden lg:relative lg:block rounded-r-xl transition-all duration-300"
            :class="sidebar ? 'translate-x-0 bg-inherit lg:bg-inherit' : '-translate-x-full bg-inherit lg:translate-x-0',
                openSidebar ? '' : 'z-[199]'">
            <div class="w-full p-2 lg:p-0 relative overflow-y-auto overflow-x-hidden h-full">
                <button x-cloak
                    class="absolute flex items-center justify-center top-0 right-0 h-8 w-8 lg:hidden cursor-pointer text-colorsubtitleform rounded-full p-2"
                    :class="sidebar ? 'bg-white' : ''" @click="sidebar=false,backdrop = false">✕</button>

                {{-- <div class="relative p-3">
                    <h1 class="text-colorsubtitleform text-[10px]">VER</h1>
                    <div class="flex flex-wrap">
                        <button
                            class="p-1.5 inline-block text-center rounded bg-neutral-100 hover:bg-neutral-200 text-neutral-600">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" color="currentColor"
                                fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4 block mx-auto">
                                <path
                                    d="M2 18C2 16.4596 2 15.6893 2.34673 15.1235C2.54074 14.8069 2.80693 14.5407 3.12353 14.3467C3.68934 14 4.45956 14 6 14C7.54044 14 8.31066 14 8.87647 14.3467C9.19307 14.5407 9.45926 14.8069 9.65327 15.1235C10 15.6893 10 16.4596 10 18C10 19.5404 10 20.3107 9.65327 20.8765C9.45926 21.1931 9.19307 21.4593 8.87647 21.6533C8.31066 22 7.54044 22 6 22C4.45956 22 3.68934 22 3.12353 21.6533C2.80693 21.4593 2.54074 21.1931 2.34673 20.8765C2 20.3107 2 19.5404 2 18Z" />
                                <path
                                    d="M14 18C14 16.4596 14 15.6893 14.3467 15.1235C14.5407 14.8069 14.8069 14.5407 15.1235 14.3467C15.6893 14 16.4596 14 18 14C19.5404 14 20.3107 14 20.8765 14.3467C21.1931 14.5407 21.4593 14.8069 21.6533 15.1235C22 15.6893 22 16.4596 22 18C22 19.5404 22 20.3107 21.6533 20.8765C21.4593 21.1931 21.1931 21.4593 20.8765 21.6533C20.3107 22 19.5404 22 18 22C16.4596 22 15.6893 22 15.1235 21.6533C14.8069 21.4593 14.5407 21.1931 14.3467 20.8765C14 20.3107 14 19.5404 14 18Z" />
                                <path
                                    d="M2 6C2 4.45956 2 3.68934 2.34673 3.12353C2.54074 2.80693 2.80693 2.54074 3.12353 2.34673C3.68934 2 4.45956 2 6 2C7.54044 2 8.31066 2 8.87647 2.34673C9.19307 2.54074 9.45926 2.80693 9.65327 3.12353C10 3.68934 10 4.45956 10 6C10 7.54044 10 8.31066 9.65327 8.87647C9.45926 9.19307 9.19307 9.45926 8.87647 9.65327C8.31066 10 7.54044 10 6 10C4.45956 10 3.68934 10 3.12353 9.65327C2.80693 9.45926 2.54074 9.19307 2.34673 8.87647C2 8.31066 2 7.54044 2 6Z" />
                                <path
                                    d="M14 6C14 4.45956 14 3.68934 14.3467 3.12353C14.5407 2.80693 14.8069 2.54074 15.1235 2.34673C15.6893 2 16.4596 2 18 2C19.5404 2 20.3107 2 20.8765 2.34673C21.1931 2.54074 21.4593 2.80693 21.6533 3.12353C22 3.68934 22 4.45956 22 6C22 7.54044 22 8.31066 21.6533 8.87647C21.4593 9.19307 21.1931 9.45926 20.8765 9.65327C20.3107 10 19.5404 10 18 10C16.4596 10 15.6893 10 15.1235 9.65327C14.8069 9.45926 14.5407 9.19307 14.3467 8.87647C14 8.31066 14 7.54044 14 6Z" />
                            </svg>
                        </button>
                        <button
                            class="p-1.5 inline-block text-center rounded bg-white hover:bg-neutral-200 text-neutral-600">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" color="currentColor"
                                fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4 block mx-auto">
                                <path d="M9 5L21 5" />
                                <path d="M3 5L5 5" />
                                <path d="M9 12L21 12" />
                                <path d="M3 12L5 12" />
                                <path d="M9 19L21 19" />
                                <path d="M3 19L5 19" />
                            </svg>
                        </button>
                    </div>
                </div> --}}

                {{-- <span x-text="sidebar"></span> --}}

                <h1 class="text-colorsubtitleform text-[10px] py-2">FILTRAR</h1>
                @if (count($marcas) > 0)
                    <x-simple-card x-data="{ openfilter: {{ empty($searchmarcas) ? 'false' : 'true' }} }" class="w-full lg:shadow lg:rounded-md text-colorsubtitleform">
                        <button type="button"
                            class="w-full p-1 py-2 cursor-pointer flex gap-1 justify-between items-center"
                            @click="openfilter = !openfilter">
                            <h1 class="pl-2 pt-2 font-semibold text-[11px]">MARCAS</h1>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                class="block w-6 h-6">
                                <path d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                            </svg>
                        </button>
                        <div class="w-full flex flex-col" x-show="openfilter" x-transition>
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
                    <x-simple-card x-data="{ openfilter: {{ empty($searchcategorias) ? 'false' : 'true' }} }"
                        class="w-full mt-3 lg:shadow lg:rounded-md text-colorsubtitleform">
                        <button type="button"
                            class="w-full p-1 py-2 cursor-pointer flex gap-1 justify-between items-center"
                            @click="openfilter = !openfilter">
                            <h1 class="pl-2 pt-2 font-semibold text-[11px]">CATEGORÍAS</h1>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                class="block w-6 h-6">
                                <path d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                            </svg>
                        </button>
                        <div class="w-full flex flex-col" x-show="openfilter" x-transition>
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
                    <x-simple-card x-data="{ openfilter: {{ empty($searchsubcategorias) ? 'false' : 'true' }} }"
                        class="w-full mt-3 lg:shadow lg:rounded-md text-colorsubtitleform">
                        <button type="button"
                            class="w-full p-1 py-2 cursor-pointer flex gap-1 justify-between items-center"
                            @click="openfilter = !openfilter">
                            <h1 class="pl-2 pt-2 font-semibold text-[11px]">SUBCATEGORÍAS</h1>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                class="block w-6 h-6">
                                <path d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                            </svg>
                        </button>
                        <div class="w-full flex flex-col" x-show="openfilter" x-transition>
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
                            class="w-full mt-3 flex flex-col lg:shadow lg:rounded-md text-colorsubtitleform">
                            {{-- <h1 class="pl-2 pt-2 font-semibold text-[11px]">{{ $item->name }}</h1> --}}
                            <button type="button"
                                class="w-full p-1 py-2 cursor-pointer flex gap-1 justify-between items-center"
                                @click="openfilter = !openfilter">
                                <h1 class="pl-2 pt-2 font-semibold text-[11px]">{{ $item->name }}</h1>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" stroke-linecap="round"
                                    stroke-linejoin="round" class="block w-6 h-6">
                                    <path d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            </button>
                            <div class="w-full flex flex-col" x-show="openfilter" x-transition>
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
            <div class="w-full flex justify-end pb-3">
                <button @click="sidebar = true, backdrop = true"
                    class="lg:hidden p-1.5 inline-flex items-center justify-center gap-1 rounded text-xs bg-neutral-200 text-colorsubtitleform">
                    FILTRAR
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3" />
                    </svg>
                </button>
            </div>
            <div class="w-full grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 ">
                @foreach ($productos as $item)
                    @php
                        $image = $item->getImageURL();
                        $secondimage = $item->getSecondImageURL();
                        $promocion = $item->getPromocionDisponible();
                        $descuento = $item->getPorcentajeDescuento($promocion);
                        $combo = $item->getAmountCombo($promocion, $pricetype);
                        $priceCombo = $combo ? $combo->total : 0;
                        // if ($almacendefault->name) {
                        //     $stock = formatDecimalOrInteger($item->almacens->first()->pivot->cantidad);
                        //     $almacenStock = $almacendefault->name . " [$stock " . $item->unit->name . ']';
                        // }
                    @endphp

                    <x-card-producto-virtual :route="route('productos.show', $item)" :name="$item->name" :marca="$item->marca->name" :partnumber="$item->partnumber"
                        :image="$image" :secondimage="$secondimage" :promocion="$promocion"
                        wire:key="cardproduct{{ $item->id }}" x-data="{ addcart: isXL ? false : true }"
                        @mouseover="addcart = true" @mouseleave="isXL && (addcart = false)"
                        class="w-full py-3 pb-5 rounded-xl shadow shadow-shadowminicard hover:shadow-xl hover:shadow-shadowminicard overflow-hidden transition ease-in-out duration-150"
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
                                                    <x-icon-image-unknown class="w-full h-full text-neutral-500" />
                                                @endif
                                            </div>
                                            <div class="p-1">
                                                <h1 class="text-[10px] leading-3 text-left">
                                                    {{ $itemcombo->name }}</h1>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        @endif

                        {{-- <p class="text-[10px]">
                            {{ $item->precio_real_compra }}
                        </p> --}}

                        @if ($empresa->usarLista())
                            @if ($pricetype)
                                @php
                                    $price = $item->calcularPrecioVentaLista($pricetype);
                                    $price =
                                        !is_null($promocion) && $promocion->isRemate()
                                            ? $item->precio_real_compra
                                            : $price;

                                    $pricesale =
                                        $descuento > 0
                                            ? $item->getPrecioDescuento($price, $descuento, 0, $pricetype)
                                            : $price;
                                @endphp

                                {{-- PRECIO COMPRA SIN LISTA {{ $item->pricebuy }}
                                PRECIO REAL COMPRA {{ $item->precio_real_compra }}
                                <br>
                                {{ $pricetype->name }}
                                {{ $item->calcularPrecioVentaLista($pricetype) }}
                                <br>
                                PV SIN LISTA {{ $item->pricesale }}
                                <br>
                                PV CON DSCT LISTA
                                {{ $item->getPrecioDescuento($item->calcularPrecioVentaLista($pricetype), 20, 0, $pricetype) }}
                                <br>
                                DESCUENTO {{ $item->getPorcentajeDescuento($promocion) }}
                                <br>
                                <br> --}}
                            @else
                                <p class="text-[10px] text-colorerror leading-3 py-2">
                                    CONFIGURAR LISTA DE PRECIOS PARA TIENDA WEB...</p>
                            @endif
                        @else
                            @php
                                $price = $item->pricesale;
                                $price = !is_null($promocion) && $promocion->isRemate() ? $item->pricebuy : $price;
                                $pricesale = $descuento > 0 ? $item->getPrecioDescuento($price, $descuento, 0) : $price;
                            @endphp
                        @endif

                        @isset($price)
                            @if ($descuento > 0)
                                <small class="block w-full line-through text-red-600 text-center">
                                    {{ $moneda->simbolo }}
                                    {{ formatDecimalOrInteger($price + $priceCombo, 2, ', ') }}
                                </small>
                            @endif
                            <h1 class="text-neutral-700 font-semibold text-2xl text-center">
                                <small class="text-[10px]">{{ $moneda->simbolo }}</small>
                                {{ formatDecimalOrInteger($pricesale + $priceCombo, 2, ', ') }}
                                <small class="text-[10px]">{{ $moneda->currency }}</small>
                            </h1>
                            @if ($empresa->verDolar())
                                <h1 class="text-blue-700 font-medium text-xs text-center">
                                    <small class="text-[10px]">$. </small>
                                    {{ convertMoneda($pricesale + $priceCombo, 'USD', $empresa->tipocambio, 2, ', ') }}
                                    <small class="text-[10px]">USD</small>
                                </h1>
                            @endif

                            <x-slot name="footer">
                                <x-button-like class="absolute top-1 right-1" wire:loading.attr="disabled"
                                    wire:click="add_to_wishlist({{ $item->id }}, 1)" />

                                <div x-cloak x-show="addcart"
                                    class="w-full bg-fondominicard p-1 flex items-end gap-1 justify-end mt-1 absolute bottom-0"
                                    x-data="carshoop"
                                    x-transition:enter="opacity-0 transition ease-in-out duration-300"
                                    x-transition:enter-start="opacity-0 translate-y-full"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    x-transition:leave="transition translate-y-full ease-in-out duration-300"
                                    x-transition:leave-start="opacity-100 translate-y-0"
                                    x-transition:leave-end="opacity-0 translate-y-0">
                                    <div class="w-full flex-1">
                                        <button type="button" wire:loading.attr="disabled" @click="qty = qty-1"
                                            x-bind:disabled="qty == 1"
                                            class="font-medium hover:bg-neutral-400 hover:ring-2 hover:ring-neutral-300 text-xl w-9 h-9 bg-neutral-300 text-gray-500 p-2.5 pt-1.5 align-middle inline-flex items-center justify-center rounded-xl disabled:opacity-25 transition ease-in-out duration-150">-</button>
                                        <span x-text="qty"
                                            class="font-medium text-sm px-2 text-colorlabel inline-block w-8 text-center">1</span>
                                        <button type="button" wire:loading.attr="disabled" @click="qty = qty+1"
                                            class="font-medium hover:bg-neutral-400 hover:ring-2 hover:ring-neutral-300 text-xl w-9 h-9 bg-neutral-300 text-gray-500 p-2.5 pt-1.5 align-middle inline-flex items-center justify-center rounded-xl disabled:opacity-25 transition ease-in-out duration-150">+</button>
                                    </div>

                                    <x-button-add-car type="button" wire:loading.attr="disabled" class="!rounded-xl"
                                        @click="add_to_cart({{ $item->id }})" />
                                </div>
                            </x-slot>
                        @endisset
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
