<div
    class="w-full grid grid-cols-[repeat(auto-fill,minmax(180px,1fr))] sm:grid-cols-[repeat(auto-fill,minmax(200px,1fr))] gap-1 mt-1">
    @foreach ($productos as $item)
        @php
            $almacen = null;
            $promocion = null;
            $image = !empty($item->imagen) ? pathURLProductImage($item->imagen->urlmobile) : null;
            $priceold = $item->getPrecioVentaDefault($pricetype);
            $pricesale = $item->getPrecioVenta($pricetype);

            if ($item->descuento > 0 || $item->liquidacion) {
                $promocion = $item->promocions->where('type', '<>', \App\Enums\PromocionesEnum::COMBO->value)->first();
            }
        @endphp

        <form method="post" id="cardproduct{{ $item->id }}" class="w-full block" x-data="{ serie_id: null, seriealmacen_id: null, promocion_id: '{{ $promocion->id ?? null }}' }"
            @submit.prevent="enviarFormulario($event)" x-ref="formulario{{ $item->id }}" autocomplete="off" novalidate>
            <x-card-producto :name="$item->name" :image="$image" :category="$item->name_category" :marca="$item->name_marca" :promocion="$promocion"
                class="w-full h-full" id="card_{{ $item->id }}">

                <input type="hidden" name="producto_id" value="{{ $item->id }}" />
                {{-- @if (!empty($promocion)) --}}
                <input type="hidden" name="promocion_id" value="{{ !empty($promocion) ? $promocion->id : null }}" />
                <input type="hidden" name="moneda_id" x-model="moneda_id" />
                <input type="hidden" name="pricetype_id" x-model="pricetype_id">
                <input type="hidden" name="open_modal" value="true" />
                {{-- @endif --}}

                @if ($item->isNovedad())
                    <div class="w-full flex justify-end">
                        @if (!empty($empresa->textnovedad))
                            <span class="span-novedad">
                                {{ $empresa->textnovedad }}</span>
                        @endif
                        <x-icon-novedad />
                    </div>
                @endif

                <div class="w-full flex flex-col gap-1 py-2">
                    @if ($pricesale > 0)
                        @if ($promocion && $empresa->verOldprice())
                            <p class="block w-full line-through text-red-600 text-center">
                                {{ $moneda->simbolo }}
                                {{ decimalOrInteger($priceold, $pricetype->decimals ?? 2, ', ') }}
                            </p>

                            <p
                                class="block w-full text-center pt-1 xs:pt-0 text-xs leading-none text-colorsubtitleform">
                                Promoción válida hasta agotar stock.
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

                        <div class="w-full">
                            <x-label value="Precio  venta" />
                            <div class="w-full relative">
                                <x-input class="block pl-7 w-full text-end disabled:bg-gray-200 input-number-none"
                                    name="price" type="number" min="0" step="0.001"
                                    value="{{ $moneda->isDolar() ? convertMoneda($pricesale, 'USD', $empresa->tipocambio, 3) : $pricesale }}"
                                    onkeypress="return validarDecimal(event, 12)" />
                                <small
                                    class="text-xs left-2.5 absolute top-[50%] -translate-y-[50%] font-medium text-left text-colorsubtitleform">
                                    {{ $moneda->simbolo }}</small>
                            </div>
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
                                    @foreach ($item->series as $ser)
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
                                @php
                                    $unidades =
                                        ' [' . decimalOrInteger($alm->pivot->cantidad) . ' ' . $item->unit->name . ']';
                                @endphp
                                <x-input-radio class="py-2 !text-[10px]" for="almacen_{{ $item->id . $alm->id }}"
                                    :text="$alm->name . $unidades">
                                    <input name="selectedalmacen_{{ $item->id }}"
                                        wire:key="{{ $item->id . $alm->id }}"
                                        class="sr-only peer peer-disabled:opacity-25" type="radio"
                                        id="almacen_{{ $item->id . $alm->id }}" value="{{ $alm->id }}"
                                        @if ($almacen_id === $alm->id || count($item->almacens) == 1) checked @endif />
                                </x-input-radio>
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
                                    x-bind:disabled="cantidad == 1" class="btn-increment-cart">-</button>
                                <x-input x-model="cantidad"
                                    class="w-full rounded-xl flex-1 text-center text-colorlabel input-number-none numeric_onpaste_number"
                                    type="number" step="1" min="1" name="cantidad"
                                    onkeypress="return validarNumero(event, 4)"
                                    @blur="if (!cantidad || cantidad === '0') cantidad = '1'" />
                                <button type="button" wire:loading.attr="disabled" @click="parseFloat(cantidad++)"
                                    class="btn-increment-cart">+</button>
                            </div>
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
                    <x-jet-input-error for="cart.{{ $item->id }}.promocion_id" />
                </x-slot>

                {{-- <div wire:loading.flex
                    class="loading-overlay rounded shadow-md shadow-shadowminicard hidden">
                    <x-loading-next />
                </div> --}}
            </x-card-producto>
        </form>
    @endforeach
</div>
