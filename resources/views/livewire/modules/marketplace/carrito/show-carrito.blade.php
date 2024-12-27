<div class="py-5" @updatecart.window ="(data)=> {
    $wire.render().then((result) => {})
    }">
    @if (Cart::instance('shopping')->count() > 0)
        <div class="w-full md:max-w-3xl mx-auto flex flex-col gap-3 sm:gap-5">
            <div class="w-full">
                <div class="w-full flex flex-wrap gap-2 items-center justify-between py-3">
                    <h1 class="font-semibold text-lg sm:text-2xl text-primary">
                        Resumen del carrito de compras</h1>

                    <button wire:click="delete" wire:loading.attr="disabled"
                        class="btn-outline-secondary ring-2 inline-block hover:bg-red-700 hover:text-white hover:ring-red-300">
                        ELIMINAR TODO</button>
                </div>

                <div class="w-full flex flex-col gap-3">
                    @foreach ($shoppings as $item)
                        @php
                            $combo = getAmountCombo($item->options->promocion, $pricetype);
                            $image =
                                !is_null($item->model) && count($item->model->images) > 0
                                    ? pathURLProductImage($item->model->images->first()->url)
                                    : null;
                        @endphp

                        <div class="relative w-full flex flex-col gap-2 text-xs p-1 sm:p-2 border border-borderminicard shadow-md shadow-shadowminicard rounded-lg md:rounded-2xl"
                            wire:key="{{ $item->rowId }}">
                            <div
                                class="{{ $item->options->is_disponible ? '' : 'opacity-50' }} w-full flex flex-col xs:flex-row gap-2 text-xs">
                                <div class="w-full h-48 xs:size-32 sm:size-40 rounded overflow-hidden">
                                    @if ($image)
                                        <img src="{{ $image }}" alt=""
                                            class="block w-full h-full object-scale-down object-center overflow-hidden">
                                    @else
                                        <x-icon-file-upload class="w-full h-full" type="unknown" />
                                    @endif
                                </div>
                                <div class="w-ful flex-1">
                                    <p class="leading-tight text-xs text-colorlabel text-justify">
                                        {{ !is_null($item->model) ? $item->model->name : $item->name }}</p>
                                    @if ($combo)
                                        <div class="w-full flex items-center flex-wrap gap-1 sm:gap-3">
                                            @foreach ($combo->products as $itemcombo)
                                                <span class="block w-5 h-5 text-colorsubtitleform">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                        color="currentColor" fill="none" stroke="currentColor"
                                                        stroke-width="2.5" stroke-linecap="round"
                                                        stroke-linejoin="round" class="w-full h-full block">
                                                        <path d="M12 4V20M20 12H4" />
                                                    </svg>
                                                </span>

                                                <a class="size-16 sm:size-20 block rounded-lg relative"
                                                    href="{{ route('productos.show', $itemcombo->producto_slug) }}">
                                                    @if ($itemcombo->image)
                                                        <img src="{{ $itemcombo->image }}" alt="{{ $itemcombo->image }}"
                                                            class="block w-full h-auto object-scale-down overflow-hidden rounded-lg">
                                                    @else
                                                        <x-icon-image-unknown
                                                            class="w-full h-full text-colorsubtitleform" />
                                                    @endif

                                                    @if ($itemcombo->price <= 0)
                                                        <x-span-text text="GRATIS" type="green"
                                                            class="text-nowrap absolute bottom-0 left-[50%] -translate-x-[50%] !text-[9px] py-0.5" />
                                                    @endif
                                                </a>
                                            @endforeach
                                        </div>
                                        <p class="leading-tight text-xs text-colorlabel">
                                            {{ $item->options->promocion->titulo }}</p>
                                    @endif

                                    @if ($item->options->is_disponible)
                                        <h1
                                            class="text-colorlabel text-sm sm:text-lg md:text-xl font-semibold text-end !leading-none">
                                            @if ($item->options->promocion)
                                                <span
                                                    class="text-[10px] md:text-xs p-0.5 rounded text-colorerror font-medium line-through">
                                                    @if ($combo)
                                                        {{ number_format(getPrecioventa($item->model, $pricetype) + $combo->total_normal, 2, '.', ', ') }}
                                                    @else
                                                        {{ number_format(getPrecioventa($item->model, $pricetype), 2, '.', ', ') }}
                                                    @endif
                                                </span>
                                                <br>
                                            @endif

                                            <small
                                                class="text-[10px] font-medium">{{ $item->options->simbolo }}</small>
                                            {{ number_format($item->price, 2, '.', ', ') }}
                                        </h1>

                                        <h1
                                            class="text-colorlabel text-sm sm:text-lg md:text-xl font-semibold text-end !leading-tight">
                                            <small class="text-[10px] font-medium">TOTAL :
                                                {{ $item->options->simbolo }}</small>
                                            {{ number_format($item->price * $item->qty, 2, '.', ', ') }}
                                        </h1>
                                    @endif
                                </div>
                            </div>

                            @if ($item->options->is_disponible)
                                <div class="w-full flex flex-wrap xs:flex-nowrap gap-1 justify-between items-end">
                                    <div class="w-full flex-1 flex items-end gap-1">
                                        @if ($item->qty > 1)
                                            <button onclick="updatecart(this, '{{ $item->rowId }}')"
                                                :key="{{ $item->rowId }}" type="button" data-function="decrement"
                                                wire:loading.attr="disabled" class="btn-increment-cart">-</button>
                                        @else
                                            <span class="btn-increment-cart disabled"
                                                :key="{{ rand() }}">-</span>
                                        @endif

                                        <x-input value="{{ $item->qty }}"
                                            class="w-14 text-center text-colorlabel input-number-none" type="number"
                                            step="1" min="1" onkeypress="return validarNumero(event, 4)"
                                            x-on:blur="if ($el.value == '' || $el.value == 0 ) {
                                            $el.value = {{ $item->qty }}
                                        }
                                        if ( $el.value != {{ $item->qty }}) {
                                            updateqty($el, '{{ $item->rowId }}', $el.value)
                                        }"
                                            onpaste="return validarPasteNumero(event, 12)" />
                                        <button onclick="updatecart(this, '{{ $item->rowId }}')" type="button"
                                            data-function="increment" wire:loading.attr="disabled"
                                            class="btn-increment-cart">+</button>
                                    </div>

                                    <button onclick="deleteitemcart('{{ $item->rowId }}')"
                                        wire:loading.attr="disabled"
                                        class="btn-outline-secondary !p-2 !text-[10px] ring-2 font-semibold hover:bg-red-700 hover:text-white hover:ring-red-300">
                                        ELIMINAR</button>
                                </div>
                            @else
                                <div
                                    class="absolute top-0 left-0 bg-body w-full h-full opacity-50 rounded-lg md:rounded-2xl">
                                </div>
                                <div
                                    class="absolute w-full h-full flex flex-col xs:flex-row flex-wrap justify-center gap-2 items-center top-[50%] left-[50%] -translate-x-[50%] -translate-y-[50%]">
                                    <span class="btn-outline-secondary bg-inherit inline-block text-center">
                                        {{ !empty($item->options->promocion_id) ? 'PROMOCIÓN NO DISPONIBLE' : 'PRODUCTO NO DISPONIBLE' }}</span>
                                    <x-button-secondary wire:click="deleteitem('{{ $item->rowId }}')"
                                        wire:loading.attr="disabled" class="inline-block text-center">
                                        ELIMINAR</x-button-secondary>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="w-full">
                {{-- <h1 class="text-lg font-semibold text-primary border-b border-borderminicard">
                    RESUMEN DEL PEDIDO</h1> --}}

                <div class="w-full grid grid-cols-2 gap-3">
                    <p class="text-sm text-colorsubtitleform font-medium">
                        TOTAL</p>
                    <p class="text-3xl font-semibold text-colorlabel text-end">
                        <small class="text-[10px] font-medium">{{ $moneda->simbolo }}</small>
                        {{-- {{ decimalOrInteger(Cart::instance('shopping')->subtotal(), 2, ' ,') }} --}}
                        {{ decimalOrInteger(getAmountCart($shoppings)->total, 2, ', ') }}
                    </p>
                </div>
                <div class="w-full p-1 flex justify-end items-end">
                    <a href="{{ route('carshoop.create') }}" class="btn-next">
                        <span class="btn-effect"><span>CONTINUAR COMPRA</span></span>
                    </a>
                </div>
            </div>
        </div>
    @else
        <h1 class="text-xs p-2 font-medium text-colorsubtitleform">
            NO EXISTEN PRODUCTOS AGREGADOS EN EL CARRITO...</h1>
    @endif
</div>
