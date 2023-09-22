<div class="mt-3" id="form_create_venta">

    <x-title-next titulo="Registrar venta" class="mb-5" />

    <div class="w-full flex flex-wrap md:flex-nowrap gap-3">
        <div class="w-full lg:w-1/3">
            <x-label value="Vincular cotización :" />
            <x-select class="block w-full" id="cotizacion_id" wire:model.defer="cotizacion_id"
                data-placeholder="Seleccionar..." data-minimum-results-for-search="3">
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
            <x-select class="block w-full" id="ventamoneda_id" wire:model.defer="moneda_id"
                data-placeholder="Seleccionar..." data-minimum-results-for-search="Infinity">
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

    <div class="w-full flex flex-col gap-3 lg:flex-row mt-3">
        <div class="w-full flex-shrink">
            <x-card-next titulo="Cliente" class="border border-next-500">

                <div class="w-full flex flex-wrap md:flex-nowrap gap-2">
                    <div class="w-full md:flex-shrink-0 md:w-60 lg:w-80">
                        <x-label value="DNI / RUC :" />
                        <div class="w-full inline-flex">
                            <x-input class="block w-full" wire:model.defer="document" minlength="0" maxlength="11" />
                            <x-button-add class="px-2" wire:click="getClient" wire:target="getClient"
                                wire:loading.attr="disable">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8" />
                                    <path d="m21 21-4.3-4.3" />
                                </svg>
                            </x-button-add>
                        </div>
                        <x-jet-input-error for="document" />
                    </div>
                    <div class="w-full md:flex-shrink-1">
                        <x-label value="Cliente (Razón Social) :" />
                        <x-input class="block w-full" wire:model.defer="name"
                            placeholder="Nombres / razón social del cliente" />
                        <x-jet-input-error for="name" />
                    </div>
                </div>

                <div class="w-full flex flex-wrap md:flex-nowrap gap-2 mt-2">
                    <div class="w-full md:w-2/3">
                        <x-label value="Dirección :" />
                        <x-input class="block w-full" wire:model.defer="direccion"
                            placeholder="Dirección del cliente" />
                        <x-jet-input-error for="direccion" />
                    </div>
                    @if (isset($empresa->id))
                        @if ($empresa->uselistprice)
                            <div class="w-full md:w-1/3">
                                <x-label value="Lista precio asignado :" />
                                <x-input class="block w-full" wire:model.defer="pricetypeasigned" disabled readonly />
                            </div>
                        @endif
                    @endif
                </div>

                @if ($mensaje)
                    <div class="w-full mt-2">
                        <x-label value="Mensaje :" />
                        <x-input class="block w-full" wire:model.defer="mensaje" disabled readonly />
                        <x-jet-input-error for="mensaje" />
                    </div>
                @endif
            </x-card-next>

            <x-card-next titulo="Agregar productos" class="mt-3 border border-next-500 bg-transparent">

                @if (isset($empresa->id))
                    @if (!$empresa->uselistprice || ($empresa->uselistprice && count($pricetypes)))
                        <div class="w-full grid sm:grid-cols-6 xl:grid-cols-5 gap-1 gap-x-2">

                            <div class="w-full sm:col-span-4 xl:col-span-2">
                                <x-label value="Descripcion producto :" />
                                <x-input class="block w-full disabled:bg-gray-200" wire:model.lazy="search" />
                                <x-jet-input-error for="search" />
                            </div>

                            <div class=" w-full sm:col-span-2 xl:col-span-1">
                                <x-label value="Buscar serie :" />
                                <x-input class="block w-full"
                                    wire:keydown.enter="getProductoBySerie($event.target.value)"
                                    wire:model.defer="searchserie" />
                                <x-jet-input-error for="searchserie" />
                            </div>

                            <div class=" w-full sm:col-span-2 xl:col-span-1">
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
                            </div>

                            @if ($empresa->uselistprice)
                                <div class=" w-full sm:col-span-2 xl:col-span-1">
                                    <x-label value="Lista precios :" />
                                    <div id="parentv7">
                                        <x-select class="block w-full" id="ventapricetype_id"
                                            wire:model.defer="pricetype_id" data-dropdown-parent="#parentv7"
                                            data-minimum-results-for-search="Infinity"
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
                            @endif
                        </div>

                        <div class="w-full mt-1">
                            <x-label-check for="disponibles">
                                <x-input wire:model="disponibles" name="disponibles" value="1" type="checkbox"
                                    id="disponibles" />
                                MOSTRAR SOLO DISPONIBLES
                            </x-label-check>

                            {{-- <x-label textSize="[10px]"
                                class="inline-flex items-center tracking-widest font-semibold gap-2 cursor-pointer bg-next-100 rounded-lg p-1"
                                for="disponibles">
                                <x-input wire:model="disponibles" name="disponibles" value="1" type="checkbox"
                                    id="disponibles" />
                                MOSTRAR SOLO DISPONIBLES
                            </x-label> --}}
                        </div>

                        <x-loading-next wire:loading
                            wire:target="search,disponibles,loadbycategory,loadprice,getClient" />

                        <div wire:init="loadProductos" wire:loading.remove
                            wire:target="search,disponibles,loadbycategory,loadprice,getClient">

                            @if (count($productos))
                                <div class="w-full py-2">
                                    {{ $productos->links() }}
                                </div>

                                <div class="flex gap-2 flex-wrap justify-between mt-1">
                                    @foreach ($productos as $item)
                                        <form id="cardproduct{{ $item->id }}"
                                            class="w-full bg-fondominicard flex flex-col justify-between sm:w-60 group rounded shadow shadow-shadowminicard p-1 text-xs relative hover:shadow-md hover:shadow-shadowminicard"
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
                                                    <div
                                                        class="w-full h-60 sm:h-32 rounded shadow border border-borderminicard">
                                                        @if (count($item->defaultImage))
                                                            <img src="{{ asset('storage/productos/' . $item->defaultImage->first()->url) }}"
                                                                alt=""
                                                                class="w-full h-full object-scale-down">
                                                        @else
                                                            <img src="{{ asset('storage/productos/' . $item->images->first()->url) }}"
                                                                alt=""
                                                                class="w-full h-full object-scale-down">
                                                        @endif
                                                    </div>
                                                @endif

                                                <h1 class="text-[10px] font-semibold leading-3 text-center mt-1">
                                                    {{ $item->name }}</h1>

                                                @php
                                                    $precios = \App\Helpers\GetPrice::getPriceProducto($item, $empresa->uselistprice ? $pricetype_id : null, $empresa->usepricedolar, $empresa->tipocambio)->getData();
                                                @endphp

                                                <div
                                                    class="w-full bg-transparent rounded shadow-md p-1 mt-1 border border-borderminicard">
                                                    {{-- <p>{{ var_dump($precios) }}</p> --}}

                                                    <div class="w-full flex gap-1 items-center justify-start">
                                                        @if ($empresa->uselistprice)
                                                            <span
                                                                class="text-[9px] font-semibold leading-3 p-1 bg-fondospancardproduct text-textspancardproduct rounded uppercase">
                                                                {{ $pricetype->name }}</span>
                                                        @endif
                                                    </div>

                                                    @if ($empresa->usepricedolar && $empresa->viewpricedolar)
                                                        <div
                                                            class="w-full flex font-semibold gap-1 items-center justify-between mt-1">
                                                            <h1 class="text-xs font-semibold leading-3 text-green-500">
                                                                <small>PRECIO DOLÁRES : </small> $.
                                                                {{ number_format($precios->pricewithdescountDolar, $precios->decimal, '.', ', ') }}
                                                            </h1>

                                                            @if (count($item->ofertasdisponibles))
                                                                <p
                                                                    class="text-[10px] inline-block leading-3 bg-red-100 p-0.5 rounded text-red-500">
                                                                    <small>ANTES : </small>$.
                                                                    {{ number_format($precios->priceDolar, $precios->decimal, '.', ', ') }}
                                                                </p>
                                                            @endif
                                                        </div>
                                                    @endif


                                                    @if (count($item->ofertasdisponibles))
                                                        <div
                                                            class="w-full flex font-semibold gap-1 items-center justify-between mt-1">
                                                            <h1 class="text-xs leading-3 text-green-500">
                                                                <small>PRECIO SOLES : </small>
                                                            </h1>

                                                            <p
                                                                class="text-[10px] inline-block leading-3 bg-red-100 p-0.5 rounded text-red-500">
                                                                <small>ANTES : </small>S/.
                                                                {{ number_format($precios->pricesale, $precios->decimal, '.', ', ') }}
                                                            </p>
                                                        </div>

                                                        <x-input class="block w-full p-2 disabled:bg-gray-200"
                                                            name="price" type="number" min="0"
                                                            step="0.0001"
                                                            value="{{ $precios->pricewithdescount }}" />
                                                    @else
                                                        <h1
                                                            class="text-xs font-semibold leading-3 text-green-500 mt-1">
                                                            <small>PRECIO SOLES: </small>
                                                            {{-- S/. {{ $precios->pricesale }} --}}
                                                        </h1>

                                                        <x-input class="block w-full p-2 disabled:bg-gray-200"
                                                            name="price" type="number" min="0"
                                                            step="0.0001"
                                                            value="{{ $precios->pricemanual ?? $precios->pricesale }}" />
                                                    @endif

                                                    @if ($empresa->uselistprice)
                                                        @if (!$precios->existsrango)
                                                            <small
                                                                class="text-red-500 bg-red-50 p-0.5 rounded font-semibold inline-block mt-1">
                                                                Rango de precio no disponible <a class="underline px-1"
                                                                    href="#">REGISTRAR</a></small>
                                                        @endif
                                                    @endif
                                                </div>

                                                @if (count($item->almacens))
                                                    <div class="w-full flex gap-1 flex-wrap mt-3">
                                                        @foreach ($item->almacens as $almacen)
                                                            <x-input-radio :for="'almacen_' . $item->id . $almacen->id" :text="$almacen->name"
                                                                :cantidad="floatval($almacen->pivot->cantidad)" textSize="[10px]">
                                                                <x-input class="sr-only peer" type="radio"
                                                                    :id="'almacen_' . $item->id . $almacen->id" :name="'almacen_' . $item->id . '[]'"
                                                                    value="{{ $almacen->id }}" />
                                                            </x-input-radio>
                                                        @endforeach
                                                    </div>
                                                @endif

                                            </div>

                                            {{-- @if ($empresa->uselistprice) --}}
                                            @if (!$empresa->uselistprice || $precios->pricesale > 0)
                                                <div class="w-full flex items-end gap-1 justify-end mt-2">
                                                    @if (count($item->seriesdisponibles))
                                                        <div class="w-full">
                                                            <small class="w-full block">Ingresar serie</small>
                                                            <x-input class="block w-full p-2 disabled:bg-gray-200"
                                                                name="serie" />
                                                        </div>
                                                    @else
                                                        <div class="w-full">
                                                            <small class="w-full block">Cantidad</small>
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

                                            <x-jet-input-error for="cart.{{ $item->id }}.price" />
                                            <x-jet-input-error for="cart.{{ $item->id }}.almacen_id" />
                                            <x-jet-input-error for="cart.{{ $item->id }}.serie" />
                                            <x-jet-input-error for="cart.{{ $item->id }}.cantidad" />
                                        </form>
                                    @endforeach
                                </div>
                            @else
                                <x-loading-next wire:loading wire:target="loadProductos" />
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
            </x-card-next>
        </div>

        <div
            class="@if (count($carrito)) w-full block lg:w-80 xl:w-96 flex-shrink-0 @else hidden @endif ">

            <div class="w-full @if (count($carrito)) block @else hidden @endif">
                <form wire:submit.prevent="save">
                    <x-card-next titulo="Generar comprobante electrónico"
                        class="border border-next-500 bg-transparent">
                        @if (count($typecomprobantes))
                            <div class="w-full flex item-center flex-wrap gap-1">
                                @foreach ($typecomprobantes as $item)
                                    <x-input-radio textSize="[10px]" for="typecomprobante_{{ $item->id }}"
                                        :text="$item->descripcion . ' [' . $item->serie . ']'">
                                        <input wire:model.defer="typecomprobante_id" class="sr-only peer"
                                            type="radio" id="typecomprobante_{{ $item->id }}"
                                            name="typecomprobante" value="{{ $item->id }}" />
                                    </x-input-radio>
                                @endforeach
                            </div>
                            <x-jet-input-error for="typecomprobante_id" />

                            <div class="w-full flex flex-wrap lg:flex-col md:flex-nowrap gap-3 lg:gap-1 mt-2">
                                <div class="w-full lg:w-full">
                                    <x-label value="Tipo pago :" />
                                    <x-select class="block w-full" id="ventatypepayment_id"
                                        wire:model="typepayment_id" data-minimum-results-for-search="Infinity"
                                        data-placeholder="Seleccionar...">
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
                                        <div class="w-full lg:w-full">
                                            <x-label value="Incrementar venta (%):" />
                                            <x-input class="block w-full" type="number" min="0"
                                                step="0.10" wire:model="increment" />
                                            <x-jet-input-error for="increment" />
                                        </div>
                                        <div class="w-full lg:w-full">
                                            <x-label value="Cuotas :" />
                                            <div class="w-full inline-flex">
                                                <x-input class="block w-full" type="number" min="1"
                                                    step="1" max="10" {{-- wire:keydown.enter="calcular_cuotas" --}}
                                                    wire:model.defer="countcuotas" />
                                                {{-- <x-button class="px-2 py-1.5" wire:click="calcular_cuotas"
                                                    wire:loading.attr="disabled" wire:target="calcular_cuotas">
                                                    CALCULAR
                                                </x-button> --}}
                                            </div>
                                            <x-jet-input-error for="countcuotas" />
                                        </div>
                                    @endif
                                @else
                                    <p class="text-colorerror text-xs font-semibold bg-red-100 p-0.5 rounded">
                                        Seleccione tipo de pago.</p>
                                @endif
                            </div>
                            {{-- @if ($typepayment)
                                @if ($typepayment->paycuotas)

                                    <x-loading-next wire:loading wire:target="calcular_cuotas" />

                                    <div wire:loading.remove wire:target="calcular_cuotas"
                                        class="w-full flex flex-wrap gap-3 mt-2">
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
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <x-jet-input-error for="cuotas" />
                                @endif
                            @endif --}}
                        @else
                            <p class="text-colorerror text-xs font-semibold bg-red-100 p-0.5 rounded">
                                No existen tipos de comprobantes a generar.</p>
                        @endif

                        @if ($typepayment)
                            <div class="w-full @if ($typepayment->paycuotas) hidden @else block @endif">
                                <div
                                    class="w-full grid grid-cols-1 mt-1 @if (count($accounts) > 1) md:grid-cols-2 lg:grid-cols-1 @else lg:grid-cols-1 @endif gap-3 lg:gap-1">
                                    <div class="w-full">
                                        <x-label value="Método pago :" />
                                        <div id="parentv9">
                                            <x-select class="block w-full" id="ventamethodpayment_id"
                                                wire:model.defer="methodpayment_id" data-dropdown-parent="#parentv9"
                                                data-minimum-results-for-search="Infinity"
                                                data-placeholder="Seleccionar...">
                                                <x-slot name="options">
                                                    @if (count($methodpayments))
                                                        @foreach ($methodpayments as $item)
                                                            <option value="{{ $item->id }}">{{ $item->name }}
                                                            </option>
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
                                            <x-select class="block w-full" id="ventacuenta_id"
                                                wire:model.defer="cuenta_id" data-dropdown-parent="#parentv10"
                                                data-minimum-results-for-search="Infinity"
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
                            </div>
                        @endif

                    </x-card-next>

                    <div wire:loading.remove wire:target="increment"
                        class="w-full @if (count($carrito)) block @else hidden @endif">
                        <x-card-next titulo="Resumen compra" class="mt-3 border border-next-500 bg-transparent">
                            <p class="text-[10px]">TOTAL EXONERADO : S/. <span
                                    class="font-bold text-xs">{{ number_format($total, 2, '.', ', ') }}</span></p>
                            <p class="text-[10px]">TOTAL GRAVADO : S/. <span class="font-bold text-xs">0.00</span></p>
                            <p class="text-[10px]">TOTAL IGV : S/. <span class="font-bold text-xs">0.00</span></p>
                            <p class="text-[10px]">TOTAL DESCUENTOS : S/. <span class="font-bold text-xs">0.00</span>
                            </p>
                            <p class="text-[10px]">TOTAL PAGAR : S/. <span
                                    class="font-bold text-xs">{{ number_format($total, 2, '.', ', ') }}</span></p>
                            @if ($increment)
                                <p class="text-[10px]">TOTAL PAGAR +
                                    ({{ number_format($increment, 2, '.', ', ') }}% INCREMENTO)
                                    :
                                    S/.
                                    <span
                                        class="font-bold text-xs">{{ number_format($totalIncrement, 2, '.', ', ') }}</span>
                                </p>
                            @endif
                        </x-card-next>
                    </div>

                    {{-- <div class="w-full  @if ($typepayment->paycuotas) hidden @else block @endif"> --}}
                    {{-- <x-card-next titulo="Datos pago" class="mt-3 border border-next-500 bg-transparent"> --}}

                    {{-- </x-card-next> --}}
                    {{-- </div> --}}

                    <div class="mt-3 text-left">

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <div>
                                        <p
                                            class="text-colorerror text-xs inline-block font-semibold bg-red-100 p-0.5 rounded mt-1">
                                            {{ $error }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- <x-jet-input-error for="document" />
                        <x-jet-input-error for="name" />
                        <x-jet-input-error for="client_id" />
                        <x-jet-input-error for="direccion" />
                        <x-jet-input-error for="tribute_id" />
                        <x-jet-input-error for="concept_id" />
                        <x-jet-input-error for="typemovement_id" />
                        <x-jet-input-error for="opencaja_id" />
                        <x-jet-input-error for="empresa_id" />
                        <x-jet-input-error for="typecomprobante_id" />

                        <x-jet-input-error for="carrito" />
                        <x-jet-input-error for="cuotas" />
                        <x-jet-input-error for="countcuotas" /> --}}
                    </div>

                    <x-loading-next wire:loading
                        wire:target="save,addtocar,getProductoBySerie,deleteitemcart,increment" />

                    <div class="w-full flex flex-row mt-2 gap-2 justify-end text-right">
                        <x-button type="submit" wire:loading.attr="disabled" wire:target="save">
                            {{ __('REGISTRAR') }}
                        </x-button>
                    </div>
                </form>
            </div>

            @if (count($carrito))
                <x-card-next wire:loading.remove wire:target="deleteitemcart" titulo="Carrito compras"
                    class="mt-3 border relative border-next-500 bg-transparent">

                    @if (count($carrito))
                        <span
                            class="text-white font-semibold absolute -top-8 left-0 flex items-center justify-center w-5 h-5 p-0.5 leading-3 bg-next-500 ring-1 ring-white rounded-full text-[10px] animate-bounce">
                            {{ count($carrito) }}</span>
                    @endif

                    <div class="flex gap-2 flex-wrap justify-start mt-1">
                        @foreach ($carrito as $item)
                            <div
                                class="w-full bg-fondominicard border border-borderminicard flex flex-col justify-between sm:w-60 lg:w-full group rounded shadow shadow-shadowminicard p-1 text-xs relative hover:shadow-md hover:shadow-shadowminicard cursor-pointer">
                                <div class="w-full">

                                    @if ($item->status == 0)
                                        <span
                                            class="absolute bottom-1 left-0 text-[8px] font-semibold leading-3 p-0.5 pr-1 rounded-r-lg bg-orange-500 text-white bg-opacity-80 group-hover:bg-opacity-100 transition-all ease-in-out duration-150">
                                            Pendiente Pago</span>
                                    @endif

                                    {{-- @if (count($item->producto->images))
                                        <div class="w-full h-60 sm:h-32 rounded shadow border">
                                            @if ($item->producto->defaultImage)
                                                <img src="{{ asset('storage/productos/' . $item->producto->defaultImage->first()->url) }}"
                                                    alt="" class="w-full h-full object-scale-down">
                                            @else
                                                <img src="{{ asset('storage/productos/' . $item->producto->images->first()->url) }}"
                                                    alt="" class="w-full h-full object-scale-down">
                                            @endif
                                        </div>
                                    @endif --}}

                                    <div class="w-full inline-flex gap-2 text-colorminicard">
                                        <h1 class="w-full text-[10px] leading-3 text-left mt-1">
                                            {{ $item->producto->name }}</h1>
                                        <h1 class="whitespace-nowrap text-right text-xs font-semibold leading-3">
                                            {{ number_format($item->total, 2) }}
                                        </h1>
                                    </div>


                                    <div class="w-full flex gap-1 items-start mt-2 justify-between">
                                        <div class="w-full">
                                            <span
                                                class="text-[9px] font-semibold inline-flex leading-3 p-1 bg-fondospancardproduct text-textspancardproduct rounded uppercase">
                                                P. UNIT:
                                                {{-- {{ $item->moneda->simbolo }} --}}
                                                {{ number_format($item->price, 2, '.', ', ') }}
                                            </span>
                                            <span
                                                class="text-[9px] font-semibold inline-flex  leading-3 p-1 bg-fondospancardproduct text-textspancardproduct rounded uppercase">
                                                {{ \App\Helpers\FormatoPersonalizado::getValue($item->cantidad) }}
                                                {{ $item->producto->unit->name }}
                                            </span>
                                            <span
                                                class="text-[9px] font-semibold inline-flex leading-3 p-1 bg-fondospancardproduct text-textspancardproduct rounded uppercase">
                                                {{ $item->almacen->name }}
                                            </span>
                                        </div>

                                        @if (count($item->carshoopseries))
                                            <x-button wire:loading.attr="disabled" wire:target="save">
                                                {{ __('SERIES') }}
                                                <span
                                                    class="absolute -top-2 -left-2 block w-4 h-4 p-0.5 leading-3 bg-next-500 ring-1 ring-white rounded-full text-[10px]">
                                                    {{ count($item->carshoopseries) }}</span>
                                            </x-button>
                                        @endif
                                    </div>
                                </div>



                                {{-- @if (count($item->carshoopseries))
                                    <div class="w-full mt-2 ">
                                        <x-button size="xs" class="" wire:loading.attr="disabled"
                                            wire:target="save">
                                            {{ __('VER SERIES') }}
                                            <span
                                                class="absolute -top-2 -left-2 block w-4 h-4 p-0.5 leading-3 bg-next-500 ring-1 ring-white rounded-full text-[10px] animate-bounce">
                                                {{ count($item->carshoopseries) }}</span>
                                        </x-button>
                                    </div> --}}

                                {{-- <h1 class="w-full block text-[10px] mt-2">SERIES</h1>
                                    <div class="w-full flex flex-wrap gap-1">
                                        @foreach ($item->carshoopseries as $shoopserie)
                                            <span
                                                class="text-[8px] font-semibold rounded py-0.5 px-1 inline-flex gap-1 items-center bg-fondospancardproduct text-textspancardproduct">
                                                {{ $shoopserie->serie->serie }}
                                                <x-button-delete></x-button-delete>
                                            </span>
                                        @endforeach
                                    </div> --}}
                                {{-- @endif --}}

                                <div class="w-full flex items-end gap-1 justify-end mt-2">
                                    <x-button-delete wire:click="deleteitemcart({{ $item->id }})"
                                        wire:loading.attr="disabled" wire:target="deleteitemcart"></x-button-delete>

                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-card-next>
            @endif

        </div>
    </div>

    @section('scripts')
        <script>
            // document.addEventListener("DOMContentLoaded", () => {

            renderSelect2();

            $("#ventamoneda_id").on("change", (e) => {
                deshabilitarSelects();
                @this.moneda_id = e.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });

            $("#searchcategory").on("change", (e) => {
                deshabilitarSelects();
                // @this.searchcategory = e.target.value;
                @this.loadbycategory(e.target.value);
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
                // @this.pricetype_id = e.target.value;
                @this.loadprice(e.target.value);
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

            // })
        </script>
    @endsection
</div>
