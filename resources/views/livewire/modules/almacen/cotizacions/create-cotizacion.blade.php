<div class="relative w-full flex flex-col gap-3 lg:gap-5" x-data="createcot">
    <div wire:loading.flex class="fixed loading-overlay hidden">
        <x-loading-next />
    </div>

    <x-form-card titulo="DATOS DE LA COTIZACIÓN">
        <form wire:submit.prevent="save" class="w-full flex flex-col gap-2">
            <div class="w-full grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-2">

                <div class="w-full xs:col-span-2 sm:col-span-1">
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
                </div>

                <div class="w-full xs:col-span-2">
                    <x-label value="Cliente / Razón Social :" />
                    <x-input class="block w-full" wire:model.defer="name" />
                    <x-jet-input-error for="name" />
                </div>

                <div class="w-full xs:col-span-2">
                    <x-label value="Dirección :" />
                    <x-input class="block w-full" wire:model.defer="direccion" />
                    <x-jet-input-error for="direccion" />
                </div>

                <div class="w-full">
                    <x-label value="Moneda :" />
                    <div id="parentmoneda_id" class="relative" x-init="selec2Moneda">
                        <x-select class="block w-full" x-ref="selectmoneda" id="moneda_id">
                            <x-slot name="options">
                                @if (count($monedas) > 0)
                                    @foreach ($monedas as $item)
                                        <option value="{{ $item->id }}" data-code="{{ $item->code }}"
                                            data-simbolo="{{ $item->simbolo }}" data-currency="{{ $item->currency }}">
                                            {{ $item->currency }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="moneda_id" />
                </div>

                <div class="w-full">
                    <x-label value="Modalidad de pago :" />
                    <div id="parenttypepayment_id" class="relative" x-init="selec2Typepayment">
                        <x-select class="block w-full" x-ref="selecttypepayment" id="typepayment_id">
                            <x-slot name="options">
                                @if (count($typepayments) > 0)
                                    @foreach ($typepayments as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="typepayment_id" />
                </div>

                <div class="w-full">
                    <x-label value="Afectación IGV :" />
                    <div id="parentrpcot_afectacionigv" x-init="selectCotAfectacion" class="relative">
                        <x-select class="block w-full" x-ref="rpcot_afectacionigv" id="rpcot_afectacionigv"
                            data-dropdown-parent="null">
                            <x-slot name="options">
                                <option value="0">EXONERAR IGV</option>
                                <option value="1">INCLUIR IGV</option>
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="afectacionigv" />
                </div>

                <div>
                    <x-label value="Tiempo de entrega :" />
                    <div class="w-full flex">
                        <x-input class="block w-1/3 text-end !rounded-tr-none !rounded-br-none"
                            wire:model.defer="timeentrega" type="number" min="1"
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
                    <x-jet-input-error for="timeentrega" />
                    <x-jet-input-error for="datecode" />
                </div>

                <div class="w-full">
                    <x-label value="Validéz cotización :" />
                    <x-input type="date" class="block w-full" wire:model.defer="validez"
                        min="{{ now('America/Lima')->format('Y-m-d') }}" />
                    <x-jet-input-error for="validez" />
                </div>

                <div class="w-full xs:col-span-2 lg:col-span-4 xl:col-span-5">
                    <x-label value="Descripción de cotización :" />
                    <x-text-area class="block w-full" wire:model.defer="detalle" x-ref="detalle_cotizacion"
                        style="overflow:hidden;resize:none;" x-on:input="adjustHeight($el)"></x-text-area>
                    <x-jet-input-error for="detalle" />
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

            <div class="w-full fixed z-[3] bottom-0 p-1 md:p-3 left-0 xs:px-8 bg-body"
                :class="openSidebar ? 'md:w-[calc(100%-12rem)] md:left-48' : 'md:w-[calc(100%-4rem)] md:left-16'">
                <div class="w-full xl:max-w-7xl mx-auto flex justify-end px-1 lg:px-3">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('Save') }}</x-button>
                </div>
            </div>
        </form>
    </x-form-card>

    <x-form-card titulo="AGREGAR GARANTÍAS" class="gap-2">
        <div class="w-full grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-2">
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
                            onpaste="return validarPasteNumero(event, 11)" />
                        <div id="parentdatecodegarantia" class="relative flex-1" x-init="selectWarrantyGarantia">
                            <x-select class="block w-full" x-ref="rpcot_datecodegarantia" id="datecodegarantia">
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
            @if (count($garantias) > 0)
                <div
                    class="w-full sm:col-span-2 lg:col-span-3 xl:col-span-4 grid grid-cols-[repeat(auto-fill,minmax(120px,1fr))] gap-1 self-start">
                    @foreach ($garantias as $item)
                        <x-simple-card
                            class="p-2.5 text-center font-medium h-28 flex flex-col items-center justify-center gap-2">
                            <h1 class="text-xs text-colortitleform leading-none">
                                {{ $item['name'] }}</h1>
                            <h1 class="text-xs text-colorsubtitleform">
                                {{ $item['time'] }} {{ getNameTime($item['datecode']) }}
                            </h1>

                            <div class="w-full flex gap-2 justify-center items-center">
                                <div class="">
                                    <x-button-edit wire:loading.attr="disabled"
                                        wire:click="editgarantia('{{ $item['id'] }}')"
                                        wire:key="editgar_{{ $item['id'] }}" />
                                    <x-button-delete wire:loading.attr="disabled"
                                        wire:click="deletegarantia('{{ $item['id'] }}')"
                                        wire.key="deletgar_{{ $item['id'] }}" />
                                </div>
                            </div>
                        </x-simple-card>
                    @endforeach
                </div>
            @endif
        </div>
    </x-form-card>

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
                    <x-select class="block w-full uppercase" x-ref="selectprod" data-minimum-results-for-search="3"
                        id="producto_id">
                        <x-slot name="options">
                            @if (count($productos) > 0)
                                @foreach ($productos as $item)
                                    <option data-marca="{{ $item->name_marca }}"
                                        data-category="{{ $item->name_category }}"
                                        data-subcategory="{{ $item->name_subcategory }}"
                                        data-requireserie="{{ $item->isRequiredserie() }}"
                                        data-image="{{ !empty($item->image) ? pathURLProductImage($item->image) : null }}"
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
                    step="0.001" onkeypress="return validarDecimal(event, 11)" />
                <x-jet-input-error for="pricesale" />
            </div>
            <div class="w-full">
                <x-label value="Cantidad :" />
                <x-input class="block w-full text-end input-number-none" wire:key="{{ rand() }}"
                    wire:model.defer="cantidad" wire:keydown.enter="addproducto" type="number" min="1"
                    step="1" onkeypress="return validarDecimal(event, 11)" />
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

    @if (count($itemcotizacions) > 0)
        <div
            class="w-full grid grid-cols-[repeat(auto-fill,minmax(160px,1fr))] sm:grid-cols-[repeat(auto-fill,minmax(200px,1fr))] gap-1">
            @foreach ($itemcotizacions as $key => $item)
                <x-card-producto :image="!empty($item['imagen']) ? pathURLProductImage($item['imagen']) : null" :name="$item['name']" id="cardprodcot_{{ $item['id'] }}"
                    x-data="{ showForm: false }">

                    <table class="w-full table text-[10px] text-colorsubtitleform">
                        <tr>
                            <td class="align-middle text-center text-xl font-semibold" colspan="2">
                                {{ decimalOrInteger($item['cantidad']) }}
                                <small>{{ $item['unit'] }}</small>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="align-middle text-center">TOTAL</td>
                        </tr>
                        <tr>
                            <td class="text-center" colspan="2">
                                <span class="text-xl font-semibold !leading-none">
                                    {{ number_format($item['total'], 2, '.', ', ') }}
                                </span>
                                <small x-text="namemoneda"></small>
                            </td>
                        </tr>
                        <tr>
                            <td class="align-middle">P.U. VENTA</td>
                            <td class="text-end">
                                <span class="text-sm font-semibold">
                                    {{ number_format($item['pricesale'] + $item['igv'], 2, '.', ', ') }}</span>
                                SOLES

                                @if ($item['igv'] > 0)
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

                    <x-slot name="footer">
                        <x-button-edit wire:key="edit_{{ $item['id'] }}"
                            wire:click="edititem('{{ $item['id'] }}')" wire:loading.attr="disabled" />
                        <x-button-delete wire:key="delete_{{ $item['id'] }}"
                            wire:click="removeitem('{{ $item['id'] }}')" wire:loading.attr="disabled" />
                    </x-slot>
                </x-card-producto>
            @endforeach
        </div>
    @else
        {{-- <p class="font-semibold text-xs text-colorerror">
            Cotización no contiene items agregados</p> --}}
    @endif


    <x-form-card titulo="OTROS AGREGADOS Y/O SERVICIOS" class="gap-2">
        <div class="w-full grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-2">
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
                        <x-input class="block w-full text-end input-number-none" wire:key="{{ rand() }}"
                            wire:model.defer="priceother" wire:keydown.enter="addotherproducto" type="number"
                            min="0.001" step="0.01" onkeypress="return validarDecimal(event, 18)" />
                        <x-jet-input-error for="priceother" />
                    </div>
                    <div class="w-full xl:col-span-2">
                        <x-label value="Cantidad :" />
                        <x-input class="block w-full text-end input-number-none" wire:key="{{ rand() }}"
                            wire:model.defer="cantidadother" wire:keydown.enter="addotherproducto" type="number"
                            min="1" step="1" onkeypress="return validarDecimal(event, 18)" />
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

            @if (count($others) > 0)
                <div class="w-full md:col-span-3 lg:col-span-4 xl:col-span-3">
                    <x-table class="w-full">
                        <x-slot name="body">
                            @foreach ($others as $key => $item)
                                <tr>
                                    <td class="p-2 leading-none" style="min-width: 180px;">
                                        <p class="italic text-colorsubtitleform font-normal">
                                            {{ $item['name'] }}</p>

                                        <small x-text="simbolo"></small>
                                        <span class="text-sm font-semibold">
                                            {{ number_format($item['pricesale'] + $item['igv'], 2, '.', ', ') }}</span>
                                        <small>C/U</small>
                                        @if ($item['igv'] > 0)
                                            INC. IGV
                                        @endif
                                    </td>
                                    <td class="p-2 text-center font-normal italic leading-none" width="80px">
                                        @if (!empty($item['marca']))
                                            {{ $item['marca'] }}
                                        @endif
                                    </td>
                                    <td class="p-2 text-center font-semibold text-sm" width="80px">
                                        x {{ decimalOrInteger($item['cantidad']) }}
                                        <small class="font-normal text-[10px]">{{ $item['unit'] }}</small>
                                    </td>
                                    <td class="p-2 text-center" width="100px">
                                        <small x-text="simbolo"></small>
                                        <span class="text-sm font-semibold !leading-none">
                                            {{ number_format($item['total'], 2, '.', ', ') }}
                                        </span>
                                    </td>
                                    <td class="p-2 text-end" width="70px">
                                        <x-button-edit wire:click="edititemother('{{ $item['id'] }}')"
                                            wire:key="{{ $item['id'] }}" wire:loading.attr="disabled" />
                                        <x-button-delete wire:click="removeitemother('{{ $item['id'] }}')"
                                            wire:key="{{ $item['id'] }}" wire:loading.attr="disabled" />
                                    </td>
                                </tr>
                            @endforeach
                        </x-slot>
                    </x-table>

                </div>
            @endif
        </div>
    </x-form-card>


    <x-form-card titulo="AGREGAR OFERTAS" class="gap-2">
        <div class="w-full grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-2">
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
                                                data-image="{{ !empty($item->image) ? pathURLProductImage($item->image) : null }}"
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
                        <x-input class="block w-full text-end input-number-none" wire:key="{{ rand() }}"
                            wire:model.defer="cantidadfree" wire:keydown.enter="addproductofree" type="number"
                            min="1" step="1" onkeypress="return validarNumero(event, 11)" />
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

            @if (count($ofertas) > 0)
                <div
                    class="w-full md:col-span-3 lg:col-span-2 xl:col-span-3 grid grid-cols-[repeat(auto-fill,minmax(160px,1fr))] sm:grid-cols-[repeat(auto-fill,minmax(175px,1fr))] gap-1">
                    @foreach ($ofertas as $key => $item)
                        <x-card-producto :image="!empty($item['imagen']) ? pathURLProductImage($item['imagen']) : null" :name="$item['name']" id="cardprodfree_{{ $item['id'] }}"
                            x-data="{ showForm: false }">

                            <table class="w-full table text-[10px] text-colorsubtitleform">
                                <tr>
                                    <td class="align-middle text-center text-xl font-semibold" colspan="2">
                                        {{ decimalOrInteger($item['cantidad']) }}
                                        <small>{{ $item['unit'] }}</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center" colspan="2">
                                        <x-span-text text="GRATIS" type="green" class="px-2" />
                                    </td>
                                </tr>
                            </table>

                            <x-slot name="footer">
                                <x-button-edit wire:key="editfree_{{ $item['id'] }}"
                                    wire:click="edititemfree('{{ $item['id'] }}')" wire:loading.attr="disabled" />
                                <x-button-delete wire:key="deletefree_{{ $item['id'] }}"
                                    wire:click="removeitemfree('{{ $item['id'] }}')"
                                    wire:loading.attr="disabled" />
                            </x-slot>
                        </x-card-producto>
                    @endforeach
                </div>
            @endif
        </div>

    </x-form-card>


    {{-- @if (count($itemcotizacions) > 0) --}}
    <table class="w-full table text-xs text-colorsubtitleform">
        <tr>
            <td class="align-bottom text-sm font-semibold text-end">
                SUBTOTAL :
            </td>
            <td class="text-end text-xl font-semibold w-40">
                <small class="text-xs font-medium" x-text="simbolo"></small>
                {{ number_format($subtotal, 2, '.', ', ') }}
                <small class="text-xs font-medium" x-text="namemoneda"></small>
            </td>
        </tr>
        <tr>
            <td class="align-bottom text-sm font-semibold text-end">
                IGV :
            </td>
            <td class="text-end text-xl font-semibold w-40">
                <small class="text-xs font-medium" x-text="simbolo"></small>
                {{ number_format($igv, 2, '.', ', ') }}
                <small class="text-xs font-medium" x-text="namemoneda"></small>
            </td>
        </tr>
        <tr>
            <td class="align-bottom text-sm font-semibold text-end">
                TOTAL :
            </td>
            <td class="text-end text-xl font-semibold w-40">
                <small class="text-xs font-medium" x-text="simbolo"></small>
                {{ number_format($total, 2, '.', ', ') }}
                <small class="text-xs font-medium" x-text="namemoneda"></small>
            </td>
        </tr>
    </table>
    {{-- @endif --}}

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('createcot', () => ({
                open: false,
                simbolo: '',
                codemoneda: '',
                namemoneda: '',
                document: @entangle('document').defer,
                name: @entangle('name').defer,
                direccion: @entangle('direccion').defer,
                client_id: @entangle('client_id').defer,
                addadress: @entangle('addadress').defer,
                afectacionigv: @entangle('afectacionigv').defer,
                typepayment_id: @entangle('typepayment_id').defer,
                moneda_id: @entangle('moneda_id').defer,
                ubigeo_id: @entangle('ubigeo_id').defer,
                datecode: @entangle('datecode').defer,
                producto_id: @entangle('producto_id'),
                pricetype_id: @entangle('pricetype_id'),
                typegarantia_id: @entangle('typegarantia_id').defer,
                datecodegarantia: @entangle('datecodegarantia').defer,
                productofree_id: @entangle('productofree_id').defer,

                init() {
                    this.adjustHeight(this.$refs.detalle_cotizacion);
                    this.$watch("typepayment_id", (value) => {
                        this.selectTPC.val(value).trigger("change");
                    });
                    this.$watch("afectacionigv", (value) => {
                        this.selectAFIGV.val(value).trigger("change");
                    });
                    this.$watch("moneda_id", (value) => {
                        this.selectMC.val(value).trigger("change");
                    });
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

                    Livewire.hook('message.processed', () => {
                        this.adjustHeight(this.$refs.detalle_cotizacion);
                        this.selectAFIGV.select2().val(this.afectacionigv).trigger('change');
                        this.selectTPC.select2().val(this.typepayment_id).trigger('change');
                        this.selectCEN.select2().val(this.datecode).trigger('change');
                        this.selectCTG.select2().val(this.typegarantia_id).trigger('change');
                        this.selectCWTG.select2().val(this.datecodegarantia).trigger('change');
                        this.selectPTC.select2().val(this.pricetype_id).trigger('change');
                        this.selectMC.select2().val(this.moneda_id).trigger('change');
                        this.selectUBC.select2().val(this.ubigeo_id).trigger('change');
                        this.selectP.select2({
                            templateResult: formatOption
                        }).val(this.producto_id).trigger('change');
                        this.selectPFC.select2({
                            templateResult: formatOption
                        }).val(this.productofree_id).trigger('change');

                    });
                },
                numeric() {
                    // this.exonerado = toDecimal(this.exonerado > 0 ? this.exonerado : 0);
                    // this.gravado = toDecimal(this.gravado > 0 ? this.gravado : 0);
                    // this.igv = toDecimal(this.igv > 0 ? this.igv : 0);
                    // this.otros = toDecimal(this.otros > 0 ? this.otros : 0);
                    // this.descuento = toDecimal(this.descuento > 0 ? this.descuento : 0);
                },
                adjustHeight($el) {
                    $el.style.height = 'auto';
                    $el.style.height = $el.scrollHeight + 'px';
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

        function selectCotAfectacion() {
            this.selectAFIGV = $(this.$refs.rpcot_afectacionigv).select2();
            this.selectAFIGV.val(this.afectacionigv).trigger("change");
            this.selectAFIGV.on("select2:select", (event) => {
                this.afectacionigv = event.target.value;
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

        function selec2Typepayment() {
            this.selectTPC = $(this.$refs.selecttypepayment).select2();
            this.selectTPC.val(this.typepayment_id).trigger("change");
            this.selectTPC.on("select2:select", (event) => {
                this.typepayment_id = event.target.value;
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

        function selec2Moneda() {
            this.selectMC = $(this.$refs.selectmoneda).select2();
            this.selectMC.val(this.moneda_id).trigger("change");
            this.selectMC.on("select2:select", (event) => {
                this.moneda_id = event.target.value;
                let codemoneda = event.target.options[event.target.selectedIndex].getAttribute('data-code');
                let datasimbolo = event.target.options[event.target.selectedIndex].getAttribute(
                    'data-simbolo');
                let namemoneda = event.target.options[event.target.selectedIndex].getAttribute(
                    'data-currency');
                this.open = codemoneda == 'USD' ? true : false;
                this.simbolo = datasimbolo;
                this.codemoneda = codemoneda;
                this.namemoneda = namemoneda;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
        }

        function formatOption(option) {
            if (!option.id) {
                return option.text;
            }
            const image = $(option.element).data('image') ?? '';
            const marca = $(option.element).data('marca') ?? '';
            const category = $(option.element).data('category') ?? '';
            const subcategory = $(option.element).data('subcategory') ?? '';

            let html = `<div class="custom-list-select">
                        <div class="image-custom-select">`;
            if (image) {
                html +=
                    `<img src="${image}" class="w-full h-full object-scale-down block" alt="${option.text}">`;
            } else {
                html += `<x-icon-image-unknown class="w-full h-full" />`;
            }
            html += `</div>
                            <div class="content-custom-select">
                                <p class="title-custom-select">
                                    ${option.text}</p>
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
