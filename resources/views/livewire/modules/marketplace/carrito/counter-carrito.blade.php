<div @updatecart.window ="(data) => $wire.render()">
    @if (count($shoppings) > 0)
        <div class="group" x-on:click.away="cartOpen = false" x-data="{ cartOpen: false }" x-init="$watch('cartOpen', (value) => {
            if (value) {
                if (!backdrop) {
                    document.body.style.overflow = 'hidden';
                }
            } else {
                if (!backdrop) {
                    document.body.style.overflow = 'auto';
                }
            }
        })">
            <button x-on:click="cartOpen = !cartOpen" wire:loading.attr="disabled"
                class="flex p-3 px-1 sm:px-3 w-full h-full justify-start items-center hover:opacity-80 disabled:opacity-75 disabled:bg-opacity-75 transition ease-in-out duration-150"
                id="button-counter-cart" role="button" aria-label="Carrito de compras">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="block w-8 h-8">
                    <circle cx="8" cy="21" r="1" />
                    <circle cx="19" cy="21" r="1" />
                    <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                </svg>
            </button>

            <span id="countercart"
                class="flex absolute w-4 h-4 top-0.5 -right-1 xl:right-1 tracking-tight h-100 justify-center items-center leading-3 text-[9px] bg-fondobadgemarketplace text-colorbadgemarketplace rounded-full">
                {{-- {{ Cart::instance('shopping')->count() }} --}}
                {{ count($shoppings) }}
            </span>

            <div style="transform:translateX(100%)" x-cloak
                x-bind:style="cartOpen ? 'transform:translateX(0)' : 'transform:translateX(100%)'"
                class="fixed z-[699] right-0 top-0 w-full xs:max-w-xl h-full flex flex-col justify-center transform overflow-y-auto transition duration-300"
                :class="cartOpen ? 'ease-out' : 'ease-in'">
                <div
                    class="w-full bg-fondominicard sm:rounded-tl-xl md:shadow-inner md:shadow-shadowminicard h-full flex-1 overflow-y-auto items-center justify-between p-1 sm:p-2">
                    <div class="w-full flex justify-between items-center gap-2 pb-3">
                        <h1 class="text-2xl font-medium text-primary">Mi carrito</h1>
                        <button @click="cartOpen = !cartOpen" id="button-toggle-cart" role="button"
                            aria-label="Boton Mostrar / ocutar carrito"
                            class="text-colorsubtitleform focus:outline-none">
                            <svg class="h-5 w-5" fill="none" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                <path d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="w-full flex flex-col gap-3">
                        @foreach ($shoppings as $item)
                            @php
                                $combo = getAmountCombo($item->options->promocion, $pricetype);
                                $image =
                                    !is_null($item->model) && !empty($item->model->imagen)
                                        ? pathURLProductImage($item->model->imagen->url)
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
                                                        @if ($itemcombo->imagen)
                                                            <img src="{{ $itemcombo->imagen }}"
                                                                alt="{{ $itemcombo->producto_slug }}"
                                                                class="block w-full h-full object-scale-down overflow-hidden rounded-lg">
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
                                                class="w-14 text-center text-colorlabel input-number-none"
                                                type="number" step="1" min="1"
                                                onkeypress="return validarNumero(event, 4)"
                                                x-on:blur="if ($el.value == '' || $el.value == 0 ) {
                                            $el.value = {{ $item->qty }}
                                        }
                                        if ( $el.value != {{ $item->qty }}) {
                                            updateqty($el, '{{ $item->rowId }}', $el.value)
                                        }"
                                                onpaste="return validarPasteNumero(event, 12)" aria-label="cantidad" />
                                            <button onclick="updatecart(this, '{{ $item->rowId }}')" type="button"
                                                data-function="increment" wire:loading.attr="disabled"
                                                class="btn-increment-cart" role="button"
                                                aria-label="Incrementar">+</button>
                                        </div>

                                        <button onclick="deleteitemcart(this, '{{ $item->rowId }}')"
                                            wire:loading.attr="disabled" role="button" aria-label="Eliminar carrito"
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
                                        <x-button-secondary onclick="deleteitemcart(this, '{{ $item->rowId }}')"
                                            wire:loading.attr="disabled" class="inline-block text-center"
                                            role="button" aria-label="Eliminar carrito">
                                            ELIMINAR</x-button-secondary>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <div
                    class="w-full flex-shrink-0 p-3 mt-auto sm:rounded-bl-xl bg-next-800 flex justify-between items-center flex-row gap-2">
                    <div>
                        <p class="text-[10px] text-white">TOTAL</p>
                        <p class="text-xl text-white font-semibold">
                            <small class="text-[10px] font-medium">{{ $moneda->simbolo }} </small>
                            {{-- {{ number_format(Cart::instance('shopping')->subtotal(), 2, '.', ', ') }} --}}
                            {{ number_format(getAmountCart($shoppings)->total, 2, '.', ', ') }}
                        </p>
                    </div>
                    <a href="{{ route('carshoop') }}" class="btn-next btn-white btn-hover-transparent">
                        <span class="btn-effect"><span>REALIZAR PAGO</span></span>
                    </a>
                </div>
            </div>
        </div>
    @else
        <span x-init="document.body.style.overflow = 'auto'"
            class="flex p-3 px-1 sm:px-3 w-full h-full justify-start items-center hover:opacity-80 disabled:opacity-75 disabled:bg-opacity-75 transition ease-in-out duration-150">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="block w-8 h-8">
                <circle cx="8" cy="21" r="1" />
                <circle cx="19" cy="21" r="1" />
                <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
            </svg>
        </span>
    @endif

    <script>
        document.addEventListener('livewire:load', function() {
            Livewire.hook('message.processed', () => {
                $(componentloading).fadeOut();
            });
        })
    </script>
</div>
