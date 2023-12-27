<div class="relative" x-data="{ readytoload: false }">
    <x-form-card titulo="LISTADO PRODUCTOS">
        <div class="w-full">
            @if (isset($empresa->id))
                @if (!$empresa->uselistprice || ($empresa->uselistprice && count($pricetypes)))
                    <div class="w-full grid md:grid-cols-6 2xl:grid-cols-5 gap-1">

                        <div class="w-full md:col-span-6 2xl:col-span-2">
                            <x-label value="Descripcion producto :" />
                            <x-input class="block w-full disabled:bg-gray-200" wire:model.lazy="search" />
                            <x-jet-input-error for="search" />
                        </div>

                        <div class=" w-full md:col-span-2 2xl:col-span-1">
                            <x-label value="Buscar serie :" />
                            <x-input class="block w-full" wire:keydown.enter="getProductoBySerie"
                                wire:model.defer="searchserie" />
                            <x-jet-input-error for="searchserie" />
                        </div>

                        <div class=" w-full md:col-span-2 2xl:col-span-1">
                            <x-label value="Almacén :" />
                            <div id="parentalmacen_id" class="relative" x-data="{ almacen_id: @entangle('almacen_id') }" x-init="select2AlmacenAlpine"
                                wire:ignore>
                                <x-select class="block w-full" id="almacen_id" x-ref="select">
                                    <x-slot name="options">
                                        @if (count($sucursal->almacens))
                                            @foreach ($sucursal->almacens as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        @endif
                                    </x-slot>
                                </x-select>
                                <x-icon-select />
                            </div>
                            <x-jet-input-error for="almacen_id" />
                        </div>

                        <div class=" w-full md:col-span-2 2xl:col-span-1">
                            <x-label value="Categoría :" />
                            <div id="parentsearchcategory" class="relative" x-data="{ searchcategory: @entangle('searchcategory') }"
                                x-init="select2CategoryAlpine" wire:ignore>
                                <x-select class="block w-full" id="searchcategory" x-ref="select"
                                    data-placeholder="null">
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

                        @if ($empresa->uselistprice)
                            <div class=" w-full md:col-span-2 2xl:col-span-1">
                                <x-label value="Lista precios :" />
                                <div id="parentventapricetype_id" class="relative" x-data="{ pricetype_id: @entangle('pricetype_id') }"
                                    x-init="select2PricetypeAlpine" wire:ignore>
                                    <x-select class="block w-full" id="ventapricetype_id" x-ref="select">
                                        <x-slot name="options">
                                            @if (count($pricetypes))
                                                @foreach ($pricetypes as $item)
                                                    <option value="{{ $item->id }}">
                                                        {{ $item->name }}</option>
                                                @endforeach
                                            @endif
                                        </x-slot>
                                    </x-select>
                                    <x-icon-select />
                                </div>
                                <x-jet-input-error for="pricetype_id" />
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

                    <div wire:init="loadProductos">
                        @if (count($productos))
                            <div class="w-full py-2">
                                {{ $productos->onEachSide(0)->links('livewire::pagination-default') }}
                            </div>
                            <div class="flex gap-2 flex-wrap justify-between mt-1">
                                @foreach ($productos as $item)
                                    <form id="cardproduct{{ $item->id }}" class="w-full xs:w-auto"
                                        wire:submit.prevent="addtocar(Object.fromEntries(new FormData($event.target)), {{ $item->id }})">
                                        @php
                                            $image = null;
                                            $almacen = null;

                                            if (count($item->images)) {
                                                if (count($item->defaultImage)) {
                                                    $image = asset('storage/productos/' . $item->defaultImage->first()->url);
                                                } else {
                                                    $image = asset('storage/productos/' . $item->images->first()->url);
                                                }
                                            }

                                            $discount = count($item->ofertasdisponibles) ? $item->ofertasdisponibles()->first()->descuento : null;

                                            if ($almacendefault->name) {
                                                $stock = \App\Helpers\FormatoPersonalizado::getValue($item->almacens->first()->pivot->cantidad);
                                                $almacen = $almacendefault->name . " [$stock " . $item->unit->name . ']';
                                            }
                                        @endphp








                                        <x-card-producto :name="$item->name" :image="$image ?? null" :category="$item->category->name ?? null"
                                            :almacen="$moneda->currency ?? null" :discount="$discount ?? null" class="h-full">

                                            @if ($empresa->uselistprice)
                                                @if (count($pricetypes))
                                                    @php
                                                        $precios = \App\Helpers\GetPrice::getPriceProducto($item, $empresa->uselistprice ? $pricetype_id : null, $empresa->tipocambio)->getData();
                                                    @endphp
                                                    <x-prices-card-product :name="$empresa->uselistprice ? $pricetype->name : null">
                                                        @if (count($item->ofertasdisponibles))
                                                            <x-slot name="buttonpricemanual">
                                                                <p
                                                                    class="inline-block font-semibold text-[9px] leading-3 bg-red-100 p-1 rounded text-red-500">
                                                                    ANTES : {{ $moneda->simbolo }}
                                                                    {{ number_format($moneda->code == 'USD' ? $precios->priceDolar : $precios->pricesale, $precios->decimal, '.', ', ') }}
                                                                </p>
                                                            </x-slot>

                                                            <x-input class="block w-full p-2 disabled:bg-gray-200"
                                                                name="price" type="number" min="0"
                                                                step="0.0001"
                                                                value="{{ $moneda->code == 'USD' ? $precios->pricewithdescountDolar : $precios->pricewithdescount }}" />
                                                        @else
                                                            @php
                                                                $price = 0.0;

                                                                if ($moneda->code == 'USD') {
                                                                    $price = $precios->pricewithdescountDolar ?? $precios->priceDolar;
                                                                } else {
                                                                    $price = $precios->pricemanual ?? $precios->pricesale;
                                                                }
                                                            @endphp

                                                            <x-slot name="buttonpricemanual">
                                                                @if ($stock <= $item->minstock)
                                                                    <p
                                                                        class="inline-block font-semibold text-[9px] leading-3 bg-orange-500 p-1 rounded text-white">
                                                                        {{ $almacen ?? '-' }}
                                                                    @else
                                                                    <p
                                                                        class="inline-block font-semibold text-[9px] leading-3 bg-green-500 p-1 rounded text-white">
                                                                        {{ $almacen ?? '-' }}
                                                                @endif

                                                                </p>
                                                            </x-slot>

                                                            <x-input class="block w-full p-2 disabled:bg-gray-200"
                                                                name="price" type="number" min="0"
                                                                step="0.0001" value="{{ $price }}" />
                                                        @endif

                                                        @if ($empresa->uselistprice)
                                                            @if (!$precios->existsrango)
                                                                <small
                                                                    class="text-red-500 bg-red-50 p-0.5 rounded font-semibold inline-block mt-1">
                                                                    Rango de precio no disponible
                                                                    <a class="underline px-1"
                                                                        href="#">REGISTRAR</a>
                                                                </small>
                                                            @endif
                                                        @endif
                                                    </x-prices-card-product>
                                                @else
                                                    <small
                                                        class="text-red-500 bg-red-50 text-xs p-0.5 rounded font-semibold inline-block mt-1">
                                                        Configurar lista de precios
                                                        <a class="underline px-1" href="#">REGISTRAR</a>
                                                    </small>
                                                @endif
                                            @else
                                                <div class="w-full flex flex-col">
                                                    @php
                                                        $precios = \App\Helpers\GetPrice::getPriceProducto($item, null, $empresa->tipocambio)->getData();
                                                    @endphp

                                                    <x-prices-card-product name="PRECIO VENTA">
                                                        @if (count($item->ofertasdisponibles))
                                                            <x-slot name="buttonpricemanual">
                                                                <p
                                                                    class="inline-block font-semibold text-[9px] leading-3 bg-red-100 p-1 rounded text-red-500">
                                                                    ANTES : {{ $moneda->simbolo }}
                                                                    {{ number_format($moneda->code == 'USD' ? $precios->priceDolar : $precios->pricesale, $precios->decimal, '.', ', ') }}
                                                                </p>
                                                            </x-slot>

                                                            <x-input class="block w-full p-2 disabled:bg-gray-200"
                                                                name="price" type="number" min="0"
                                                                step="0.0001"
                                                                value="{{ $moneda->code == 'USD' ? $precios->pricewithdescountDolar : $precios->pricewithdescount }}" />
                                                        @else
                                                            @php
                                                                $price = 0.0;

                                                                if ($moneda->code == 'USD') {
                                                                    $price = $precios->pricewithdescountDolar ?? $precios->priceDolar;
                                                                } else {
                                                                    $price = $precios->pricemanual ?? $precios->pricesale;
                                                                }
                                                            @endphp

                                                            <x-slot name="buttonpricemanual">
                                                                <p
                                                                    class="inline-block font-semibold text-[9px] leading-3 bg-fondospancardproduct p-1 rounded text-textspancardproduct">
                                                                    {{ $moneda->currency }}
                                                                </p>
                                                            </x-slot>

                                                            <x-input class="block w-full p-2 disabled:bg-gray-200"
                                                                name="price" type="number" min="0"
                                                                step="0.0001" value="{{ $price }}" />
                                                        @endif
                                                    </x-prices-card-product>
                                                </div>
                                            @endif

                                            <x-slot name="footer">
                                                @if (!$empresa->uselistprice || $precios->pricesale > 0)
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
                                                                    value="1" />
                                                            </div>
                                                        @endif

                                                        <x-button-add-car type="submit" wire:loading.attr="disabled"
                                                            wire:target="addtocar">
                                                        </x-button-add-car>
                                                    </div>
                                                @else
                                                    <small
                                                        class="text-red-500 bg-red-50 text-xs p-0.5 rounded font-semibold inline-block mt-1">
                                                        Configurar lista de precios
                                                        <a class="underline px-1" href="#">REGISTRAR</a>
                                                    </small>
                                                @endif
                                            </x-slot>
                                            <x-slot name="messages">
                                                <x-jet-input-error for="cart.{{ $item->id }}.price" />
                                                <x-jet-input-error for="cart.{{ $item->id }}.almacen_id" />
                                                <x-jet-input-error for="cart.{{ $item->id }}.serie" />
                                                <x-jet-input-error for="cart.{{ $item->id }}.cantidad" />
                                            </x-slot>

                                            <div wire:loading.flex
                                                class="loading-overlay rounded hidden shadow-md shadow-shadowminicard">
                                                <x-loading-next />
                                            </div>
                                        </x-card-producto>
                                    </form>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @else
                    <small class="text-colorerror bg-red-50 text-xs p-0.5 rounded font-semibold inline-block mt-1">
                        Configurar lista de precios
                        <a class="underline px-1" href="#">REGISTRAR</a>
                    </small>
                @endif
            @else
                <small class="text-colorerror bg-red-50 text-xs p-0.5 rounded font-semibold inline-block mt-1">
                    Configurar datos de la empresa, para usar precios de los productos
                    <a class="underline px-1" href="#">REGISTRAR</a>
                </small>
            @endif
        </div>

        <div x-show="readytoload" wire:loading.flex wire:target="loadProductos" class="loading-overlay rounded">
            <x-loading-next />
        </div>
    </x-form-card>

    <script>
        function select2AlmacenAlpine() {
            this.selectA = $(this.$refs.select).select2();
            this.selectA.val(this.almacen_id).trigger("change");
            this.selectA.on("select2:select", (event) => {
                    this.almacen_id = event.target.value;
                })
                .on('select2:open', function(e) {
                    const evt = "scroll.select2";
                    $(e.target).parents().off(evt);
                    $(window).off(evt);
                });
        }

        function select2CategoryAlpine() {
            this.select2 = $(this.$refs.select).select2();
            this.select2.val(this.searchcategory).trigger("change");
            this.select2.on("select2:select", (event) => {
                    this.searchcategory = event.target.value;
                })
                .on('select2:open', function(e) {
                    const evt = "scroll.select2";
                    $(e.target).parents().off(evt);
                    $(window).off(evt);
                });
            this.$watch("searchcategory", (value) => {
                this.select2.val(value).trigger("change");
            });
        }

        function select2PricetypeAlpine() {
            this.selectP = $(this.$refs.select).select2();
            this.selectP.val(this.pricetype_id).trigger("change");
            this.selectP.on("select2:select", (event) => {
                    @this.setPricetypeId(event.target.value);
                    this.pricetype_id = event.target.value;
                })
                .on('select2:open', function(e) {
                    const evt = "scroll.select2";
                    $(e.target).parents().off(evt);
                    $(window).off(evt);
                });
            this.$watch("pricetype_id", (value) => {
                this.selectP.val(value).trigger("change");
            });
        }

        window.addEventListener('reset-form', data => {
            document.getElementById("cardproduct" + data.detail).reset();
        });

        window.addEventListener('setMoneda', data => {
            @this.setMoneda(data.detail);
            // this.pricetype_id = event.target.value;
        });

        window.addEventListener('setPricetypeId', data => {
            @this.setPricetypeId(data.detail);
            // this.pricetype_id = event.target.value;
        });

    </script>
</div>
