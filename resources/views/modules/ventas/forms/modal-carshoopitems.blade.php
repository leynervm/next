<div x-data="{ open: false }" wire:key="modal_{{ $item->id }}">
    <button type="button" @click="open = true" wire:click="openmodalcarshoops({{ $item->id }})"
        class="text-primary w-full flex font-semibold gap-3 items-center justify-center text-[10px] tracking-widest p-2.5 rounded-lg shadow-md shadow-shadowminicard"
        style="box-shadow: 0px 0px 3px #ccc">

        @if ($item->promocion && $item->promocion->isCombo())
            VER COMBO
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" fill="currentColor" stroke="currentColor"
                stroke-width=".5" class="block size-4 text-primary">
                <path
                    d="m19.534 11.063.88-1.253L24 4.709c.21-.297.418-.599.64-.887a1 1 0 0 1 .576-.411 1.28 1.28 0 0 1 1.065.224l2.336 1.717 3.694 2.715 4.071 2.994q1.371 1.006 2.738 2.016a.9.9 0 0 0 .562.178c.464-.002.93-.025 1.39.025 1.611.183 2.798 1.481 2.896 2.891q.082 1.207.174 2.414c.114 1.513.238 3.022.343 4.533.082 1.131.142 2.261.219 3.39.069 1.01.151 2.021.226 3.031l.217 2.944q.091 1.211.171 2.43.119 1.81.215 3.625c.041.791.053 1.582.089 2.373.021.443.069.882.091 1.326q.009.336-.034.672a7 7 0 0 1-.142.768 2.22 2.22 0 0 1-.974 1.342c-.64.434-1.351.686-2.135.688q-2.011.005-4.025 0l-10.745.005H15.223c-3.129 0-6.256-.009-9.385.005a3.7 3.7 0 0 1-2.528-.901c-.759-.654-1.074-1.495-1.017-2.482q.062-1.031.133-2.062c.053-.754.114-1.511.167-2.267q.039-.613.069-1.223l.133-2.24q.064-1.243.137-2.489.069-1.193.153-2.386l.169-2.443q.105-1.495.199-2.99c.057-.928.103-1.856.16-2.784q.126-2.011.258-4.023c.03-.45.03-.907.096-1.353.185-1.255.894-2.11 2.066-2.59q.64-.256 1.328-.235c.439.009.88-.002 1.319.005.133 0 .174-.037.174-.174q-.009-2.56-.009-5.125c0-1.509.016-3.022-.002-4.533-.009-.663.443-1.12 1.109-1.129q1.063-.01 2.13-.007l6.245.007c.279 0 .539.053.766.224a.91.91 0 0 1 .389.667q.023.183.025.366v7.504zm4.537 32.482h18.087c.233 0 .469 0 .693-.041.491-.094.882-.633.843-1.131l-.032-.265q-.151-2.002-.293-4.002c-.075-1.125-.139-2.249-.21-3.374q-.089-1.474-.185-2.949c-.069-.976-.155-1.95-.222-2.926-.096-1.429-.181-2.857-.274-4.286l-.261-3.961-.304-4.418c-.034-.48-.423-.93-.907-.965-.539-.037-1.083-.016-1.623-.011-.263 0-.523.032-.784.03-.24-.002-.48-.046-.72-.046q-9.029-.007-18.057 0c-.315 0-.629.046-.944.064-.215.011-.43.023-.645.011-.295-.016-.587-.075-.882-.075q-3.271-.007-6.54-.005a2 2 0 0 0-.279.018 4.6 4.6 0 0 1-1.131.023 18 18 0 0 0-2.039-.027 1.2 1.2 0 0 0-1.143 1.127q-.082 1.287-.176 2.576l-.265 3.845-.265 4.034-.208 3.093q-.133 1.874-.261 3.755c-.085 1.287-.16 2.571-.245 3.858l-.315 4.699a1.37 1.37 0 0 0 .094.722c.229.455.608.626 1.099.624l7.623.002h10.775ZM10.997 13.236q.069.009.13.011h6.069c.107 0 .13-.034.13-.137l.005-2.805q0-2.841.009-5.685c0-.139-.037-.183-.183-.183q-3.003.007-6.009.005l-.151.007zm9.653 0h15.054l-9.897-7.278z" />
                <path
                    d="m19.534 11.063.88-1.253L24 4.709c.21-.297.418-.599.64-.887a1 1 0 0 1 .576-.411 1.28 1.28 0 0 1 1.065.224l2.336 1.717 3.694 2.715 4.071 2.994q1.371 1.006 2.738 2.016a.9.9 0 0 0 .562.178c.464-.002.93-.025 1.39.025 1.611.183 2.798 1.481 2.896 2.891q.082 1.207.174 2.414c.114 1.513.238 3.022.343 4.533.082 1.131.142 2.261.219 3.39.069 1.01.151 2.021.226 3.031l.217 2.944q.091 1.211.171 2.43.119 1.81.215 3.625c.041.791.053 1.582.089 2.373.021.443.069.882.091 1.326q.009.336-.034.672a7 7 0 0 1-.142.768 2.22 2.22 0 0 1-.974 1.342c-.64.434-1.351.686-2.135.688q-2.011.005-4.025 0l-10.745.005H15.223c-3.129 0-6.256-.009-9.385.005a3.7 3.7 0 0 1-2.528-.901c-.759-.654-1.074-1.495-1.017-2.482q.062-1.031.133-2.062c.053-.754.114-1.511.167-2.267q.039-.613.069-1.223l.133-2.24q.064-1.243.137-2.489.069-1.193.153-2.386l.169-2.443q.105-1.495.199-2.99c.057-.928.103-1.856.16-2.784q.126-2.011.258-4.023c.03-.45.03-.907.096-1.353.185-1.255.894-2.11 2.066-2.59q.64-.256 1.328-.235c.439.009.88-.002 1.319.005.133 0 .174-.037.174-.174q-.009-2.56-.009-5.125c0-1.509.016-3.022-.002-4.533-.009-.663.443-1.12 1.109-1.129q1.063-.01 2.13-.007l6.245.007c.279 0 .539.053.766.224a.91.91 0 0 1 .389.667q.023.183.025.366v7.504zm4.537 32.482h18.087c.233 0 .469 0 .693-.041.491-.094.882-.633.843-1.131l-.032-.265q-.151-2.002-.293-4.002c-.075-1.125-.139-2.249-.21-3.374q-.089-1.474-.185-2.949c-.069-.976-.155-1.95-.222-2.926-.096-1.429-.181-2.857-.274-4.286l-.261-3.961-.304-4.418c-.034-.48-.423-.93-.907-.965-.539-.037-1.083-.016-1.623-.011-.263 0-.523.032-.784.03-.24-.002-.48-.046-.72-.046q-9.029-.007-18.057 0c-.315 0-.629.046-.944.064-.215.011-.43.023-.645.011-.295-.016-.587-.075-.882-.075q-3.271-.007-6.54-.005a2 2 0 0 0-.279.018 4.6 4.6 0 0 1-1.131.023 18 18 0 0 0-2.039-.027 1.2 1.2 0 0 0-1.143 1.127q-.082 1.287-.176 2.576l-.265 3.845-.265 4.034-.208 3.093q-.133 1.874-.261 3.755c-.085 1.287-.16 2.571-.245 3.858l-.315 4.699a1.37 1.37 0 0 0 .094.722c.229.455.608.626 1.099.624l7.623.002h10.775ZM10.997 13.236q.069.009.13.011h6.069c.107 0 .13-.034.13-.137l.005-2.805q0-2.841.009-5.685c0-.139-.037-.183-.183-.183q-3.003.007-6.009.005l-.151.007zm9.653 0h15.054l-9.897-7.278z" />
                <path
                    d="M32.923 22.329c.19-.274.432-.48.773-.521l-19.177-.261a1.44 1.44 0 0 0-.841.046 1.2 1.2 0 0 0-.608.558 1.44 1.44 0 0 0 .023 1.506c.672 1.111 1.586 1.982 2.619 2.706 1.559 1.09 3.31 1.696 5.134 2.041a16 16 0 0 0 2.846.265v.078l.37-.032q.386-.03.777-.055a29 29 0 0 0 1.831-.169 13.7 13.7 0 0 0 3.671-.992c1.552-.67 2.914-1.6 4.007-2.923q.496-.603.791-1.326a1.05 1.05 0 0 0-.037-.937 1.65 1.65 0 0 0-.869-.777 1.4 1.4 0 0 0-.576-.069 1.42 1.42 0 0 0-1.015.667m.281.194-.281-.194m.281.194-.144.215a7 7 0 0 1-.389.544zm-.281-.194-.16.235a5 5 0 0 1-.354.496m.514-.731-.514.731m0 0c-.754.896-1.705 1.531-2.789 1.995zm-10.665 2.762a16.7 16.7 0 0 0 3.801.16 12.1 12.1 0 0 0 4.071-.926zm0 0c-1.378-.231-2.681-.645-3.854-1.378zm-3.854-1.378a7.3 7.3 0 0 1-2.233-2.128zm-2.233-2.13a1.33 1.33 0 0 0-.859-.571z" />
            </svg>
        @else
            @if (
                $item->kardexes->sum('cantidad') < $item->cantidad ||
                    ($item->producto->isRequiredserie() && count($item->itemseries) < $item->cantidad))
                {{-- @if ($item->producto->isRequiredserie())
                    DESCONTAR SERIES
                @else
                    DESCONTAR STOCK
                @endif --}}
                <span class="text-red-600">DESCONTAR STOCK</span>
                <x-icon-file-upload type="searchbox" class="!m-0 !flex-none !p-0 size-4 text-red-600" />
            @else
                VER DETALLES
                <x-icon-file-upload type="searchbox" class="!m-0 !flex-none !p-0 size-4 text-primary" />
            @endif
        @endif
    </button>

    <div class="fixed z-[99] inset-0 overflow-y-auto transition-opacity" x-show="open" x-cloak style="display: none"
        {{-- @keydown.escape.window="open = true" --}}>
        <div class="absolute inset-0 bg-neutral-800 opacity-75"></div>
        <div class="w-full p-2 flex items-center justify-center min-h-screen">
            <div class="w-full bg-body p-2 sm:p-3 rounded-md shadow-xl transform transition-all sm:max-w-6xl sm:w-full"
                @click.away="open = true" x-transition:enter="ease-out duration-100"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0">

                <div class="w-full flex items-start justify-between">
                    <div class="text-left flex-1">
                        <h3 class="text-sm sm:text-lg !leading-none font-medium text-colorsubtitleform">
                            @if ($item->promocion && $item->promocion->isCombo())
                                {{ strtoupper($item->promocion->titulo) }}
                            @else
                                {{ strtoupper($item->producto->name) }}
                            @endif
                        </h3>

                        <div>
                            <small class="text-colorerror">
                                @if ($item->isNoAlterStock())
                                    NO ALTERA STOCK
                                @elseif ($item->isReservedStock())
                                    STOCK RESERVADO
                                @elseif ($item->isIncrementStock())
                                    INCREMENTA STOCK
                                @elseif($item->isDiscountStock())
                                    DISMINUYE STOCK
                                @endif
                            </small>
                        </div>
                    </div>
                    <span class="cursor-pointer text-lg text-colorlabel" @click="open = false">✕</span>
                </div>

                <div
                    class="w-full grid grid-cols-[repeat(auto-fill,minmax(170px,1fr))] sm:grid-cols-[repeat(auto-fill,minmax(200px,1fr))] gap-1 mt-1">

                    @php
                        $imagenprod = !empty($item->producto->imagen)
                            ? pathURLProductImage($item->producto->imagen->url)
                            : null;
                    @endphp
                    <x-card-producto :image="$imagenprod" :name="$item->producto->name" :marca="$item->producto->marca->name" :category="$item->producto->category->name"
                        :promocion="$item->promocion" class="overflow-hidden">

                        <h1 class="text-xl align-middle !leading-none font-semibold mt-1 text-center text-colorlabel">
                            {{ decimalOrInteger($item->cantidad, 2, ', ') }}
                            <small class="text-[10px] font-medium">
                                {{ $item->producto->unit->name }}</small>
                        </h1>

                        <h1 class="text-xl text-center font-semibold text-colortitleform">
                            <small class="text-[10px] font-medium">
                                {{ $moneda->simbolo }}</small>
                            {{ decimalOrInteger($item->total, 2, ', ') }}
                            <small class="text-[10px] font-medium">
                                {{ $moneda->currency }}</small>
                        </h1>

                        @if ($item->cantidad > 1)
                            <div class="text-sm font-semibold text-colorlabel leading-3">
                                <small class="text-[10px] font-medium">P.U.V:</small>
                                {{ decimalOrInteger($item->price + $item->igv, 2, ', ') }}
                                <small class="text-[10px] font-medium">
                                    {{ $moneda->currency }}</small>
                            </div>
                        @endif


                        @if ($item->kardexes->sum('cantidad') < $item->cantidad)
                            @if (count($item->producto->almacens) > 0)
                                <div class="w-full flex flex-col gap-2">
                                    @foreach ($item->producto->almacens as $almacen)
                                        <form wire:key="{{ $item->id . $almacen->id }}"
                                            wire:submit.prevent="confirmkardexstock({{ $almacen->id }})"
                                            class="w-full p-2 flex flex-col gap-2 justify-start rounded-xl border border-borderminicard hover:shadow hover:shadow-shadowminicard">
                                            <div class="text-colorsubtitleform text-center">
                                                <small class="w-full block text-center text-[8px] leading-3">
                                                    STOCK ACTUAL</small>
                                                <span class="inline-block text-2xl text-center font-semibold">
                                                    {{ decimalOrInteger($almacen->pivot->cantidad) }}</span>
                                                <small class="inline-block text-left text-[10px] leading-3">
                                                    {{ $item->producto->unit->name }}
                                                    <br>
                                                    {{ $almacen->name }}
                                                </small>
                                            </div>

                                            @if ($item->producto->isRequiredserie())
                                                <div class="w-full">
                                                    <x-label value="SELECCIONAR SERIES :" />
                                                    <div class="relative"
                                                        id="parentserie_id_{{ $item->id . $almacen->id }}">
                                                        <x-select class="block w-full relative" x-init="initializeSelect2($el, {{ $almacen->id }})"
                                                            wire:model.defer="almacens.{{ $almacen->id }}.serie_id"
                                                            id="serie_id_{{ $item->id . $almacen->id }}"
                                                            data-dropdown-parent="null" data-placeholder="null"
                                                            x-ref="serie_id_{{ $almacen->id }}"
                                                            data-minimum-results-for-search="3">
                                                            <x-slot name="options">
                                                                @foreach ($item->producto->seriesdisponibles->where('almacen_id', $almacen->id) as $ser)
                                                                    <option value="{{ $ser->id }}">
                                                                        {{ $ser->serie }}
                                                                    </option>
                                                                @endforeach
                                                            </x-slot>
                                                        </x-select>
                                                        <x-icon-select />
                                                    </div>
                                                    <x-jet-input-error for="almacens.{{ $almacen->id }}.serie_id" />
                                                </div>
                                            @else
                                                <div class="w-full">
                                                    <x-label value="STOCK DESCONTAR :" />
                                                    <x-input class="block w-full"
                                                        wire:model.defer="almacens.{{ $almacen->id }}.cantidad"
                                                        x-mask:dynamic="$money($input, '.', '', 0)"
                                                        onkeypress="return validarDecimal(event, 9)"
                                                        wire:key="cantidad_{{ $almacen->id }}"
                                                        wire:loading.class="bg-blue-50" />
                                                    <x-jet-input-error for="almacens.{{ $almacen->id }}.cantidad" />
                                                </div>
                                            @endif

                                            <div class="w-full">
                                                <x-button type="submit">AGREGAR</x-button>
                                            </div>
                                        </form>
                                    @endforeach
                                </div>
                            @else
                                <small class="text-colorerror font-semibold text-[11px]">
                                    No existen almacénes</small>
                            @endif
                        @endif

                        @if ($item->producto->isRequiredserie())
                            <x-table class="w-full block mt-2">
                                <x-slot name="body">
                                    @foreach ($item->itemseries as $itemserie)
                                        <tr>
                                            <td class="p-1 align-middle font-medium">
                                                {{ $itemserie->serie->serie }}
                                                [{{ $itemserie->serie->almacen->name }}]
                                            </td>
                                            <td class="align-middle text-center" width="40px">
                                                @if ($itemserie->serie)
                                                    <x-button-delete wire:loading.attr="disabled"
                                                        wire:key="deleteitemserie_{{ $itemserie->id }}"
                                                        x-on:click="confirmDeleteSerie({{ $itemserie->id }}, '{{ $itemserie->serie->serie }}')" />
                                                @else
                                                    <x-button-delete wire:loading.attr="disabled"
                                                        wire:key="deleteitemserie_{{ $itemserie->id }}"
                                                        x-on:click="confirmDeleteSerie({{ $itemserie->id }})" />
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </x-slot>
                            </x-table>
                        @else
                            <div class="w-full grid grid-cols-[repeat(auto-fill,minmax(100px,1fr))] gap-2 mt-2">
                                @if (count($item->kardexes) > 0)
                                    @foreach ($item->kardexes as $kardex)
                                        <div class="w-full p-1.5 flex flex-col items-center justify-start">
                                            <h1 class="text-colorsubtitleform text-sm font-semibold !leading-none">
                                                {{ decimalOrInteger($kardex->cantidad) }}
                                                <small class="text-[10px] font-normal">
                                                    {{ $item->producto->unit->name }}</small>
                                            </h1>

                                            <h1 class="text-colortitleform text-[10px] font-semibold">
                                                {{ $kardex->almacen->name }}</h1>

                                            @if (!$item->producto->isRequiredserie())
                                                <x-button-delete
                                                    wire:click="deletekardex({{ $item->id }},{{ $kardex->id }})"
                                                    wire:loading.attr="disabled" class="inline-flex" />
                                            @endif
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        @endif
                    </x-card-producto>


                    @foreach ($item->carshoopitems as $itemcarshoop)
                        @php
                            $imagen = !empty($itemcarshoop->producto->imagen)
                                ? pathURLProductImage($itemcarshoop->producto->imagen->url)
                                : null;
                        @endphp
                        <x-card-producto :image="$imagen" :name="$itemcarshoop->producto->name" :marca="$itemcarshoop->producto->marca->name" :category="$itemcarshoop->producto->category->name"
                            class="overflow-hidden">

                            <h1
                                class="text-xl align-middle !leading-none font-semibold mt-1 text-center text-colorlabel">
                                {{ decimalOrInteger($itemcarshoop->cantidad, 2, ', ') }}
                                <small class="text-[10px] font-medium">
                                    {{ $itemcarshoop->producto->unit->name }}</small>

                                @if ($itemcarshoop->itempromo)
                                    <small class="text-center">
                                        @if ($itemcarshoop->itempromo->isDescuento())
                                            <x-span-text type="green" class="mx-auto inline-block"
                                                text="{{ decimalOrInteger($itemcarshoop->itempromo->descuento) }}% DSCT" />
                                        @endif
                                        @if ($itemcarshoop->itempromo->isLiquidacion())
                                            <x-span-text type="green" class="mx-auto inline-block"
                                                text="LIQUIDACIÓN" />
                                        @endif
                                        @if ($itemcarshoop->itempromo->isGratuito())
                                            <x-span-text type="green" class="mx-auto inline-block"
                                                text="GRATIS" />
                                        @endif
                                    </small>
                                @endif
                            </h1>

                            @if ($itemcarshoop->itempromo && !$itemcarshoop->itempromo->isGratuito())
                                <h1 class="text-xl text-center font-semibold text-colortitleform">
                                    <small class="text-[10px] font-medium">
                                        {{ $moneda->simbolo }}</small>
                                    {{ decimalOrInteger($itemcarshoop->total, 2, ', ') }}
                                    <small class="text-[10px] font-medium">
                                        {{ $moneda->currency }}</small>
                                </h1>

                                @if ($itemcarshoop->cantidad > 1)
                                    <div class="text-sm font-semibold text-colorlabel leading-3">
                                        <small class="text-[10px] font-medium">P.U.V:</small>
                                        {{ decimalOrInteger($itemcarshoop->price + $itemcarshoop->igv, 2, ', ') }}
                                        <small class="text-[10px] font-medium">
                                            {{ $moneda->currency }}</small>
                                    </div>
                                @endif
                            @endif

                            @if ($itemcarshoop->kardexes->sum('cantidad') < $itemcarshoop->cantidad)
                                @if (count($item->producto->almacens) > 0)
                                    <div class="w-full flex flex-col gap-2">
                                        @foreach ($itemcarshoop->producto->almacens as $almacen)
                                            <form wire:key="formserie_{{ $itemcarshoop->id . $almacen->id }}"
                                                wire:submit.prevent="confirmkardexstockitem({{ $almacen->id }}, {{ $itemcarshoop->id }})"
                                                class="w-full p-2 flex flex-col gap-2 justify-start rounded-xl border border-borderminicard hover:shadow hover:shadow-shadowminicard">
                                                <div class="text-colorsubtitleform text-center">
                                                    <small class="w-full block text-center text-[8px] leading-3">
                                                        STOCK ACTUAL</small>
                                                    <span class="inline-block text-2xl text-center font-semibold">
                                                        {{ decimalOrInteger($almacen->pivot->cantidad) }}</span>
                                                    <small class="inline-block text-left text-[10px] leading-3">
                                                        {{ $itemcarshoop->producto->unit->name }}
                                                        <br>
                                                        {{ $almacen->name }}
                                                    </small>
                                                </div>

                                                @if ($itemcarshoop->producto->isRequiredserie())
                                                    <div class="w-full">
                                                        <x-label value="SELECCIONAR SERIES :" />
                                                        <div class="relative"
                                                            id="parentserie_id_{{ $itemcarshoop->id . $almacen->id }}">
                                                            <x-select class="block w-full relative"
                                                                x-init="select2Carshoopitem($el, {{ $itemcarshoop->id }}, {{ $almacen->id }})"
                                                                wire:model.defer="almacenitem.{{ $itemcarshoop->id }}.almacens.{{ $almacen->id }}.serie_id"
                                                                id="serie_id_{{ $itemcarshoop->id . $almacen->id }}"
                                                                data-dropdown-parent="null" data-placeholder="null"
                                                                x-ref="serie_id_{{ $itemcarshoop->id . $almacen->id }}"
                                                                data-minimum-results-for-search="3">
                                                                <x-slot name="options">
                                                                    @foreach ($itemcarshoop->producto->seriesdisponibles->where('almacen_id', $almacen->id) as $ser)
                                                                        <option value="{{ $ser->id }}">
                                                                            {{ $ser->serie }}
                                                                        </option>
                                                                    @endforeach
                                                                </x-slot>
                                                            </x-select>
                                                            <x-icon-select />
                                                        </div>
                                                        <x-jet-input-error
                                                            for="almacenitem.{{ $itemcarshoop->id }}.almacens.{{ $almacen->id }}.serie_id" />
                                                    </div>
                                                @else
                                                    <div class="w-full">
                                                        <x-label value="STOCK DESCONTAR :" />
                                                        <x-input class="block w-full"
                                                            wire:model.defer="almacenitem.{{ $itemcarshoop->id }}.almacens.{{ $almacen->id }}.cantidad"
                                                            x-mask:dynamic="$money($input, '.', '', 0)"
                                                            onkeypress="return validarDecimal(event, 9)"
                                                            wire:key="cantidad_{{ $itemcarshoop->id }}"
                                                            wire:loading.class="bg-blue-50" />
                                                        <x-jet-input-error
                                                            for="almacenitem.{{ $itemcarshoop->id }}.almacens.{{ $almacen->id }}.cantidad" />
                                                    </div>
                                                @endif

                                                <div class="w-full">
                                                    <x-button type="submit">AGREGAR</x-button>
                                                </div>
                                            </form>
                                        @endforeach
                                    </div>
                                @else
                                    <small class="text-colorerror font-semibold text-[11px]">
                                        No existen almacénes</small>
                                @endif
                            @endif

                            @if ($itemcarshoop->producto->isRequiredserie())
                                <x-table class="w-full block mt-2">
                                    <x-slot name="body">
                                        @foreach ($itemcarshoop->itemseries as $itemserie)
                                            <tr>
                                                <td class="p-1 align-middle font-medium">
                                                    {{ $itemserie->serie->serie }}
                                                    [{{ $itemserie->serie->almacen->name }}]
                                                </td>
                                                <td class="align-middle text-center" width="40px">
                                                    <x-button-delete wire:loading.attr="disabled"
                                                        wire:click="deleteitemserieitem({{ $itemserie->id }})" />
                                                </td>
                                            </tr>
                                        @endforeach
                                    </x-slot>
                                </x-table>
                            @else
                                <div class="w-full grid grid-cols-[repeat(auto-fill,minmax(100px,1fr))] gap-2 mt-2">
                                    @if (count($itemcarshoop->kardexes) > 0)
                                        @foreach ($itemcarshoop->kardexes as $kardex)
                                            <div class="w-full p-1.5 flex flex-col items-center justify-start">
                                                <h1 class="text-colorsubtitleform text-sm font-semibold !leading-none">
                                                    {{ decimalOrInteger($kardex->cantidad) }}
                                                    <small class="text-[10px] font-normal">
                                                        {{ $itemcarshoop->producto->unit->name }}</small>
                                                </h1>

                                                <h1 class="text-colortitleform text-[10px] font-semibold">
                                                    {{ $kardex->almacen->name }}</h1>

                                                @if (!$itemcarshoop->producto->isRequiredserie())
                                                    <x-button-delete
                                                        wire:click="deletekardexcarshoop({{ $itemcarshoop->id }},{{ $kardex->id }})"
                                                        wire:loading.attr="disabled" class="inline-flex" />
                                                @endif
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            @endif
                        </x-card-producto>
                    @endforeach
                </div>

                @if ($viewloading)
                    <div wire:loading.flex class="loading-overlay hidden fixed"
                        wire:key="discountstock{{ $item->id }}">
                        <x-loading-next />
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
