<div class="flex gap-2 flex-wrap justify-around xl:justify-start mt-1" x-data="shoppingCart()">
    @foreach ($productos as $item)
        <form id="cardproduct{{ $item->id }}" class="w-full xs:w-auto"
            @submit.prevent="addtocarrito($event, {{ $item->id }})" {{-- wire:submit.prevent="addtocar(Object.fromEntries(new FormData($event.target)), {{ $item->id }})" --}}>
            @php
                $image = $item->getImageURL();
                $pricesale = $item->obtenerPrecioVenta($pricetype);
                $promocion = $item->getPromocionDisponible();
                $descuento = $item->getPorcentajeDescuento($promocion);
                $combo = $item->getAmountCombo($promocion, $pricetype, $almacen_id);
                // $priceCombo = $combo ? $combo->total : 0;
                $almacen = null;

                if ($almacendefault->name) {
                    $stock = formatDecimalOrInteger($item->almacens->first()->pivot->cantidad);
                    $almacenStock = $almacendefault->name . " [$stock " . $item->unit->name . ']';
                }
            @endphp

            <x-card-producto :name="$item->name" :image="$image ?? null" :category="$item->category->name ?? null" :almacen="$item->marca->name ?? null" :promocion="$promocion"
                class="h-full overflow-hidden">

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

                <x-prices-card-product :name="$almacenStock ?? '***'">
                    @if ($pricesale > 0)
                        @if ($descuento > 0)
                            <span class="block w-full line-through text-red-600 text-right">
                                {{ $moneda->simbolo }}
                                {{ formatDecimalOrInteger(getPriceAntes($pricesale, $descuento), $pricetype->decimals ?? 2, ', ') }}
                            </span>
                        @endif

                        <small class="text-[10px] font-semibold text-right">{{ $moneda->currency }}</small>
                        @if ($moneda->code == 'USD')
                            <x-input class="block w-full text-right disabled:bg-gray-200" name="price" type="number"
                                min="0" step="0.0001"
                                value="{{ convertMoneda($pricesale, 'USD', $empresa->tipocambio, 3) }}"
                                onkeypress="return validarDecimal(event, 12)" />
                        @else
                            <x-input class="block w-full text-right disabled:bg-gray-200" name="price" type="number"
                                min="0" step="0.0001" value="{{ formatDecimalOrInteger($pricesale, 3) }}"
                                onkeypress="return validarDecimal(event, 12)" />
                        @endif
                    @else
                        <p class="text-colorerror text-[10px] font-semibold text-center">
                            PRECIO DE VENTA NO ENCONTRADO</p>
                    @endif
                </x-prices-card-product>

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
                        <div class="w-full flex items-end gap-1 justify-end mt-1">
                            @if (count($item->seriesdisponibles) > 0)
                                <div class="w-full flex-1">
                                    <x-label value="Ingresar serie :" />
                                    <x-input class="block w-full disabled:bg-gray-200" name="serie" required
                                        min="3" />
                                </div>
                            @else
                                <div class="w-full flex-1">
                                    <x-label value="Cantidad :" />
                                    <x-input class="block w-full disabled:bg-gray-200" name="cantidad" type="number"
                                        min="1" required max="{{ $stock }}" value="1"
                                        onkeypress="return validarDecimal(event, 12)" />
                                </div>
                            @endif
                            <x-button-add-car type="submit" wire:loading.attr="disabled" />
                        </div>
                    </x-slot>
                @endif

                <x-slot name="messages">
                    <x-jet-input-error for="cart.{{ $item->id }}.price" />
                    <x-jet-input-error for="cart.{{ $item->id }}.almacen_id" />
                    <x-jet-input-error for="cart.{{ $item->id }}.serie" />
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
        function shoppingCart() {
            return {
                addtocarrito(event, producto_id) {
                    let form = event.target;
                    const cart = {
                        price: form.price.value,
                        cantidad: form.cantidad == undefined ? 1 : form.cantidad.value,
                        serie: form.serie == undefined ? null : form.serie.value
                    };
                    this.$wire.addtocar(producto_id, cart).then(result => {
                        console.log('addtocar ejecutado correctamente');
                    }).catch(error => {
                        console.error(error);
                    });
                }
            }
        }
    </script>
</div>
