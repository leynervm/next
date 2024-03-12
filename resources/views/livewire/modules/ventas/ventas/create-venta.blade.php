<div class="relative">
    <x-form-card titulo="PRODUCTOS">
        <div class="w-full">
            {{-- @if (!$empresa->uselistprice || ($empresa->uselistprice && count($pricetypes))) --}}
            <div class="w-full flex flex-col gap-2 md:flex-row md:flex-shrink">
                <div class="w-full flex-shrink-1">
                    <x-label value="Descripcion producto :" />
                    <x-input class="block w-full disabled:bg-gray-200" wire:model.lazy="search"
                        placeholder="Buscar producto..." />
                    <x-jet-input-error for="search" />
                </div>

                @if ($empresa->usarLista())
                    @if (count($pricetypes) > 1)
                        <div class="w-full md:w-64 lg:w-80">
                            <x-label value="Lista precios :" />
                            <div id="parentventapricetype_id" class="relative" x-data="{ pricetype_id: @entangle('pricetype_id') }"
                                x-init="select2Pricetype">
                                <x-select class="block w-full" id="ventapricetype_id" x-ref="selectp">
                                    <x-slot name="options">
                                        @foreach ($pricetypes as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->name }}</option>
                                        @endforeach
                                    </x-slot>
                                </x-select>
                                <x-icon-select />
                            </div>
                            <x-jet-input-error for="pricetype_id" />
                        </div>
                    @endif
                @endif
            </div>

            <div class="w-full flex flex-wrap gap-2 mt-2">
                <div class="w-full md:max-w-xs">
                    <x-label value="Buscar serie :" />
                    <x-input class="block w-full" wire:keydown.enter="getProductoBySerie" wire:model.defer="searchserie"
                        placeholder="Buscar serie..." />
                    <x-jet-input-error for="searchserie" />
                </div>

                @if (count($sucursal->almacens) > 1)
                    <div class="w-full md:max-w-xs">
                        <x-label value="Almacén :" />
                        <div id="parentalmacen_id" class="relative" x-data="{ almacen_id: @entangle('almacen_id') }" x-init="select2Almacen">
                            <x-select class="block w-full" id="almacen_id" x-ref="selecta">
                                <x-slot name="options">
                                    @foreach ($sucursal->almacens as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="almacen_id" />
                    </div>
                @endif

                <div class=" w-full md:max-w-xs">
                    <x-label value="Marca :" />
                    <div id="parentsearchmarca" class="relative" x-data="{ searchmarca: @entangle('searchmarca') }" x-init="select2Marca">
                        <x-select class="block w-full" id="searchmarca" x-ref="selectmarca" data-placeholder="null"
                            data-minimum-results-for-search="3">
                            <x-slot name="options">
                                @if (count($marcas))
                                    @foreach ($marcas as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="searchmarca" />
                </div>

                <div class=" w-full md:max-w-xs">
                    <x-label value="Categoría :" />
                    <div id="parentsearchcategory" class="relative" x-data="{ searchcategory: @entangle('searchcategory') }" x-init="select2Category">
                        <x-select class="block w-full" id="searchcategory" x-ref="selectcat" data-placeholder="null"
                            data-minimum-results-for-search="3">
                            <x-slot name="options">
                                @if (count($categories))
                                    @foreach ($categories as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="searchcategory" />
                </div>

                @if (count($subcategories) > 1)
                    <div class=" w-full md:max-w-xs">
                        <x-label value="Subcategoría :" />
                        <div id="parentsearchsubcategory" class="relative" x-data="{ searchsubcategory: @entangle('searchsubcategory') }"
                            x-init="SelectSubcategory">
                            <x-select class="block w-full" id="searchsubcategory" x-ref="selectsubcat"
                                data-placeholder="null" data-minimum-results-for-search="3">
                                <x-slot name="options">
                                    @foreach ($subcategories as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="searchsubcategory" />
                    </div>
                @endif
            </div>

            <div class="w-full mt-1">
                <x-label-check for="disponibles">
                    <x-input wire:model="disponibles" name="disponibles" value="1" type="checkbox"
                        id="disponibles" />
                    MOSTRAR SOLO DISPONIBLES
                </x-label-check>
            </div>

            @if (count($productos) > 0)
                @if ($productos->hasPages())
                    <div class="w-full py-2">
                        {{ $productos->onEachSide(0)->links('livewire::pagination-default') }}
                    </div>
                @endif

                <div class="flex gap-2 flex-wrap justify-around xl:justify-start mt-1">
                    @foreach ($productos as $item)
                        <form id="cardproduct{{ $item->id }}" class="w-full xs:w-auto"
                            wire:submit.prevent="addtocar(Object.fromEntries(new FormData($event.target)), {{ $item->id }})">
                            @php
                                $image = null;
                                $almacen = null;
                                $promocion = null;

                                if (count($item->images)) {
                                    if (count($item->defaultImage)) {
                                        $image = asset('storage/productos/' . $item->defaultImage->first()->url);
                                    } else {
                                        $image = asset('storage/productos/' . $item->images->first()->url);
                                    }
                                }

                                if ($almacendefault->name) {
                                    $stock = formatDecimalOrInteger(
                                        $item->almacens->find($almacen_id)->pivot->cantidad,
                                    );
                                    $almacen = $almacendefault->name . " [$stock " . $item->unit->name . ']';
                                }

                                $promocion = count($item->promocions) > 0 ? $item->promocions->first() : null;
                                $precios = getPrecio(
                                    $item,
                                    $empresa->usarLista() ? $pricetype_id : null,
                                    $empresa->tipocambio,
                                )->getData();
                            @endphp

                            <x-card-producto :name="$item->name" :image="$image ?? null" :category="$item->category->name ?? null" :almacen="$moneda->currency ?? null"
                                :promocion="$promocion" class="h-full overflow-hidden">
                                {{-- <p class="text-colorlabel text-[9px]">{{ var_dump($precios) }}</p> --}}

                                @if (count($item->promocions) > 0)
                                    <div class="w-full my-2">
                                        @if ($item->promocions()->with('itempromos.producto.unit')->first()->isCombo())
                                            @foreach ($item->promocions->first()->itempromos as $itempromo)
                                                @php
                                                    $stockCombo = formatDecimalOrInteger(
                                                        $itempromo->producto->almacens->find($almacen_id)->pivot
                                                            ->cantidad ?? 0,
                                                    );
                                                    $colorstock =
                                                        $stockCombo > 0 ? 'text-next-500' : 'text-colorerror';
                                                    $fondostock = $stockCombo > 0 ? 'bg-green-500' : 'bg-red-500';
                                                @endphp
                                                <h1 class="{{ $colorstock }} text-[10px] leading-3 text-left">
                                                    <span
                                                        class="w-1.5 h-1.5 inline-block rounded-full {{ $fondostock }}"></span>
                                                    {{ $itempromo->producto->name }}
                                                    <span class="font-semibold">[{{ $stockCombo }}
                                                        {{ $itempromo->producto->unit->name }}]</span>
                                                </h1>
                                            @endforeach
                                        @endif
                                    </div>
                                @endif


                                @if ($empresa->usarLista())
                                    @if (count($pricetypes))
                                        <x-prices-card-product :name="$empresa->usarLista() ? $pricetype->name : null">
                                            <x-slot name="buttonpricemanual">
                                                <x-span-text :text="$almacen ?? '-'" class="leading-3 !tracking-normal"
                                                    :type="$stock <= $item->minstock ? 'orange' : ''" />
                                                @if ($precios->existsrango && count($item->descuentosactivos))
                                                    <p
                                                        class="inline-block font-semibold text-[9px] leading-3 bg-red-100 p-1 rounded text-red-500">
                                                        ANTES : {{ $moneda->simbolo }}
                                                        {{ number_format($moneda->code == 'USD' ? $precios->priceDolar : $precios->pricesale, $precios->decimal, '.', ', ') }}
                                                    </p>
                                                @endif
                                            </x-slot>

                                            @if ($precios->existsrango || $precios->pricemanual > 0)
                                                @if (count($item->descuentosactivos) > 0)
                                                    <x-input class="block w-full p-2 disabled:bg-gray-200"
                                                        name="price" type="number" min="0" step="0.0001"
                                                        value="{{ $moneda->code == 'USD' ? $precios->pricewithdescountDolar : $precios->pricewithdescount }}"
                                                        onkeypress="return validarDecimal(event, 12)" />
                                                @else
                                                    @php
                                                        $price = 0.0;
                                                        if ($moneda->code == 'USD') {
                                                            $price =
                                                                $precios->pricewithdescountDolar > 0
                                                                    ? $precios->pricewithdescountDolar
                                                                    : $precios->priceDolar;
                                                        } else {
                                                            $price = !is_null($precios->pricemanual)
                                                                ? $precios->pricemanual
                                                                : $precios->pricesale;
                                                        }
                                                    @endphp

                                                    <x-input class="block w-full p-2 disabled:bg-gray-200"
                                                        name="price" type="number" min="0" step="0.0001"
                                                        value="{{ $price }}"
                                                        onkeypress="return validarDecimal(event, 12)" />
                                                @endif
                                            @else
                                                <p>
                                                    <x-span-text text="RANGO DE PRECIO NO DISPONIBLE"
                                                        class="!tracking-normal inline-block leading-3"
                                                        type="red" />
                                                </p>
                                            @endif
                                        </x-prices-card-product>
                                    @else
                                        <x-span-text text="CONFIGURAR LISTA DE PRECIOS" />
                                    @endif
                                @else
                                    <div class="w-full flex flex-col">
                                        <x-prices-card-product :name="null">
                                            <x-slot name="buttonpricemanual">
                                                <x-span-text :text="$almacen ?? '-'" class="leading-3 !tracking-normal"
                                                    :type="$stock <= $item->minstock ? 'orange' : ''" />
                                                @if (count($item->descuentosactivos) > 0)
                                                    {{ $item->descuentos }}

                                                    <p
                                                        class="inline-block font-semibold text-[9px] leading-3 bg-red-100 p-1 rounded text-red-500">
                                                        ANTES : {{ $moneda->simbolo }}
                                                        {{ number_format($moneda->code == 'USD' ? $precios->priceDolar : $precios->pricesale, $precios->decimal, '.', ', ') }}
                                                    </p>
                                                @endif
                                            </x-slot>

                                            @if (count($item->descuentosactivos) > 0)
                                                <x-input class="block w-full p-2 disabled:bg-gray-200" name="price"
                                                    type="number" min="0" step="0.0001"
                                                    value="{{ $moneda->code == 'USD' ? $precios->pricewithdescountDolar : $precios->pricewithdescount }}" />
                                            @else
                                                @php
                                                    $price = 0.0;

                                                    if ($moneda->code == 'USD') {
                                                        $price =
                                                            $precios->pricewithdescountDolar ?? $precios->priceDolar;
                                                    } else {
                                                        $price = $precios->pricewithdescount ?? $precios->pricesale;
                                                    }
                                                @endphp

                                                <x-input class="block w-full p-2 disabled:bg-gray-200" name="price"
                                                    type="number" min="0" step="0.0001"
                                                    value="{{ $price }}"
                                                    onkeypress="return validarDecimal(event, 12)" />
                                            @endif
                                        </x-prices-card-product>
                                    </div>
                                @endif

                                @if (Module::isEnabled('Almacen'))
                                    @if (count($item->garantiaproductos) > 0)
                                        <div class="absolute right-1 flex flex-col gap-1 top-1">
                                            @foreach ($item->garantiaproductos as $garantia)
                                                <div x-data="{ isHovered: false }" @mouseover="isHovered = true"
                                                    @mouseleave="isHovered = false"
                                                    class="relative w-5 h-5 bg-green-500 text-white rounded-full p-0.5">
                                                    <svg class="w-full h-full block"
                                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                        fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                        <path
                                                            d="M11.9982 2C8.99043 2 7.04018 4.01899 4.73371 4.7549C3.79589 5.05413 3.32697 5.20374 3.1372 5.41465C2.94743 5.62556 2.89186 5.93375 2.78072 6.55013C1.59143 13.146 4.1909 19.244 10.3903 21.6175C11.0564 21.8725 11.3894 22 12.0015 22C12.6135 22 12.9466 21.8725 13.6126 21.6175C19.8116 19.2439 22.4086 13.146 21.219 6.55013C21.1078 5.93364 21.0522 5.6254 20.8624 5.41449C20.6726 5.20358 20.2037 5.05405 19.2659 4.75499C16.9585 4.01915 15.0061 2 11.9982 2Z" />
                                                        <path d="M9 13C9 13 10 13 11 15C11 15 14.1765 10 17 9" />
                                                    </svg>

                                                    <p class="absolute w-5 top-0 left-0 text-white rounded-md p-0.5 text-[8px] h-full whitespace-nowrap opacity-0 overflow-hidden bg-green-500 ease-in-out duration-150"
                                                        :class="isHovered &&
                                                            '-translate-x-full opacity-100 w-auto max-w-[100px] truncate'">
                                                        {{ $garantia->typegarantia->name }}</p>

                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                @endif

                                @if (isset($precios))
                                    @if (!$empresa->usarLista() || ($empresa->usarLista() && $precios->existsrango))
                                        <x-slot name="footer">
                                            <div class="w-full flex items-end gap-1 justify-end mt-1">
                                                @if (count($item->seriesdisponibles))
                                                    <div class="w-full">
                                                        <x-label value="Ingresar serie :" />
                                                        <x-input class="block w-full p-2 disabled:bg-gray-200"
                                                            name="serie" />
                                                    </div>
                                                @else
                                                    <div class="w-full">
                                                        <x-label value="Cantidad :" />
                                                        <x-input class="block w-full p-2 disabled:bg-gray-200"
                                                            name="cantidad" type="number" min="0"
                                                            value="1"
                                                            onkeypress="return validarDecimal(event, 12)" />
                                                    </div>
                                                @endif
                                                <x-button-add-car type="submit" wire:loading.attr="disabled" />
                                            </div>
                                        </x-slot>
                                    @endif
                                @endif

                                <x-slot name="messages">
                                    <x-jet-input-error for="cart.{{ $item->id }}.price" />
                                    <x-jet-input-error for="cart.{{ $item->id }}.almacen_id" />
                                    <x-jet-input-error for="cart.{{ $item->id }}.serie" />
                                    <x-jet-input-error for="cart.{{ $item->id }}.cantidad" />
                                </x-slot>

                                <div wire:loading.flex
                                    class="loading-overlay rounded shadow-md shadow-shadowminicard hidden">
                                    <x-loading-next />
                                </div>
                            </x-card-producto>
                        </form>
                    @endforeach
                </div>
            @else
                <div>
                    @php
                        $almacenstring = is_null($almacendefault)
                            ? '...[SUCURSAL SIN ALAMACENES]'
                            : $almacendefault->name;
                    @endphp
                    <x-span-text :text="'NO SE ENCONTRARON REGISTROS DE PRODUCTOS PARA EL ALMACEN, ' . $almacenstring" class="inline-block" type="" />
                </div>
            @endif
        </div>

        <div wire:loading.flex wire:target="render" class="loading-overlay rounded hidden">
            <x-loading-next />
        </div>
    </x-form-card>

    {{-- <x-jet-dialog-modal wire:model="open" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Combo del producto') }}
            <x-button-close-modal wire:click="$toggle('open')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="confirmar">
                <div class="w-full flex flex-col gap-2">
                    <div class="w-full relative">
                        @if (count($producto->images))
                            <x-simple-card
                                class="w-full h-60 md:max-w-md mx-auto mb-1 border border-borderminicard animate__animated animate__fadeIn animate__faster">
                                @if (count($producto->defaultImage))
                                    <img src="{{ asset('storage/productos/' . $producto->defaultImage->first()->url) }}"
                                        class="w-full h-full object-cover animate__animated animate__fadeIn animate__faster">
                                @else
                                    <img src="{{ asset('storage/productos/' . $producto->images->first()->url) }}"
                                        class="w-full h-full object-cover animate__animated animate__fadeIn animate__faster">
                                @endif
                            </x-simple-card>
                        @else
                            <x-icon-file-upload class="w-full h-60 text-gray-300" />
                        @endif

                        <div wire:loading.flex class="loading-overlay rounded hidden">
                            <x-loading-next />
                        </div>
                    </div>

                    <h1 class="text-colorlabel text-xs text-center">{{ $producto->name }}</h1>

                    @if (count($producto->promocions) > 0)
                        @php
                            $promocion = $producto->promocions()->with('itempromos')->first();
                        @endphp

                        @if (count($promocion->itempromos) > 0)
                            <div class="flex gap-2 flex-wrap justify-around xl:justify-start mt-1">
                                @foreach ($promocion->itempromos as $itempromo)
                                    @php
                                        if ($itempromo->isSinDescuento()) {
                                            $typecombo = 'SIN DESCUENTO';
                                            $color = 'orange';
                                        } elseif ($itempromo->isDescuento()) {
                                            $typecombo = formatDecimalOrInteger($itempromo->descuento) . '% DSCT';
                                            $color = 'green';
                                        } else {
                                            $typecombo = 'GRATIS';
                                            $color = 'green';
                                        }
                                    @endphp

                                    <form id="cardproduct{{ $itempromo->producto->id }}" class="w-full xs:w-auto"
                                        wire:submit.prevent="addtocar(Object.fromEntries(new FormData($event.target)), {{ $producto->id }})">
                                        @php
                                            $image = null;
                                            $almacen = null;
                                            $promocion = null;

                                            if (count($itempromo->producto->images)) {
                                                if (count($itempromo->producto->defaultImage)) {
                                                    $image = asset(
                                                        'storage/productos/' .
                                                            $itempromo->producto->defaultImage->first()->url,
                                                    );
                                                } else {
                                                    $image = asset(
                                                        'storage/productos/' .
                                                            $itempromo->producto->images->first()->url,
                                                    );
                                                }
                                            }

                                            if ($almacendefault->name) {
                                                $stock = formatDecimalOrInteger(
                                                    $itempromo->producto->almacens->first()->pivot->cantidad,
                                                );
                                                $almacen =
                                                    $almacendefault->name .
                                                    " [$stock " .
                                                    $itempromo->producto->unit->name .
                                                    ']';
                                            }

                                            $precios = getPrecio(
                                                $producto,
                                                $empresa->usarLista() ? $pricetype_id : null,
                                                $empresa->tipocambio,
                                            )->getData();
                                        @endphp

                                        <x-card-producto :name="$itempromo->producto->name" :image="$image ?? null" :category="$itempromo->producto->category->name ?? null"
                                            :almacen="$moneda->currency ?? null" class="h-full overflow-hidden">

                                            @if ($empresa->usarLista())
                                                @php
                                                    $precioscombo = getPrecio(
                                                        $itempromo->producto,
                                                        $pricetype_id,
                                                        $empresa->tipocambio,
                                                    )->getData();
                                                    $priceitem = $precioscombo->pricesale;
                                                    $priceDolar = $precioscombo->priceDolar;

                                                    if ($itempromo->isDescuento()) {
                                                        $descuentoitem = number_format(
                                                            (($priceitem - $precioscombo->pricebuy) *
                                                                $itempromo->descuento) /
                                                                100,
                                                            $precioscombo->decimal,
                                                            '.',
                                                            '',
                                                        );
                                                        $priceitem = number_format(
                                                            $priceitem - $descuentoitem,
                                                            $precioscombo->decimal,
                                                            '.',
                                                            '',
                                                        );
                                                    }

                                                    $price = $price + $priceitem;
                                                @endphp

                                                @if ($precioscombo->existsrango)
                                                    @if ($itempromo->isDescuento())
                                                        <h1
                                                            class="text-[10px] font-medium text-red-500 mt-1 line-through leading-3">
                                                            S/.
                                                            {{ number_format($precioscombo->pricesale, $precios->decimal, '.', ', ') }}
                                                            <small>SOLES</small>
                                                        </h1>
                                                    @endif

                                                    <h1 class="text-xs font-semibold text-green-500 mt-1 leading-3">
                                                        S/.
                                                        {{ number_format($priceitem, $precios->decimal, '.', ', ') }}
                                                        <small>SOLES</small>
                                                    </h1>
                                                @else
                                                    <x-prices-card-product :name="$lista->name">
                                                        <div>
                                                            <x-span-text text="RANGO DE PRECIO NO DISPONIBLE"
                                                                class="leading-3 !tracking-normal !text-red-500 !bg-red-50 !text-[9px] font-semibold"
                                                                type="red" />
                                                        </div>
                                                    </x-prices-card-product>
                                                @endif
                                            @else
                                                @php
                                                    $precioscombo = getPrecio(
                                                        $itempromo->producto,
                                                        null,
                                                        $empresa->tipocambio,
                                                    )->getData();
                                                    $priceitem = $precioscombo->pricesale;
                                                    $priceDolar =
                                                        $precioscombo->pricewithdescountDolar ??
                                                        $precioscombo->priceDolar;

                                                    if ($itempromo->isDescuento()) {
                                                        $descuentoitem = number_format(
                                                            (($precioscombo->pricesale - $precioscombo->pricebuy) *
                                                                $itempromo->descuento) /
                                                                100,
                                                            $precioscombo->decimal,
                                                            '.',
                                                            '',
                                                        );
                                                        $priceitem = number_format(
                                                            $precioscombo->pricesale - $descuentoitem,
                                                            $precioscombo->decimal,
                                                            '.',
                                                            '',
                                                        );
                                                    }

                                                    $price = $price + $priceitem;
                                                @endphp

                                                @if ($itempromo->descuento > 0)
                                                    <div>
                                                        <x-span-text :text="'ANTES : S/. ' .
                                                            number_format(
                                                                $precioscombo->pricesale ?? 0,
                                                                $precioscombo->decimal,
                                                                '.',
                                                                ', ',
                                                            )"
                                                            class="leading-3 !tracking-normal text-[8px]"
                                                            type="red" />
                                                    </div>
                                                @endif

                                                <x-label-price>
                                                    S/.
                                                    {{ number_format($priceitem ?? 0, $precioscombo->decimal, '.', ', ') }}
                                                    <small>SOLES</small>
                                                </x-label-price>
                                            @endif
                                           
                                            <x-span-text :text="$typecombo" :type="$color"
                                                class="leading-3 !tracking-normal absolute right-1 bottom-1" />

                                            @if (Module::isEnabled('Almacen'))
                                                @if (count($item->garantiaproductos) > 0)
                                                    <div class="absolute right-1 flex flex-col gap-1 top-1">
                                                        @foreach ($item->garantiaproductos as $garantia)
                                                            <div x-data="{ isHovered: false }" @mouseover="isHovered = true"
                                                                @mouseleave="isHovered = false"
                                                                class="relative w-5 h-5 bg-green-500 text-white rounded-full p-0.5">
                                                                <svg class="w-full h-full block"
                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                    viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round">
                                                                    <path
                                                                        d="M11.9982 2C8.99043 2 7.04018 4.01899 4.73371 4.7549C3.79589 5.05413 3.32697 5.20374 3.1372 5.41465C2.94743 5.62556 2.89186 5.93375 2.78072 6.55013C1.59143 13.146 4.1909 19.244 10.3903 21.6175C11.0564 21.8725 11.3894 22 12.0015 22C12.6135 22 12.9466 21.8725 13.6126 21.6175C19.8116 19.2439 22.4086 13.146 21.219 6.55013C21.1078 5.93364 21.0522 5.6254 20.8624 5.41449C20.6726 5.20358 20.2037 5.05405 19.2659 4.75499C16.9585 4.01915 15.0061 2 11.9982 2Z" />
                                                                    <path
                                                                        d="M9 13C9 13 10 13 11 15C11 15 14.1765 10 17 9" />
                                                                </svg>

                                                                <p class="absolute w-5 top-0 left-0 text-white rounded-md p-0.5 text-[8px] h-full whitespace-nowrap opacity-0 overflow-hidden bg-green-500 ease-in-out duration-150"
                                                                    :class="isHovered &&
                                                                        '-translate-x-full opacity-100 w-auto max-w-[100px] truncate'">
                                                                    {{ $garantia->typegarantia->name }}</p>

                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            @endif


                                            <x-slot name="messages">
                                                <x-jet-input-error for="cart.{{ $itempromo->producto->id }}.price" />
                                                <x-jet-input-error
                                                    for="cart.{{ $itempromo->producto->id }}.almacen_id" />
                                                <x-jet-input-error for="cart.{{ $itempromo->producto->id }}.serie" />
                                                <x-jet-input-error
                                                    for="cart.{{ $itempromo->producto->id }}.cantidad" />
                                            </x-slot>

                                            <div wire:loading.flex
                                                class="loading-overlay rounded shadow-md shadow-shadowminicard hidden">
                                                <x-loading-next />
                                            </div>
                                        </x-card-producto>
                                    </form>
                                @endforeach
                            </div>
                        @endif
                    @endif
                    {{ $producto }}
                </div>

                <div class="w-full flex justify-end pt-4">
                    <x-button type="submit" wire:click="confirmar" wire:loading.attr="disabled">
                        {{ __('CONFIRMAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal> --}}

    <script>
        function select2Almacen() {
            this.selectA = $(this.$refs.selecta).select2();
            this.selectA.val(this.almacen_id).trigger("change");
            this.selectA.on("select2:select", (event) => {
                this.almacen_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("almacen_id", (value) => {
                this.selectA.val(value).trigger("change");
            });

            Livewire.hook('message.processed', () => {
                this.selectA.select2().val(this.almacen_id).trigger('change');
            });
        }

        function select2Marca() {
            this.selectM = $(this.$refs.selectmarca).select2();
            this.selectM.val(this.searchmarca).trigger("change");
            this.selectM.on("select2:select", (event) => {
                this.searchmarca = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("searchmarca", (value) => {
                this.selectM.val(value).trigger("change");
            });

            Livewire.hook('message.processed', () => {
                this.selectM.select2().val(this.searchmarca).trigger('change');
            });
        }

        function select2Category() {
            this.selectC = $(this.$refs.selectcat).select2();
            this.selectC.val(this.searchcategory).trigger("change");
            this.selectC.on("select2:select", (event) => {
                this.searchcategory = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("searchcategory", (value) => {
                this.selectC.val(value).trigger("change");
            });

            Livewire.hook('message.processed', () => {
                this.selectC.select2().val(this.searchcategory).trigger('change');
            });
        }

        function select2Pricetype() {
            this.selectP = $(this.$refs.selectp).select2();
            this.selectP.val(this.pricetype_id).trigger("change");
            this.selectP.on("select2:select", (event) => {
                @this.setPricetypeId(event.target.value);
                // this.pricetype_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("pricetype_id", (value) => {
                this.selectP.val(value).trigger("change");
            });

            Livewire.hook('message.processed', () => {
                this.selectP.select2().val(this.pricetype_id).trigger('change');
            });
        }

        function SelectSubcategory() {
            this.selectSC = $(this.$refs.selectsubcat).select2();
            this.selectSC.val(this.searchsubcategory).trigger("change");
            this.selectSC.on("select2:select", (event) => {
                this.searchsubcategory = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("searchsubcategory", (value) => {
                this.selectSC.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectSC.select2('destroy');
                this.selectSC.select2().val(this.searchsubcategory).trigger('change');
            });
        }

        window.addEventListener('reset-form', data => {
            let form = document.getElementById("cardproduct" + data.detail);
            if (form) {
                form.reset();
            }
        });

        window.addEventListener('deleted', () => {
            @this.render();
        });

        window.addEventListener('setMoneda', data => {
            @this.setMoneda(data.detail);
        });

        window.addEventListener('setPricetypeId', data => {
            if (data.detail) {
                @this.setPricetypeId(data.detail);
            }
        });
    </script>
</div>
