<div
    class="w-full grid grid-cols-[repeat(auto-fill,minmax(180px,1fr))] sm:grid-cols-[repeat(auto-fill,minmax(200px,1fr))] gap-1 mt-1">
    @foreach ($productos as $item)
        <form id="cardproduct{{ $item->id }}" class="w-full block" x-data="{ serie_id: null, seriealmacen_id: null }"
            @submit.prevent="addtocarrito($event, {{ $item->id }}, serie_id, seriealmacen_id)" autocomplete="off"
            novalidate>
            @php
                $image = !empty($item->image) ? pathURLProductImage($item->image) : null;
                $promocion = verifyPromocion($item->promocion);
                $descuento = getDscto($promocion);
                $combo = $item->getAmountCombo($promocion, $pricetype, $almacen_id);
                $almacen = null;
                $pricesale = $item->obtenerPrecioVenta($pricetype);
            @endphp

            <x-card-producto :name="$item->name" :image="$image" :category="$item->name_category" :marca="$item->name_marca" :promocion="$promocion"
                class="w-full h-full" id="card_{{ $item->id }}">

                @if ($item->isNovedad())
                    <div class="w-full flex justify-end">
                        @if (!empty($empresa->textnovedad))
                            <span class="span-novedad">
                                {{ $empresa->textnovedad }}</span>
                        @endif
                        <x-icon-novedad />
                    </div>
                @endif

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
                                    <div class="p-1 w-full flex-1">
                                        <h1 class="text-[10px] leading-3 text-left">
                                            {{ $itemcombo->name }}
                                            <b>[{{ $itemcombo->stock }}
                                                {{ $itemcombo->unit }}]</b>
                                        </h1>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endif

                <div class="w-full py-2">
                    @if ($pricesale > 0)
                        @if ($descuento > 0)
                            <p class="block w-full line-through text-red-600 text-center">
                                {{ $moneda->simbolo }}
                                {{ decimalOrInteger(getPriceAntes($pricesale, $descuento), $pricetype->decimals ?? 2, ', ') }}
                            </p>
                        @endif

                        <div class="w-full relative">
                            <x-input class="block pl-7 w-full text-end disabled:bg-gray-200 input-number-none"
                                name="price" type="number" min="0" step="0.001"
                                value="{{ $moneda->isDolar() ? convertMoneda($pricesale, 'USD', $empresa->tipocambio, 3) : $pricesale }}"
                                onkeypress="return validarDecimal(event, 12)" />
                            <small
                                class="text-xs left-2.5 absolute top-[50%] -translate-y-[50%] font-medium text-left text-colorsubtitleform">
                                {{ $moneda->simbolo }}</small>
                        </div>
                    @else
                        <p class="text-colorerror text-[10px] font-semibold text-center">
                            PRECIO DE VENTA NO DISPONIBLE</p>
                    @endif
                </div>

                @if ($item->isRequiredserie())
                    <div class="w-full">
                        <x-label value="Seleccionar serie :" />
                        <div id="parentserieproducto_{{ $item->id }}" class="relative">
                            <x-select name="serie_id" class="block w-full relative"
                                id="serieproducto_{{ $item->id }}" data-placeholder="null"
                                data-minimum-results-for-search="0" x-init="$nextTick(() => {
                                    $($el).select2({ templateResult: formatOption }).on('select2:open', function(e) {
                                        const evt = 'scroll.select2';
                                        $(e.target).parents().off(evt);
                                        $(window).off(evt);
                                    }).on('change', (e) => {
                                        serie_id = $el.value;
                                        const paramsData = $(e.target).select2('data')[0];
                                        seriealmacen_id = paramsData ? paramsData.element.dataset.almacen_id : null;
                                    });
                                    $watch('serie_id', (value) => {
                                        $($el).val(value).select2({ templateResult: formatOption }).trigger('change');
                                    });
                                    Livewire.hook('message.processed', () => {
                                        $($el).select2({ templateResult: formatOption }).val(serie_id).trigger('change');
                                    });
                                })">
                                <x-slot name="options">
                                    @foreach ($item->seriesdisponibles as $ser)
                                        <option data-almacen_id="{{ $ser->almacen_id }}" value="{{ $ser->id }}"
                                            title="{{ $ser->almacen->name }}">
                                            {{ $ser->serie }}</option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="cart.{{ $item->id }}.serie" />
                        <x-jet-input-error for="cart.{{ $item->id }}.serie_id" />
                    </div>
                @else
                    @if (count($item->almacens) > 0)
                        <div class="w-full flex flex-wrap gap-1">
                            @foreach ($item->almacens as $alm)
                                <x-input-radio class="py-2 !text-[10px]" for="almacen_{{ $item->id . $alm->id }}"
                                    :text="$alm->name .
                                        ' [' .
                                        decimalOrInteger($alm->pivot->cantidad) .
                                        ' ' .
                                        $item->unit->name .
                                        ']'">
                                    <input name="selectedalmacen_{{ $item->id }}"
                                        class="sr-only peer peer-disabled:opacity-25" type="radio"
                                        id="almacen_{{ $item->id . $alm->id }}" value="{{ $alm->id }}"
                                        @if ($almacen_id === $alm->id || count($item->almacens) == 1) checked @endif />
                                </x-input-radio>
                            @endforeach
                        </div>
                    @endif
                @endif

                @if (Module::isEnabled('Almacen'))
                    @if (count($item->garantiaproductos) > 0)
                        <div class="absolute right-1 flex flex-col gap-1 top-1">
                            @foreach ($item->garantiaproductos as $garantia)
                                <div x-data="{ isHovered: false }" @mouseover="isHovered = true"
                                    @mouseleave="isHovered = false"
                                    class="relative w-5 h-5 bg-green-500 text-white rounded-full p-0.5">
                                    <svg class="w-full h-full block" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
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

                @if ($pricesale > 0)
                    <x-slot name="footer">
                        @if (!$item->isRequiredserie())
                            <div class="w-full flex-1 flex justify-center xl:justify-start gap-0.5"
                                x-data="{ cantidad: 1 }">
                                <button type="button" wire:loading.attr="disabled" @click="parseFloat(cantidad--)"
                                    x-bind:disabled="cantidad == 1"
                                    class="font-medium hover:bg-neutral-400 hover:ring-2 hover:ring-neutral-300 text-xl w-9 h-9 bg-neutral-300 text-gray-500 p-2.5 pt-1.5 align-middle inline-flex items-center justify-center rounded-xl disabled:opacity-25 disabled:ring-0 disabled:hover:bg-neutral-300 transition ease-in-out duration-150">-</button>
                                <x-input x-model="cantidad"
                                    class="w-full rounded-xl flex-1 text-center text-colorlabel input-number-none numeric_onpaste_number"
                                    type="number" step="1" min="1" name="cantidad"
                                    onkeypress="return validarNumero(event, 4)"
                                    @blur="if (!cantidad || cantidad === '0') cantidad = '1'" />
                                <button type="button" wire:loading.attr="disabled" @click="parseFloat(cantidad++)"
                                    class="font-medium hover:bg-neutral-400 hover:ring-2 hover:ring-neutral-300 text-xl w-9 h-9 bg-neutral-300 text-gray-500 p-2.5 pt-1.5 align-middle inline-flex items-center justify-center rounded-xl disabled:opacity-25 transition ease-in-out duration-150">+</button>
                            </div>
                            {{-- <div class="w-full flex-1">
                                <x-label value="Cantidad :" />
                                <x-input class="block w-full disabled:bg-gray-200 input-number-none" name="cantidad"
                                    type="number" min="1" required value="1"
                                    onkeypress="return validarDecimal(event, 12)" />
                            </div> --}}
                        @endif
                        <x-button-add-car
                            class="{{ $item->isRequiredserie() ? 'flex-1 text-[10px] flex items-center justify-center gap-3' : '' }}"
                            type="submit" wire:loading.attr="disabled">
                            @if ($item->isRequiredserie())
                                AGREGAR PRODUCTO
                            @endif
                        </x-button-add-car>
                    </x-slot>
                @endif

                <x-slot name="messages">
                    <x-jet-input-error for="cart.{{ $item->id }}.price" />
                    <x-jet-input-error for="cart.{{ $item->id }}.almacen_id" />
                    <x-jet-input-error for="cart.{{ $item->id }}.cantidad" />
                </x-slot>

                {{-- <div wire:loading.flex
                    class="loading-overlay rounded shadow-md shadow-shadowminicard hidden">
                    <x-loading-next />
                </div> --}}
            </x-card-producto>
        </form>
    @endforeach

    <script>
        function formatOption(option) {
            var $option = $(`<p>${option.text}</p>
                <p class="select2-subtitle-option text-[10px] !text-next-500">${option.title}</p>`);
            return $option;
        }
    </script>
</div>
