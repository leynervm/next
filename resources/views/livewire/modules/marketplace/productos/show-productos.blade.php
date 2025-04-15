<div x-data="show_productos" @getcombos.window ="(data) => $wire.getcombos(data.detail.producto_id)"
    wire:init="loadProductos">

    <x-loading-web-next wire:key="loadingproductosweb" wire:loading />

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
            <div
                class="w-full flex flex-col gap-3 px-2 pb-2 xl:px-0 h-full max-h-full overflow-y-auto !overflow-x-hidden">
                <div class="w-full flex justify-end items-end xl:hidden">
                    <button class="p-2.5 font-semibold !leading-none text-colorsubtitleform"
                        @click="sidebar=false;backdrop = false;document.body.style.overflow = 'auto';">✕</button>
                </div>

                @if (count($selectedcategorias) > 0 ||
                        count($selectedsubcategorias) > 0 ||
                        count($selectedmarcas) > 0 ||
                        count($selectedespecificacions) > 0 ||
                        $filterselected !== 'name_asc')
                    <x-simple-card class="w-full text-colorsubtitleform p-2.5 relative flex flex-col gap-1">
                        <small class="font-medium text-colorsubtitleform text-sm flex justify-between">
                            Filtros aplicados
                            <button wire:click="resetfilters" type="button" id="reset-button-filters" role="button"
                                aria-label="Resetear filtros"
                                class="bg-fondospancardproduct text-textspancardproduct p-1 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill-rule="evenodd"
                                    clip-rule="evenodd" fill="currentColor" class="size-5 block text-colorsubtitleform">
                                    <path
                                        d="M21.4356 2.5218C21.8377 2.90032 21.8569 3.53319 21.4784 3.93537L14.8289 11.0004L13.4141 9.58556L20.022 2.56464C20.4005 2.16246 21.0334 2.14329 21.4356 2.5218ZM11.1141 9.8569C11.4092 9.67983 11.787 9.72634 12.0303 9.96969L14.5303 12.4697C14.671 12.6103 14.75 12.8011 14.75 13C14.75 15.6795 13.814 17.8177 12.8877 19.2772C12.4241 20.0075 11.9599 20.573 11.6089 20.9585C11.4396 21.1445 11.1279 21.4442 11.0102 21.5573L11.01 21.5575L11.01 21.5575L10.9977 21.5693C10.8136 21.7275 10.5643 21.7869 10.3287 21.7285C8.22723 21.2085 6.31516 20.2792 4.86851 18.5743C4.55974 18.2104 4.27528 17.8148 4.01631 17.3852C4.46585 17.4158 5.01987 17.425 5.62737 17.3722C7.1693 17.2381 9.14788 16.694 10.5761 14.9802C10.8412 14.662 10.7982 14.189 10.48 13.9239C10.1618 13.6587 9.6889 13.7017 9.42373 14.0199C8.35191 15.3061 6.83049 15.7619 5.49742 15.8778C4.83657 15.9353 4.24455 15.9066 3.81796 15.8632C3.76922 15.8583 3.72288 15.8545 3.67748 15.8508H3.67747H3.67746C3.53541 15.8392 3.40251 15.8283 3.23357 15.7848C2.72636 14.4883 2.39274 12.9551 2.25226 11.1471C2.23306 10.9001 2.33712 10.6595 2.5303 10.5043C2.72348 10.3491 2.98083 10.2994 3.21793 10.3714C6.09614 11.2454 8.85755 11.2108 11.1141 9.8569ZM5 6.5C5 5.67157 5.67157 5 6.5 5C7.32843 5 8 5.67157 8 6.5C8 7.32843 7.32843 8 6.5 8C5.67157 8 5 7.32843 5 6.5ZM12 3.95238C12 3.4264 11.5523 3 11 3C10.4477 3 10 3.4264 10 3.95238V4.04762C10 4.5736 10.4477 5 11 5C11.5523 5 12 4.5736 12 4.04762V3.95238Z" />
                                </svg>
                            </button>
                        </small>

                        <div class="w-full flex flex-wrap gap-1">

                            @if ($filterselected !== 'name_asc')
                                <span
                                    class="px-1 py-0.5 !leading-none uppercase text-[10px] font-semibold inline-flex gap-1 items-center justify-center rounded bg-fondospancardproduct text-colorsubtitleform">
                                    {{ str_replace('_', ' ', $filterselected) }}
                                    <button type="button" wire:click="$set('filterselected', 'name_asc')"
                                        class="text-xs text-textspancardproduct p-0.5 rounded cursor-pointer outline-none shadow-none shadow-0 border ring-0 focus:ring-0">
                                        ✕
                                    </button>
                                </span>
                            @endif

                            @foreach ($selectedcategorias as $item)
                                <span
                                    class="px-1 py-0.5 !leading-none uppercase text-[10px] font-semibold inline-flex gap-1 items-center justify-center rounded bg-fondospancardproduct text-colorsubtitleform">
                                    {{ str_replace('-', ' ', $item) }}
                                    <label for="quitcategory_{{ $item }}"
                                        class="text-xs text-textspancardproduct p-0.5 rounded cursor-pointer">
                                        <input type="checkbox" wire:model.lazy="selectedcategorias" name="categorias[]"
                                            class="hidden rounded outline-none cursor-pointer shadow-none shadow-0 border ring-0 focus:ring-0"
                                            value="{{ $item }}" id="quitcategory_{{ $item }}">
                                        ✕
                                    </label>
                                </span>
                            @endforeach

                            @foreach ($selectedsubcategorias as $item)
                                <span
                                    class="px-1 py-0.5 !leading-none uppercase text-[10px] font-semibold inline-flex gap-1 items-center justify-center rounded bg-fondospancardproduct text-colorsubtitleform">
                                    {{ str_replace('-', ' ', $item) }}
                                    <label for="quitsubcat_{{ $item }}"
                                        class="text-xs text-textspancardproduct p-0.5 rounded cursor-pointer">
                                        <input type="checkbox" wire:model.lazy="selectedsubcategorias"
                                            name="subcategorias[]"
                                            class="hidden rounded outline-none cursor-pointer shadow-none shadow-0 border ring-0 focus:ring-0"
                                            value="{{ $item }}" id="quitsubcat_{{ $item }}">
                                        ✕
                                    </label>
                                </span>
                            @endforeach

                            @foreach ($selectedmarcas as $item)
                                <span
                                    class="px-1 py-0.5 !leading-none uppercase text-[10px] font-semibold inline-flex gap-1 items-center justify-center rounded bg-fondospancardproduct text-colorsubtitleform">
                                    {{ str_replace('-', ' ', $item) }}
                                    <label for="marca_{{ $item }}"
                                        class="text-xs text-textspancardproduct p-0.5 rounded cursor-pointer">
                                        <input type="checkbox" wire:model.lazy="selectedmarcas" name="marcas[]"
                                            class="hidden rounded outline-none cursor-pointer shadow-none shadow-0 border ring-0 focus:ring-0"
                                            value="{{ $item }}" id="marca_{{ $item }}">
                                        ✕
                                    </label>
                                </span>
                            @endforeach

                            @foreach ($selectedespecificacions as $item)
                                <span
                                    class="px-1 py-0.5 !leading-none uppercase text-[10px] font-semibold inline-flex gap-1 items-center justify-center rounded bg-fondospancardproduct text-colorsubtitleform">
                                    {{ str_replace('-', ' ', $item) }}
                                    <label for="quitespecif_{{ $item }}"
                                        class="text-xs text-textspancardproduct p-0.5 rounded cursor-pointer">
                                        <input type="checkbox" wire:model.lazy="selectedespecificacions"
                                            name="especificacions[]"
                                            class="hidden rounded outline-none cursor-pointer shadow-none shadow-0 border ring-0 focus:ring-0"
                                            value="{{ $item }}" id="quitespecif_{{ $item }}">
                                        ✕
                                    </label>
                                </span>
                            @endforeach
                        </div>
                    </x-simple-card>
                @endif

                <x-simple-card class="w-full text-colorsubtitleform relative">
                    <x-input class="w-full block p-2.5 pr-8" id="search-productos" {{-- wire:model.lazy="search" --}}
                        x-model.lazy="buscar" x-on:input="verifyFilters($event.target.value)"
                        placeholder="Buscar en {{ count($selectedcategorias) > 0 ? str_replace('-', ' ', mb_strtoupper(implode(', ', $selectedcategorias), 'UTF-8')) : ' Next' }}" />
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="block absolute top-1/2 -translate-y-1/2 right-2 size-6 text-borderminicard">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.3-4.3"></path>
                    </svg>
                </x-simple-card>

                @if (count($productos) > 0)
                    {{-- @if (count($selectedcategorias) > 0 || count($selectedsubcategorias) > 0 || count($selectedmarcas) > 0 || count($selectedespecificacions) > 0 || trim($search) !== '') --}}
                    <x-simple-card class="w-full text-colorsubtitleform">
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
                                                value="{{ $filter }}" name="filterselected"
                                                style="display: none;" wire:key="key_{{ $filter }}" />
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
                        </div>
                    </x-simple-card>
                    {{-- @endif --}}
                @endif

                @if (count($categories) > 0)
                    <x-simple-card x-data="{ openfilter: {{ empty($searchcategorias) ? 'false' : 'false' }} }" wire:key="dropdowncategories"
                        class="w-full text-colorsubtitleform">
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
                        <div class="w-full flex flex-col {{-- max-h-60 overflow-y-auto --}}" x-cloak x-show="openfilter"
                            x-transition>
                            @foreach ($categories as $item)
                                <label for="category_id_{{ $item->id }}"
                                    class="text-xs font-medium hover:bg-shadowminicard hover:text-textspancardproduct rounded p-2 cursor-pointer inline-flex items-center gap-1 transition-colors ease-in-out duration-150">
                                    <input type="checkbox" wire:model.lazy="selectedcategorias" name="categorias[]"
                                        class="rounded p-2 outline-none cursor-pointer shadow-none shadow-0 border ring-0 focus:ring-0 hover:text-next-500 checked:text-next-500 focus:text-next-500"
                                        value="{{ $item->slug }}" id="category_id_{{ $item->id }}">
                                    {{ str_replace('-', ' ', $item->name) }}
                                </label>
                            @endforeach
                        </div>
                    </x-simple-card>
                @endif

                @if (count($subcategories) > 0)
                    <x-simple-card x-data="{ openfilter: {{ empty($searchsubcategorias) ? 'false' : 'false' }} }" wire:key="dropdownsubcategories"
                        class="w-full text-colorsubtitleform">
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
                    <x-simple-card x-data="{ openfilter: {{ empty($searchmarcas) ? 'false' : 'false' }} }" wire:key="dropdownmarcas"
                        class="w-full text-colorsubtitleform">
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
                        <x-simple-card x-data="{ openfilter: false }"
                            wire:key="dropdowncaracteristicas_{{ $caracteristica }}"
                            class="w-full flex flex-col text-colorsubtitleform">
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

        <div class="w-full relative flex-1 h-auto">
            <div
                class="grid grid-cols-[repeat(auto-fill,minmax(170px,1fr))] sm:grid-cols-[repeat(auto-fill,minmax(200px,1fr))] gap-1 self-start">
                @foreach ($productos as $item)
                    @php
                        $image = !empty($item->imagen) ? pathURLProductImage($item->imagen->urlmobile) : null;
                        $secondimage = !empty($item->image_2) ? pathURLProductImage($item->image_2) : null;
                        $priceold = $item->getPrecioVentaDefault($pricetype);
                        $pricesale = $item->getPrecioVenta($pricetype);
                        $paddingTop = '';
                        $promocion = null;

                        if ($item->descuento > 0 || $item->liquidacion) {
                            $promocion = $item->promocions
                                ->where('type', '<>', \App\Enums\PromocionesEnum::COMBO->value)
                                ->first();
                        }
                        if ($item->stock > 0 && $empresa->viewAlmacens() == false) {
                            $paddingTop = 'xl:pb-20';
                            if (!empty($promocion)) {
                                $paddingTop = 'xl:pb-12';
                            }
                        }
                    @endphp

                    <x-card-producto-virtual :route="route('productos.show', $item)" :name="$item->name" :marca="$item->name_marca" :subcategory="$item->name_subcategory"
                        :sku="$item->sku" :novedad="$item->isNovedad()" :image="$image" :secondimage="$secondimage" :promocion="$promocion"
                        wire:key="cardproduct{{ $item->id }}"
                        class="w-full pt-0 rounded-md md:rounded-xl ring-1 ring-borderminicard hover:shadow-md hover:shadow-shadowminicard overflow-hidden transition ease-in-out duration-150">

                        @if ($pricesale > 0)
                            <div class="{{ $paddingTop }} text-center flex flex-col justify-center items-center">
                                @if ($empresa->verDolar())
                                    <h1 class="text-blue-700 font-medium text-[1rem] text-center">
                                        <small class="text-xs">$. </small>
                                        {{ convertMoneda($pricesale, 'USD', $empresa->tipocambio, 2, ', ') }}
                                    </h1>
                                @endif
                                <h1 class="text-colorlabel font-semibold text-sm sm:text-2xl text-center">
                                    <small class="text-sm">{{ $moneda->simbolo }}</small>
                                    {{ decimalOrInteger($pricesale, 2, ', ') }}
                                </h1>
                                @if ($promocion && $empresa->verOldprice())
                                    <h1 class="text-colorsubtitleform text-center text-[10px] text-red-600">
                                        {{ $moneda->simbolo }}
                                        <small class="text-sm inline-block line-through">
                                            {{ decimalOrInteger($priceold, 2, ', ') }}</small>
                                    </h1>
                                @endif

                                @if (!empty($promocion))
                                    <p
                                        class="w-full text-center pt-1 xs:pt-0 text-xs leading-none text-colorsubtitleform">
                                        Promoción válida hasta agotar stock.
                                        <br>
                                        [{{ decimalOrInteger($promocion->limit - $promocion->outs) }}
                                        {{ $item->unit->name }}] disponibles

                                        @if (!empty($promocion->expiredate))
                                            <br>
                                            @if ($promocion->expiredate)
                                                Promoción válida hasta el
                                                {{ formatDate($promocion->expiredate, 'DD MMMM Y') }}
                                            @endif
                                        @endif
                                    </p>
                                @endif

                                @if ($item->stock > 0)
                                    <x-slot name="buttonscart">
                                        @auth
                                            <x-button-like
                                                class="absolute top-1 right-1 {{ in_array($item->id, $favoritos) ? 'activo' : '' }}"
                                                wire:loading.attr="disabled"
                                                onclick="addfavoritos(this, '{{ encryptText($item->id) }}')" />
                                        @endauth

                                        <div class="w-full bg-body p-1 flex flex-col items-end gap-1 justify-end"
                                            x-data="{ qty: 1 }">
                                            <div
                                                class="w-full flex-1 flex justify-center xl:justify-start gap-0.5 gap-x-1">
                                                <template x-if="parseFloat(qty)>1">
                                                    <button x-on:click="parseFloat(qty--)" class="btn-increment-cart"
                                                        type="button" wire:loading.attr="disabled"
                                                        :key="{{ $item->id }}">-</button>
                                                </template>
                                                <template x-if="parseFloat(qty)==1">
                                                    <span class="btn-increment-cart disabled"
                                                        :key="{{ rand() }}">-</span>
                                                </template>

                                                <x-input x-model="qty"
                                                    class="w-full rounded-xl flex-1 text-center text-colorlabel input-number-none"
                                                    type="number" step="1" min="1"
                                                    onpaste="return validarPasteNumero(event, 12)"
                                                    onkeypress="return validarNumero(event, 5)"
                                                    x-on:blur="if (!qty || qty === '0') qty = '1'"
                                                    aria-label="price" />

                                                <button class="btn-increment-cart" x-on:click="parseFloat(qty++)"
                                                    type="button" wire:loading.attr="disabled">+</button>
                                            </div>

                                            <x-button-add-car type="button" wire:loading.attr="disabled"
                                                class="rounded-xl w-full !flex gap-0.5 items-center justify-center text-[10px]"
                                                x-on:click="addproductocart('{{ encryptText($item->id) }}', '{{ $promocion ? encryptText($promocion->id) : null }}', qty, true)">
                                                AGREGAR</x-button-add-car>
                                        </div>
                                    </x-slot>
                                @else
                                    <span
                                        class="inline-block p-2 sm:p-2.5 rounded-lg text-[9px] sm:text-[10px] text-colorerror border border-colorerror">
                                        AGOTADO
                                        <x-icons.agotado />
                                    </span>
                                @endif
                            </div>
                        @else
                            <p class="text-colorerror text-[10px] font-semibold text-center">
                                PRECIO DE VENTA NO ENCONTRADO</p>
                        @endif

                        <x-slot name="footer">
                            @if ($empresa->viewAlmacens())
                                @if (count($item->almacens) > 0)
                                    <div class="w-full p-1 flex flex-wrap items-end gap-1">
                                        @foreach ($item->almacens as $alm)
                                            <span
                                                class="inline-block p-2 sm:p-2.5 rounded-lg text-[9px] sm:text-[10px] text-primary border border-next-300">
                                                {{ $alm->name }}
                                                <x-icons.almacen />
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            @endif
                        </x-slot>
                    </x-card-producto-virtual>
                @endforeach
            </div>
            @if (count($productos) > 0 && $productos->hasPages())
                <div
                    class="paginator-marketplace-productos w-full flex justify-center items-center p-1 mt-2 sticky bottom-0 right-0 bg-body z-10">
                    {{ $productos->onEachSide(0)->links('livewire::pagination-default') }}
                </div>
            @endif
        </div>
    </div>

    {{-- @if ($lazyload && count($productos) == 0)
        <p class="text-xs py-12 block w-full text-colorlabel">
            NO SE ENCONTRARON REGISTROS DE PRODUCTOS...</p>
    @endif --}}

    <x-jet-dialog-modal wire:model="open" maxWidth="5xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('AGREGAR PRODUCTO AL CARRITO') }}
        </x-slot>

        <x-slot name="content">
            @if (!empty($producto->id))
                @php
                    $pricesale = $producto->getPrecioVenta($pricetype);
                    $priceold = $producto->getPrecioVentaDefault($pricetype);
                    $image = !empty($producto->imagen) > 0 ? pathURLProductImage($producto->imagen->url) : null;
                    $promocion_producto = null;
                    if ($producto->descuento > 0 || $producto->liquidacion) {
                        $promocion_producto = $producto->promocions
                            ->where('type', '<>', \App\Enums\PromocionesEnum::COMBO->value)
                            ->first();
                    }
                @endphp

                <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="w-full max-w-full h-48 sm:h-64 rounded-lg md:rounded-xl overflow-hidden relative">
                        @if ($image)
                            <picture>
                                <img src="{{ $image }}" alt=""
                                    class="block w-full h-full object-scale-down">
                            </picture>
                        @else
                            <x-icon-image-unknown class="w-full h-full text-colorsubtitleform" />
                        @endif

                        @if ($producto->descuento > 0 || $producto->liquidacion)
                            <div class="w-auto h-auto bg-red-600 absolute -left-7 top-5 -rotate-[35deg] leading-none">
                                <p class="text-white text-xs block font-medium py-1.5 px-10 tracking-widest">
                                    @if ($empresa->isTitlePromocion())
                                        PROMOCIÓN
                                    @elseif($empresa->isTitleOferta())
                                        OFERTA
                                    @elseif($empresa->isTitleLiquidacion())
                                        LIQUIDACIÓN
                                    @else
                                        @if ($promocion_producto->isDescuento())
                                            - {{ decimalOrInteger($promocion_producto->descuento) }}%
                                        @endif
                                        {{ getTextPromocion($promocion_producto->type) }}
                                    @endif
                                </p>
                            </div>
                        @endif
                    </div>

                    <div class="w-full">
                        <div class="w-full md:border-b border-b-borderminicard pb-2 md:pb-5">
                            @if ($producto->isNovedad())
                                <div class="inline-block">
                                    @if (!empty($empresa->textnovedad))
                                        <span class="span-novedad p-1.5 px-2">
                                            {{ $empresa->textnovedad }}</span>
                                    @endif
                                    <x-icon-novedad class="size-6" />
                                </div>
                            @endif

                            <p class="text-[10px] text-colorsubtitleform font-medium">
                                {{ $producto->category->name }} | {{ $producto->subcategory->name }}</p>

                            <div class="w-full flex gap-2 justify-between items-center flex-wrap">
                                <div>
                                    <p class="text-colorsubtitleform font-semibold">
                                        {{ $producto->marca->name }}</p>
                                </div>
                                <div class="text-[10px] text-colorsubtitleform flex gap-3">
                                    @if (!empty($producto->partnumber))
                                        <span>N° PARTE: {{ $producto->partnumber }}</span>
                                    @endif

                                    @if (!empty($producto->sku))
                                        <span>SKU: {{ $producto->sku }}</span>
                                    @endif
                                </div>
                            </div>
                            <p class="text-colorlabel text-[10px] leading-3 xs:text-sm xs:leading-5">
                                {{ $producto->name }}</p>
                            @if (!empty($producto->modelo))
                                <span class="text-[10px] text-colorsubtitleform inline-block">
                                    MODELO : {{ $producto->modelo }}</span>
                            @endif
                        </div>

                        <div class="w-full relative">
                            <div class="w-full sm:pt-3 flex items-center justify-between gap-2">
                                @if ($pricesale > 0)
                                    <div class="w-full flex-1">
                                        <h1
                                            class="font-semibold text-2xl sm:text-3xl text-center md:text-start text-colorlabel">
                                            <small class="text-lg"> {{ $moneda->simbolo }}</small>
                                            {{ decimalOrInteger($pricesale, 2, ', ') }}
                                        </h1>

                                        @if ($priceold > $pricesale && $empresa->verOldprice())
                                            <h1
                                                class="text-colorsubtitleform text-[10px] sm:text-xs text-red-600 text-center md:text-start !leading-none">
                                                {{ $moneda->simbolo }}
                                                <small class="text-sm sm:text-lg inline-block line-through">
                                                    {{ decimalOrInteger($priceold, 2, ', ') }}</small>
                                            </h1>
                                        @endif

                                        @if ($empresa->verDolar())
                                            <h1 class="text-blue-700 font-medium text-xs text-center md:text-start">
                                                <small class="text-[10px]">$. </small>
                                                {{ convertMoneda($pricesale, 'USD', $empresa->tipocambio, 2, ', ') }}
                                                <small class="text-[10px]">USD</small>
                                            </h1>
                                        @endif
                                    </div>

                                    @auth
                                        <div class="flex-shrink-0">
                                            <x-button-like
                                                class="{{ in_array($producto->id, $favoritos) ? 'activo' : '' }}"
                                                wire:loading.attr="disabled"
                                                onclick="addfavoritos(this, '{{ encryptText($producto->id) }}')" />
                                        </div>
                                    @endauth
                                @else
                                    <p class="w-full flex-1 text-colorerror text-[10px] font-semibold text-center">
                                        PRECIO DE VENTA NO ENCONTRADO</p>
                                @endif
                            </div>

                            @if ($producto->stock > 0)
                                @if ($pricesale > 0)
                                    <div wire:ignore class="w-full flex items-center gap-1 justify-end mt-1 sm:mt-5"
                                        x-data="{ qty: 1 }">
                                        <div
                                            class="w-full flex-1 flex justify-center xl:justify-start gap-0.5 gap-x-1">
                                            <template x-if="parseFloat(qty)>1">
                                                <button x-on:click="parseFloat(qty--)" class="btn-increment-cart"
                                                    type="button" wire:loading.attr="disabled"
                                                    :key="{{ $item->id }}">-</button>
                                            </template>
                                            <template x-if="parseFloat(qty)==1">
                                                <span class="btn-increment-cart disabled"
                                                    :key="{{ rand() }}">-</span>
                                            </template>

                                            <x-input x-model="qty"
                                                class="w-20 rounded-xl text-center text-colorlabel input-number-none"
                                                type="number" step="1" min="1"
                                                onpaste="return validarPasteNumero(event, 12)"
                                                onkeypress="return validarNumero(event, 5)"
                                                x-on:blur="if (!qty || qty === '0') qty = '1'" />

                                            <button class="btn-increment-cart" x-on:click="parseFloat(qty++)"
                                                type="button" wire:loading.attr="disabled">+</button>
                                        </div>

                                        <x-button-add-car type="button"
                                            x-on:click="addproductocart('{{ encryptText($producto->id) }}', '{{ $promocion_producto ? encryptText($promocion_producto->id) : null }}', qty)"
                                            class="px-2.5 !flex gap-1 items-center text-xs flex-shrink-0"
                                            classIcon="!w-6 !h-6">AGREGAR</x-button-add-car>
                                    </div>

                                    @if ($promocion_producto)
                                        <p
                                            class="w-full text-center pt-1 xs:pt-0 xs:text-justify text-xs leading-none text-colorsubtitleform">
                                            Promoción válida hasta agotar stock.
                                            <br>
                                            [{{ decimalOrInteger($promocion_producto->limit - $promocion_producto->outs) }}
                                            {{ $producto->unit->name }}] disponibles

                                            @if (!empty($promocion_producto->expiredate))
                                                <br>
                                                @if ($promocion_producto->expiredate)
                                                    Promoción válida hasta el
                                                    {{ formatDate($promocion_producto->expiredate, 'DD MMMM Y') }}
                                                @endif
                                            @endif
                                        </p>
                                    @endif
                                @endif
                            @else
                                <div class="flex justify-end">
                                    {{-- <span
                                        class="inline-block p-2 sm:p-2.5 rounded-lg text-[9px] sm:text-[10px] text-colorerror border border-colorerror">
                                        AGOTADO
                                        <x-icons.agotado />
                                    </span> --}}

                                    <span class="inline-block p-1 rounded-lg bg-red-600 text-white text-[10px]">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48"
                                            fill="currentColor" stroke="currentColor" stroke-width="0.7"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="inline-block w-4 h-4">
                                            <path
                                                d="m13.58,16.14c-2.68-5.62-.35-11.07,3.31-13.8,3.93-2.94,9.38-3.15,13.53-.47,1.56,1.01,2.8,2.31,3.71,3.9.92,1.6,1.4,3.33,1.44,5.16.05,1.81-.37,3.53-1.19,5.2,1.38.67,2.69,1.38,4.05,1.95,1.82.76,3.34,1.86,4.69,3.26,1.41,1.48,2.94,2.87,4.41,4.29.7.68.61,1.19-.28,1.63-1.81.88-3.62,1.77-5.45,2.63-.32.15-.44.32-.44.68.02,2.79,0,5.57.02,8.36,0,.56-.2.89-.72,1.14-5.35,2.58-10.69,5.16-16.02,7.76-.46.23-.86.2-1.31-.02-5.34-2.6-10.68-5.18-16.02-7.76-.48-.23-.69-.54-.68-1.06.01-2.79,0-5.57.02-8.36,0-.38-.1-.59-.47-.76-1.83-.86-3.64-1.75-5.45-2.63-.85-.41-.94-.93-.26-1.6,2.06-2,4.12-3.99,6.18-5.98.2-.2.45-.36.71-.49,1.9-.93,3.81-1.85,5.72-2.78.17-.08.33-.17.52-.27Zm-5.26,14.82c-.02.09-.04.13-.04.18,0,2.43,0,4.87.01,7.3,0,.16.18.38.34.46,5.01,2.44,10.02,4.87,15.04,7.28.18.08.47.08.65,0,5.01-2.41,10.01-4.83,15.01-7.24.29-.14.38-.31.38-.62-.01-2.32,0-4.64,0-6.95,0-.12-.02-.25-.03-.41-.21.1-.36.16-.52.24-2.65,1.28-5.29,2.56-7.94,3.84-.66.32-.89.28-1.41-.23-1.8-1.75-3.6-3.49-5.41-5.24-.13-.13-.27-.24-.47-.43-.14.17-.25.32-.38.45-1.79,1.74-3.58,3.47-5.37,5.2-.55.53-.77.56-1.46.23-2.63-1.27-5.26-2.55-7.89-3.82-.16-.08-.33-.15-.52-.23ZM24.01,1.6c-5.41-.03-9.9,4.26-9.94,9.49-.04,5.33,4.37,9.7,9.81,9.72,5.56.03,10.02-4.24,10.04-9.59.02-5.29-4.42-9.6-9.91-9.62Zm-1.5,26.63c-.06-.04-.14-.09-.23-.14-4.78-2.32-9.57-4.63-14.34-6.95-.29-.14-.42-.05-.61.13-1.61,1.57-3.24,3.14-4.85,4.71-.07.06-.12.14-.21.24,5.01,2.42,9.97,4.82,14.88,7.2,1.81-1.75,3.58-3.47,5.37-5.19Zm8.35,5.19c4.91-2.38,9.88-4.78,14.9-7.21-1.71-1.66-3.38-3.25-5.02-4.87-.27-.27-.47-.31-.82-.14-3.87,1.89-7.74,3.76-11.62,5.63-.95.46-1.9.92-2.83,1.38,1.8,1.74,3.57,3.45,5.39,5.21Zm7.82-13.4c-1.78-.86-3.45-1.67-5.11-2.47-4.8,6.58-14.71,6.38-19.17,0-1.66.8-3.33,1.61-5.07,2.45.17.1.25.15.34.19,4.68,2.26,9.35,4.53,14.03,6.78.17.08.47.05.65-.03,3.01-1.44,6.01-2.89,9.01-4.34,1.74-.84,3.47-1.68,5.32-2.58Z" />
                                            <path
                                                d="m24.04,12.31c-1.42,1.37-2.75,2.65-4.07,3.94-.11.11-.21.22-.33.32-.4.33-.88.32-1.2-.01-.31-.32-.31-.77.03-1.13.51-.52,1.05-1.02,1.57-1.52.91-.89,1.82-1.77,2.77-2.7-.13-.13-.25-.27-.38-.39-1.29-1.25-2.59-2.5-3.87-3.75-.43-.42-.46-.89-.1-1.23.36-.34.84-.32,1.27.1,1.28,1.23,2.56,2.48,3.83,3.72.13.13.24.28.38.45.22-.2.37-.33.51-.47,1.25-1.21,2.51-2.43,3.76-3.65.47-.45.94-.51,1.31-.17.39.35.34.84-.14,1.31-1.39,1.35-2.77,2.69-4.2,4.07.14.14.26.27.39.4,1.22,1.18,2.44,2.36,3.66,3.54.09.08.17.16.25.25.38.41.41.87.06,1.2-.35.33-.83.32-1.24-.07-1.15-1.1-2.29-2.21-3.43-3.33-.28-.27-.54-.56-.84-.87Z" />
                                        </svg>
                                        AGOTADO
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                @if ($producto->promocions->where('type', \App\Enums\PromocionesEnum::COMBO->value)->count() > 0)
                    <div class="w-full">
                        <h1 class="font-semibold text-lg sm:text-2xl py-3 text-colorsubtitleform">
                            Combos sugeridos para tí</h1>

                        <div class="w-full grid grid-cols-1 sm:grid-cols-[repeat(auto-fill,minmax(340px,1fr))] gap-2">
                            @foreach ($producto->promocions->where('type', \App\Enums\PromocionesEnum::COMBO->value)->all() as $item)
                                @php
                                    $combo = getAmountCombo($item, $pricetype);
                                @endphp
                                @if ($combo->is_disponible && $combo->stock_disponible)
                                    <div
                                        class="w-full flex flex-col justify-between border border-borderminicard shadow-md shadow-shadowminicard p-1 md:p-2 rounded-lg md:rounded-2xl">
                                        <div class="w-full">
                                            <h1
                                                class="text-colorlabel font-medium text-xs md:text-[1rem] !leading-none pb-2">
                                                {{ $item->titulo }}</h1>

                                            <div class="w-full flex items-center flex-wrap gap-1">
                                                <div class="w-20 flex flex-col justify-center items-center">
                                                    <div class="w-full block rounded-lg relative">
                                                        @if ($image)
                                                            <img src="{{ $image }}" alt="{{ $image }}"
                                                                class="block w-full h-auto max-h-20 object-scale-down overflow-hidden rounded-lg">
                                                        @else
                                                            <x-icon-image-unknown
                                                                class="w-full h-full max-h-20 text-colorsubtitleform" />
                                                        @endif
                                                    </div>
                                                </div>

                                                @foreach ($combo->products as $itemcombo)
                                                    @php
                                                        $opacidad =
                                                            $itemcombo->stock > 0 ? '' : 'opacity-50 saturate-0';
                                                    @endphp

                                                    <span class="block w-5 h-5 text-colorsubtitleform">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                            color="currentColor" fill="none" stroke="currentColor"
                                                            stroke-width="2.5" stroke-linecap="round"
                                                            stroke-linejoin="round" class="w-full h-full block">
                                                            <path d="M12 4V20M20 12H4" />
                                                        </svg>
                                                    </span>

                                                    <div wire:ignore
                                                        class="w-20 flex flex-col justify-center items-center">
                                                        <a class="w-full block rounded-lg relative"
                                                            href="{{ route('productos.show', $itemcombo->producto_slug) }}">
                                                            @if ($itemcombo->imagen)
                                                                <img src="{{ $itemcombo->imagen }}"
                                                                    alt="{{ $itemcombo->producto_slug }}"
                                                                    class="{{ $opacidad }} block w-full h-auto max-h-16 object-scale-down overflow-hidden rounded-lg">
                                                            @else
                                                                <x-icon-image-unknown
                                                                    class="w-full h-full max-h-16 text-colorsubtitleform {{ $opacidad }}" />
                                                            @endif

                                                            @if ($itemcombo->stock > 0)
                                                                @if ($itemcombo->price <= 0)
                                                                    <x-span-text text="GRATIS" type="green"
                                                                        class="text-nowrap absolute bottom-0 left-[50%] -translate-x-[50%] !text-[9px] py-0.5" />
                                                                @endif
                                                            @else
                                                                <x-span-text text="AGOTADO"
                                                                    class="text-nowrap absolute bottom-0 left-[50%] -translate-x-[50%] !text-[9px] py-0.5" />
                                                            @endif
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="w-full pt-2 flex flex-wrap gap-3 items-end justify-end">
                                            <div class="w-full flex-1">
                                                <h1
                                                    class="text-colorlabel text-lg md:text-xl font-semibold text-center !leading-tight">
                                                    <small class="text-[10px] font-medium">S/.</small>
                                                    {{ number_format($pricesale + $combo->total, 2, '.', ', ') }}

                                                    <span
                                                        class="text-[10px] md:text-xs p-0.5 rounded text-colorerror font-medium line-through">
                                                        {{ number_format($priceold + $combo->total_normal, 2, '.', ', ') }}</span>
                                                </h1>

                                                <p
                                                    class="w-full text-center text-[10px] leading-none text-colorsubtitleform">
                                                    Promoción válida hasta agotar stock.
                                                    <br>
                                                    [{{ decimalOrInteger($item->limit - $item->outs) }}
                                                    {{ $combo->unit }}] disponibles

                                                    @if (!empty($item->expiredate))
                                                        <br>
                                                        @if ($item->expiredate)
                                                            Promoción válida hasta el
                                                            {{ formatDate($item->expiredate, 'DD MMMM Y') }}
                                                        @endif
                                                    @endif
                                                </p>
                                            </div>

                                            <x-button-add-car type="button"
                                                wire:key="promocion_id_{{ $item->id }}"
                                                class="px-2.5 !flex gap-1 items-center text-xs flex-shrink-0"
                                                onclick="addproductocart(null, '{{ encryptText($item->id) }}')">
                                                AGREGAR</x-button-add-car>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            @endif
        </x-slot>
    </x-jet-dialog-modal>


    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('show_productos', () => ({
                buscar: @entangle('search'),
                selectedcategorias: @entangle('selectedcategorias'),
                selectedsubcategorias: @entangle('selectedsubcategorias'),
                selectedmarcas: @entangle('selectedmarcas'),
                selectedespecificacions: @entangle('selectedespecificacions'),
                isSticky: false,
                init() {
                    // this.$watch('buscar', (value, oldvalue) => {
                    //     if (value == '') {
                    //         this.updatedValueAlpine(value)
                    //     }
                    // })
                    Livewire.hook('message.processed', () => {
                        if (this.selectedcategorias.length > 0 ||
                            this.selectedsubcategorias.length > 0 ||
                            this.selectedmarcas.length > 0 ||
                            this.selectedespecificacions.length > 0) {
                            this.updatedValueAlpine('')
                        } else {
                            this.verifyFilters(this.buscar)
                        }
                    })

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
                verifyFilters(value) {
                    if (this.selectedcategorias.length == 0 && this.selectedsubcategorias
                        .length == 0 && this.selectedmarcas.length == 0 && this
                        .selectedespecificacions.length == 0) {
                        this.updatedValueAlpine(value);
                    }
                },
                updatedValueAlpine(value) {
                    // console.log(this.search);
                    const input = document.querySelector('[x-ref=searchdesk]');
                    const inputmobile = document.querySelector('[x-ref=searchmobile]');

                    if (input) {
                        const componentEl = input.closest('[x-data]');
                        if (componentEl) {
                            const component = Alpine.$data(componentEl);
                            component.search = value;
                        }

                        input.value = value;
                        input.dispatchEvent(new Event('input'));
                    }

                    if (inputmobile) {
                        inputmobile.value = value;
                        inputmobile.dispatchEvent(new Event('input'));
                    }
                }
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
