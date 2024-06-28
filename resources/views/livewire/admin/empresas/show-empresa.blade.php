<div class="w-full flex flex-col gap-8" x-data="showempresa">
    <div wire:loading.flex class="loading-overlay rounded hidden fixed">
        <x-loading-next />
    </div>

    <x-form-card titulo="DATOS EMPRESA">
        <form wire:submit.prevent="update" class="w-full flex flex-col gap-8">
            <div class="w-full grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-2">
                <div class="w-full">
                    <x-label value="RUC :" />
                    <div class="w-full inline-flex gap-1">
                        @if (auth()->user()->isAdmin())
                            <x-input class="flex-1 block w-full" wire:keydown.enter="searchclient" type="number"
                                wire:model.defer="empresa.document" onkeypress="return validarNumero(event, 11)"
                                onkeydown="disabledEnter(event)" />
                        @else
                            <x-disabled-text :text="$empresa->document" class="flex-1 w-full block" />
                        @endif

                        <x-button-add class="px-2" wire:click="searchclient" wire:loading.attr="disabled">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.3-4.3"></path>
                            </svg>
                        </x-button-add>
                    </div>
                    <x-jet-input-error for="empresa.document" />
                </div>

                <div class="w-full sm:col-span-2">
                    <x-label value="Razón Social :" />
                    <x-input class="block w-full" wire:model.defer="empresa.name" />
                    <x-jet-input-error for="empresa.name" />
                </div>

                <div class="w-full sm:col-span-2 xl:col-span-1">
                    <x-label value="Dirección :" />
                    <x-input class="block w-full" wire:model.defer="empresa.direccion" />
                    <x-jet-input-error for="empresa.direccion" />
                </div>

                <div class="w-full sm:col-span-2">
                    <x-label value="Ubigeo :" />
                    {{-- 20201987297 --}}
                    <div id="parentubigeoempresa_id" class="relative" x-init="SelectUbigeoEmp">
                        <x-select class="block w-full" x-ref="selectubigeo" id="ubigeoempresa_id"
                            data-minimum-results-for-search="3">
                            <x-slot name="options">
                                @foreach ($ubigeos as $item)
                                    <option value="{{ $item->id }}">{{ $item->region }} / {{ $item->provincia }}
                                        /
                                        {{ $item->distrito }} / {{ $item->ubigeo_reniec }}</option>
                                @endforeach
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="empresa.ubigeo_id" />
                </div>

                <div class="w-full">
                    <x-label value="Estado :" />
                    <x-disabled-text :text="$empresa->estado ?? '-'" />
                    <x-jet-input-error for="empresa.estado" />
                </div>

                <div class="w-full">
                    <x-label value="Condición :" />
                    <x-disabled-text :text="$empresa->condicion ?? '-'" />
                    <x-jet-input-error for="empresa.condicion" />
                </div>

                <div class="w-full">
                    <x-label value="Correo :" />
                    <x-input class="block w-full" wire:model.defer="empresa.email" placeholder="@" />
                    <x-jet-input-error for="empresa.correo" />
                </div>

                <div class="w-full">
                    <x-label value="Web :" />
                    <x-input class="block w-full" wire:model.defer="empresa.web" placeholder="www.misitioweb.com" />
                    <x-jet-input-error for="empresa.web" />
                </div>

                <div class="w-full">
                    <x-label value="Porcentaje IGV :" />
                    <x-input class="block w-full" wire:model.defer="empresa.igv" type="number" placeholder="0.00" />
                    <x-jet-input-error for="empresa.igv" />
                </div>
            </div>
            <div class="w-full flex justify-end">
                <x-button type="submit">{{ __('ACTUALIZAR') }}</x-button>
            </div>
        </form>
    </x-form-card>

    <div class="grid gap-8 lg:grid-cols-2">
        <x-form-card titulo="LOGOTIPO & ICONO">
            <div class="w-full grid gap-3 xs:grid-cols-2">
                <div class="relative w-full text-center">
                    <x-simple-card class="w-full h-40 md:max-w-md mx-auto mb-1 !shadow-none">
                        @if (isset($logo))
                            <img class="w-full h-full object-scale-down animate__animated animate__fadeIn animate__faster"
                                src="{{ $logo->temporaryUrl() }}" />
                        @else
                            @if ($empresa->image)
                                <img class="w-full h-full object-scale-down animate__animated animate__fadeIn animate__faster"
                                    src="{{ $empresa->image->getLogoEmpresa() }}" />
                            @else
                                <x-icon-file-upload class="w-full h-full text-gray-300" />
                            @endif
                        @endif
                    </x-simple-card>

                    <div class="w-full flex gap-2 flex-wrap justify-center">
                        @if (isset($logo))
                            <x-button class="inline-flex !rounded-lg" wire:loading.attr="disabled"
                                wire:click="savelogo">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline-block" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path
                                        d="M17.4776 9.01106C17.485 9.01102 17.4925 9.01101 17.5 9.01101C19.9853 9.01101 22 11.0294 22 13.5193C22 15.8398 20.25 17.7508 18 18M17.4776 9.01106C17.4924 8.84606 17.5 8.67896 17.5 8.51009C17.5 5.46695 15.0376 3 12 3C9.12324 3 6.76233 5.21267 6.52042 8.03192M17.4776 9.01106C17.3753 10.1476 16.9286 11.1846 16.2428 12.0165M6.52042 8.03192C3.98398 8.27373 2 10.4139 2 13.0183C2 15.4417 3.71776 17.4632 6 17.9273M6.52042 8.03192C6.67826 8.01687 6.83823 8.00917 7 8.00917C8.12582 8.00917 9.16474 8.38194 10.0005 9.01101" />
                                    <path
                                        d="M12 21L12 13M12 21C11.2998 21 9.99153 19.0057 9.5 18.5M12 21C12.7002 21 14.0085 19.0057 14.5 18.5" />
                                </svg>
                                GUARDAR LOGO
                            </x-button>
                            <x-button class="inline-flex !rounded-lg" wire:loading.attr="disabled"
                                wire:click="clearLogo">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline-block"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 6h18" />
                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                    <line x1="10" x2="10" y1="11" y2="17" />
                                    <line x1="14" x2="14" y1="11" y2="17" />
                                </svg>
                                LIMPIAR
                            </x-button>
                        @else
                            <x-input-file class="!rounded-lg" :for="$idlogo" :titulo="$empresa->image ? 'CAMBIAR LOGO' : 'SELECCIONAR LOGO'" wire:loading.remove>
                                <input type="file" class="hidden" wire:model.defer="logo"
                                    id="{{ $idlogo }}" accept="image/jpg, image/jpeg, image/png" />
                            </x-input-file>
                        @endif

                        @if (!isset($logo) && $empresa->image)
                            <x-button wire:click="deletelogo" wire:loading.attr="disabled" class="!rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline-block"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 6h18" />
                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                    <line x1="10" x2="10" y1="11" y2="17" />
                                    <line x1="14" x2="14" y1="11" y2="17" />
                                </svg>
                                ELIMINAR LOGO
                            </x-button>
                        @endif
                    </div>
                    <x-jet-input-error for="logo" class="text-center" />
                </div>

                <div class="relative w-full text-center">
                    <x-simple-card class="w-full h-40 md:max-w-md mx-auto mb-1 !shadow-none">
                        @if (isset($icono))
                            <img x-bind:src="URL.createObjectURL(icono)"
                                class="w-full h-full object-scale-down animate__animated animate__fadeIn animate__faster">
                        @else
                            @if ($empresa->icono)
                                <img class="w-full h-full object-scale-down animate__animated animate__fadeIn animate__faster"
                                    src="{{ $empresa->getIconoURL() }}" />
                            @else
                                <x-icon-file-upload class="w-full h-full text-gray-300" />
                            @endif
                        @endif
                    </x-simple-card>

                    <div class="w-full flex gap-1 flex-wrap justify-center">
                        @if (isset($icono))
                            <x-button class="inline-flex !rounded-lg" wire:loading.attr="disabled"
                                wire:click="saveicono">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline-block"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M17.4776 9.01106C17.485 9.01102 17.4925 9.01101 17.5 9.01101C19.9853 9.01101 22 11.0294 22 13.5193C22 15.8398 20.25 17.7508 18 18M17.4776 9.01106C17.4924 8.84606 17.5 8.67896 17.5 8.51009C17.5 5.46695 15.0376 3 12 3C9.12324 3 6.76233 5.21267 6.52042 8.03192M17.4776 9.01106C17.3753 10.1476 16.9286 11.1846 16.2428 12.0165M6.52042 8.03192C3.98398 8.27373 2 10.4139 2 13.0183C2 15.4417 3.71776 17.4632 6 17.9273M6.52042 8.03192C6.67826 8.01687 6.83823 8.00917 7 8.00917C8.12582 8.00917 9.16474 8.38194 10.0005 9.01101" />
                                    <path
                                        d="M12 21L12 13M12 21C11.2998 21 9.99153 19.0057 9.5 18.5M12 21C12.7002 21 14.0085 19.0057 14.5 18.5" />
                                </svg>
                                GUARDAR ICONO
                            </x-button>
                            <x-button class="inline-flex px-6 !rounded-lg" wire:loading.attr="disabled"
                                wire:click="clearIcono">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline-block"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 6h18" />
                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                    <line x1="10" x2="10" y1="11" y2="17" />
                                    <line x1="14" x2="14" y1="11" y2="17" />
                                </svg>
                                LIMPIAR
                            </x-button>
                        @else
                            <x-input-file for="{{ $idicono }}" :titulo="$empresa->icono ? 'CAMBIAR ÍCONO' : 'SELECCIONAR ÍCONO'" class="!rounded-lg"
                                wire:loading.remove>
                                <input type="file" class="hidden" wire:model.defer="icono"
                                    id="{{ $idicono }}" accept=".ico"
                                    @change="icono = $event.target.files[0]" />
                            </x-input-file>
                        @endif

                        @if (!isset($icono) && $empresa->icono)
                            <x-button wire:click="deleteicono({{ $empresa->id }})" wire:loading.attr="disabled"
                                class="!rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline-block"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 6h18" />
                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                    <line x1="10" x2="10" y1="11" y2="17" />
                                    <line x1="14" x2="14" y1="11" y2="17" />
                                </svg>
                                ELIMINAR ICONO
                            </x-button>
                        @endif
                    </div>
                    <x-jet-input-error for="icono" class="text-center" />
                </div>
            </div>
        </x-form-card>

        <x-form-card titulo="TELÉFONOS" subtitulo="Agregue números de teléfono para contactarse.">
            <div class="w-full flex flex-col gap-3 justify-between h-full">
                @if (count($empresa->telephones))
                    <div class="w-full flex gap-2 flex-wrap h-full">
                        @foreach ($empresa->telephones as $item)
                            <x-minicard title="" :content="formatTelefono($item->phone)">
                                <x-slot name="title">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mx-auto"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path
                                            d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                                        <path d="M14.05 2a9 9 0 0 1 8 7.94" />
                                        <path d="M14.05 6A5 5 0 0 1 18 10" />
                                    </svg>
                                </x-slot>
                                <x-slot name="buttons">
                                    <x-button-edit wire:click="editphone({{ $item->id }})"
                                        wire:loading.attr="disabled" />
                                    <x-button-delete wire:click="deletephone({{ $item->id }})"
                                        wire:loading.attr="disabled" />
                                </x-slot>
                            </x-minicard>
                        @endforeach
                    </div>
                @endif

                <div class="w-full mt-3 flex justify-end">
                    <x-button wire:click="openmodalphone" wire:loading.attr="disabled">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M16.243 5.243h3m3 0h-3m0 0v-3m0 3v3M18.118 14.702L14 15.5c-2.782-1.396-4.5-3-5.5-5.5l.77-4.13L7.815 2H4.064c-1.128 0-2.016.932-1.847 2.047.42 2.783 1.66 7.83 5.283 11.453 3.805 3.805 9.286 5.456 12.302 6.113 1.165.253 2.198-.655 2.198-1.848v-3.584l-3.882-1.479z" />
                        </svg>
                    </x-button>
                </div>
            </div>
        </x-form-card>
    </div>

    <x-form-card titulo="OTRAS OPCIONES" subtitulo="Seleccionar las opciones según su preferencia de uso.">
        <form wire:submit.prevent="updateopciones" class="w-full flex flex-col gap-8">
            @if (Module::isEnabled('Ventas'))
                <div class="w-full grid grid-cols-1 md:grid-cols-2 items-start gap-2 lg:gap-8">
                    <div class="w-full flex flex-col gap-1">
                        <div class="w-full">
                            <x-label value="Tipos precio venta (Productos):" />
                            <div class="w-full relative" id="parentuselistprice" x-init="SelectPrecios">
                                <x-select class="block w-full" x-ref="selectprice" id="uselistprice"
                                    data-parent="parentuselistprice">
                                    <x-slot name="options">
                                        <option value="0" title="Ingresar precio de venta manualmente">
                                            PRECIO MANUAL</option>
                                        <option value="1"
                                            title="Precio automático en base al % ganacia del rango de precio compra">
                                            LISTA DE PRECIOS (05 LISTAS DISPONIBLES)
                                        </option>
                                        {{-- <option value="2"
                                            title="Precio automático de acuerdo al % ganancia del producto">
                                            PRECIO AUTOMÁTICO
                                        </option> --}}
                                    </x-slot>
                                </x-select>
                                <x-icon-select />
                            </div>
                            <x-jet-input-error for="empresa.uselistprice" />
                        </div>

                        <div class="block">
                            <x-label-check for="usepricedolar">
                                <x-input x-model="usepricedolar" type="checkbox" id="usepricedolar"
                                    @change="changePricedolar" />USAR PRECIO EN DÓLARES</x-label-check>
                            <x-jet-input-error for="empresa.usepricedolar" />

                            <div x-show="!cambioauto">
                                <div x-show="openpricedolar" style="display: none;">
                                    <x-input class="w-full" type="number" x-model="tipocambio" placeholder="0.00"
                                        min="0" step="0.0001" />
                                </div>
                            </div>

                            <div x-show="cambioauto">
                                <x-disabled-text text="" x-text="tipocambio" />
                            </div>
                            <x-jet-input-error for="empresa.tipocambio" />
                        </div>

                        <div class="w-full" x-show="openpricedolar">
                            <x-label-check for="viewpricedolar">
                                <x-input x-model="viewpricedolar" name="viewpricedolar" type="checkbox"
                                    id="viewpricedolar" />VER PRECIO EN DÓLARES</x-label-check>
                            <x-jet-input-error for="empresa.viewpricedolar" />
                        </div>

                        <div class="w-full" x-show="openpricedolar">
                            <x-label-check for="tipocambioauto">
                                <x-input name="tipocambioauto" x-model="cambioauto" type="checkbox"
                                    id="tipocambioauto" @change="changeAutomatico" />
                                <div class="flex flex-col flex-1">
                                    ACTUALIZAR TIPO CAMBIO AUTOMÁTICO
                                    <small class="font-medium text-xs tracking-normal">
                                        El tipo de cambio se actualizará todos los dias a las 12:00 am
                                        hrs</small>
                                </div>
                            </x-label-check>
                            <x-jet-input-error for="empresa.tipocambioauto" />
                        </div>
                    </div>

                    <div class="w-full flex flex-col gap-1">
                        <div class="w-full">
                            <x-label value="Etiqueta de producto promocionado :" />
                            <div class="w-full relative" id="parentviewtextopromocion" x-init="SelectTextoPromocion">
                                <x-select class="block w-full" x-ref="selecttextopromo" id="viewtextopromocion"
                                    data-parent="parentviewtextopromocion">
                                    <x-slot name="options">
                                        <option value="0">
                                            TEXTO POR DEFECTO</option>
                                        <option value="1">
                                            MOSTRAR TEXTO DE "PROMOCIÓN"
                                        </option>
                                        <option value="2">
                                            MOSTRAR TEXTO DE "LIQUIDACIÓN"
                                        </option>
                                    </x-slot>
                                </x-select>
                                <x-icon-select />
                            </div>
                            <x-jet-input-error for="empresa.viewtextopromocion" />
                        </div>

                        <div class="block">
                            <x-label-check for="viewpriceantes">
                                <x-input wire:model.defer="empresa.viewpriceantes" value="1"
                                    name="viewpriceantes" type="checkbox" id="viewpriceantes" />
                                MOSTRAR PRECIO ANTERIOR EN PRODUCTOS OFERTADOS
                            </x-label-check>
                            <x-jet-input-error for="empresa.viewpriceantes" />
                        </div>

                        <div class="block">
                            <x-label-check for="viewlogomarca">
                                <x-input wire:model.defer="empresa.viewlogomarca" value="1" name="viewlogomarca"
                                    type="checkbox" id="viewlogomarca" />
                                MOSTRAR LOGO MARCA EN PRODUCTOS
                            </x-label-check>
                            <x-jet-input-error for="empresa.viewlogomarca" />
                        </div>

                        <div class="w-full" x-data="loadimage()">
                            <x-label-check for="usemarca_agua">
                                <x-input wire:model.defer="empresa.usemarkagua" value="1" type="checkbox"
                                    x-on:change="openmark = !openmark" id="usemarca_agua" />
                                <div class="flex flex-col flex-1">
                                    AGREGAR MARCA DE AGUA EN IMAGENES DE PRODUCTOS
                                    <small class="font-medium text-xs tracking-normal">
                                        Se recomienda usar imagen transparente PNG</small>
                                </div>
                            </x-label-check>
                            <x-jet-input-error for="usemarkagua" />

                            <div class="grid grid-cols-1 xs:grid-cols-2 md:grid-cols-1 xl:grid-cols-2 gap-2 items-start mt-2"
                                style="display: none;" x-show="openmark" x-cloak>
                                <div class="w-full grid grid-cols-1 gap-2 items-start">
                                    <div class="w-full">
                                        <x-label value="Posición:" />
                                        <div class="w-full relative" id="parentposicion" x-init="SelectPosicion">
                                            <x-select class="block w-full" x-ref="selectposicion" id="posicion"
                                                data-parent="parentposicion">
                                                <x-slot name="options">
                                                    <option value="top">SUPERIOR</option>
                                                    <option value="top-left">SUPERIOR IZQUIERDA</option>
                                                    <option value="top-right">SUPERIOR DERECHA</option>
                                                    <option value="left">IZQUIERDA</option>
                                                    <option value="center">CENTRADO</option>
                                                    <option value="right">DERECHA</option>
                                                    <option value="bottom">POSTERIOR</option>
                                                    <option value="bottom-left">POSTERIOR IZQUIERDA</option>
                                                    <option value="bottom-right">POSTERIOR DERECHA</option>
                                                </x-slot>
                                            </x-select>
                                            <x-icon-select />
                                        </div>
                                        <x-jet-input-error for="empresa.alignmark" />
                                    </div>
                                    <div class="w-full">
                                        <x-label value="Ancho :" />
                                        <x-input class="block w-full" wire:model.defer="empresa.widthmark"
                                            type="number" step="1"
                                            onkeypress="return validarNumero(event, 3)" />
                                        <x-jet-input-error for="empresa.widthmark" />
                                    </div>
                                    <div class="w-full">
                                        <x-label value="Alto :" />
                                        <x-input class="block w-full" wire:model.defer="empresa.heightmark"
                                            type="number" step="1"
                                            onkeypress="return validarNumero(event, 3)" />
                                        <x-jet-input-error for="empresa.heightmark" />
                                    </div>
                                </div>

                                <div class="w-full">
                                    <x-simple-card class="w-full h-28 md:max-w-xs mb-1 mx-auto !shadow-none">
                                        <template x-if="markagua">
                                            <img id="markagua" class="object-scale-down block w-full max-w-full h-full"
                                                :src="markagua" />
                                        </template>
                                        <template x-if="!markagua">
                                            @if ($empresa->markagua)
                                                <img id="markagua"
                                                    class="object-scale-down block w-full max-w-full h-full"
                                                    src="{{ $empresa->getMarkAguaURL() }}" />
                                            @else
                                                <x-icon-fileupload class="w-full h-full !my-0" />
                                            @endif
                                        </template>
                                    </x-simple-card>
                                    <x-jet-input-error for="mark" />

                                    <div class="w-full flex gap-2 flex-wrap justify-center">
                                        <template x-if="markagua">
                                            <x-button class="inline-flex !rounded-lg" @click="reset"
                                                wire:loading.attr="disabled">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline-block"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M3 6h18" />
                                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                                    <line x1="10" x2="10" y1="11"
                                                        y2="17" />
                                                    <line x1="14" x2="14" y1="11"
                                                        y2="17" />
                                                </svg>
                                                LIMPIAR
                                            </x-button>
                                        </template>

                                        <x-input-file for="fileMark" titulo="SELECCIONAR MARCA AGUA"
                                            wire:loading.class="disabled:opacity-25" class="!rounded-lg">
                                            <input type="file" class="hidden" wire:model="mark" id="fileMark"
                                                accept="image/png" @change="loadlogo" />
                                        </x-input-file>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="w-full" x-data="{ openvalidatemail: {{ $empresa->validatemail ? 'true' : 'false' }} }">
                        <x-label-check for="validatemail">
                            <x-input wire:model.defer="validatemail" value="1" type="checkbox"
                                x-on:change="openvalidatemail = !openvalidatemail" id="validatemail" />
                            VERIFICAR CORREO CORPORATIVO PARA DASHBOARD
                        </x-label-check>
                        <x-jet-input-error for="validatemail" />

                        <div x-show="openvalidatemail">
                            <x-input class="block w-full" wire:model.defer="dominiocorreo"
                                placeholder="Ingrese dominio web del correo a validar..." />
                            <x-jet-input-error for="dominiocorreo" />
                        </div>
                    </div> --}}
                </div>
            @endif

            @if (Module::isEnabled('Employer'))
                <div class="w-full md:w-1/2">
                    <h1 class="text-xs font-semibold text-colortitleform">
                        CONFIGURAR ADELANTO PERSONAL</h1>

                    <div class="w-full mt-3">
                        <x-label value="Monto máximo adelanto :" />
                        <x-input class="block w-full" wire:model.defer="empresa.montoadelanto" placeholder="0.00"
                            type="number" onkeypress="return validarDecimal(event, 11)" />
                        <x-jet-input-error for="empresa.montoadelanto" />
                    </div>
                </div>
            @endif

            <div class="w-full flex justify-end">
                <x-button type="submit">{{ __('ACTUALIZAR') }}</x-button>
            </div>
        </form>
    </x-form-card>




    <x-jet-dialog-modal wire:model="openphone" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Nuevo teléfono') }}
            <x-button-close-modal wire:click="$toggle('openphone')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="savetelefono">
                <div class="w-full">
                    <x-label value="Teléfono :" />
                    <x-input class="block w-full" wire:model.defer="phone" type="number"
                        onkeypress="return validarNumero(event, 9)" />
                    <x-jet-input-error for="phone" />
                    <x-jet-input-error for="empresa.id" />
                </div>

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('REGISTRAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('showempresa', () => ({
                uselistprice: @entangle('empresa.uselistprice').defer,
                viewtextopromocion: @entangle('empresa.viewtextopromocion').defer,
                ubigeo_id: @entangle('empresa.ubigeo_id').defer,
                usepricedolar: @entangle('empresa.usepricedolar').defer,
                viewpricedolar: @entangle('empresa.viewpricedolar').defer,
                tipocambio: @entangle('empresa.tipocambio').defer,
                cambioauto: @entangle('empresa.tipocambioauto').defer,
                openpricedolar: @entangle('empresa.usepricedolar').defer,
                loadingdolar: false,
                alignmark: @entangle('empresa.alignmark').defer,
                usemarkagua: @entangle('empresa.usemarkagua').defer,

                init() {
                    this.usepricedolar = !!this.usepricedolar;
                    this.viewpricedolar = !!this.viewpricedolar;
                    this.cambioauto = !!this.cambioauto;
                },
                changePricedolar() {
                    if (this.usepricedolar) {
                        this.getTipocambio();
                    } else {
                        this.viewpricedolar = false;
                        this.cambioauto = false;
                        this.openpricedolar = false;
                    }
                },
                changeAutomatico() {
                    if (this.cambioauto) {
                        this.getTipocambio();
                    }
                },
                async getTipocambio() {
                    try {
                        this.loadingdolar = true;
                        const response = await fetch("{{ route('api.tipocambio') }}");
                        const data = await response.json();
                        this.tipocambio = data.venta;
                        this.loadingdolar = false;
                        this.openpricedolar = true;

                    } catch (error) {
                        console.error('Error al obtener el tipo de cambio', error);
                        this.tipocambio = null;
                        this.loadingdolar = false;
                        this.openpricedolar = true;
                    }
                }
            }))
        });

        function loadimage() {
            return {
                openmark: false,
                markagua: null,
                loadlogo() {
                    let file = document.getElementById('fileMark').files[0];
                    var reader = new FileReader();
                    reader.onload = (e) => this.markagua = e.target.result;
                    reader.readAsDataURL(file);
                },
                reset() {
                    this.markagua = null;
                    @this.clearMark();
                },
                init() {
                    if (this.usemarkagua) {
                        this.openmark = true;
                    }
                }
            }
        }

        function SelectUbigeoEmp() {
            this.selectU = $(this.$refs.selectubigeo).select2();
            this.selectU.val(this.ubigeo_id).trigger("change");
            this.selectU.on("select2:select", (event) => {
                this.ubigeo_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("ubigeo_id", (value) => {
                this.selectU.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectU.select2().val(this.ubigeo_id).trigger('change');
            });
        }

        function SelectPosicion() {
            this.selectPO = $(this.$refs.selectposicion).select2();
            this.selectPO.val(this.alignmark).trigger("change");
            this.selectPO.on("select2:select", (event) => {
                this.alignmark = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("alignmark", (value) => {
                this.selectPO.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectPO.select2().val(this.alignmark).trigger('change');
            });
        }

        function SelectPrecios() {
            this.selectMP = $(this.$refs.selectprice).select2({
                templateResult: formatOption
            });
            this.selectMP.val(this.uselistprice).trigger("change");
            this.selectMP.on("select2:select", (event) => {
                this.uselistprice = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("uselistprice", (value) => {
                this.selectMP.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectMP.select2({
                    templateResult: formatOption
                }).val(this.uselistprice).trigger('change');
            });
        }

        function SelectTextoPromocion() {
            this.selectTP = $(this.$refs.selecttextopromo).select2();
            this.selectTP.val(this.viewtextopromocion).trigger("change");
            this.selectTP.on("select2:select", (event) => {
                this.viewtextopromocion = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("viewtextopromocion", (value) => {
                this.selectTP.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectTP.select2().val(this.viewtextopromocion).trigger('change');
            });
        }

        function formatOption(option) {
            var $option = $(
                '<strong>' + option.text + '</strong><p class="select2-subtitle-option">' + option.title + '</p>'
            );
            return $option;
        };
    </script>
</div>
