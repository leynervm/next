<div class="w-full flex flex-col lg:flex-row gap-2 lg:h-[calc(100vh-4rem)]" x-data="loader">

    <x-loading-web-next wire:key="loadingcreateventa" wire:loading />

    <div class="w-full flex flex-col gap-5 lg:flex-shrink-0 lg:w-80 lg:overflow-y-auto soft-scrollbar h-full">
        <x-form-card titulo="GENERAR NUEVA VENTA">
            <form wire:submit.prevent="save" class="w-full flex flex-col gap-2" autocomplete="off">
                <div class="w-full flex flex-col gap-1">

                    <div class="w-full mb-1">
                        <x-button-secondary wire:click="limpiarventa" class="block w-full justify-center" type="button"
                            wire:loading.attr="disabled">{{ __('New sale') }}</x-button-secondary>
                    </div>

                    @include('modules.ventas.forms.comprobante')

                    @if (Module::isEnabled('Facturacion'))
                        @can('admin.ventas.create.guias')
                            @include('modules.ventas.forms.guia-remision')
                        @endcan
                    @endif

                    @can('admin.ventas.create.guias')
                        @if (count($comprobantesguia) > 0)
                            <div class="block w-full" x-show="!sincronizegre">
                                <x-label-check for="incluyeguia" x-show="openguia">
                                    <x-input x-model="incluyeguia" name="incluyeguia" type="checkbox"
                                        id="incluyeguia" />GENERAR GUÍA REMISIÓN
                                </x-label-check>
                            </div>
                        @endif
                    @endcan

                    <div class="w-full flex flex-col gap-0.5">
                        <x-jet-input-error for="typepayment_id" />
                        <x-jet-input-error for="items" />
                        <x-jet-input-error for="typepay" />
                        <x-jet-input-error for="concept.id" />
                        <x-jet-input-error for="parcialpayments" />
                        {{-- <x-jet-input-error for="client_id" /> --}}
                    </div>

                    <div class="w-full">
                        <x-button class="block w-full" type="submit" wire:loading.attr="disabled">
                            {{ __('Save sale') }}</x-button>
                    </div>

                    {{-- @if ($errors->any())
                        <div class="w-full flex flex-col gap-1">
                            @foreach ($errors->keys() as $key)
                                <x-jet-input-error :for="$key" />
                            @endforeach
                        </div>
                    @endif --}}
                </div>
            </form>
        </x-form-card>

        <x-form-card titulo="RESUMEN DE VENTA" class="text-colorlabel">
            <div class="w-full">
                <table class="w-full table text-[10px]">
                    <tr>
                        <td>EXONERADO :</td>
                        <td class="text-end">
                            <span class="font-semibold text-sm">{{ number_format($exonerado, 2, '.', ', ') }}</span>
                            <small>{{ $moneda->currency }}</small>
                        </td>
                    </tr>
                    <tr>
                        <td>GRAVADO :</td>
                        <td class="text-end">
                            <span class="font-semibold text-sm"> {{ number_format($gravado, 2, '.', ', ') }}</span>
                            <small>{{ $moneda->currency }}</small>
                        </td>
                    </tr>
                    <tr>
                        <td>IGV :</td>
                        <td class="text-end">
                            <span class="font-semibold text-sm"> {{ number_format($igv, 2, '.', ', ') }}</span>
                            <small>{{ $moneda->currency }}</small>
                        </td>
                    </tr>
                    <tr>
                        <td>GRATUITO :</td>
                        <td class="text-end">
                            <span class="font-semibold text-sm">
                                {{ number_format($gratuito + $igvgratuito, 2, '.', ', ') }}</span>
                            <small>{{ $moneda->currency }}</small>
                        </td>
                    </tr>
                    {{-- <tr>
                        <td>DESCUENTOS :</td>
                        <td class="text-end">
                            <span class="font-semibold text-sm">
                                {{ number_format($descuentos, 2, '.', ', ') }}</span>
                            <small>{{ $moneda->currency }}</small>
                        </td>
                    </tr> --}}
                    <tr>
                        <td>SUBTOTAL :</td>
                        <td class="text-end">
                            <span class="font-semibold text-sm">
                                {{ number_format($subtotal, 2, '.', ', ') }}</span>
                            {{-- <span class="font-semibold text-sm">
                                {{ number_format($total, 2, '.', ', ') }}</span> --}}
                            <small>{{ $moneda->currency }}</small>
                        </td>
                    </tr>

                    <tr>
                        <td>TOTAL PAGAR :</td>
                        <td class="text-end">
                            <span class="font-semibold text-xl">
                                {{ number_format($total, 2, '.', ', ') }}</span>
                            <small>{{ $moneda->currency }}</small>

                            @if ($increment > 0)
                                <br>
                                INC. {{ decimalOrInteger($increment) }}%
                                ({{ number_format($amountincrement, 2, '.', ', ') }})
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>PENDIENTE :</td>
                        <td class="text-end">
                            <span class="font-semibold text-xl text-red-600">
                                {{ number_format($total - $paymentactual, 2, '.', ', ') }}</span>
                            <small>{{ $moneda->currency }}</small>
                        </td>
                    </tr>
                </table>
            </div>
        </x-form-card>

        <x-form-card titulo="PAGO PARCIAL" class="text-colorlabel" style="display: none" x-show="typepay > 0">
            <div class="w-full flex flex-col">
                <div class="w-full flex flex-wrap gap-2">
                    @foreach ($parcialpayments as $index => $item)
                        <div class="block text-center size-28 rounded-xl border p-2.5 border-borderminicard">
                            <h1 class="text-lg text-center leading-5 font-semibold text-colorlabel">
                                {{ number_format($item['amount'], 2, '.', ', ') }}</h1>
                            <span class="text-[9px] leading-none text-center text-colorsubtitleform mt-2">
                                {{ $item['method'] }}</span>

                            @if (!empty($item['detalle']))
                                <p
                                    class="w-full block uppercase text-[10px] leading-none text-center text-colorsubtitleform mt-0.5">
                                    <small class="font-semibold">DETALLE</small>
                                    {{ $item['detalle'] }}
                                </p>
                            @endif
                            <div class="w-full text-center">
                                <x-button-delete wire:click="removepay({{ $index }})" />
                            </div>
                        </div>
                    @endforeach
                </div>
                <x-jet-input-error for="parcialpayments" />
            </div>
        </x-form-card>

        <div class="w-full" x-data="{ showcart: true }">
            <div class="text-end px-3">
                <button class="text-amber-500 relative inline-block w-6 h-6 cursor-pointer" @click="showcart=!showcart">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" class="w-full h-full block">
                        <path d="M8 16L16.7201 15.2733C19.4486 15.046 20.0611 14.45 20.3635 11.7289L21 6" />
                        <path d="M6 6H22" />
                        <circle cx="6" cy="20" r="2" />
                        <circle cx="17" cy="20" r="2" />
                        <path d="M8 20L15 20" />
                        <path
                            d="M2 2H2.966C3.91068 2 4.73414 2.62459 4.96326 3.51493L7.93852 15.0765C8.08887 15.6608 7.9602 16.2797 7.58824 16.7616L6.63213 18" />
                    </svg>
                    <small
                        class="bg-amber-500 text-white font-semibold absolute -top-3 -right-1 flex items-center justify-center w-4 h-4 p-0.5 leading-3 rounded-full text-[8px]">
                        {{ count($carshoops) }}</small>
                </button>
            </div>
            @if (count($carshoops) > 0)
                <div class="w-full" x-show="showcart" x-transition>
                    <div class="flex gap-2 flex-wrap justify-start">
                        @foreach ($carshoops as $item)
                            <x-simple-card
                                class="w-full flex flex-col border border-borderminicard justify-between lg:max-w-sm xl:w-full group p-1 text-xs relative overflow-hidden">

                                @if ($item->promocion)
                                    <span
                                        class="bg-red-600 mr-auto inline-block rounded-md text-white text-[9px] font-medium p-1 leading-3">
                                        PROMOCIÓN</span>
                                @endif

                                <h1 class="text-colorlabel w-full text-[10px] leading-none text-left z-[1] pt-1">
                                    <span class="font-semibold">
                                        {{ decimalOrInteger($item->cantidad) }}
                                        {{ $item->producto->unit->name }}</span>
                                    {{ $item->producto->name }}

                                    @if (count($item->carshoopseries) == 1)
                                        <br>SN:
                                        @if (!empty($item->carshoopseries->first()->serie))
                                            {{ $item->carshoopseries->first()->serie->serie }}
                                        @else
                                            <small class="text-colorerror">NO DISPONIBLE</small>
                                        @endif
                                    @endif
                                </h1>

                                @if (count($item->carshoopitems) > 0)
                                    <div class="w-full flex items-center flex-wrap gap-1">
                                        <div class="w-20 flex flex-col justify-center items-center opacity-50">
                                            <div class="w-full block rounded-lg relative">
                                                @if ($item->producto->image)
                                                    <img src="{{ pathURLProductImage($item->producto->image) }}"
                                                        alt="{{ pathURLProductImage($item->producto->image) }}"
                                                        class="block w-full h-auto max-h-16 object-scale-down overflow-hidden rounded-lg">
                                                @else
                                                    <x-icon-image-unknown
                                                        class="w-full h-full max-h-16 text-colorsubtitleform" />
                                                @endif
                                            </div>
                                        </div>

                                        @foreach ($item->carshoopitems as $carshoopitem)
                                            <span class="block w-5 h-5 text-colorsubtitleform">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    color="currentColor" fill="none" stroke="currentColor"
                                                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                                                    class="w-full h-full block">
                                                    <path d="M12 4V20M20 12H4" />
                                                </svg>
                                            </span>

                                            <div class="w-20 flex flex-col justify-center items-center">
                                                <div class="w-full block rounded-lg relative">
                                                    @if ($carshoopitem->producto->image)
                                                        <img src="{{ pathURLProductImage($carshoopitem->producto->image) }}"
                                                            alt="{{ pathURLProductImage($carshoopitem->producto->image) }}"
                                                            title="{{ $carshoopitem->producto->name }}"
                                                            class="block w-full h-auto max-h-16 object-scale-down overflow-hidden rounded-lg">
                                                    @else
                                                        <x-icon-image-unknown
                                                            class="w-full h-full max-h-16 text-colorsubtitleform" />
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <h1 class="text-colorerror w-full text-[10px] leading-none text-center">
                                        {{ strtoupper($item->promocion->titulo) }}</h1>
                                @endif

                                <div class="w-full flex gap-1 items-end">
                                    <h1 class="text-colorlabel whitespace-nowrap text-xs text-right">
                                        <small class="text-[10px] font-medium">{{ $item->moneda->simbolo }}</small>
                                        {{ number_format($item->price + $item->igv, 2, '.', ', ') }}
                                    </h1>

                                    <h1
                                        class="flex-1 w-full text-colorlabel whitespace-nowrap leading-3 text-lg font-semibold text-right">
                                        <small class="text-[9px] font-medium">IMPORTE <br>
                                            {{ $item->moneda->simbolo }}</small>
                                        {{ number_format($item->total, 2, '.', ', ') }}
                                    </h1>
                                </div>

                                <div class="w-full flex flex-wrap gap-1 mt-2">
                                    <x-span-text :text="$item->almacen->name" class="leading-3 !tracking-normal" />

                                    @if ($item->isNoAlterStock())
                                        <x-span-text text="NO ALTERA STOCK" class="leading-3 !tracking-normal" />
                                    @elseif ($item->isReservedStock())
                                        <x-span-text text="STOCK RESERVADO" class="leading-3 !tracking-normal"
                                            type="orange" />
                                    @elseif ($item->isIncrementStock())
                                        <x-span-text text="INCREMENTA STOCK" class="leading-3 !tracking-normal"
                                            type="green" />
                                    @elseif($item->isDiscountStock())
                                        <x-span-text text="DISMINUYE STOCK" class="leading-3 !tracking-normal"
                                            type="red" />
                                    @endif
                                </div>

                                @if (count($item->carshoopseries) > 1)
                                    <div x-data="{ showForm: false }" class="w-full mt-1" key="formseries">
                                        <x-button @click="showForm = !showForm" class="whitespace-nowrap">
                                            {{ __('VER SERIES') }}</x-button>
                                        <div x-show="showForm" x-cloack style="display: none;" x-transition
                                            class="block w-full rounded mt-1"
                                            wire:key="formseries_{{ $item->id }}">
                                            <div class="w-full flex flex-wrap gap-1">
                                                @foreach ($item->carshoopseries as $itemserie)
                                                    <span
                                                        class="inline-flex items-center gap-1 text-[10px] bg-fondospancardproduct text-textspancardproduct p-1 rounded-lg">
                                                        @if ($itemserie->serie)
                                                            {{ $itemserie->serie->serie }}
                                                            <x-button-delete wire:loading.attr="disabled"
                                                                key="deleteitemserie_{{ $itemserie->id }}"
                                                                x-on:click="confirmDeleteSerie({{ $itemserie->id }}, '{{ $itemserie->serie->serie }}')" />
                                                        @else
                                                            <small class="text-colorerror">NO DISPONIBLE</small>
                                                            <x-button-delete wire:loading.attr="disabled"
                                                                key="deleteitemserie_{{ $itemserie->id }}"
                                                                x-on:click="confirmDeleteSerie({{ $itemserie->id }})" />
                                                        @endif
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div
                                    class="w-full flex items-end gap-2 {{ is_null($item->promocion_id) ? 'justify-between' : 'justify-end' }} mt-2">
                                    @if (is_null($item->promocion_id))
                                        @can('admin.ventas.create.gratuito')
                                            <div>
                                                <x-label-check textSize="[9px]" for="gratuito_{{ $item->id }}">
                                                    <x-input wire:change="updategratis({{ $item->id }})"
                                                        value="1" type="checkbox" id="gratuito_{{ $item->id }}"
                                                        :checked="$item->isGratuito()" />
                                                    GRATUITO</x-label-check>
                                            </div>
                                        @endcan
                                    @endif
                                    <x-button-delete wire:loading.attr="disabled" :key="'deletecarshoop_{{ $item->id }}'"
                                        @click="confirmDeleteCarshoop('{{ $item->id }}')" />
                                </div>
                            </x-simple-card>
                        @endforeach
                    </div>

                    <div class="w-full flex mt-2">
                        <x-button-secondary wire:loading.attr="disabled" class="inline-block"
                            @click="confirmDeleteAllCarshoop">ELIMINAR TODO</x-button-secondary>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="w-full flex-1 lg:overflow-y-auto soft-scrollbar h-full lg:pr-2">
        <div class="w-full relative">
            @include('modules.ventas.forms.filters')
            @include('modules.ventas.forms.productos')

            @if ($productos->hasPages())
                <div
                    class="w-full flex justify-center items-center sm:justify-end p-1 sticky -bottom-1 right-0 bg-body">
                    {{ $productos->onEachSide(0)->links('livewire::pagination-default') }}
                </div>
            @endif
        </div>
    </div>

    <x-jet-dialog-modal wire:model="opencombos" maxWidth="5xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Add product to cart') }}
        </x-slot>

        <x-slot name="content">
            @if (!empty($producto['id']))
                @php
                    $pricesale = $producto->getPrecioVenta($pricetype);
                    $priceold = $producto->getPrecioVentaDefault($pricetype);
                    $image = count($producto->images) > 0 ? pathURLProductImage($producto->images->first()->url) : null;
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
                                    @elseif($empresa->isTitleLiquidacion())
                                        LIQUIDACIÓN
                                    @else
                                        @if ($producto->descuento > 0)
                                            - {{ decimalOrInteger($producto->descuento) }}% DSCT
                                        @else
                                            LIQUIDACIÓN
                                        @endif
                                    @endif
                                </p>
                            </div>
                        @endif
                    </div>

                    <div class="w-full">
                        <div class="w-full pb-2 md:pb-5">
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

                        <div class="w-full relative" x-data="{ qty: 1, serieproductoprm_id: null, seriealmacenprm_id: null }">
                            <form class="w-full flex flex-col gap-2 mt-1 sm:mt-5"
                                @submit.prevent="enviarFormulario($event)" wire:ignore>

                                @if ($pricesale > 0)
                                    <div class="w-full flex-1">
                                        <div class="w-full">
                                            <x-label value="Precio  venta" />
                                            <div class="w-full relative max-w-xs">
                                                <x-input
                                                    class="block pl-7 w-full text-end disabled:bg-gray-200 input-number-none"
                                                    name="price" type="number" min="0" step="0.001"
                                                    value="{{ $moneda->isDolar() ? convertMoneda($pricesale, 'USD', $empresa->tipocambio, 3) : $pricesale }}"
                                                    onkeypress="return validarDecimal(event, 12)" />
                                                <small
                                                    class="text-xs left-2.5 absolute top-[50%] -translate-y-[50%] font-medium text-left text-colorsubtitleform">
                                                    {{ $moneda->simbolo }}</small>
                                            </div>
                                        </div>

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
                                @else
                                    <p class="w-full flex-1 text-colorerror text-[10px] font-semibold text-center">
                                        PRECIO DE VENTA NO ENCONTRADO</p>
                                @endif


                                <input type="hidden" name="open_modal" value="false" />
                                <input type="hidden" name="producto_id" value="{{ $producto->id }}" />
                                <input type="hidden" name="moneda_id" value="{{ $moneda->id ?? null }}" />
                                <input type="hidden" name="pricetype_id" value="{{ $pricetype->id ?? null }}" />
                                <input type="hidden" name="promocion_id"
                                    value="{{ !empty($promocion_producto) ? $promocion_producto->id : null }}" />

                                @if (count($producto->almacens) > 0 && !$producto->isRequiredserie())
                                    <div class="w-full flex flex-wrap gap-1">
                                        @foreach ($producto->almacens as $alm)
                                            <x-input-radio class="py-2 !text-[10px]"
                                                for="almacen_{{ $alm->id }}" :text="$alm->name .
                                                    ' [' .
                                                    decimalOrInteger($alm->pivot->cantidad) .
                                                    ' ' .
                                                    $producto->unit->name .
                                                    ']'">
                                                <input name="selectedalmacen_{{ $producto->id }}"
                                                    class="sr-only peer peer-disabled:opacity-25" type="radio"
                                                    id="almacen_{{ $alm->id }}" value="{{ $alm->id }}"
                                                    @if ($almacen_id === $alm->id || count($producto->almacens) == 1) checked @endif />
                                            </x-input-radio>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="w-full flex items-end gap-1 justify-end mt-1 sm:mt-5">
                                    @if ($producto->isRequiredserie())
                                        <div class="w-full flex-1">
                                            <x-label value="Seleccionar serie :" />
                                            <div id="parentserieproductoprm_{{ $producto->id }}" class="relative"
                                                x-init="SerieProductoPrm">
                                                <x-select name="serie_id" class="block w-full relative"
                                                    x-ref="serieproductoprm"
                                                    id="serieproductoprm_{{ $producto->id }}" data-placeholder="null"
                                                    data-minimum-results-for-search="0">
                                                    <x-slot name="options">
                                                        @foreach ($producto->seriesdisponibles as $ser)
                                                            <option data-almacen_id="{{ $ser->almacen_id }}"
                                                                value="{{ $ser->id }}"
                                                                title="{{ $ser->almacen->name }}">
                                                                {{ $ser->serie }}</option>
                                                        @endforeach
                                                    </x-slot>
                                                </x-select>
                                                <x-icon-select />
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex-1 w-full">
                                            <button type="button" x-on:click="qty = qty-1"
                                                x-bind:disabled="qty == 1" class="btn-increment-cart">
                                                -</button>
                                            <x-input
                                                class="inline-block max-w-16 text-center disabled:bg-gray-200 input-number-none"
                                                name="cantidad" type="number" min="0" step="0.001"
                                                x-model="qty" value="1"
                                                onkeypress="return validarDecimal(event, 12)" />
                                            <button type="button" wire:loading.attr="disabled"
                                                x-on:click="qty = qty+1" class="btn-increment-cart">
                                                +</button>
                                        </div>
                                    @endif

                                    <x-button-add-car type="submit"
                                        class="px-2.5 !flex gap-1 items-center text-xs flex-shrink-0">
                                        AGREGAR</x-button-add-car>
                                </div>
                            </form>

                            <x-jet-input-error for="cart.{{ $producto->id }}.serie" />
                            <x-jet-input-error for="cart.{{ $producto->id }}.serie_id" />
                            <x-jet-input-error for="cart.{{ $producto->id }}.price" />
                            <x-jet-input-error for="cart.{{ $producto->id }}.almacen_id" />
                            <x-jet-input-error for="cart.{{ $producto->id }}.cantidad" />
                            <x-jet-input-error for="cart.{{ $producto->id }}.promocion_id" />

                            @if ($promocion_producto)
                                <p
                                    class="w-full text-center pt-1 xs:pt-0 xs:text-justify text-xs leading-none text-colorsubtitleform">
                                    Promoción válida hasta agotar stock.

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
                                    <div>
                                        <form wire:ignore @submit.prevent="enviarFormulario($event)"
                                            class="w-full flex flex-col justify-between border border-borderminicard shadow-md shadow-shadowminicard p-1 md:p-2 rounded-lg md:rounded-2xl">
                                            <div class="w-full">
                                                <h1
                                                    class="text-colorlabel font-medium text-xs md:text-[1rem] !leading-none pb-2">
                                                    {{ $item->titulo }}</h1>

                                                <div class="w-full flex items-center flex-wrap gap-1">
                                                    <div
                                                        class="w-20 flex flex-col justify-center items-center opacity-50">
                                                        <div class="w-full block rounded-lg relative">
                                                            @if ($image)
                                                                <img src="{{ $image }}"
                                                                    alt="{{ $image }}"
                                                                    class="block w-full h-auto max-h-16 object-scale-down overflow-hidden rounded-lg">
                                                            @else
                                                                <x-icon-image-unknown
                                                                    class="w-full h-full max-h-16 text-colorsubtitleform" />
                                                            @endif
                                                        </div>
                                                    </div>

                                                    @foreach ($combo->products as $itemcombo)
                                                        @php
                                                            $opacidad =
                                                                $itemcombo->stock > 0 ? '' : 'opacity-50 saturate-0';
                                                        @endphp

                                                        <span class="block w-5 h-5 text-colorsubtitleform">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                viewBox="0 0 24 24" color="currentColor"
                                                                fill="none" stroke="currentColor"
                                                                stroke-width="2.5" stroke-linecap="round"
                                                                stroke-linejoin="round" class="w-full h-full block">
                                                                <path d="M12 4V20M20 12H4" />
                                                            </svg>
                                                        </span>

                                                        <div class="w-20 flex flex-col justify-center items-center">
                                                            <div class="w-full block rounded-lg relative"
                                                                href="{{ route('productos.show', $itemcombo->producto_slug) }}">
                                                                @if ($itemcombo->image)
                                                                    <img src="{{ $itemcombo->image }}"
                                                                        alt="{{ $itemcombo->image }}"
                                                                        title="{{ $itemcombo->name }}"
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
                                                            </div>
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

                                                <x-button-add-car type="submit"
                                                    wire:key="promocion_id_{{ $item->id }}"
                                                    wire:loading.attr="disabled"
                                                    class="px-2.5 !flex gap-1 items-center text-xs flex-shrink-0">
                                                    AGREGAR</x-button-add-car>
                                            </div>

                                            <input type="hidden" name="promocion_id" value="{{ $item->id }}">
                                            <input type="hidden" name="moneda_id" x-model="moneda_id" />
                                            <input type="hidden" name="pricetype_id" x-model="pricetype_id">

                                            @if (count($producto->almacens) > 0 && !$producto->isRequiredserie())
                                                <div class="w-full flex flex-wrap gap-1 mt-2">
                                                    @foreach ($producto->almacens as $alm)
                                                        <x-input-radio class="py-2 !text-[10px]"
                                                            for="almacenprm_{{ $item->id . $alm->id }}"
                                                            :text="$alm->name .
                                                                ' [' .
                                                                decimalOrInteger($alm->pivot->cantidad) .
                                                                ' ' .
                                                                $producto->unit->name .
                                                                ']'">
                                                            <input name="almacen_id"
                                                                class="sr-only peer peer-disabled:opacity-25"
                                                                type="radio"
                                                                id="almacenprm_{{ $item->id . $alm->id }}"
                                                                value="{{ $alm->id }}"
                                                                @if ($almacen_id === $alm->id || count($producto->almacens) == 1) checked @endif />
                                                        </x-input-radio>
                                                    @endforeach
                                                </div>
                                                <x-jet-input-error for="cart.{{ $item->id }}.almacen_id" />
                                            @endif
                                        </form>
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
        async function fetchAsyncDatos(ruta, data = {}) {
            try {
                const response = await fetch(ruta, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                });

                if (!response.ok) {
                    console.log('Error en la respuesta del servidor');
                    throw new Error('Error en la respuesta del servidor');
                }

                const datos = await response.json();
                return datos;
            } catch (error) {
                console.error('Error al realizar la petición:', error);
                return null;
            }
        }

        document.addEventListener('alpine:init', () => {
            Alpine.data('loader', () => ({

                vehiculosml: false,
                loadingprivate: false,
                loadingpublic: false,
                loadingdestinatario: false,
                codemotivotraslado: '',
                codemodalidad: '',
                paymentcuotas: false,
                formapago: '',
                code: '',
                codecomprobante: '',
                sendsunat: '',
                openguia: true,
                document: @entangle('document').defer,
                name: @entangle('name').defer,
                direccion: @entangle('direccion').defer,
                moneda_id: @entangle('moneda_id'),
                seriecomprobante_id: @entangle('seriecomprobante_id').defer,
                typepayment_id: @entangle('typepayment_id'),
                methodpayment_id: @entangle('methodpayment_id').defer,
                serieguia_id: @entangle('serieguia_id').defer,
                motivotraslado_id: @entangle('motivotraslado_id').defer,
                modalidadtransporte_id: @entangle('modalidadtransporte_id').defer,
                ubigeoorigen_id: @entangle('ubigeoorigen_id').defer,
                ubigeodestino_id: @entangle('ubigeodestino_id').defer,
                typepay: @entangle('typepay').defer,
                parcialpayments: @entangle('parcialpayments').defer,
                istransferencia: @entangle('istransferencia').defer,
                ubigeos: [],
                typepayments: [],
                methodpayments: [],
                seriecomprobantes: [],
                searchmarca: @entangle('searchmarca'),
                searchcategory: @entangle('searchcategory'),
                searchsubcategory: @entangle('searchsubcategory'),
                pricetype_id: @entangle('pricetype_id'),

                searchserie: @entangle('searchserie').defer,
                incluyeguia: @entangle('incluyeguia').defer,
                sincronizegre: @entangle('sincronizegre').defer,
                cotizacion_id: @entangle('cotizacion_id').defer,

                ructransport: @entangle('ructransport').defer,
                nametransport: @entangle('nametransport').defer,
                documentdestinatario: @entangle('documentdestinatario').defer,
                namedestinatario: @entangle('namedestinatario').defer,
                documentdriver: @entangle('documentdriver').defer,
                namedriver: @entangle('namedriver').defer,

                init() {

                    $(this.$refs.selectcomprobante).select2();
                    $(this.$refs.selectmethodpayment).select2();
                    $(this.$refs.selectpayment).select2();
                    this.obtenerDatos();

                    this.$watch("moneda_id", (value) => {
                        const message = this.updatemonedacart(value);
                    });
                    this.$watch("methodpayment_id", (value) => {
                        this.selectMP.val(value).trigger("change");
                    });
                    this.$watch("seriecomprobante_id", (value, oldvalue) => {
                        let digits = this.document.length ?? 0;
                        if (this.codecomprobante == '01' && digits !== 11) {
                            this.seriecomprobante_id = '';
                            this.selectTC.val(this.seriecomprobante_id).trigger("change");
                            this.$dispatch('validation', {
                                title: 'INGRESE N° RUC VÁLIDO PARA EL COMPROBANTE SELECCIONADO !',
                                text: null
                            });
                            return false;
                        }
                    });

                    Livewire.hook('message.processed', () => {
                        this.selectTC.select2({
                            data: this.seriecomprobantes
                        }).val(this.seriecomprobante_id).trigger('change');

                        this.selectMP.select2({
                            data: this.methodpayments
                        }).val(this.methodpayment_id).trigger('change');

                        const componentloading = document.querySelector('[x-ref="loadingnext"]');
                        $(componentloading).fadeOut();
                    });
                },
                enviarFormulario(event) {
                    const componentloading = document.querySelector('[x-ref="loadingnext"]');
                    $(componentloading).fadeIn();
                    const formData = new FormData(event.target);
                    route = '{{ route('admin.ventas.addcarshoop') }}';

                    fetch(route, {
                            method: 'POST',
                            headers: {
                                //'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: formData,
                        }).then(response => response.json())
                        .then(responseData => {
                            if (responseData.success) {
                                // console.log(responseData);

                                if (responseData.open_modal) {
                                    this.$wire.getcombos(responseData.producto_id).then(
                                        result => {});
                                } else {
                                    if (responseData.mensaje) {
                                        toastMixin.fire({
                                            title: responseData.mensaje,
                                            icon: 'success',
                                            timer: 3000,
                                        });
                                    }
                                    this.$wire.setTotal().then(result => {});
                                }
                                // onSuccess(responseData);
                            } else {
                                // console.log(responseData);
                                if (responseData.errors) {
                                    this.$wire.seterrors(responseData.errors, responseData
                                        .producto_id).then(result => {});
                                }

                                if (responseData.error) {
                                    toastMixin.fire({
                                        title: responseData.error,
                                        icon: 'error',
                                        timer: null,
                                    });
                                }
                            }
                        }).catch(error => {
                            // console.log(error);
                            toastMixin.fire({
                                title: error.message,
                                icon: 'error',
                                timer: null,
                            });
                        }).finally(() => {});
                },
                searchBySerie() {
                    const componentloading = document.querySelector('[x-ref="loadingnext"]');
                    $(componentloading).fadeIn();
                    const formData = new FormData();
                    formData.append('searchserie', this.searchserie);
                    formData.append('moneda_id', this.moneda_id);
                    formData.append('pricetype_id', this.pricetype_id);
                    route = '{{ route('admin.ventas.getproductobyserie') }}';
                    // console.log(formData);

                    fetch(route, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: formData,
                        }).then(response => response.json())
                        .then(responseData => {
                            if (responseData.success) {
                                this.searchserie = '';
                                this.$wire.render().then(result => {});
                                this.$wire.setTotal().then(result => {});
                                toastMixin.fire({
                                    title: responseData.mensaje,
                                    icon: 'success',
                                    timer: 3000,
                                });
                            } else {
                                if (responseData.errors) {
                                    this.$wire.seterrors(responseData.errors, responseData
                                        .producto_id).then(result => {});
                                }

                                if (responseData.error) {
                                    toastMixin.fire({
                                        title: responseData.error,
                                        icon: 'error',
                                        timer: null,
                                    });
                                }
                            }
                        }).catch(error => {
                            toastMixin.fire({
                                title: error.message,
                                icon: 'error',
                                timer: null,
                            });
                        }).finally(() => {});
                },
                toggle() {
                    this.vehiculosml = !this.vehiculosml;
                    if (this.vehiculosml) {
                        this.loadingpublic = false;
                        this.loadingprivate = false;
                    } else {
                        this.selectedModalidadtransporte(this.codemodalidad);
                    }
                },
                toggleguia() {
                    this.incluyeguia = !this.incluyeguia;
                },
                getCodeMotivo(target) {
                    this.codemotivotraslado = target.options[target.selectedIndex].getAttribute(
                        'data-code');
                    this.selectedMotivotraslado(this.codemotivotraslado);
                },
                getCodeModalidad(target) {
                    this.codemodalidad = target.options[target.selectedIndex].getAttribute(
                        'data-code');
                    if (!this.vehiculosml) {
                        this.selectedModalidadtransporte(this.codemodalidad);
                    }
                },
                selectedModalidadtransporte(value) {
                    // console.log(value);
                    switch (value) {
                        case '01':
                            this.loadingpublic = true;
                            this.loadingprivate = false;
                            break;
                        case '02':
                            this.loadingprivate = true;
                            this.loadingpublic = false;
                            break;
                        default:
                            this.loadingprivate = false;
                            this.loadingpublic = false;
                    }
                },
                selectedMotivotraslado(value) {
                    switch (value) {
                        case '01':
                            this.loadingdestinatario = false;
                            break;
                        case '03':
                            this.loadingdestinatario = true;
                            break;
                        default:
                            this.loadingdestinatario = false;
                            this.loadingprivate = false;
                            this.loadingpublic = false;
                    }
                },
                savepay(event) {
                    this.$wire.savepay();
                    event.preventDefault();
                },
                confirmDeleteCarshoop(carshoop_id) {
                    swal.fire({
                        title: 'ELIMINAR ITEM DEL CARRITO ?',
                        text: "Se eliminará un registro del carrito de ventas y se actualizará el stock del producto.",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#0FB9B9',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Confirmar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // const message = this.deleteitem(carshoop_id);
                            this.$wire.delete(carshoop_id);
                        }
                    })
                },
                confirmDeleteAllCarshoop() {
                    swal.fire({
                        title: 'ELIMINAR TODOS LOS ITEMS DEL CARRITO DE COMPRAS ?',
                        text: "Se eliminarán todos los productos del carrito de ventas y se actualizará su stock correspondientes.",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#0FB9B9',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Confirmar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // const message = this.deleteAll();
                            this.$wire.deleteall();
                        }
                    })
                },
                confirmDeleteSerie(itemserie_id, serie = null) {
                    let mensaje = serie == null ? `SERIE NO DISPONIBLE, ELIMINAR DEL CARRITO ?` :
                        `ELIMINAR SERIE ${serie} DEL CARRITO ?`;
                    swal.fire({
                        title: mensaje,
                        text: null,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#0FB9B9',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Confirmar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.$wire.deleteserie(itemserie_id);
                        }
                    })
                },
                async updatemonedacart(moneda_id) {
                    const route =
                        "{{ route('admin.carshoop.updatemoneda', ['moneda_id' => ':moneda_id']) }}"
                        .replace(':moneda_id', moneda_id);
                    const token = document.querySelector('meta[name="csrf-token"]')
                        .getAttribute('content');

                    const response = await axios.post(route, {
                        headers: {
                            'X-CSRF-TOKEN': token
                        },
                        moneda_id: moneda_id
                    }).catch(function(error) {
                        console.log(error);
                    });

                    if (response.status === 200) {
                        this.$wire.setTotal();
                    } else {
                        throw new Error('Error al actualizar moneda');
                    }
                },
                async obtenerDatos() {
                    const ROUTE = {
                        typepayments: "{{ route('admin.ventas.typepayments.list') }}",
                        methodpayments: "{{ route('admin.ventas.methodpayments.list') }}",
                        seriecomprobantes: "{{ route('admin.ventas.seriecomprobantes.list') }}",
                        ubigeos: "{{ route('admin.ventas.ubigeos.list') }}"
                    }

                    // const [typepayments, methodpayments] = await Promise.all([
                    //     fetchAsyncDatos(ROUTE.typepayments),
                    //     fetchAsyncDatos(ROUTE.methodpayments)
                    // ]);

                    this.typepayments = await fetchAsyncDatos(ROUTE.typepayments);
                    this.selectPayment()
                    this.methodpayments = await fetchAsyncDatos(ROUTE.methodpayments);
                    this.selectMethodpayment()
                    this.seriecomprobantes = await fetchAsyncDatos(ROUTE.seriecomprobantes);
                    this.selectComprobante()
                    this.ubigeos = await fetchAsyncDatos(ROUTE.ubigeos);
                    this.selectUbigeoEmision()
                    this.selectUbigeoDestino()
                },
                selectUbigeoEmision() {
                    this.selectUE = $(this.$refs.ubigeoemision).select2({
                        data: this.ubigeos
                    });
                    this.selectUE.val(this.ubigeoorigen_id).trigger("change");
                    this.selectUE.on("select2:select", (event) => {
                        this.ubigeoorigen_id = event.target.value;
                    }).on('select2:open', function(e) {
                        const evt = "scroll.select2";
                        $(e.target).parents().off(evt);
                        $(window).off(evt);
                    });
                    this.$watch("ubigeoorigen_id", (value) => {
                        this.selectUE.val(value).trigger("change");
                    });
                    Livewire.hook('message.processed', () => {
                        this.selectUE.select2('destroy');
                        this.selectUE.select2({
                            data: this.ubigeos
                        }).val(this.ubigeoorigen_id).trigger('change');
                    });
                },
                selectUbigeoDestino() {
                    this.selectUD = $(this.$refs.ubigeodestino).select2({
                        data: this.ubigeos
                    });
                    this.selectUD.val(this.ubigeodestino_id).trigger("change");
                    this.selectUD.on("select2:select", (event) => {
                        this.ubigeodestino_id = event.target.value;
                    }).on('select2:open', function(e) {
                        const evt = "scroll.select2";
                        $(e.target).parents().off(evt);
                        $(window).off(evt);
                    });
                    this.$watch("ubigeodestino_id", (value) => {
                        this.selectUD.val(value).trigger("change");
                    });
                    Livewire.hook('message.processed', () => {
                        this.selectUD.select2('destroy');
                        this.selectUD.select2({
                            data: this.ubigeos
                        }).val(this.ubigeodestino_id).trigger('change');
                    });
                },
                selectMethodpayment() {
                    this.selectMP = $(this.$refs.selectmethodpayment).select2({
                        data: this.methodpayments,
                    }).on('select2:open', (e) => {
                        const evt = "scroll.select2";
                        $(e.target).parents().off(evt);
                        $(window).off(evt);
                    }).on('change', (e) => {
                        this.methodpayment_id = e.target.value
                        // console.log($(e.target).select2('data')[0]);
                        const paramsData = $(e.target).select2('data')[0];
                        if (paramsData) {
                            this.istransferencia = paramsData.transferencia;
                        } else {
                            this.istransferencia = false;
                        }
                    }).val(this.methodpayment_id).trigger("change");
                },
                selectPayment() {
                    this.selectTP = $(this.$refs.selectpayment).select2({
                        data: this.typepayments
                    }).val(this.typepayment_id).trigger("change");
                    this.selectTP.on("select2:select", (event) => {
                        this.$wire.set('typepayment_id', event.target.value, true)
                        this.$wire.$refresh()
                    }).on('select2:open', function(e) {
                        const evt = "scroll.select2";
                        $(e.target).parents().off(evt);
                        $(window).off(evt);
                    });
                    this.$watch("typepayment_id", (value) => {
                        this.selectTP.val(value).trigger("change");
                    });

                    Livewire.hook('message.processed', () => {
                        let typepayment = this.typepayments.find(item => item.id == this
                            .typepayment_id);
                        if (typepayment) {
                            switch (typepayment.paycuotas) {
                                case true:
                                    this.paymentcuotas = true;
                                    this.typepay = '1';
                                    break;
                                case false:
                                    this.paymentcuotas = false;
                                    break;
                                default:
                                    this.paymentcuotas = false;
                                    this.formapago = '';
                            }
                        } else {
                            this.paymentcuotas = false;
                        }
                        this.selectTP.select2({
                            data: this.typepayments,
                            templateResult: function(item) {
                                var $option = $('<span data-paycuotas="' + item
                                    .paycuotas +
                                    '">' + item.text + '</span>'
                                );
                                return $option;
                            },
                        }).val(this.typepayment_id).trigger('change');
                    });
                },
                selectComprobante() {
                    this.selectTC = $(this.$refs.selectcomprobante).select2({
                        data: this.seriecomprobantes,
                    }).on('change', (e) => {
                        this.seriecomprobante_id = e.target.value
                        const paramsData = $(e.target).select2('data')[0];
                        if (paramsData) {
                            this.sendsunat = paramsData.sunat;
                            this.codecomprobante = paramsData.code;
                            switch (this.sendsunat) {
                                case false:
                                    this.incluyeguia = false;
                                    this.openguia = false;
                                    break;
                                case true:
                                    this.openguia = true;
                                    break;
                                default:
                                    this.openguia = false;
                                    this.incluyeguia = false;
                                    this.sendsunat = '';
                            }
                        } else {
                            this.openguia = false;
                            this.incluyeguia = false;
                            this.sendsunat = '';
                        }
                    }).on('select2:open', function(e) {
                        const evt = "scroll.select2";
                        $(e.target).parents().off(evt);
                        $(window).off(evt);
                    }).val(this.seriecomprobante_id).trigger("change");

                },
                consultaCliente() {
                    let route = "{{ route('consultacliente') }}";
                    const headers = {
                        // 'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    }

                    const formData = new FormData();
                    formData.append('document', this.document);
                    formData.append('autosaved', true);
                    formData.append('searchbd', true);
                    formData.append('obtenerlista', true);

                    sendRequest(route, headers, formData, (response) => {
                        if (response.errors) {
                            this.$wire.seterrors(response.errors).then(result => {});
                        }
                        this.name = response.name;
                        this.direccion = response.direccion;

                        if (response.pricetype) {
                            this.pricetype_id = response.pricetype.id
                        }
                        if (response.id) {
                            this.$wire.set('client_id', response.id);
                        }
                    }, (response) => {
                        this.name = '';
                        this.direccion = '';
                        this.$wire.set('client_id', null);

                        if (response.error) {
                            swal.fire({
                                title: response.error,
                                text: null,
                                icon: 'error',
                                confirmButtonColor: '#0FB9B9',
                                confirmButtonText: 'Cerrar',
                            })
                        }
                        if (response.errors) {
                            this.$wire.seterrors(response.errors).then(result => {});
                        }
                    })
                },
                consultaRUC(modelDocument, modelName) {
                    this.$wire.validatesearchcliente(modelDocument).then(result => {
                        console.log(result);
                        if (result) {
                            let route = "{{ route('consultacliente') }}";
                            const headers = {
                                // 'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': "{{ csrf_token() }}"
                            }

                            const formData = new FormData();
                            formData.append('document', this[modelDocument]);
                            formData.append('autosaved', false);
                            formData.append('searchbd', true);
                            formData.append('obtenerlista', false);

                            sendRequest(route, headers, formData, (response) => {
                                if (response.errors) {
                                    this.$wire.seterrors(response.errors).then(
                                    result => {});
                                }
                                this[modelName] = response.name;
                            }, (response) => {
                                this[modelName] = '';
                                if (response.error) {
                                    swal.fire({
                                        title: response.error,
                                        text: null,
                                        icon: 'error',
                                        confirmButtonColor: '#0FB9B9',
                                        confirmButtonText: 'Cerrar',
                                    })
                                }
                                if (response.errors) {
                                    this.$wire.seterrors(response.errors).then(
                                    result => {});
                                }
                            })
                        }
                    });
                }
                // getSelectedData(ref, propiedad, nameData) {
                //     const selectData = $(this.$refs[ref]).select2('data')[0];
                //     this[propiedad] = selectData.transferencia;
                // }
            }));
        })


        function SerieProductoPrm() {
            this.selectSPP = $(this.$refs.serieproductoprm).select2({
                templateResult: function(option) {
                    if (!option.id) {
                        return option.text;
                    }
                    let html = `<p>${option.text}</p>
                        <p class="select2-subtitle-option text-[10px] !text-next-500">${option.title}</p>`;
                    return $(html);
                }
            }).on('change', (e) => {
                this.serieproductoprm_id = e.target.value
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            }).val(this.serieproductoprm_id).trigger("change");

            this.$watch("serieproductoprm_id", (value) => {
                this.selectSPP.val(value).trigger("change");
            });

            Livewire.hook('message.processed', () => {
                this.selectSPP.select2({
                    templateResult: function(option) {
                        if (!option.id) {
                            return option.text;
                        }
                        let html = `<p>${option.text}</p>
                                    <p class="select2-subtitle-option text-[10px] !text-next-500">${option.title}</p>`;
                        return $(html);
                    }
                }).val(this.serieproductoprm_id).trigger('change');
            });
        }
    </script>
</div>
