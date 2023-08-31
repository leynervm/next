<div class="mt-3 max-w-7xl md:p-3 mx-auto shadow-xl rounded">

    <div class="mb-3">
        <x-link-next href="{{ route('admin.almacen.productos') }}" titulo="Productos">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m16 16 2 2 4-4" />
                <path
                    d="M21 10V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l2-1.14" />
                <path d="M16.5 9.4 7.55 4.24" />
                <polyline points="3.29 7 12 12 20.71 7" />
                <line x1="12" x2="12" y1="22" y2="12" />
            </svg>
        </x-link-next>
    </div>

    <div class="w-full flex flex-wrap lg:flex-nowrap gap-3 ">
        <x-card-next titulo="Datos producto" class="w-full lg:w-1/2 border border-next-500">
            <div class="w-full flex flex-wrap sm:flex-nowrap gap-3">
                <div class="w-full sm:w-1/2">
                    @if (count($producto->images))
                        <div class="block w-full h-60 sm:h-40">
                            @if (count($producto->defaultImage))
                                <img src="{{ asset('storage/productos/' . $producto->defaultImage->first()->url) }}"
                                    alt="" class="w-full h-full object-scale-down">
                            @else
                                <img src="{{ asset('storage/productos/' . $producto->images->first()->url) }}"
                                    alt="" class="w-full h-full object-scale-down">
                            @endif
                        </div>
                    @endif

                    <h1 class="text-xs text-center font-semibold tracking-widest mt-2">{{ $producto->name }}</h1>
                </div>
                <div class="w-full sm:w-1/2">

                    <p
                        class="text-[10px] inline-flex p-1 rounded-lg tracking-widest font-semibold bg-fondospancardproduct text-textspancardproduct">
                        PRECIO COMPRA: {{ $producto->pricebuy }}</p>
                    <p
                        class="text-[10px] inline-flex p-1 rounded-lg tracking-widest font-semibold bg-fondospancardproduct text-textspancardproduct">
                        IGV COMPRA: {{ $producto->igv }}</p>

                    <p
                        class="text-[10px] inline-flex p-1 rounded-lg tracking-widest font-semibold bg-fondospancardproduct text-textspancardproduct">
                        UNID. MEDIDA: {{ $producto->unit->name }}</p>

                    <p
                        class="text-[10px] leading-3 inline-flex p-1 tracking-widest rounded-lg font-semibold bg-fondospancardproduct text-textspancardproduct">
                        UBICACIÓN:{{ $producto->almacenarea->name }} \ {{ $producto->estante->name }}
                    </p>

                    <p
                        class="text-[10px] p-1 inline-flex tracking-widest rounded-lg font-semibold bg-fondospancardproduct text-textspancardproduct">
                        CATEGORÍA:{{ $producto->category->name }}
                        @if ($producto->subcategory_id)
                            \ {{ $producto->subcategory->name }}
                        @endif
                    </p>

                    <div class="w-full inline-flex flex-wrap gap-1 justify-between items-start">
                        <span
                            class="text-[10px] p-1 rounded-lg tracking-widest font-semibold bg-fondospancardproduct text-textspancardproduct">MARCA:
                            {{ $producto->marca->name }}</span>
                        @if ($producto->marca->logo)
                            <div class="w-24 h-14">
                                <img src="{{ asset('storage/marcas/' . $producto->marca->logo) }}" alt=""
                                    class="w-full h-full object-scale-down">
                            </div>
                        @endif
                    </div>

                </div>
            </div>
            <div class="w-full mt-3 flex flex-wrap justify-between">

                <x-label textSize="[10px]"
                    class="inline-flex leading-3 items-center tracking-widest font-semibold gap-2 cursor-pointer bg-next-100 rounded-lg p-1"
                    for="publicado_dit">
                    <x-input wire:model="publicado" name="publicado" type="checkbox" id="publicado_dit"
                        value="1" />
                    DISPONIBLE TIENDA WEB
                </x-label>

                <div>
                    <x-button-delete wire:click="confirm_delete({{ $producto->id }})" wire:loading.attr="disabled"
                        wire:target="confirm_delete"></x-button-delete>
                </div>
            </div>
        </x-card-next>

        <x-card-next titulo="Almacén" class="w-full lg:w-1/2 border border-next-500">
            @if (count($producto->almacens))
                @foreach ($producto->almacens as $item)
                    <div
                        class="inline-flex flex-col gap-1 justify-between w-32 h-32 border rounded-xl shadow-md p-1 cursor-pointer hover:shadow-lg">

                        <div class="h-full flex flex-col gap-1 justify-center items-center">
                            <span class="block w-6 h-6 mx-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path
                                        d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                                    <polyline points="3.29 7 12 12 20.71 7" />
                                    <line x1="12" x2="12" y1="22" y2="12" />
                                </svg>
                            </span>

                            <h1 class="text-[10px] text-center leading-3 font-semibold">{{ $item->name }}</h1>

                            <h1 class="text-xl text-center leading-4 font-semibold">
                                {{ floatval($item->pivot->cantidad) }}
                                <span class="w-full text-center text-[10px] font-normal">
                                    UND</span>
                            </h1>
                        </div>

                        <div class="flex justify-end items-end gap-1">
                            <x-button-edit wire:click="editalmacen({{ $item->id }})" wire:loading.attr="disabled"
                                wire:target="editalmacen, confirm_delete_almacen">
                            </x-button-edit>
                            <x-button-delete wire:click="confirm_delete_almacen({{ $item->id }})"
                                wire:loading.attr="disabled" wire:target="confirm_delete_almacen, editalmacen">
                            </x-button-delete>
                        </div>
                    </div>
                @endforeach
            @endif

            <x-slot name="footer">
                <x-button wire:click="openmodal" wire:target="openmodal" wire:loading.attr="disabled">AÑADIR ALMACEN
                </x-button>
            </x-slot>
        </x-card-next>
    </div>

    <x-card-next titulo="Especificaciones" class="w-full mt-3 border border-next-500">
        <div class="w-full flex flex-wrap lg:flex-nowrap gap-3">
            <div class="w-full lg:w-2/6 flex justify-center items-start">
                <x-button wire:click="modalespefificacion" wire:target="modalespefificacion"
                    wire:loading.attr="disabled">AÑADIR ESPECIFICACIÓN
                </x-button>
            </div>
            <div class="w-full lg:w-4/6">
                @if (count($producto->especificaciones))
                    <div class="w-full flex flex-wrap gap-1">
                        @foreach ($producto->especificaciones as $item)
                            <div
                                class="inline-flex gap-2 items-center justify-between p-1 font-semibold rounded-md bg-fondospancardproduct text-textspancardproduct">
                                <span class="text-[10px] ">{{ $item->caracteristica->name }}
                                    : {{ $item->name }}</span>

                                <x-button-delete wire:click="delete_especificacion({{ $item->id }})"
                                    wire:target="delete_especificacion" wire:loading.attr="sisabled">
                                </x-button-delete>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </x-card-next>

    <x-card-next titulo="Imágenes relacionadas" class="w-full mt-3 border border-next-500">
        <div class="w-full flex flex-wrap lg:flex-nowrap gap-3">
            <div class="w-full lg:w-2/6 relative">
                <form wire:submit.prevent="add_image" class="">
                    <div x-data="{ isUploading: @entangle('isUploading'), progress: 0 }" x-on:livewire-upload-start="isUploading = true"
                        x-on:livewire-upload-finish="isUploading = false"
                        x-on:livewire-upload-error="$wire.emit('errorImage'), isUploading = false"
                        x-on:livewire-upload-progress="progress = $event.detail.progress">

                        <div x-show="isUploading" class="loading-overlay rounded">
                            <div class="spinner"></div>
                        </div>

                        @if (isset($imagen))
                            <div class="w-full h-60 md:max-w-md mx-auto mb-1 duration-300">
                                <img class="w-full h-full object-scale-down" src="{{ $imagen->temporaryUrl() }}"
                                    alt="">
                            </div>
                        @endif

                        <x-input-file :for="$identificador" titulo="SELECCIONAR IMAGEN" wire:loading.remove
                            wire:target="imagen">
                            <input type="file" class="hidden" wire:model="imagen" id="{{ $identificador }}"
                                accept="image/jpg, image/jpeg, image/png" />

                            @if (isset($imagen))
                                <x-slot name="clear">
                                    <x-button class="inline-flex px-6" wire:loading.attr="disabled"
                                        wire:target="add_image" type="submit">
                                        REGISTRAR
                                    </x-button>
                                    <x-button class="inline-flex px-6" wire:loading.attr="disabled"
                                        wire:target="clearImage" wire:click="clearImage">
                                        LIMPIAR
                                    </x-button>
                                </x-slot>
                            @endif
                        </x-input-file>
                    </div>
                    <x-jet-input-error wire:loading.remove wire:target="imagen" for="imagen" class="text-center" />
                    <x-jet-input-error for="producto.id" />
                </form>
            </div>
            <div class="w-full lg:w-4/6">
                @if (count($producto->images))
                    <div class="w-full flex flex-wrap gap-1">
                        @foreach ($producto->images as $item)
                            <div
                                class="w-48 group shadow-md border rounded-md relative overflow-hidden hover:shadow-lg">
                                <div class="w-full h-24 block">
                                    <img src="{{ asset('storage/productos/' . $item->url) }}" alt=""
                                        class="w-full h-full object-cover">
                                </div>
                                <div class="w-full flex gap-1 justify-between p-1">
                                    <span
                                        class="text-[10px] p-1 rounded-lg bg-fondospancardproduct text-textspancardproduct truncate">{{ $item->url }}</span>
                                    <x-button-delete></x-button-delete>
                                </div>
                                @if ($item->default == 1)
                                    <span
                                        class="absolute top-1 left-1 w-6 h-6 rounded-full bg-green-400 p-1 text-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12" />
                                        </svg>
                                    </span>
                                @else
                                    <button type="button" wire:loading.attr="disabled"
                                        wire:click="update_image_default({{ $item->id }})"
                                        class="absolute top-1 -left-7 w-6 h-6 group-hover:translate-x-8 rounded-full bg-green-500 p-1 text-white focus:ring-2 focus:ring-green-300 transition ease-out duration-150 hover:scale-110 disabled:opacity-25">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12" />
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </x-card-next>

    <x-card-next titulo="Series" class="w-full mt-3 border border-next-500">
        <div class="w-full flex flex-wrap lg:flex-nowrap gap-3">
            <div class="w-full lg:w-2/6">
                <form wire:submit.prevent="add_serie">
                    <x-label value="Almacén :" />
                    <x-select class="block w-full" id="almacenproducto_id" wire:model="almacen_id">
                        <x-slot name="options">
                            @if (count($producto->almacens))
                                @foreach ($producto->almacens as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            @endif
                        </x-slot>
                    </x-select>
                    <x-jet-input-error for="almacen_id" />

                    <x-label value="Serie :" class="mt-2" />
                    <x-input class="block w-full" wire:model.defer="serie" />
                    <x-jet-input-error for="serie" />
                    <x-jet-input-error for="producto.id" />

                    <div class="mt-3 flex justify-end">
                        <x-button type="submit" wire:loading.atrr="disabled" wire:target="add_especificacion">
                            REGISTRAR
                        </x-button>
                    </div>
                </form>
            </div>
            <div class="w-full lg:w-4/6">
                @if (count($producto->series))
                    <div class="w-full flex mb-2">
                        @if (count($producto->almacens) > 1)
                            <div class="relative" x-data="{ open: false }">
                                <button x-on:click="open = !open"
                                    :class="{ 'bg-next-50': open, 'bg-transparent': !open }"
                                    class="border bg-transparent rounde-sm w-full text-xs font-semibold border-next-300 text-next-500 p-2 px-3 focus:ring-0 focus:border-next-400 text-center inline-flex items-center"
                                    type="button">Filtrar almacén
                                    <svg :class="{ 'rotate-180': open }"
                                        class="w-4 h-full ml-2 transform transition duration-150" aria-hidden="true"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7">
                                        </path>
                                    </svg>
                                </button>

                                <div :class="{ 'block': open, 'hidden': !open }" x-show="open"
                                    x-on:click.away="open = !open"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    class="absolute hidden z-10 w-auto max-w-xs bg-white rounded-lg shadow-md">
                                    <ul class="p-2 space-y-1 text-next-700" aria-labelledby="dropdownCheckboxButton">
                                        @if (count($producto->almacens))
                                            @foreach ($producto->almacens as $item)
                                                <li>
                                                    <div
                                                        class="flex flex-nowrap items-center hover:bg-next-50 rounded-lg p-1 break-keep">
                                                        <input id="searchalmacen_{{ $item->id }}" type="checkbox"
                                                            value="{{ $item->id }}" wire:loading.attr="disabled"
                                                            wire:model="searchseriealmacen"
                                                            name="searchseriealmacen[]"
                                                            class="w-4 h-4 text-next-600 border-next-300 cursor-pointer rounded focus:ring-0 focus:ring-transparent disabled:opacity-25">
                                                        <label for="searchalmacen_{{ $item->id }}"
                                                            class="pl-2 text-xs font-medium text-next-900 cursor-pointer break-keep">{{ $item->name }}</label>
                                                    </div>
                                                </li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="w-full flex flex-wrap gap-1">
                        @if (count($seriesDisponibles))
                            @foreach ($seriesDisponibles as $item)
                                <div
                                    class="inline-flex gap-2 items-center justify-between p-1 font-semibold rounded-md bg-fondospancardproduct text-textspancardproduct">
                                    <span class="text-[10px]">{{ $item->serie }}</span>
                                    <x-button-delete wire:click="delete_serie({{ $item->id }})"
                                        wire:loading.attr="disabled" wire:target="delete_serie"></x-button-delete>
                                </div>
                            @endforeach
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </x-card-next>

    <x-card-next titulo="Garantías" class="w-full mt-3 border border-next-500">
        <div class="w-full flex flex-wrap lg:flex-nowrap gap-3">
            <div class="w-full lg:w-2/6">
                <form wire:submit.prevent="add_garantia">
                    <x-label value="Garantías disponibles :" />
                    <x-select class="block w-full" id="typegarantia_id" wire:model="typegarantia_id">
                        <x-slot name="options">
                            @if (count($typegarantias))
                                @foreach ($typegarantias as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            @endif
                        </x-slot>
                    </x-select>
                    <x-jet-input-error for="typegarantia_id" />

                    <x-label value="Tiempo garantía (Meses) :" class="mt-2" />
                    <x-input class="block w-full" wire:model.defer="time" type="number" />
                    <x-jet-input-error for="time" />
                    <x-jet-input-error for="producto.id" />

                    <div class="mt-3 flex justify-end">
                        <x-button type="submit" wire:loading.atrr="disabled" wire:target="add_especificacion">
                            REGISTRAR
                        </x-button>
                    </div>
                </form>
            </div>
            <div class="w-full lg:w-4/6">
                @if (count($producto->garantiaproductos))
                    <div class="w-full flex flex-wrap gap-1">
                        @foreach ($producto->garantiaproductos as $item)
                            <div
                                class="relative inline-flex mt-7 flex-col justify-between items-center w-36 border gap-1 p-1 font-semibold rounded-md cursor-pointer shadow hover:shadow-lg">
                                <span
                                    class="text-[10px] p-1 rounded-lg bg-fondospancardproduct text-textspancardproduct">{{ $item->typegarantia->name }}</span>

                                <div class="w-full flex justify-end">
                                    <x-button-delete wire:click="delete_garantia({{ $item->id }})"
                                        wire:loading.attr="disabled" wire:target="delete_garantia"></x-button-delete>
                                </div>
                                <span class="absolute right-2 -top-3 w-4 h-4 block text-red-500 ml-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full"
                                        viewBox="0 0 320 512" fill="currentColor" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path
                                            d="M310.6 246.6l-127.1 128C176.4 380.9 168.2 384 160 384s-16.38-3.125-22.63-9.375l-127.1-128C.2244 237.5-2.516 223.7 2.438 211.8S19.07 192 32 192h255.1c12.94 0 24.62 7.781 29.58 19.75S319.8 237.5 310.6 246.6z" />
                                    </svg>
                                </span>
                                <span
                                    class="absolute right-0 -top-8 bg-red-500 text-white text-[10px] font-semibold tracking-widest p-1.5 px-2 rounded-sm">{{ $item->time }}
                                    {{ $item->time > 1 ? 'MESES' : 'MES' }}</span>

                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </x-card-next>

    <x-card-next titulo="Incrementar precio venta(%)" class="w-full mt-3 border border-next-500">
        <div class="w-full flex flex-wrap lg:flex-nowrap gap-3">
            <div class="w-full lg:w-2/6">
                <form wire:submit.prevent="add_incremento">
                    <x-label value="Lista precios disponibles :" />
                    <x-select class="block w-full" id="pricetypeproducto_id" wire:model="pricetype_id">
                        <x-slot name="options">
                            @if (count($pricetypes))
                                @foreach ($pricetypes as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            @endif
                        </x-slot>
                    </x-select>
                    <x-jet-input-error for="pricetype_id" />

                    <x-label value="Incremento (%) :" class="mt-2" />
                    <x-input class="block w-full" wire:model.defer="increment" type="number" />
                    <x-jet-input-error for="increment" />
                    <x-jet-input-error for="producto.id" />

                    <div class="mt-3 flex justify-end">
                        <x-button type="submit" wire:loading.atrr="disabled" wire:target="add_especificacion">
                            REGISTRAR
                        </x-button>
                    </div>
                </form>
            </div>
            <div class="w-full lg:w-4/6">

            </div>
        </div>
    </x-card-next>

    <x-card-next titulo="Detalles del producto" class="w-full mt-3 border border-next-500">
        <div class="w-full">
            <form wire:submit.prevent="add_detalle">
                <div>
                    <x-label value="Título :" />
                    <x-input class="block w-full" wire:model.defer="titulo" />
                    <x-jet-input-error for="titulo" />
                </div>

                <div wire:ignore>
                    <x-label value="Descripción :" class="mt-2" />
                    <x-textarea class="w-full" id="descripcionproducto" wire:model.defer="descripcion"
                        rows="4">
                    </x-textarea>
                </div>
                <x-jet-input-error for="descripcion" />
                <x-jet-input-error for="producto.id" />

                <div class="mt-3 flex justify-end">
                    <x-button type="submit" wire:loading.atrr="disabled" wire:target="add_detalle">
                        REGISTRAR
                    </x-button>
                </div>
            </form>
        </div>

        @if (count($producto->detalleproductos))
            <div class="w-full lg:grid lg:grid-cols-2 gap-3 mt-3">
                @foreach ($producto->detalleproductos as $item)
                    <div class="w-full p-1 rounded-xs shadow border hover:shadow-lg">
                        <p
                            class="font-semibold p-1 text-xs text-center bg-fondospancardproduct text-textspancardproduct">
                            {{ $item->title }}</p>
                        <div class="">
                            {!! $item->descripcion !!}
                        </div>

                        <div class="flex gap-1 justify-end mt-1">
                            <x-button-edit></x-button-edit>
                            <x-button-delete></x-button-delete>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </x-card-next>

    <x-jet-dialog-modal wire:model="openalmacen" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Agregar almacén') }}
            <x-button-add wire:click="$toggle('openalmacen')" wire:loading.attr="disabled">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </x-button-add>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="add_almacen">
                <div class="w-full">
                    <x-label value="Nombre :" />
                    <x-select class="block w-full" id="newalmacen_id" wire:model="newalmacen_id">
                        <x-slot name="options">
                            @if (count($almacens))
                                @foreach ($almacens as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            @endif
                        </x-slot>
                    </x-select>
                    <x-jet-input-error for="newalmacen_id" />
                </div>

                <div class="w-full mt-2">
                    <x-label value="Cantidad (Stock) :" />
                    <x-input class="block w-full" wire:model.defer="newcantidad" type="number" step="0.01" />
                    <x-jet-input-error for="newcantidad" />
                </div>

                <div class="w-full flex flex-row pt-4 gap-2 justify-end text-right">
                    <x-button type="submit" wire:loading.attr="disabled" wire:target="add_almacen">
                        {{ __('REGISTRAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <x-jet-dialog-modal wire:model="openespecificacion" maxWidth="3xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Agregar especificaciones') }}
            <x-button-add wire:click="$toggle('openespecificacion')" wire:loading.attr="disabled">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </x-button-add>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="add_especificacion">
                <div class="w-full">
                    @if (count($caracteristicas))
                        @foreach ($caracteristicas as $item)
                            <fieldset class="w-full border p-2 rounded-sm border-next-500 mb-2">
                                <legend class="text-next-500 text-sm px-1">{{ $item->name }}</legend>
                                <div class="w-full flex gap-2 flex-wrap">
                                    @if (count($item->especificacions))
                                        @foreach ($item->especificacions as $especificacion)
                                            <x-input-radio :for="'especificacion_' . $especificacion->id" :text="$especificacion->name">
                                                <x-input wire:model.defer="selectedEspecificacion.{{ $item->id }}"
                                                    class="sr-only peer" type="radio" :id="'especificacion_' . $especificacion->id"
                                                    :name="'especificaciones_' . $item->id . '[]'" value="{{ $especificacion->id }}" />
                                            </x-input-radio>
                                        @endforeach
                                    @endif
                                </div>
                            </fieldset>
                        @endforeach
                    @endif
                    <x-jet-input-error for="selectedEspecificacion" />
                </div>

                <div class="w-full flex flex-row pt-4 gap-2 justify-end text-right">
                    <x-button type="submit" wire:loading.attr="disabled" wire:target="add_almacen">
                        {{ __('REGISTRAR CAMBIOS') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        document.addEventListener("livewire:load", () => {

            $("#newalmacen_id").select2({
                placeholder: "Seleccionar",
                minimumResultsForSearch: Infinity
            }).on("change", function(e) {
                e.target.setAttribute("disabled", true);
                @this.newalmacen_id = e.target.value;
            });

            $("#almacenproducto_id").select2({
                placeholder: "Seleccionar",
                minimumResultsForSearch: Infinity
            }).on("change", function(e) {
                e.target.setAttribute("disabled", true);
                @this.almacen_id = e.target.value;
            });

            $("#caracteristica_id").select2({
                placeholder: "Seleccionar",
                minimumResultsForSearch: Infinity
            }).on("change", function(e) {
                $("#especificacion_id").attr("disabled", true);
                e.target.setAttribute("disabled", true);
                @this.caracteristica_id = e.target.value;
            });

            $("#especificacion_id").select2({
                placeholder: "Seleccionar",
                minimumResultsForSearch: Infinity
            }).on("change", function(e) {
                e.target.setAttribute("disabled", true);
                @this.especificacion_id = e.target.value;
            });

            $("#typegarantia_id").select2({
                placeholder: "Seleccionar",
                minimumResultsForSearch: Infinity
            }).on("change", function(e) {
                e.target.setAttribute("disabled", true);
                @this.typegarantia_id = e.target.value;
            });

            window.addEventListener("render-select2", () => {
                $("#newalmacen_id, #almacenproducto_id, #caracteristica_id, #especificacion_id, #typegarantia_id")
                    .select2({
                        placeholder: "Seleccionar",
                        minimumResultsForSearch: Infinity
                    });

            })


            CKEDITOR.ClassicEditor.create(document.getElementById("descripcionproducto"), {
                // https://ckeditor.com/docs/ckeditor5/latest/features/toolbar/toolbar.html#extended-toolbar-configuration-format
                toolbar: {
                    items: [
                        'undo', 'redo', '|',
                        'exportPDF', 'exportWord', '|',
                        'findAndReplace', 'selectAll', '|',
                        'heading', '|',
                        'bold', 'italic', 'strikethrough', 'underline', 'code', 'subscript',
                        'superscript', 'removeFormat', '|',
                        'bulletedList', 'numberedList', 'todoList', '|',
                        'outdent', 'indent', '|',
                        // '-',
                        'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', 'highlight',
                        '|',
                        'alignment', '|',
                        'link', 'insertImage', 'blockQuote', 'insertTable', 'mediaEmbed',
                        'codeBlock',
                        'htmlEmbed', '|',
                        'specialCharacters', 'horizontalLine', 'pageBreak', '|',
                        'textPartLanguage', '|',
                        'sourceEditing'
                    ],
                    shouldNotGroupWhenFull: true
                },
                // Changing the language of the interface requires loading the language file using the <script> tag.
                language: 'es',
                list: {
                    properties: {
                        styles: true,
                        startIndex: true,
                        reversed: true
                    }
                },
                // https://ckeditor.com/docs/ckeditor5/latest/features/headings.html#configuration
                heading: {
                    options: [{
                            model: 'paragraph',
                            title: 'Paragraph',
                            class: 'ck-heading_paragraph'
                        },
                        {
                            model: 'heading1',
                            view: 'h1',
                            title: 'Heading 1',
                            class: 'ck-heading_heading1'
                        },
                        {
                            model: 'heading2',
                            view: 'h2',
                            title: 'Heading 2',
                            class: 'ck-heading_heading2'
                        },
                        {
                            model: 'heading3',
                            view: 'h3',
                            title: 'Heading 3',
                            class: 'ck-heading_heading3'
                        },
                        {
                            model: 'heading4',
                            view: 'h4',
                            title: 'Heading 4',
                            class: 'ck-heading_heading4'
                        },
                        {
                            model: 'heading5',
                            view: 'h5',
                            title: 'Heading 5',
                            class: 'ck-heading_heading5'
                        },
                        {
                            model: 'heading6',
                            view: 'h6',
                            title: 'Heading 6',
                            class: 'ck-heading_heading6'
                        }
                    ]
                },
                // https://ckeditor.com/docs/ckeditor5/latest/features/editor-placeholder.html#using-the-editor-configuration
                placeholder: 'Ingresar contenido...',
                // https://ckeditor.com/docs/ckeditor5/latest/features/font.html#configuring-the-font-family-feature
                fontFamily: {
                    options: [
                        'default',
                        'Arial, Helvetica, sans-serif',
                        'Courier New, Courier, monospace',
                        'Georgia, serif',
                        'Lucida Sans Unicode, Lucida Grande, sans-serif',
                        'Tahoma, Geneva, sans-serif',
                        'Times New Roman, Times, serif',
                        'Trebuchet MS, Helvetica, sans-serif',
                        'Verdana, Geneva, sans-serif'
                    ],
                    supportAllValues: true
                },
                // https://ckeditor.com/docs/ckeditor5/latest/features/font.html#configuring-the-font-size-feature
                fontSize: {
                    options: [8, 9, 10, 11, 12, 13, 14, 'default', 18, 20, 22],
                    supportAllValues: true
                },
                // Be careful with the setting below. It instructs CKEditor to accept ALL HTML markup.
                // https://ckeditor.com/docs/ckeditor5/latest/features/general-html-support.html#enabling-all-html-features
                htmlSupport: {
                    allow: [{
                        name: /.*/,
                        attributes: true,
                        classes: true,
                        styles: true
                    }]
                },
                // Be careful with enabling previews
                // https://ckeditor.com/docs/ckeditor5/latest/features/html-embed.html#content-previews
                htmlEmbed: {
                    showPreviews: true
                },
                // https://ckeditor.com/docs/ckeditor5/latest/features/link.html#custom-link-attributes-decorators
                link: {
                    decorators: {
                        addTargetToExternalLinks: true,
                        defaultProtocol: 'https://',
                        toggleDownloadable: {
                            mode: 'manual',
                            label: 'Downloadable',
                            attributes: {
                                download: 'file'
                            }
                        }
                    }
                },
                // https://ckeditor.com/docs/ckeditor5/latest/features/mentions.html#configuration
                mention: {
                    feeds: [{
                        marker: '@',
                        feed: [
                            '@apple', '@bears', '@brownie', '@cake', '@cake', '@candy',
                            '@canes', '@chocolate', '@cookie', '@cotton', '@cream',
                            '@cupcake', '@danish', '@donut', '@dragée', '@fruitcake',
                            '@gingerbread', '@gummi', '@ice', '@jelly-o',
                            '@liquorice', '@macaroon', '@marzipan', '@oat', '@pie', '@plum',
                            '@pudding', '@sesame', '@snaps', '@soufflé',
                            '@sugar', '@sweet', '@topping', '@wafer'
                        ],
                        minimumCharacters: 1
                    }]
                },
                // The "super-build" contains more premium features that require additional configuration, disable them below.
                // Do not turn them on unless you read the documentation and know how to configure them and setup the editor.
                removePlugins: [
                    // These two are commercial, but you can try them out without registering to a trial.
                    // 'ExportPdf',
                    // 'ExportWord',
                    'CKBox',
                    'CKFinder',
                    'EasyImage',
                    // This sample uses the Base64UploadAdapter to handle image uploads as it requires no configuration.
                    // https://ckeditor.com/docs/ckeditor5/latest/features/images/image-upload/base64-upload-adapter.html
                    // Storing images as Base64 is usually a very bad idea.
                    // Replace it on production website with other solutions:
                    // https://ckeditor.com/docs/ckeditor5/latest/features/images/image-upload/image-upload.html
                    // 'Base64UploadAdapter',
                    'RealTimeCollaborativeComments',
                    'RealTimeCollaborativeTrackChanges',
                    'RealTimeCollaborativeRevisionHistory',
                    'PresenceList',
                    'Comments',
                    'TrackChanges',
                    'TrackChangesData',
                    'RevisionHistory',
                    'Pagination',
                    'WProofreader',
                    // Careful, with the Mathtype plugin CKEditor will not load when loading this sample
                    // from a local file system (file://) - load this site via HTTP server if you enable MathType.
                    'MathType',
                    // The following features are part of the Productivity Pack and require additional license.
                    'SlashCommand',
                    'Template',
                    'DocumentOutline',
                    'FormatPainter',
                    'TableOfContents'
                ],

                locale: {
                    dateTimeFormat: date => format(date, 'dd/MM/yyyy')
                },

                table: {
                    // Agregar el filtro
                    addClassToAllCells: true, // Para agregar la clase a todas las celdas de la tabla
                    class: 'w-full' // Nombre de la clase que se agregará a las tablas
                }

                // height: '500px',

            }).then(function(editor) {
                // editor.config.height = '500px';
                // editor.ui.view.editable.element.style.height = '500px';
                editor.model.document.on("change:data", () => {
                    @this.set('descripcion', editor.getData());
                });

                //PARA ESCUCHAR EVENTOS CON EMIT
                Livewire.on("resetCKEditor", () => {
                    console.log("Reset CKEditor");
                    editor.setData('');
                });

                //PARA ESCUCHAR EVENTOS CON DISPATCHBROWSEREVENT
                // window.addEventListener("resetCKEditor", () => {
                //     console.log("Reset CKEditor");
                //     editor.setData('');
                // });
            }).catch(error => {
                console.log(error);
            });

            window.addEventListener("producto_almacen.confirmDelete", data => {
                swal.fire({
                    title: 'Eliminar registro con nombre: ' + data.detail.name,
                    text: "Se eliminará un registro de la base de datos, incluyendo las series vinculadas.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // console.log(data.detail);
                        Livewire.emitTo('almacen::productos.view-producto', 'delete_almacen',
                            data
                            .detail.id);
                    }
                })
            });

            window.addEventListener("producto.confirmDelete", data => {
                swal.fire({
                    title: 'Eliminar registro con nombre: ' + data.detail.name,
                    text: "Se eliminará un registro de la base de datos, incluyendo todos los datos relacionados.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // console.log(data.detail);
                        Livewire.emitTo('almacen::productos.view-producto', 'delete', data
                            .detail.id);
                    }
                })
            });

        })
    </script>

</div>
