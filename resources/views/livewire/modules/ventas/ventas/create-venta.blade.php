<div class="relative">
    <x-form-card titulo="LISTADO PRODUCTOS">
        <div class="w-full">
            {{-- @if (!$empresa->uselistprice || ($empresa->uselistprice && count($pricetypes))) --}}
            <div class="w-full flex flex-col gap-2 md:flex-row md:flex-shrink">
                <div class="w-full flex-shrink-1">
                    <x-label value="Descripcion producto :" />
                    <x-input class="block w-full disabled:bg-gray-200" wire:model.lazy="search"
                        placeholder="Buscar producto..." />
                    <x-jet-input-error for="search" />
                </div>

                @if ($empresa->uselistprice)
                    @if (count($pricetypes) > 1)
                        <div class="w-full md:w-64 lg:w-80">
                            <x-label value="Lista precios :" />
                            <div id="parentventapricetype_id" class="relative" x-data="{ pricetype_id: @entangle('pricetype_id') }"
                                x-init="select2PricetypeAlpine" wire:ignore>
                                <x-select class="block w-full" id="ventapricetype_id" x-ref="select">
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

            <div class="w-full grid xs:grid-cols-2 md:grid-cols-3 2xl:grid-cols-5 gap-2 mt-2">
                <div class="w-full">
                    <x-label value="Buscar serie :" />
                    <x-input class="block w-full" wire:keydown.enter="getProductoBySerie" wire:model.defer="searchserie"
                        placeholder="Buscar serie..." />
                    <x-jet-input-error for="searchserie" />
                </div>

                @if (count($sucursal->almacens) > 1)
                    <div class="w-full">
                        <x-label value="Almacén :" />
                        <div id="parentalmacen_id" class="relative" x-data="{ almacen_id: @entangle('almacen_id') }" x-init="select2AlmacenAlpine"
                            wire:ignore>
                            <x-select class="block w-full" id="almacen_id" x-ref="select">
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

                <div class=" w-full">
                    <x-label value="Categoría :" />
                    <div id="parentsearchcategory" class="relative" x-data="{ searchcategory: @entangle('searchcategory') }" x-init="select2CategoryAlpine"
                        wire:ignore>
                        <x-select class="block w-full" id="searchcategory" x-ref="select" data-placeholder="null">
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
                    <div class=" w-full">
                        <x-label value="Subcategoría :" />
                        <div id="parentsearchsubcategory" class="relative" x-data="{ searchsubcategory: @entangle('searchsubcategory') }"
                            x-init="SelectSubcategory" wire:ignore>
                            <x-select class="block w-full" id="searchsubcategory" x-ref="selectsubcat"
                                data-placeholder="null">
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

                                $discount = count($item->ofertasdisponibles) > 0 ? $item->ofertasdisponibles()->first()->descuento : null;

                                if ($almacendefault->name) {
                                    $stock = formatDecimalOrInteger($item->almacens->first()->pivot->cantidad);
                                    $almacen = $almacendefault->name . " [$stock " . $item->unit->name . ']';
                                }
                            @endphp

                            <x-card-producto :name="$item->name" :image="$image ?? null" :category="$item->category->name ?? null" :almacen="$moneda->currency ?? null"
                                :discount="$discount ?? null" class="h-full overflow-hidden">

                                @if ($empresa->uselistprice)
                                    @if (count($pricetypes))
                                        @php
                                            $precios = \App\Helpers\GetPrice::getPriceProducto($item, $empresa->uselistprice ? $pricetype_id : null, $empresa->tipocambio)->getData();
                                            // var_dump($precios);
                                        @endphp

                                        <x-prices-card-product :name="$empresa->uselistprice ? $pricetype->name : null">
                                            <x-slot name="buttonpricemanual">
                                                <x-span-text :text="$almacen ?? '-'" class="leading-3 !tracking-normal"
                                                    :type="$stock <= $item->minstock ? 'orange' : ''" />
                                                @if ($precios->existsrango && count($item->ofertasdisponibles))
                                                    <p
                                                        class="inline-block font-semibold text-[9px] leading-3 bg-red-100 p-1 rounded text-red-500">
                                                        ANTES : {{ $moneda->simbolo }}
                                                        {{ number_format($moneda->code == 'USD' ? $precios->priceDolar : $precios->pricesale, $precios->decimal, '.', ', ') }}
                                                    </p>
                                                @endif
                                            </x-slot>

                                            @if ($precios->existsrango || $precios->pricemanual > 0)
                                                @if (count($item->ofertasdisponibles))
                                                    <x-input class="block w-full p-2 disabled:bg-gray-200"
                                                        name="price" type="number" min="0" step="0.0001"
                                                        value="{{ $moneda->code == 'USD' ? $precios->pricewithdescountDolar : $precios->pricewithdescount }}" />
                                                @else
                                                    @php
                                                        $price = 0.0;
                                                        if ($moneda->code == 'USD') {
                                                            $price = $precios->pricewithdescountDolar ?? $precios->priceDolar;
                                                        } else {
                                                            $price = !is_null($precios->pricemanual) ? $precios->pricemanual : $precios->pricewithdescount ?? $precios->pricesale;
                                                        }
                                                    @endphp

                                                    <x-input class="block w-full p-2 disabled:bg-gray-200"
                                                        name="price" type="number" min="0" step="0.0001"
                                                        value="{{ $price }}" />
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
                                        @php
                                            $precios = \App\Helpers\GetPrice::getPriceProducto($item, null, $empresa->tipocambio)->getData();
                                        @endphp
                                        {{-- {{ var_dump($precios) }} --}}
                                        <x-prices-card-product :name="null">
                                            <x-slot name="buttonpricemanual">
                                                <x-span-text :text="$almacen ?? '-'" class="leading-3 !tracking-normal"
                                                    :type="$stock <= $item->minstock ? 'orange' : ''" />
                                                @if (count($item->ofertasdisponibles))
                                                    <p
                                                        class="inline-block font-semibold text-[9px] leading-3 bg-red-100 p-1 rounded text-red-500">
                                                        ANTES : {{ $moneda->simbolo }}
                                                        {{ number_format($moneda->code == 'USD' ? $precios->priceDolar : $precios->pricesale, $precios->decimal, '.', ', ') }}
                                                    </p>
                                                @endif
                                            </x-slot>

                                            @if (count($item->ofertasdisponibles))
                                                <x-input class="block w-full p-2 disabled:bg-gray-200" name="price"
                                                    type="number" min="0" step="0.0001"
                                                    value="{{ $moneda->code == 'USD' ? $precios->pricewithdescountDolar : $precios->pricewithdescount }}" />
                                            @else
                                                @php
                                                    $price = 0.0;

                                                    if ($moneda->code == 'USD') {
                                                        $price = $precios->pricewithdescountDolar ?? $precios->priceDolar;
                                                    } else {
                                                        $price = $precios->pricewithdescount ?? $precios->pricesale;
                                                    }
                                                @endphp

                                                <x-input class="block w-full p-2 disabled:bg-gray-200" name="price"
                                                    type="number" min="0" step="0.0001"
                                                    value="{{ $price }}" />
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

                                <x-slot name="footer">
                                    {{-- @if ($empresa->uselistprice == 0 || (float) $precios->pricesale > 0) --}}
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
                                                <x-input class="block w-full p-2 disabled:bg-gray-200" name="cantidad"
                                                    type="number" min="0" value="1" />
                                            </div>
                                        @endif

                                        <x-button-add-car type="submit" wire:loading.attr="disabled"
                                            wire:target="addtocar">
                                        </x-button-add-car>
                                    </div>
                                    {{-- @endif --}}
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
            @else
                <div>
                    @php
                        $almacenstring = is_null($almacendefault) ? '...[SUCURSAL SIN ALAMACENES]' : $almacendefault->name;
                    @endphp
                    <x-span-text :text="'NO SE ENCONTRARON REGISTROS DE PRODUCTOS PARA EL ALMACEN, ' . $almacenstring" class="inline-block" type="" />
                </div>
            @endif

            {{-- @else
                    <small class="text-colorerror bg-red-50 text-xs p-0.5 rounded font-semibold inline-block mt-1">
                        Configurar lista de precios
                        <a class="underline px-1" href="#">REGISTRAR</a>
                    </small>
                @endif --}}
        </div>

        <div wire:loading.flex wire:target="render" class="loading-overlay rounded hidden">
            <x-loading-next />
        </div>
    </x-form-card>
    <script>
        function select2AlmacenAlpine() {
            this.selectA = $(this.$refs.select).select2();
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
        }

        function select2CategoryAlpine() {
            this.select2 = $(this.$refs.select).select2();
            this.select2.val(this.searchcategory).trigger("change");
            this.select2.on("select2:select", (event) => {
                this.searchcategory = event.target.value;
            }).on('select2:open', function(e) {
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
                // this.pricetype_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("pricetype_id", (value) => {
                this.selectP.val(value).trigger("change");
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
        }

        window.addEventListener('reset-form', data => {
            document.getElementById("cardproduct" + data.detail).reset();
        });

        window.addEventListener('setMoneda', data => {
            @this.setMoneda(data.detail);
            // this.pricetype_id = event.target.value;
        });

        window.addEventListener('setPricetypeId', data => {
            console.log(data.detail);
            if (data.detail) {
                @this.setPricetypeId(data.detail);
                // this.pricetype_id = event.target.value;
            }
        });

        window.addEventListener('loadsubcategories', subcategories => {
            let subcat = document.querySelector('[x-ref="selectsubcat"]');
            $(subcat).val(null).empty().append('<option value="" selected>SELECCIONAR...</option>');
            subcategories.detail.forEach(subcateg => {
                let option = new Option(subcateg.name, subcateg.id, false, false);
                $(subcat).append(option);
            });
            $(subcat).select2().trigger('change');
        })
    </script>
</div>
