<div class="relative w-full flex flex-col gap-3 lg:gap-5" x-data="showcot">
    <div wire:loading.flex class="fixed loading-overlay hidden">
        <x-loading-next />
    </div>

    <x-form-card titulo="DATOS DE LA COTIZACIÓN">
        <h1 class="text-xl text-colortitleform font-semibold mb-2">
            {{ $cotizacion->seriecompleta }}</h1>

        <form wire:submit.prevent="update" class="w-full flex flex-col gap-2">
            <div class="w-full grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-2">

                <div class="w-full xs:col-span-2 sm:col-span-1">
                    <x-label value="DNI / RUC :" />
                    <x-disabled-text :text="$cotizacion->client->document" class="w-full flex-1 block" />
                    <x-jet-input-error for="cotizacion.document" />
                </div>

                <div class="w-full xs:col-span-2">
                    <x-label value="Cliente / Razón Social :" />
                    <x-disabled-text :text="$cotizacion->client->name" class="w-full block" />
                    <x-jet-input-error for="cotizacion.name" />
                </div>

                <div class="w-full xs:col-span-2">
                    <x-label value="Dirección :" />
                    <x-input class="block w-full" wire:model.defer="cotizacion.direccion" />
                    <x-jet-input-error for="cotizacion.direccion" />
                </div>

                <div class="w-full">
                    <x-label value="Moneda :" />
                    <x-disabled-text :text="$cotizacion->moneda->currency" class="w-full block" />
                    <x-jet-input-error for="cotizacion.moneda_id" />
                </div>

                <div class="w-full">
                    <x-label value="Modalidad de pago :" />
                    <x-disabled-text :text="$cotizacion->typepayment->name" class="w-full block" />
                    <x-jet-input-error for="cotizacion.typepayment_id" />
                </div>

                <div class="w-full">
                    <x-label value="Afectación IGV :" />
                    @if ($cotizacion->isAfectacionIGV())
                        <x-disabled-text text="INCLUIR IGV" class="w-full block" />
                    @else
                        <x-disabled-text text="EXONERAR IGV" class="w-full block" />
                    @endif
                    <x-jet-input-error for="cotizacion.afectacionigv" />
                </div>

                <div>
                    <x-label value="Tiempo de entrega :" />
                    <div class="w-full flex">
                        <x-input class="block w-1/3 text-end !rounded-tr-none !rounded-br-none"
                            wire:model.defer="cotizacion.entrega" type="number" min="1"
                            onkeypress="return validarNumero(event, 11)"
                            onpaste="return validarPasteNumero(event, 11)" />
                        <div class="relative flex-1" id="parentrpcot_entrega" x-init="selectCotEntrega">
                            <x-select class="block w-full" x-ref="rpcot_entrega" id="rpcot_entrega">
                                <x-slot name="options">
                                    <option value="D">DÍAS</option>
                                    <option value="M">MESES</option>
                                    <option value="Y">AÑOS</option>
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                    </div>
                    <x-jet-input-error for="cotizacion.entrega" />
                    <x-jet-input-error for="cotizacion.datecode" />
                </div>

                <div class="w-full">
                    <x-label value="Validéz cotización :" />
                    <x-input type="date" class="block w-full" wire:model.defer="cotizacion.validez"
                        min="{{ \Carbon\Carbon::parse($cotizacion->date)->format('Y-m-d') }}" />
                    <x-jet-input-error for="cotizacion.validez" />
                </div>

                <div class="w-full xs:col-span-2 lg:col-span-4 xl:col-span-5">
                    <x-label value="Descripción de cotización :" />
                    <x-text-area class="block w-full" wire:model.defer="cotizacion.detalle" x-ref="detalle_cotizacion"
                        style="overflow:hidden;resize:none;" x-on:input="adjustHeight($el)"></x-text-area>
                    <x-jet-input-error for="cotizacion.detalle" />
                </div>
            </div>

            <div class="w-full flex flex-col gap-2 justify-end">
                <div>
                    <x-label-check for="addadress">
                        <x-input wire:model.defer="addadress" name="addadress" x-model="addadress" type="checkbox"
                            id="addadress" />AGREGAR LUGAR INSTALACIÓN
                    </x-label-check>
                    <x-jet-input-error for="addadress" />
                </div>
                <div class="w-full grid grid-cols-1 sm:grid-cols-2 gap-2" x-cloak x-show="addadress">
                    <div class="w-full">
                        <x-label value="Lugar :" />
                        <div id="parentubigeo_id" class="relative" x-init="selec2UbigeoCot">
                            <x-select class="block w-full" x-ref="selectubigeo_id" id="ubigeo_id">
                                <x-slot name="options">
                                    @if (count($ubigeos) > 0)
                                        @foreach ($ubigeos as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->region }} / {{ $item->provincia }} / {{ $item->distrito }}
                                                / {{ $item->ubigeo_reniec }}
                                            </option>
                                        @endforeach
                                    @endif
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="ubigeo_id" />
                    </div>

                    <div class="w-full">
                        <x-label value="Dirección :" />
                        <x-input class="block w-full" wire:model.defer="direccioninstalacion" />
                        <x-jet-input-error for="direccioninstalacion" />
                    </div>
                </div>
            </div>

            <div class="w-full flex flex-col justify-end gap-1">
                @if ($cotizacion->isAprobbed())
                    <div class="w-full flex items-center justify-start gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="flex-shrink-0 block size-5 text-green-500 stroke-current" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        <p class="text-xs text-end leading-none font-medium text-green-500">
                            COTIZACIÓN APROVADO SATISFACTORIAMENTE</p>
                    </div>

                    @can('admin.cotizaciones.aprobar')
                        <div>
                            <x-button-secondary class="" wire:click="desaprobar" wire:loading.attr="disabled"
                                wire:key="desaprobarcotizacion">
                                {{ __('Desaprobar cotización') }}</x-button-secondary>
                        </div>
                    @endcan
                @endif

                @if (count($cotizacion->cotizables) > 0)
                    <div class="w-full grid grid-cols-[repeat(auto-fill,minmax(250px,1fr))] gap-1 self-start">
                        @foreach ($cotizacion->cotizables as $item)
                            @if ($item && $item->isVenta() && Module::isEnabled('Ventas'))
                                <x-simple-card class="w-full text-colorlabel p-2">
                                    @if ($item->cotizable->trashed())
                                        <h1 class="font-semibold text-sm leading-4 text-colortitleform">
                                            <span class="text-3xl">{{ $item->cotizable->seriecompleta }}</span>
                                            {{ $item->cotizable->seriecomprobante->typecomprobante->name }}
                                        </h1>
                                        <x-span-text :text="'ELIMINADO ' .
                                            formatDate($item->cotizable->deleted_at, 'DD MMMM YYYY')" type="red" class="!leading-none" />
                                    @else
                                        @can('admin.ventas.edit')
                                            <a class="font-semibold text-sm leading-4 text-linktable hover:text-hoverlinktable transition-colors ease-out duration-150"
                                                href="{{ route('admin.ventas.edit', $item->cotizable) }}"
                                                target="_blank">
                                                <span class="text-3xl">{{ $item->cotizable->seriecompleta }}</span>
                                                {{ $item->cotizable->seriecomprobante->typecomprobante->name }}
                                            </a>
                                        @endcan

                                        @cannot('admin.ventas.edit')
                                            <h1 class="font-semibold text-sm leading-4 text-colortitleform">
                                                <span class="text-3xl">{{ $item->cotizable->seriecompleta }}</span>
                                                {{ $item->cotizable->seriecomprobante->typecomprobante->name }}
                                            </h1>
                                        @endcannot
                                    @endif

                                    <h1 class="font-medium text-xs leading-4">
                                        {{ $item->cotizable->client->name }} -
                                        {{ $item->cotizable->client->document }}
                                        <p>DIRECCIÓN : {{ $item->cotizable->direccion }}</p>
                                    </h1>

                                    <h1 class="font-medium text-xs">
                                        {{ formatDate($item->cotizable->date) }}
                                    </h1>

                                    <h1 class="font-semibold text-sm leading-none text-end text-colortitleform">
                                        {{ $cotizacion->moneda->simbolo }}
                                        <span
                                            class="text-3xl">{{ number_format($item->cotizable->total, 2, '.', ', ') }}</span>
                                    </h1>
                                </x-simple-card>
                            @endif
                        @endforeach
                    </div>
                @endif

                @if ($cotizacion->isAprobbed())
                    @can('admin.cotizaciones.facturar')
                        @if (Module::isEnabled('Facturacion') || Module::isEnabled('Ventas'))
                            <div class="flex justify-end">
                                <x-button wire:click="openmodalcpe" wire:key="openmodalcpe">
                                    GENERAR COMPROBANTE</x-button>
                            </div>
                        @endif
                    @endcan
                @else
                    @can('admin.cotizaciones.aprobar')
                        <div class="flex justify-end">
                            <x-button class="" wire:click="aprobar" wire:loading.attr="disabled"
                                wire:key="aprobarcotizacion">
                                {{ __('Aprobar cotización') }}</x-button>
                        </div>
                    @endcan
                @endif
            </div>

            <div class="w-full fixed z-[3] bottom-0 p-1 md:p-3 left-0 xs:px-8 bg-body"
                :class="openSidebar ? 'md:w-[calc(100%-12rem)] md:left-48' : 'md:w-[calc(100%-4rem)] md:left-16'">
                <div class="w-full xl:max-w-7xl mx-auto flex flex-wrap items-end gap-2 justify-between px-1 lg:px-3">
                    <x-link-button href="{{ route('admin.cotizacions.print.a4', $cotizacion) }}" class="uppercase"
                        target="_blank" wire:loading.attr="disabled">
                        {{ __('Print a4') }}</x-link-button>
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('Save') }}</x-button>
                </div>
            </div>
        </form>
    </x-form-card>

    @if (!$cotizacion->isAprobbed() || ($cotizacion->isAprobbed() && count($cotizacion->cotizaciongarantias) > 0))
        <x-form-card titulo="AGREGAR GARANTÍAS" class="gap-2">
            <div
                class="w-full @if (!$cotizacion->isAprobbed()) grid @endif  grid-cols-1 xs:grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-2">
                @if (!$cotizacion->isAprobbed())
                    <div class="w-full flex flex-col gap-2">
                        <div class="w-full">
                            <x-label value="Tipo de garantía :" />
                            <div id="parenttypegarantia" class="relative" x-init="selec2Typegarantia">
                                <x-select class="block w-full" x-ref="selecttypegarantia" id="typegarantia">
                                    <x-slot name="options">
                                        @if (count($typegarantias) > 0)
                                            @foreach ($typegarantias as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        @endif
                                    </x-slot>
                                </x-select>
                                <x-icon-select />
                            </div>
                            <x-jet-input-error for="typegarantia_id" />
                        </div>

                        <div>
                            <x-label value="Tiempo de garantía :" />
                            <div class="w-full flex">
                                <x-input class="block w-1/3 text-end !rounded-tr-none !rounded-br-none"
                                    wire:model.defer="timegarantia" type="number" min="1"
                                    onkeypress="return validarNumero(event, 11)"
                                    onpaste="return validarPasteNumero(event, 11)" wire:keydown.enter="addgarantia" />
                                <div id="parentdatecodegarantia" class="relative flex-1" x-init="selectWarrantyGarantia">
                                    <x-select class="block w-full" x-ref="rpcot_datecodegarantia"
                                        id="datecodegarantia">
                                        <x-slot name="options">
                                            <option value="D">DÍAS</option>
                                            <option value="M">MESES</option>
                                            <option value="Y">AÑOS</option>
                                        </x-slot>
                                    </x-select>
                                    <x-icon-select />
                                </div>
                            </div>
                            <x-jet-input-error for="timegarantia" />
                            <x-jet-input-error for="datecodegarantia" />
                        </div>

                        <div class="flex gap-2">
                            <x-button wire:click="resetgarantia" wire:loading.attr="disabled">
                                {{ __('Limpiar') }}</x-button>
                            <x-button wire:click="addgarantia" wire:loading.attr="disabled">
                                {{ __('Agregar') }}</x-button>
                        </div>
                    </div>
                @endif
                @if (count($cotizacion->cotizaciongarantias) > 0)
                    <div
                        class="w-full sm:col-span-2 lg:col-span-3 xl:col-span-4 grid grid-cols-[repeat(auto-fill,minmax(120px,1fr))] gap-1 self-start">
                        @foreach ($cotizacion->cotizaciongarantias as $item)
                            <x-simple-card
                                class="p-2 text-center font-medium h-28 flex flex-col items-center justify-between gap-2">
                                <div class="w-full flex flex-col flex-1 justify-center">
                                    <h1 class="text-xs text-colortitleform leading-none">
                                        {{ $item->typegarantia->name }}</h1>
                                    <h1 class="text-xs text-colorsubtitleform">
                                        {{ $item->time }} {{ getNameTime($item->datecode) }}
                                    </h1>
                                </div>

                                @if (!$cotizacion->isAprobbed())
                                    @can('admin.cotizaciones.delete')
                                        <div class="w-full flex gap-2 justify-end">
                                            <x-button-delete wire:loading.attr="disabled"
                                                wire:click="deletegarantia('{{ $item->id }}')"
                                                wire.key="deletgar_{{ $item->id }}" />
                                        </div>
                                    @endcan
                                @endif
                            </x-simple-card>
                        @endforeach
                    </div>
                @endif
            </div>
        </x-form-card>
    @endif

    @php
        $ofertas = $cotizacion->tvitems->where('gratuito', \App\Models\Tvitem::GRATUITO);
        $tvitems = $cotizacion->tvitems->where('gratuito', \App\Models\Tvitem::NO_GRATUITO);
    @endphp

    @if (!$cotizacion->isAprobbed())
        <x-form-card titulo="AGREGAR PRODUCTOS" class="gap-2">
            <div
                class="w-full grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-2">
                <div class="w-full" x-cloak x-show="{{ view()->shared('empresa')->usarLista() ? true : false }}">
                    <x-label value="Lista de precio :" />
                    <div id="parentpricetype_id" class="relative" x-init="selec2Pricetype">
                        <x-select class="block w-full" x-ref="selectpricetype" id="pricetype_id">
                            <x-slot name="options">
                                @if (count($pricetypes) > 0)
                                    @foreach ($pricetypes as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="pricetype_id" />
                </div>
            </div>

            <div
                class="w-full grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-2">
                <div class="xs:col-span-2 sm:col-span-3 lg:col-span-2 xl:col-span-3">
                    <x-label value="Seleccionar producto :" />
                    <div class="w-full relative" x-init="select2Producto" id="parentproducto_id">
                        <x-select class="block w-full uppercase" x-ref="selectprod"
                            data-minimum-results-for-search="3" id="producto_id">
                            <x-slot name="options">
                                @if (count($productos) > 0)
                                    @foreach ($productos as $item)
                                        <option data-marca="{{ $item->name_marca }}"
                                            data-category="{{ $item->name_category }}"
                                            data-subcategory="{{ $item->name_subcategory }}"
                                            data-requireserie="{{ $item->isRequiredserie() }}"
                                            data-image="{{ !empty($item->imagen) ? pathURLProductImage($item->imagen->urlmobile) : null }}"
                                            value="{{ $item->id }}">
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="producto_id" />
                </div>
                <div class="w-full">
                    <x-label value="Precio :" />
                    <x-input class="block w-full text-end input-number-none" wire:key="{{ rand() }}"
                        wire:model.defer="pricesale" wire:keydown.enter="addproducto" type="number" min="0"
                        step="0.001" onkeypress="return validarNumero(event, 11)" />
                    <x-jet-input-error for="pricesale" />
                </div>
                <div class="w-full">
                    <x-label value="Cantidad :" />
                    <x-input class="block w-full text-end input-number-none" wire:key="{{ rand() }}"
                        wire:model.defer="cantidad" wire:keydown.enter="addproducto" type="number" min="1"
                        step="1" onkeypress="return validarNumero(event, 11)" />
                    <x-jet-input-error for="cantidad" />
                </div>
            </div>
            <div class="flex flex-col flex-col-reverse xs:flex-row items-start gap-2">
                <div class="flex gap-2">
                    <x-button wire:click="resetproducto" wire:loading.attr="disabled">
                        {{ __('Limpiar') }}</x-button>
                    <x-button wire:click="addproducto" wire:loading.attr="disabled">
                        {{ __('Agregar') }}</x-button>
                </div>
            </div>
        </x-form-card>
    @endif

    @if (count($tvitems) > 0)
        <x-form-card titulo="PRODUCTOS A COTIZAR">
            <div class="w-full grid grid-cols-[repeat(auto-fill,minmax(160px,1fr))] sm:grid-cols-[repeat(auto-fill,minmax(200px,1fr))] gap-1"
                wire:key="tvitems_cot">
                @foreach ($tvitems as $key => $item)
                    @php
                        $imgtvitem = null;
                        if (!empty($item->producto->imagen)) {
                            $imgtvitem = pathURLProductImage($item->producto->imagen->urlmobile);
                        }
                    @endphp
                    <x-card-producto :image="$imgtvitem" :name="$item->producto->name" id="cardprodcot_{{ $item->id }}"
                        x-data="{ showForm: false }">
                        <table class="w-full table text-[10px] text-colorsubtitleform">
                            <tr>
                                <td class="align-middle text-center text-xl font-semibold" colspan="2">
                                    {{ decimalOrInteger($item->cantidad) }}
                                    <small>{{ $item->producto->unit->name }}</small>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="align-middle text-center">TOTAL</td>
                            </tr>
                            <tr>
                                <td class="text-center" colspan="2">
                                    <span class="text-xl font-semibold !leading-none">
                                        {{ number_format($item->total, 2, '.', ', ') }}
                                    </span>
                                    <small class="text-xs font-medium">{{ $cotizacion->moneda->currency }}</small>
                                </td>
                            </tr>
                            <tr>
                                <td class="align-middle">P.U. VENTA</td>
                                <td class="text-end">
                                    <span class="text-sm font-semibold">
                                        {{ number_format($item->price + $item->igv, 2, '.', ', ') }}
                                    </span>
                                    <small class="text-xs font-medium">{{ $cotizacion->moneda->currency }}</small>

                                    @if ($item->igv > 0)
                                        INC. IGV
                                    @endif
                                </td>
                            </tr>
                            {{-- <tr>
                            <td class="align-middle">SUBTOTAL</td>
                            <td class="text-end">
                                <span class="text-sm font-semibold">
                                    {{ decimalOrInteger($item['subtotalitem'], 2, ', ') }}</span>
                                <small x-text="namemoneda"></small>
                            </td>
                        </tr> --}}
                        </table>

                        @if (!$cotizacion->isAprobbed())
                            <x-slot name="footer">
                                <div class="w-full flex justify-end">
                                    <x-button-delete wire:key="deletetvitem{{ $item->id }}"
                                        wire:click="deletetvitem('{{ $item->id }}')"
                                        wire:loading.attr="disabled" />
                                </div>
                            </x-slot>
                        @endif
                    </x-card-producto>
                @endforeach
            </div>
        </x-form-card>
    @endif

    @if (!$cotizacion->isAprobbed() || ($cotizacion->isAprobbed() && count($cotizacion->otheritems) > 0))
        <x-form-card titulo="OTROS AGREGADOS Y/O SERVICIOS" class="gap-2">
            <div
                class="w-full @if (!$cotizacion->isAprobbed()) grid @endif grid-cols-1 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-2">
                @if (!$cotizacion->isAprobbed())
                    <div class="w-full md:col-span-3 lg:col-span-4 xl:col-span-2 flex flex-col gap-2">
                        <div class="w-full grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2">
                            <div class="w-full xs:col-span-2 sm:col-span-3 md:col-span-2 lg:col-span-3 xl:col-span-4">
                                <x-label value="Descripción :" />
                                <x-text-area class="block w-full" wire:model.defer="nameother"></x-text-area>
                                <x-jet-input-error for="nameother" />
                            </div>

                            <div class="w-full xl:col-span-2">
                                <x-label value="Marca :" />
                                <x-input class="block w-full" wire:model.defer="marcaother"
                                    wire:keydown.enter="addotherproducto" />
                                <x-jet-input-error for="marcaother" />
                            </div>
                            <div class="w-full xl:col-span-2">
                                <x-label value="Precio :" />
                                <x-input class="block w-full text-end input-number-none"
                                    wire:key="{{ rand() }}" wire:model.defer="priceother"
                                    wire:keydown.enter="addotherproducto" type="number" min="0.001"
                                    step="0.01" onkeypress="return validarDecimal(event, 18)" />
                                <x-jet-input-error for="priceother" />
                            </div>
                            <div class="w-full xl:col-span-2">
                                <x-label value="Cantidad :" />
                                <x-input class="block w-full text-end input-number-none"
                                    wire:key="{{ rand() }}" wire:model.defer="cantidadother"
                                    wire:keydown.enter="addotherproducto" type="number" min="1"
                                    step="1" onkeypress="return validarDecimal(event, 18)" />
                                <x-jet-input-error for="cantidadother" />
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <x-button wire:click="resetotherproducto" wire:loading.attr="disabled">
                                {{ __('Limpiar') }}</x-button>
                            <x-button wire:click="addotherproducto" wire:loading.attr="disabled">
                                {{ __('Agregar') }}</x-button>
                        </div>
                    </div>
                @endif

                @if (count($cotizacion->otheritems) > 0)
                    <div class="w-full md:col-span-3 lg:col-span-4 xl:col-span-3">
                        <x-table class="w-full">
                            <x-slot name="body">
                                @foreach ($cotizacion->otheritems as $key => $item)
                                    <tr>
                                        <td class="p-2 leading-none" style="min-width: 180px;">
                                            <p class="italic text-colorsubtitleform font-normal">
                                                {{ $item->name }}</p>

                                            <small>{{ $cotizacion->moneda->simbolo }}</small>
                                            <span class="text-sm font-semibold">
                                                {{ number_format($item->price + $item->igv, 2, '.', ', ') }}</span>
                                            <small>C/U</small>
                                            @if ($item->igv > 0)
                                                INC. IGV
                                            @endif
                                        </td>
                                        <td class="p-2 text-center font-normal italic leading-none" width="80px">
                                            @if (!empty($item->marca))
                                                {{ $item->marca }}
                                            @endif
                                        </td>
                                        <td class="p-2 text-center font-semibold text-sm" width="80px">
                                            x {{ decimalOrInteger($item->cantidad) }}
                                            <small class="font-normal text-[10px]">{{ $item->unit->name }}</small>
                                        </td>
                                        <td class="p-2 text-center" width="100px">
                                            <small>{{ $cotizacion->moneda->simbolo }}</small>
                                            <span class="text-sm font-semibold !leading-none">
                                                {{ number_format($item->total, 2, '.', ', ') }}
                                            </span>
                                        </td>
                                        @if (!$cotizacion->isAprobbed())
                                            <td class="p-2 text-end" width="70px">
                                                <x-button-edit wire:click="edititemother('{{ $item->id }}')"
                                                    wire:key="{{ $item->id }}" wire:loading.attr="disabled" />
                                                <x-button-delete wire:click="removeitemother('{{ $item->id }}')"
                                                    wire:key="{{ $item->id }}" wire:loading.attr="disabled" />
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </x-slot>
                        </x-table>
                    </div>
                @endif
            </div>
        </x-form-card>
    @endif

    @if (!$cotizacion->isAprobbed() || ($cotizacion->isAprobbed() && count($ofertas) > 0))
        <x-form-card titulo="AGREGAR OFERTAS" class="gap-2">
            <div
                class="w-full @if (!$cotizacion->isAprobbed()) grid @endif grid-cols-1 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-2">
                @if (!$cotizacion->isAprobbed())
                    <div class="w-full md:col-span-3 lg:col-span-4 xl:col-span-2 flex flex-col gap-2">
                        <div class="w-full grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2">
                            <div class="w-full xs:col-span-2 sm:col-span-3 md:col-span-2 lg:col-span-3 xl:col-span-4">
                                <x-label value="Seleccionar producto :" />
                                <div class="w-full relative" x-init="select2ProductoFree" id="parentproductofree_id">
                                    <x-select class="block w-full uppercase" x-ref="selectprodfree"
                                        data-minimum-results-for-search="3" id="productofree_id">
                                        <x-slot name="options">
                                            @if (count($productos) > 0)
                                                @foreach ($productos as $item)
                                                    <option data-marca="{{ $item->name_marca }}"
                                                        data-category="{{ $item->name_category }}"
                                                        data-subcategory="{{ $item->name_subcategory }}"
                                                        data-requireserie="{{ $item->isRequiredserie() }}"
                                                        data-image="{{ !empty($item->imagen) ? pathURLProductImage($item->imagen->urlmobile) : null }}"
                                                        value="{{ $item->id }}">
                                                        {{ $item->name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </x-slot>
                                    </x-select>
                                    <x-icon-select />
                                </div>
                                <x-jet-input-error for="productofree_id" />
                            </div>

                            <div class="w-full xl:col-span-2">
                                <x-label value="Cantidad :" />
                                <x-input class="block w-full text-end input-number-none"
                                    wire:key="{{ rand() }}" wire:model.defer="cantidadfree"
                                    wire:keydown.enter="addproductofree" type="number" min="1"
                                    step="1" onkeypress="return validarNumero(event, 11)" />
                                <x-jet-input-error for="cantidadfree" />
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <x-button wire:click="resetproductofree" wire:loading.attr="disabled">
                                {{ __('Limpiar') }}</x-button>
                            <x-button wire:click="addproductofree" wire:loading.attr="disabled">
                                {{ __('Agregar') }}</x-button>
                        </div>
                    </div>
                @endif

                @if ($ofertas->count() > 0)
                    <div class="w-full md:col-span-3 lg:col-span-2 xl:col-span-3 grid grid-cols-[repeat(auto-fill,minmax(160px,1fr))] sm:grid-cols-[repeat(auto-fill,minmax(175px,1fr))] gap-1"
                        wire:key="tvitemsfree_cot">
                        @foreach ($ofertas as $key => $item)
                            <x-card-producto :image="!empty($item->producto->imagen->url)
                                ? pathURLProductImage($item->producto->imagen->url)
                                : null" :name="$item->producto->name" id="cardprodfree_{{ $item->id }}"
                                x-data="{ showForm: false }">

                                <table class="w-full table text-[10px] text-colorsubtitleform">
                                    <tr>
                                        <td class="align-middle text-center text-xl font-semibold" colspan="2">
                                            {{ decimalOrInteger($item->cantidad) }}
                                            <small>{{ $item->producto->unit->name }}</small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center" colspan="2">
                                            <x-span-text text="GRATIS" type="green" class="px-2" />
                                        </td>
                                    </tr>
                                </table>

                                @if (!$cotizacion->isAprobbed())
                                    <x-slot name="footer">
                                        <div class="w-full flex justify-end">
                                            <x-button-delete wire:key="deletetvitem{{ $item->id }}"
                                                wire:click="deletetvitem('{{ $item->id }}')"
                                                wire:loading.attr="disabled" />
                                        </div>
                                    </x-slot>
                                @endif
                            </x-card-producto>
                        @endforeach
                    </div>
                @endif
            </div>

        </x-form-card>
    @endif

    <table class="w-full table text-xs text-colorsubtitleform">
        <tr>
            <td class="align-bottom text-sm font-semibold text-end">
                SUBTOTAL :
            </td>
            <td class="text-end text-xl font-semibold w-40">
                <small class="text-xs font-medium">{{ $cotizacion->moneda->simbolo }}</small>
                {{ number_format($cotizacion->subtotal, 2, '.', ', ') }}
                <small class="text-xs font-medium">{{ $cotizacion->moneda->currency }}</small>
            </td>
        </tr>
        <tr>
            <td class="align-bottom text-sm font-semibold text-end">
                IGV :
            </td>
            <td class="text-end text-xl font-semibold w-40">
                <small class="text-xs font-medium">{{ $cotizacion->moneda->simbolo }}</small>
                {{ number_format($cotizacion->igv, 2, '.', ', ') }}
                <small class="text-xs font-medium">{{ $cotizacion->moneda->currency }}</small>
            </td>
        </tr>
        <tr>
            <td class="align-bottom text-sm font-semibold text-end">
                TOTAL :
            </td>
            <td class="text-end text-xl font-semibold w-40">
                <small class="text-xs font-medium">{{ $cotizacion->moneda->simbolo }}</small>
                {{ number_format($cotizacion->total, 2, '.', ', ') }}
                <small class="text-xs font-medium">{{ $cotizacion->moneda->currency }}</small>
            </td>
        </tr>
    </table>

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar otros servicios') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="updateitemother" class="w-full flex flex-col gap-2">
                <div class="w-full flex flex-col gap-2">
                    <div class="w-full">
                        <x-label value="Descripción :" />
                        <x-text-area class="block w-full" wire:model.defer="otheritem.name"></x-text-area>
                        <x-jet-input-error for="otheritem.name" />
                    </div>

                    <div class="w-full">
                        <x-label value="Marca :" />
                        <x-input class="block w-full" wire:model.defer="otheritem.marca" />
                        <x-jet-input-error for="otheritem.marca" />
                    </div>

                    <div class="w-full grid grid-cols-2 gap-2">
                        <div class="w-full">
                            <x-label value="Precio :" />
                            <x-input class="block w-full text-end input-number-none"
                                wire:model.defer="otheritem.price" type="number" min="0.01" step="0.01"
                                onkeypress="return validarDecimal(event, 18)" />
                            <x-jet-input-error for="otheritem.price" />
                        </div>
                        <div class="w-full">
                            <x-label value="Cantidad :" />
                            <x-input class="block w-full text-end input-number-none"
                                wire:model.defer="otheritem.cantidad" type="number" min="1" step="1"
                                onkeypress="return validarDecimal(event, 18)" />
                            <x-jet-input-error for="otheritem.cantidad" />
                        </div>
                    </div>
                </div>

                <div class="w-full flex gap-2 justify-end">
                    {{-- <x-button-secondary wire:click="$set" class="justify-center" type="button"
                        wire:loading.attr="disabled">
                    {{ __('Cancel') }}</x-button-secondary> --}}
                    <x-button class="" type="submit" wire:loading.attr="disabled">
                        {{ __('Save') }}</x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <x-jet-dialog-modal wire:model="opencpe" maxWidth="3xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Generar comprobante de venta') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="savecomprobante" class="w-full flex flex-col gap-2">
                <div class="w-full grid grid-cols-1 xs:grid-cols-2 lg:grid-cols-3 gap-2">

                    <div class="w-full">
                        <x-label value="Tipo de comprobante :" />
                        <div id="parenttypeccomp" class="relative" x-init="selec2Typecomprobante">
                            <x-select class="block w-full" x-ref="selecttypecpe" id="typeccomp"
                                data-placeholder="null">
                                <x-slot name="options">
                                    @if (count($typecomprobantes) > 0)
                                        @foreach ($typecomprobantes as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->typecomprobante->descripcion }} [{{ $item->serie }}]
                                            </option>
                                        @endforeach
                                    @endif
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="typecomprobante_id" />
                    </div>
                    <div class="w-full">
                        <x-label value="Moneda :" />
                        <x-disabled-text :text="$cotizacion->moneda->currency" />
                        <x-jet-input-error for="moneda_id" />
                    </div>
                    <div class="w-full">
                        <x-label value="Modalidad de pago :" />
                        <x-disabled-text :text="$cotizacion->typepayment->name" />
                        <x-jet-input-error for="typepayment_id" />
                    </div>

                    <div class="w-full">
                        <x-label value="DNI / RUC :" />
                        @if ($client_id)
                            <div class="w-full inline-flex relative">
                                <x-disabled-text :text="$document" class="w-full flex-1 block" />
                                <x-button-close-modal class="btn-desvincular" wire:click="limpiarcliente"
                                    wire:loading.attr="disabled" />
                            </div>
                        @else
                            <div class="w-full inline-flex gap-1">
                                <x-input class="block w-full flex-1 input-number-none" wire:model.defer="document"
                                    type="number" wire:keydown.enter.prevent="searchcliente"
                                    onkeypress="return validarNumero(event, 11)" {{-- onpaste="return validarPasteNumero(event, 11)" --}} />
                                <x-button-add class="px-2" wire:click="searchcliente" wire:loading.attr="disabled">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <circle cx="11" cy="11" r="8" />
                                        <path d="m21 21-4.3-4.3" />
                                    </svg>
                                </x-button-add>
                            </div>
                        @endif
                        <x-jet-input-error for="document" />
                        <x-jet-input-error for="client_id" />
                    </div>

                    <div class="w-full lg:col-span-2">
                        <x-label value="Cliente / Razón Social :" />
                        <x-input class="block w-full" wire:model.defer="name" />
                        <x-jet-input-error for="name" />
                    </div>

                    <div class="w-full lg:col-span-3">
                        <x-label value="Dirección :" />
                        <x-input class="block w-full" wire:model.defer="direccion" />
                        <x-jet-input-error for="direccion" />
                    </div>

                    <div class="w-full xs:col-span-3">
                        <x-label value="Observaciones :" />
                        <x-text-area class="block w-full" rows="3"
                            wire:model.defer="observaciones"></x-text-area>
                        <x-jet-input-error for="observaciones" />
                    </div>
                </div>

                <div class="w-full flex justify-end">
                    <x-button type="submit">{{ __('Save') }}</x-button>
                </div>

                <table class="w-full table text-xs text-colorsubtitleform">
                    <tr>
                        <td class="align-bottom text-sm font-semibold text-end">
                            SUBTOTAL :
                        </td>
                        <td class="text-end text-xl font-semibold w-40">
                            <small class="text-xs font-medium">{{ $cotizacion->moneda->simbolo }}</small>
                            {{ number_format($cotizacion->subtotal, 2, '.', ', ') }}
                            <small class="text-xs font-medium">{{ $cotizacion->moneda->currency }}</small>
                        </td>
                    </tr>
                    <tr>
                        <td class="align-bottom text-sm font-semibold text-end">
                            IGV :
                        </td>
                        <td class="text-end text-xl font-semibold w-40">
                            <small class="text-xs font-medium">{{ $cotizacion->moneda->simbolo }}</small>
                            {{ number_format($cotizacion->igv, 2, '.', ', ') }}
                            <small class="text-xs font-medium">{{ $cotizacion->moneda->currency }}</small>
                        </td>
                    </tr>
                    <tr>
                        <td class="align-bottom text-sm font-semibold text-end">
                            TOTAL :
                        </td>
                        <td class="text-end text-xl font-semibold w-40">
                            <small class="text-xs font-medium">{{ $cotizacion->moneda->simbolo }}</small>
                            {{ number_format($cotizacion->total, 2, '.', ', ') }}
                            <small class="text-xs font-medium">{{ $cotizacion->moneda->currency }}</small>
                        </td>
                    </tr>
                </table>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('showcot', () => ({
                open: false,
                datecode: @entangle('cotizacion.datecode').defer,
                producto_id: @entangle('producto_id'),
                pricetype_id: @entangle('pricetype_id'),
                typegarantia_id: @entangle('typegarantia_id').defer,
                datecodegarantia: @entangle('datecodegarantia').defer,
                productofree_id: @entangle('productofree_id').defer,
                addadress: @entangle('addadress').defer,
                ubigeo_id: @entangle('ubigeo_id').defer,
                typecomprobante_id: @entangle('typecomprobante_id').defer,
                aprobbed: '{{ $cotizacion->isAprobbed() }}',

                init() {
                    this.adjustHeight(this.$refs.detalle_cotizacion);
                    this.$watch("ubigeo_id", (value) => {
                        this.selectUBC.val(value).trigger("change");
                    });
                    this.$watch("producto_id", (value) => {
                        this.selectP.val(value).trigger("change");
                    });
                    this.$watch("productofree_id", (value) => {
                        this.selectPFC.val(value).trigger("change");
                    });
                    this.$watch("pricetype_id", (value) => {
                        this.selectPTC.val(value).trigger("change");
                    });
                    this.$watch("datecode", (value) => {
                        this.selectCEN.val(value).trigger("change");
                    });
                    this.$watch("typegarantia_id", (value) => {
                        this.selectCTG.val(value).trigger("change");
                    });
                    this.$watch("datecodegarantia", (value) => {
                        this.selectCWTG.val(value).trigger("change");
                    });
                    this.$watch("typecomprobante_id", (value) => {
                        this.selectCPEC.val(value).trigger("change");
                    });

                    Livewire.hook('message.processed', () => {
                        this.adjustHeight(this.$refs.detalle_cotizacion);

                        if (this.selectCPEC) {
                            this.selectCPEC.select2().val(this.typecomprobante_id).trigger(
                                'change');
                        }
                        if (this.selectCEN) {
                            this.selectCEN.select2().val(this.datecode).trigger('change');
                        }
                        if (this.selectCTG) {
                            this.selectCTG.select2().val(this.typegarantia_id).trigger(
                                'change');
                        }
                        if (this.selectCWTG) {
                            this.selectCWTG.select2().val(this.datecodegarantia).trigger(
                                'change');
                        }
                        if (this.selectPTC) {
                            this.selectPTC.select2().val(this.pricetype_id).trigger('change');
                        }
                        if (this.selectUBC) {
                            this.selectUBC.select2().val(this.ubigeo_id).trigger('change');
                        }
                        if (this.selectP) {
                            this.selectP.select2({
                                templateResult: formatOption
                            }).val(this.producto_id).trigger('change');
                        }
                        if (this.selectPFC) {
                            this.selectPFC.select2({
                                templateResult: formatOption
                            }).val(this.productofree_id).trigger('change');
                        }
                    });
                },
                adjustHeight($el) {
                    if ($el) {
                        $el.style.height = 'auto';
                        $el.style.height = $el.scrollHeight + 'px';
                    }
                },
            }))
        })

        function select2Producto() {
            this.selectP = $(this.$refs.selectprod).select2({
                templateResult: formatOption
            });
            this.selectP.val(this.producto_id).trigger("change");
            this.selectP.on("select2:select", (event) => {
                this.producto_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
        }

        function select2ProductoFree() {
            this.selectPFC = $(this.$refs.selectprodfree).select2({
                templateResult: formatOption
            });
            this.selectPFC.val(this.productofree_id).trigger("change");
            this.selectPFC.on("select2:select", (event) => {
                this.productofree_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
        }

        function selec2UbigeoCot() {
            this.selectUBC = $(this.$refs.selectubigeo_id).select2();
            this.selectUBC.val(this.ubigeo_id).trigger("change");
            this.selectUBC.on("select2:select", (event) => {
                this.ubigeo_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
        }

        function selectCotEntrega() {
            this.selectCEN = $(this.$refs.rpcot_entrega).select2();
            this.selectCEN.val(this.datecode).trigger("change");
            this.selectCEN.on("select2:select", (event) => {
                this.datecode = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
        }

        function selec2Typegarantia() {
            this.selectCTG = $(this.$refs.selecttypegarantia).select2();
            this.selectCTG.val(this.typegarantia_id).trigger("change");
            this.selectCTG.on("select2:select", (event) => {
                this.typegarantia_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
        }

        function selectWarrantyGarantia() {
            this.selectCWTG = $(this.$refs.rpcot_datecodegarantia).select2();
            this.selectCWTG.val(this.datecodegarantia).trigger("change");
            this.selectCWTG.on("select2:select", (event) => {
                this.datecodegarantia = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
        }

        function selec2Pricetype() {
            this.selectPTC = $(this.$refs.selectpricetype).select2();
            this.selectPTC.val(this.pricetype_id).trigger("change");
            this.selectPTC.on("select2:select", (event) => {
                this.pricetype_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
        }

        function selec2Typecomprobante() {
            this.selectCPEC = $(this.$refs.selecttypecpe).select2();
            this.selectCPEC.val(this.typecomprobante_id).trigger("change");
            this.selectCPEC.on("select2:select", (event) => {
                this.typecomprobante_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
        }

        function formatOption(data) {
            if (!data.id) {
                return data.text;
            }
            const image = $(data.element).data('image') ?? '';
            const marca = $(data.element).data('marca') ?? '';
            const category = $(data.element).data('category') ?? '';
            const subcategory = $(data.element).data('subcategory') ?? '';

            let html = `<div class="custom-list-select">
                        <div class="image-custom-select">`;
            if (image) {
                html +=
                    `<img src="${image}" class="w-full h-full object-scale-down block" alt="${data.text}">`;
            } else {
                html += `<x-icon-image-unknown class="w-full h-full" />`;
            }
            html += `</div>
                            <div class="content-custom-select">
                                <p class="title-custom-select">
                                    ${data.text}</p>
                                <p class="marca-custom-select">
                                    ${marca}</p>  
                                <div class="category-custom-select">
                                    <span class="inline-block">${category}</span>
                                    <span class="inline-block">${subcategory}</span>
                                </div>  
                            </div>
                      </div>`;
            return $(html);

        };
    </script>
</div>
