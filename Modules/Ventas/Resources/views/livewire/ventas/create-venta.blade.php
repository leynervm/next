<div class="mt-3 " id="form_create_venta">

    @if (count($opencajas))
        @foreach ($opencajas as $item)
            <x-minicard  :title="$item->caja->name" />
        @endforeach
    @endif




    <x-title-next titulo="Registrar venta" class="mb-5" />

    <div class="w-full flex flex-wrap md:flex-nowrap gap-3">
        <div class="w-full lg:w-1/3">
            <x-label value="Vincular cotización :" />
            <x-select class="block w-full" id="cotizacion_id" wire:model.defer="cotizacion_id">
                <x-slot name="options">
                    {{-- @if (count($categories))
                            @foreach ($categories as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        @endif --}}
                </x-slot>
            </x-select>
            <x-jet-input-error for="cotizacion_id" />
        </div>
        <div class="w-full lg:w-1/3">
            <x-label value="Moneda :" />
            <x-select class="block w-full" id="ventamoneda_id" wire:model.defer="moneda_id">
                <x-slot name="options">
                    @if (count($monedas))
                        @foreach ($monedas as $item)
                            <option value="{{ $item->id }}">{{ $item->currency }}</option>
                        @endforeach
                    @endif
                </x-slot>
            </x-select>
            <x-jet-input-error for="moneda_id" />
        </div>
    </div>

    <x-card-next titulo="Cliente" class="mt-3 border border-next-500">
        <div class="w-full flex flex-wrap lg:flex-nowrap gap-2">
            <div class="w-full lg:w-1/2">
                <x-label value="Buscar Cliente :" />
                <div id="parentv3">
                    <div class="w-full inline-flex">
                        <x-select class="block w-full" id="ventaclient_id" wire:model.defer="client_id"
                            data-dropdown-parent="#parentv3" data-placeholder="Seleccionar...">
                            <x-slot name="options">
                                @if (count($clients))
                                    @foreach ($clients as $item)
                                        <option value="{{ $item->id }}">{{ $item->document }} -
                                            {{ $item->name }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-button-add class="px-2" wire:click="$set('open', true)">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M14 19a6 6 0 0 0-12 0" />
                                <circle cx="8" cy="9" r="4" />
                                <line x1="19" x2="19" y1="8" y2="14" />
                                <line x1="22" x2="16" y1="11" y2="11" />
                            </svg>
                        </x-button-add>
                    </div>
                </div>
                <x-jet-input-error for="client_id" />
            </div>
            <div class="w-full lg:w-1/2">
                <x-label value="Dirección :" />
                <div id="parentv4">
                    <x-select class="block w-full" id="ventadireccion_id" wire:model.defer="direccion_id"
                        data-dropdown-parent="#parentv4" data-minimum-results-for-search="Infinity"
                        data-placeholder="Seleccionar...">
                        <x-slot name="options">
                            @if (count($client->direccions))
                                @foreach ($client->direccions as $item)
                                    <option value="{{ $item->id }}"
                                        @if ($loop->first) selected @endif>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            @endif
                        </x-slot>
                    </x-select>
                </div>
                <x-jet-input-error for="direccion_id" />
            </div>
        </div>
        {{-- @if ($mensaje) --}}
        <div class="w-full mt-2">
            <x-label value="Mensaje :" />
            <x-input class="block w-full" wire:model.defer="mensaje" disabled readonly />
            <x-jet-input-error for="mensaje" />
        </div>
        {{-- @endif --}}

    </x-card-next>

    <x-card-next titulo="Agregar productos" class="mt-3 border border-next-500 bg-transparent">
        <div class="w-full grid sm:grid-cols-2 lg:grid-cols-8 xl:grid-cols-6 gap-2">

            <div class="w-full sm:col-span-2 lg:col-span-8 xl:col-span-2">
                <x-label value="Descripcion producto :" />
                <x-input class="block w-full disabled:bg-gray-200" wire:model.lazy="search" />
                <x-jet-input-error for="search" />
            </div>

            <div class=" w-full lg:col-span-2 xl:col-span-1">
                <x-label value="Buscar serie :" />
                <x-input class="block w-full" wire:keydown.enter="getProductoBySerie($event.target.value)"
                    wire:model.defer="searchserie" />
                <x-jet-input-error for="searchserie" />
            </div>

            {{-- <div class=" w-full lg:col-span-2 xl:col-span-1">
                <x-label value="Categoría :" />
                <div id="parentv5">
                    <x-select class="block w-full" id="searchcategory" wire:model.defer="searchcategory"
                        data-dropdown-parent="#parentv5" name="searchcategory">
                        <x-slot name="options">
                            @if (count($categories))
                                @foreach ($categories as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            @endif
                        </x-slot>
                    </x-select>
                </div>
                <x-jet-input-error for="searchcategory" />
            </div> --}}

            <div class=" w-full lg:col-span-2 xl:col-span-1">
                <x-label value="Almacén :" />
                <div id="parentv6">
                    <x-select class="block w-full" id="searchalmacen" wire:model="searchalmacen"
                        data-dropdown-parent="#parentv6" data-minimum-results-for-search="Infinity">
                        <x-slot name="options">
                            @if (count($almacens))
                                @foreach ($almacens as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            @endif
                        </x-slot>
                    </x-select>
                </div>
                <x-jet-input-error for="searchalmacen" />
            </div>

            <div class=" w-full lg:col-span-2 xl:col-span-1">
                <x-label value="Lista precios :" />
                <div id="parentv7">
                    <x-select class="block w-full" id="ventapricetype_id" wire:model.defer="pricetype_id"
                        data-dropdown-parent="#parentv7" data-minimum-results-for-search="Infinity"
                        data-placeholder="Seleccionar...">
                        <x-slot name="options">
                            @if (count($pricetypes))
                                @foreach ($pricetypes as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->name }}</option>
                                @endforeach
                            @endif
                        </x-slot>
                    </x-select>
                </div>
                <x-jet-input-error for="pricetype_id" />
            </div>
        </div>
        <div class="w-full mt-1">
            <x-label textSize="[10px]"
                class="inline-flex items-center tracking-widest font-semibold gap-2 cursor-pointer bg-next-100 rounded-lg p-1"
                for="disponibles">
                <x-input wire:model="disponibles" name="disponibles" value="1" type="checkbox"
                    id="disponibles" />
                MOSTRAR SOLAMENTE PRODUCTOS DISPONIBLES
            </x-label>
        </div>

        @if (count($productos))
            <div class="flex gap-2 flex-wrap justify-start mt-1">
                @foreach ($productos as $item)
                    <form id="cardproduct{{ $item->id }}"
                        class="w-full bg-fondominicard flex flex-col justify-between sm:w-60 group rounded shadow shadow-shadowminicard p-1 text-xs relative hover:shadow-md hover:shadow-shadowminicard cursor-pointer"
                        wire:submit.prevent="addtocar(Object.fromEntries(new FormData($event.target)), {{ $item->id }})">
                        <div class="w-full">

                            @if (count($item->ofertasdisponibles))
                                <div
                                    class="absolute top-1 left-1 w-10 h-10 group-hover:shadow group-hover:shadow-red-500 flex flex-col items-center justify-center rounded-full bg-red-500 text-white bg-opacity-80 group-hover:bg-opacity-100 transition-all ease-in-out duration-150">
                                    <h1 class="font-semibold leading-3 text-[9px]">
                                        {{ $item->ofertasdisponibles()->first()->descuento }}%</h1>
                                    <p class="leading-3 text-[7px]">DSCT</p>
                                </div>
                            @endif

                            @if (count($item->existStock) == 0)
                                <span
                                    class="absolute @if (count($item->ofertasdisponibles)) top-12 @else top-2 @endif left-1 text-[10px] font-semibold leading-3 p-1 rounded-r-lg bg-red-500 text-white bg-opacity-80 group-hover:bg-opacity-100 transition-all ease-in-out duration-150">
                                    Agotado</span>
                            @endif

                            @if (count($item->images))
                                <div class="w-full h-60 sm:h-32 rounded shadow border">
                                    @if ($item->defaultImage)
                                        <img src="{{ asset('storage/productos/' . $item->defaultImage->first()->url) }}"
                                            alt="" class="w-full h-full object-scale-down">
                                    @else
                                        <img src="{{ asset('storage/productos/' . $item->images->first()->url) }}"
                                            alt="" class="w-full h-full object-scale-down">
                                    @endif
                                </div>
                            @endif

                            <h1 class="text-[10px] font-semibold leading-3 text-center mt-1">
                                {{ $item->name }}</h1>

                            @if (count($pricetypes))
                                @if ($pricetype_id)
                                    @foreach ($pricetypes as $lista)
                                        @if ($lista->id == $pricetype_id)
                                            @php
                                                $precios = \App\Helpers\GetPrice::getPriceProducto($item, $pricetype_id)->getData();
                                            @endphp

                                            <div class="w-full bg-transparent rounded shadow-md p-1 mt-1 border">
                                                <div class="w-full flex gap-1 items-center justify-start">
                                                    <span
                                                        class="text-[9px] font-semibold leading-3 p-1 bg-fondospancardproduct text-textspancardproduct rounded uppercase">
                                                        {{ $lista->name }}</span>

                                                    @if (count($item->ofertasdisponibles))
                                                        <p
                                                            class="text-[10px] font-semibold leading-3 p-1 text-red-500 rounded line-through">
                                                            S/.
                                                            {{ number_format($precios->pricesale, $precios->decimal, '.', ',') }}
                                                        </p>
                                                    @endif
                                                </div>

                                                @if (count($item->ofertasdisponibles))
                                                    <h1 class="mt-1 text-xs font-semibold leading-3 text-green-500">
                                                        <span class="text-[10px]">OFERTA : </span>
                                                        S/.
                                                    </h1>
                                                    <x-input class="block w-full p-2 disabled:bg-gray-200"
                                                        name="price" type="number" min="0"
                                                        step="{{ $stepDecimal }}"
                                                        value="{{ !$precios->rango ? '' : $precios->pricewithdescount }}" />
                                                @else
                                                    <h1 class="mt-1 text-xs font-semibold leading-3 text-green-500">
                                                        <span class="text-[10px]">PRECIO VENTA S/.</span>
                                                    </h1>
                                                    <x-input class="block w-full p-2 disabled:bg-gray-200"
                                                        name="price" type="number" min="0"
                                                        step="{{ $stepDecimal }}"
                                                        value="{{ !$precios->rango ? '' : $precios->pricesale }}" />
                                                @endif

                                                @if (!$precios->rango)
                                                    <small class="text-red-500 font-semibold">Rango de precio no
                                                        encontrado.</small>
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach


                                    {{-- @foreach ($pricetypes as $lista)
                                        @if ($lista->id == $pricetype_id)
                                            <div class="w-full bg-transparent rounded shadow-md p-1 mt-1 border">
                                                @if (count($lista->rangos))
                                                    @foreach ($lista->rangos as $rango)
                                                        @if ($item->pricebuy >= $rango->desde && $item->pricebuy <= $rango->hasta)
                                                            <div class="w-full flex gap-1 items-center justify-start">

                                                                <span
                                                                    class="text-[9px] font-semibold leading-3 p-1 bg-fondospancardproduct text-textspancardproduct rounded uppercase">
                                                                    {{ $lista->name }}
                                                                    ({{ $rango->incremento }} -
                                                                    {{ $rango->pivot->ganancia }}
                                                                    %)
                                                                </span>

                                                                @if (count($item->ofertasdisponibles))
                                                                    <p
                                                                        class="text-[10px] font-semibold leading-3 p-1 text-red-500 rounded line-through">
                                                                        S/.
                                                                        {{ App\Helpers\GetPrice::getPriceventa($item->pricebuy, $rango->incremento, $rango->pivot->ganancia, 0, $lista->decimalrounded, $lista->default === 1) }}
                                                                    </p>
                                                                @endif

                                                            </div>

                                                            @if (count($item->ofertasdisponibles))
                                                                <h1
                                                                    class="mt-1 text-xs font-semibold leading-3 text-green-500">
                                                                    <span class="text-[10px]">OFERTA : </span>
                                                                    S/.
                                                                </h1>
                                                                <x-input class="block w-full p-2 disabled:bg-gray-200"
                                                                    name="price" type="number" min="0"
                                                                    step="0.01"
                                                                    value="{{ App\Helpers\GetPrice::getPriceventa($item->pricebuy, $rango->incremento, $rango->pivot->ganancia, $item->ofertasdisponibles()->first()->descuento, $lista->decimalrounded, $lista->default === 1) }}" />
                                                            @else
                                                                <h1
                                                                    class="mt-1 text-xs font-semibold leading-3 text-green-500">
                                                                    <span class="text-[10px]">PRECIO VENTA S/.</span>
                                                                </h1>
                                                                <x-input class="block w-full p-2 disabled:bg-gray-200"
                                                                    name="price" type="number" min="0"
                                                                    step="0.01"
                                                                    value="{{ App\Helpers\GetPrice::getPriceventa($item->pricebuy, $rango->incremento, $rango->pivot->ganancia, 0, $lista->decimalrounded, $lista->default === 1) }}" />
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach --}}
                                @else
                                    <div class="w-full bg-white rounded shadow-md p-1 mt-1">
                                        Seleccionar Precio Lista
                                    </div>
                                @endif
                            @endif

                            @if (count($item->almacens))
                                <div class="w-full flex gap-1 flex-wrap mt-3">
                                    @foreach ($item->almacens as $almacen)
                                        <x-input-radio :for="'almacen_' . $item->id . $almacen->id" :text="$almacen->name" :cantidad="floatval($almacen->pivot->cantidad)"
                                            textSize="[10px]">
                                            <x-input class="sr-only peer" type="radio" :id="'almacen_' . $item->id . $almacen->id"
                                                :name="'almacen_' . $item->id . '[]'" value="{{ $almacen->id }}" />
                                        </x-input-radio>
                                    @endforeach
                                </div>
                            @endif

                        </div>

                        <div class="w-full flex items-end gap-1 justify-end mt-2">

                            @if (count($item->seriesdisponibles))
                                <div class="w-full">
                                    <small class="w-full block">Ingresar serie</small>
                                    <x-input class="block w-full p-2 disabled:bg-gray-200" name="serie" />
                                </div>
                            @else
                                <div class="w-full">
                                    <small class="w-full block">Cantidad</small>
                                    <x-input class="block w-full p-2 disabled:bg-gray-200" name="cantidad"
                                        type="number" min="0" value="1" step="0.5" />
                                </div>
                            @endif

                            <x-button-add-car type="submit" wire:loading.attr="disabled" wire:target="addtocar">
                            </x-button-add-car>

                        </div>

                        <x-jet-input-error for="cart.{{ $item->id }}.price" />
                        <x-jet-input-error for="cart.{{ $item->id }}.almacen_id" />
                        <x-jet-input-error for="cart.{{ $item->id }}.serie" />
                        <x-jet-input-error for="cart.{{ $item->id }}.cantidad" />
                    </form>
                @endforeach
            </div>
        @endif
    </x-card-next>


    <div class="w-full text-center">
        <p wire:loading class="py-2 text-xs tracking-widest shadow-lg text-next-500 rounded-lg bg-white p-1 px-2">
            Cargando...</p>
    </div>

    @if (count($carrito))
        <x-card-next titulo="Carrito compras" class="mt-3 border border-next-500 bg-transparent">
            <div class="flex gap-2 flex-wrap justify-start mt-1">
                @foreach ($carrito as $item)
                    <div
                        class="w-full bg-fondominicard flex flex-col justify-between sm:w-60 group rounded shadow shadow-shadowminicard p-1 text-xs relative hover:shadow-md hover:shadow-shadowminicard cursor-pointer">
                        <div class="w-full">

                            @if ($item->status == 0)
                                <span
                                    class="absolute top-2 left-1 text-[10px] font-semibold leading-3 p-1 rounded-r-lg bg-orange-500 text-white bg-opacity-80 group-hover:bg-opacity-100 transition-all ease-in-out duration-150">
                                    Pendiente Pago</span>
                            @endif

                            @if (count($item->producto->images))
                                <div class="w-full h-60 sm:h-32 rounded shadow border">
                                    @if ($item->producto->defaultImage)
                                        <img src="{{ asset('storage/productos/' . $item->producto->defaultImage->first()->url) }}"
                                            alt="" class="w-full h-full object-scale-down">
                                    @else
                                        <img src="{{ asset('storage/productos/' . $item->producto->images->first()->url) }}"
                                            alt="" class="w-full h-full object-scale-down">
                                    @endif
                                </div>
                            @endif

                            <h1 class="text-[10px] font-semibold leading-3 text-center mt-1">
                                {{ $item->producto->name }}</h1>


                            <h1 class="mt-1 text-center text-xs font-semibold leading-3 text-green-500">
                                <span class="text-xs">
                                    S/. {{ number_format($item->total, 2) }}
                                </span>
                            </h1>

                            <div class="w-full flex gap-1 items-start mt-2">
                                <span
                                    class="text-[9px] font-semibold leading-3 p-1 bg-fondospancardproduct text-textspancardproduct rounded uppercase">
                                    P. UNIT:
                                    {{-- {{ $item->moneda->simbolo }} --}}
                                    {{ number_format($item->price, 2, '.', ', ') }}
                                </span>
                                <span
                                    class="text-[9px] font-semibold leading-3 p-1 bg-fondospancardproduct text-textspancardproduct rounded uppercase">
                                    {{ \App\Helpers\FormatoPersonalizado::getValue($item->cantidad) }}
                                    {{ $item->producto->unit->name }}
                                </span>
                                <span
                                    class="text-[9px] font-semibold leading-3 p-1 bg-fondospancardproduct text-textspancardproduct rounded uppercase">
                                    {{ $item->almacen->name }}
                                </span>
                            </div>
                        </div>



                        @if (count($item->carshoopseries))
                            <h1 class="w-full block text-[10px] mt-2">SERIES</h1>
                            <div class="w-full flex flex-wrap gap-1">
                                @foreach ($item->carshoopseries as $shoopserie)
                                    <span
                                        class="text-[8px] font-semibold rounded py-0.5 px-1 inline-flex gap-1 items-center bg-fondospancardproduct text-textspancardproduct">
                                        {{ $shoopserie->serie->serie }}
                                        <x-button-delete></x-button-delete>
                                    </span>
                                @endforeach
                            </div>
                        @endif

                        <div class="w-full flex items-end gap-1 justify-end mt-2">
                            <x-button-delete wire:click="deleteitemcart({{ $item->id }})"
                                wire:loading.attr="disabled" wire:target="deleteitemcart"></x-button-delete>

                        </div>
                    </div>
                @endforeach
            </div>
        </x-card-next>
    @endif

    <div class="w-full @if (count($carrito)) block @else hidden @endif">
        <x-card-next titulo="Resumen compra" class="mt-3 border border-next-500 bg-transparent">
            <p class="text-[10px]">TOTAL EXONERADO : S/. <span
                    class="font-bold text-xs">{{ number_format($total, 2, '.', ', ') }}</span></p>
            <p class="text-[10px]">TOTAL GRAVADO : S/. <span class="font-bold text-xs">0.00</span></p>
            <p class="text-[10px]">TOTAL IGV : S/. <span class="font-bold text-xs">0.00</span></p>
            <p class="text-[10px]">TOTAL DESCUENTOS : S/. <span class="font-bold text-xs">0.00</span></p>
            <p class="text-[10px]">TOTAL PAGAR : S/. <span
                    class="font-bold text-xs">{{ number_format($total, 2, '.', ', ') }}</span></p>
            @if ($increment)
                <p class="text-[10px]">TOTAL PAGAR + INCREMENTO ({{ number_format($increment, 2, '.', ', ') }} %) :
                    S/.
                    <span class="font-bold text-xs">{{ number_format($totalIncrement, 2, '.', ', ') }}</span>
                </p>
            @endif

        </x-card-next>
    </div>

    <div class="w-full @if (count($carrito)) block @else hidden @endif">
        <form wire:submit.prevent="save">
            <x-card-next titulo="Generar comprobante electrónico" class="mt-3 border border-next-500 bg-transparent">
                @if (count($typecomprobantes))
                    <div class="w-full flex item-center flex-wrap gap-1">
                        @foreach ($typecomprobantes as $item)
                            <x-input-radio for="typecomprobante_{{ $item->id }}" :text="$item->descripcion . ' [' . $item->serie . ']'">
                                <input wire:model.defer="typecomprobante_id" class="sr-only peer" type="radio"
                                    id="typecomprobante_{{ $item->id }}" name="typecomprobante"
                                    value="{{ $item->id }}" />
                            </x-input-radio>
                        @endforeach
                    </div>
                    <x-jet-input-error for="typecomprobante_id" />

                    <div class="w-full flex flex-wrap md:flex-nowrap gap-3 mt-2">
                        <div class="w-full lg:w-1/3">
                            <x-label value="Tipo pago :" />
                            <x-select class="block w-full" id="ventatypepayment_id" wire:model="typepayment_id"
                                data-minimum-results-for-search="Infinity" data-placeholder="Seleccionar...">
                                <x-slot name="options">
                                    @if (count($typepayments))
                                        @foreach ($typepayments as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    @endif
                                </x-slot>
                            </x-select>
                            <x-jet-input-error for="typepayment_id" />
                        </div>

                        @if ($typepayment)
                            @if ($typepayment->paycuotas)
                                <div class="w-full lg:w-1/3">
                                    <x-label value="Incrementar valor venta :" />
                                    <x-input class="block w-full" type="number" min="0" step="0.01"
                                        wire:model.defer="increment" />
                                    <x-jet-input-error for="increment" />
                                </div>
                                <div class="w-full lg:w-1/3">
                                    <x-label value="Cuotas :" />
                                    <div class="w-full inline-flex">
                                        <x-input class="block w-full" type="number" min="1" step="1"
                                            max="10" wire:keydown.enter="calcular_cuotas"
                                            wire:model.defer="countcuotas" />
                                        <x-button class="px-2 py-1.5" wire:click="calcular_cuotas"
                                            wire:loading.attr="disabled" wire:target="calcular_cuotas">
                                            CALCULAR
                                        </x-button>
                                    </div>
                                    <x-jet-input-error for="countcuotas" />
                                </div>
                            @endif
                        @endif
                    </div>
                    @if ($typepayment)
                        @if ($typepayment->paycuotas)
                            <div class="w-full flex flex-wrap gap-3 mt-2">
                                {{-- {{ print_r($cuotas) }} --}}
                                @if (count($cuotas))
                                    @foreach ($cuotas as $item)
                                        <div class="w-48 rounded p-1 border shadow-md hover:shadow-lg">
                                            <h1 class="text-xs font-semibold text-center">
                                                Cuota{{ substr('000' . $item['cuota'], -3) }}</h1>
                                            <x-label value="Fecha pago :" textSize="[10px]" />
                                            <x-input class="block w-full" type="date"
                                                wire:model="cuotas.{{ $loop->iteration - 1 }}.date" />
                                            <x-label value="Monto Cuota :" textSize="[10px]" />
                                            <x-input class="block w-full" type="number" min="1"
                                                step="0.01"
                                                wire:model="cuotas.{{ $loop->iteration - 1 }}.amount" />
                                            {{-- {{ $item['suma'] }} --}}
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <x-jet-input-error for="cuotas" />
                        @endif
                    @endif
                @endif

            </x-card-next>

            <div class="w-full text-center mt-1" wire:loading wire:target="calcular_cuotas">
                <p
                    class="inline-block py-2 text-xs tracking-widest shadow-lg text-next-500 rounded-lg bg-white p-1 px-2">
                    Calculando cuotas...</p>
            </div>

            <div class="w-full  @if ($typepayment->paycuotas) hidden @else block @endif">
                <x-card-next titulo="Datos pago" class="mt-3 border border-next-500 bg-transparent">
                    <div
                        class="w-full grid grid-cols-1 @if (count($accounts) > 1) md:grid-cols-2 xl:grid-cols-4 @else lg:grid-cols-3 @endif gap-3">
                        <div class="w-full">
                            <x-label value="Seleccionar caja :" />
                            <div id="parentv8">
                                <x-select class="block w-full" id="ventacaja_id" wire:model.defer="opencaja_id"
                                    data-dropdown-parent="#parentv8" data-minimum-results-for-search="Infinity"
                                    data-placeholder="Seleccionar...">
                                    <x-slot name="options">
                                        @if (count($opencajas))
                                            @foreach ($opencajas as $item)
                                                <option value="{{ $item->id }}">{{ $item->caja->name }}
                                                    ({{ $item->user->name }})
                                                </option>
                                            @endforeach
                                        @endif
                                    </x-slot>
                                </x-select>
                            </div>
                            <x-jet-input-error for="opencaja_id" />
                        </div>
                        <div class="w-full">
                            <x-label value="Método pago :" />
                            <div id="parentv9">
                                <x-select class="block w-full" id="ventamethodpayment_id"
                                    wire:model.defer="methodpayment_id" data-dropdown-parent="#parentv9"
                                    data-minimum-results-for-search="Infinity" data-placeholder="Seleccionar...">
                                    <x-slot name="options">
                                        @if (count($methodpayments))
                                            @foreach ($methodpayments as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        @endif
                                    </x-slot>
                                </x-select>
                            </div>
                            <x-jet-input-error for="methodpayment_id" />
                        </div>


                        <div class="w-full @if (count($accounts) > 1) block @else hidden @endif">
                            <x-label value="Cuenta pago :" />
                            <div id="parentv10">
                                <x-select class="block w-full" id="ventacuenta_id" wire:model.defer="cuenta_id"
                                    data-dropdown-parent="#parentv10" data-minimum-results-for-search="Infinity"
                                    data-placeholder="Seleccionar...">
                                    <x-slot name="options">
                                        @if (count($accounts))
                                            @foreach ($accounts as $item)
                                                <option value="{{ $item->id }}">{{ $item->account }}
                                                    ({{ $item->descripcion }})
                                                </option>
                                            @endforeach
                                        @endif
                                    </x-slot>
                                </x-select>
                            </div>
                            <x-jet-input-error for="cuenta_id" />
                        </div>


                        <div class="w-full">
                            <x-label value="Detalle pago :" />
                            <x-input class="block w-full" wire:model.defer="detallepago" />
                            <x-jet-input-error for="detallepago" />
                        </div>
                    </div>
                </x-card-next>
            </div>

            <div class="mt-3 text-center">

                {{-- <x-jet-input-error for="countcuotas" />
                <x-jet-input-error for="cuotas" />
                <x-jet-input-error for="detallepago" />
                <x-jet-input-error for="increment" /> --}}

                <x-jet-input-error for="empresa_id" />
                <x-jet-input-error for="client_id" />
                <x-jet-input-error for="concept_id" />
                <x-jet-input-error for="typemovement_id" />
                <x-jet-input-error for="tribute_id" />
                <x-jet-input-error for="carrito" />
            </div>

            <div class="w-full flex flex-row mt-2 gap-2 justify-end text-right">
                <x-button type="submit" size="xs" class="" wire:loading.attr="disabled"
                    wire:target="save">
                    {{ __('REGISTRAR') }}
                    @if (count($carrito))
                        <span
                            class="absolute -top-2 -left-2 block w-4 h-4 p-0.5 leading-3 bg-next-500 ring-1 ring-white rounded-full text-[10px] animate-bounce">
                            {{ count($carrito) }}</span>
                    @endif
                </x-button>
            </div>
        </form>
    </div>


    <x-jet-dialog-modal wire:model="open" maxWidth="3xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Nuevo cliente') }}
            <x-button-add wire:click="$toggle('open')" wire:loading.attr="disabled">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </x-button-add>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="savecliente" id="form_ventacreate_client">
                <div class="w-full sm:grid grid-cols-3 gap-2">
                    <div class="w-full">
                        <x-label value="DNI / RUC :" />
                        <div class="w-full inline-flex">
                            <x-input class="block w-full" wire:model="document" minlength="0" maxlength="11" />
                            <x-button-add class="px-2" wire:click="">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M14 19a6 6 0 0 0-12 0" />
                                    <circle cx="8" cy="9" r="4" />
                                    <line x1="19" x2="19" y1="8" y2="14" />
                                    <line x1="22" x2="16" y1="11" y2="11" />
                                </svg>
                            </x-button-add>
                        </div>
                        <x-jet-input-error for="document" />
                    </div>
                    <div class="w-full sm:col-span-2 mt-2 sm:mt-0">
                        <x-label value="Cliente (Razón Social) :" />
                        <x-input class="block w-full" wire:model.defer="nameclient"
                            placeholder="Nombres / razón social del cliente" />
                        <x-jet-input-error for="nameclient" />
                    </div>
                    <div class="w-full mt-2 sm:mt-0">
                        <x-label value="Ubigeo :" />
                        <x-select class="block w-full" wire:model.defer="ubigeo_id" id="newclientventaubigeo_id">
                            <x-slot name="options">

                            </x-slot>
                        </x-select>
                        <x-jet-input-error for="ubigeo_id" />
                    </div>
                    <div class="w-full sm:col-span-2 mt-2 sm:mt-0">
                        <x-label value="Dirección :" />
                        <x-input class="block w-full" wire:model.defer="direccion"
                            placeholder="Dirección del cliente..." />
                        <x-jet-input-error for="direccion" />
                    </div>
                    <div class="w-full mt-2 sm:mt-0">
                        <x-label value="Correo :" />
                        <x-input class="block w-full" wire:model.defer="email" placeholder="Correo del cliente..."
                            type="email" />
                        <x-jet-input-error for="email" />
                    </div>
                    <div class="w-full mt-2 sm:mt-0">
                        <x-label value="Género :" />
                        <x-select class="block w-full" wire:model.defer="sexo" id="newclientventasexo"
                            data-minimum-results-for-search="Infinity" data-placeholder="Seleccionar...">
                            <x-slot name="options">
                                <option value="E">EMPRESARIAL</option>
                                <option value="M">MASCULINO</option>
                                <option value="F">FEMENINO</option>
                            </x-slot>
                        </x-select>
                        <x-jet-input-error for="sexo" />
                    </div>
                    <div class="w-full mt-2 sm:mt-0">
                        <x-label value="Fecha nacimiento :" />
                        <x-input type="date" class="block w-full" wire:model.defer="nacimiento" />
                        <x-jet-input-error for="nacimiento" />
                    </div>
                    <div class="w-full mt-2 sm:mt-0">
                        <x-label value="Lista precio :" />
                        <x-select class="block w-full" wire:model.defer="pricetypeclient_id"
                            id="newclientventapricetype_id" data-minimum-results-for-search="Infinity"
                            data-placeholder="Seleccionar...">
                            <x-slot name="options">
                                @if (count($pricetypes))
                                    @foreach ($pricetypes as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-jet-input-error for="pricetypeclient_id" />
                    </div>
                    <div class="w-full mt-2 sm:mt-0">
                        <x-label value="Canal registro :" />
                        <x-select class="block w-full" wire:model.defer="channelsale_id"
                            id="newclientventachannelsale_id" data-minimum-results-for-search="Infinity"
                            data-placeholder="Seleccionar...">
                            <x-slot name="options">
                                @if (count($channelsales))
                                    @foreach ($channelsales as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-jet-input-error for="channelsale_id" />
                    </div>
                    <div class="w-full mt-2 sm:mt-0">
                        <x-label value="Teléfono :" />
                        <x-input class="block w-full" wire:model.defer="telefono" placeholder="+51 999 999 999"
                            maxlength="9" />
                        <x-jet-input-error for="telefono" />
                    </div>
                </div>

                <div class="w-full flex flex-row pt-4 gap-2 justify-end text-right">
                    <x-button type="submit" size="xs" class="" wire:loading.attr="disabled"
                        wire:target="savecliente">
                        {{ __('REGISTRAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>


    <script>
        document.addEventListener("DOMContentLoaded", () => {

            renderSelect2();

            $("#ventamoneda_id").on("change", (e) => {
                deshabilitarSelects();
                @this.moneda_id = e.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });

            $("#ventaclient_id").on("change", (e) => {
                deshabilitarSelects();
                @this.client_id = e.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });

            $("#ventadireccion_id").on("change", (e) => {
                deshabilitarSelects();
                @this.direccion_id = e.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });

            $("#ventaproducto_id").on("change", (e) => {
                deshabilitarSelects();
                @this.producto_id = e.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });

            $("#searchcategory").on("change", (e) => {
                // e.target.setAttribute("disabled", true);
                deshabilitarSelects();
                @this.searchcategory = e.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });

            $("#searchalmacen").on("change", (e) => {
                deshabilitarSelects();
                @this.searchalmacen = e.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });

            $("#ventapricetype_id").on("change", (e) => {
                deshabilitarSelects();
                @this.pricetype_id = e.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });

            $("#ventatypepayment_id").on("change", (e) => {
                deshabilitarSelects();
                @this.typepayment_id = e.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });

            $("#ventacaja_id").on("change", (e) => {
                deshabilitarSelects();
                @this.opencaja_id = e.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });

            $("#ventamethodpayment_id").on("change", (e) => {
                deshabilitarSelects();
                @this.methodpayment_id = e.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });

            $("#ventacuenta_id").on("change", (e) => {
                deshabilitarSelects();
                @this.cuenta_id = e.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });


            $("#newclientventaubigeo_id").on("change", (e) => {
                deshabilitarSelects();
                @this.ubigeo_id = e.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });

            $("#newclientventasexo").on("change", (e) => {
                deshabilitarSelects();
                @this.sexo = e.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });

            $("#newclientventapricetype_id").on("change", (e) => {
                deshabilitarSelects();
                @this.pricetypeclient_id = e.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });

            $("#newclientventachannelsale_id").on("change", (e) => {
                deshabilitarSelects();
                @this.channelsale_id = e.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });

            window.addEventListener('render-ventas-select2', () => {
                renderSelect2();
            });

            window.addEventListener('reset-form', data => {
                document.getElementById("cardproduct" + data.detail).reset();
            });

            function renderSelect2() {
                var formulario = document.getElementById("form_create_venta");
                var selects = formulario.getElementsByTagName("select");

                for (var i = 0; i < selects.length; i++) {
                    if (selects[i].id !== "") {
                        $("#" + selects[i].id).select2();
                    }
                }
            }

            function deshabilitarSelects() {
                var formulario = document.getElementById("form_create_venta");
                var selects = formulario.getElementsByTagName("select");

                for (var i = 0; i < selects.length; i++) {
                    selects[i].disabled = true;
                }
            }

        })
    </script>
</div>
