<div class="w-full m-auto overflow-hidden" x-data="startsystem" id="form-configuracion">
    <style>
        [type="checkbox"] {
            box-sizing: border-box;
            padding: 0;
        }

        .form-checkbox {
            border-radius: 0.25rem;
        }

        .form-radio {
            border-radius: 50%;
        }

        .form-checkbox:checked {
            background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='M5.707 7.293a1 1 0 0 0-1.414 1.414l2 2a1 1 0 0 0 1.414 0l4-4a1 1 0 0 0-1.414-1.414L7 8.586 5.707 7.293z'/%3e%3c/svg%3e");
            border-color: transparent;
            background-color: currentColor;
            background-size: 100% 100%;
            background-position: center;
            background-repeat: no-repeat;
        }

        .form-radio:checked {
            background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3ccircle cx='8' cy='8' r='3'/%3e%3c/svg%3e");
            border-color: transparent;
            background-color: currentColor;
            background-size: 100% 100%;
            background-position: center;
            background-repeat: no-repeat;
        }
    </style>

    <div x-cloak class="w-full h-auto sm:max-h-[calc(100vh-1rem)] overflow-y-auto">
        <div class="w-full sm:border border-borderminicard lg:my-auto md:max-w-2xl mx-auto px-1 lg:px-3 sm:rounded-xl">
            <div x-cloak x-show="step === 'complete'">
                <div class="w-full bg-white rounded-lg p-10 flex items-center shadow justify-between">
                    <div class="w-full">
                        <svg class="mb-4 h-20 w-20 text-green-500 mx-auto" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>

                        <h2 class="text-2xl mb-4 text-colorsubtitleform text-center font-bold">
                            Registrado correctamente</h2>

                        <div class="text-colorsubtitleform mb-8">
                            Gracias. Su perfil de la empresa se ha configurado correctamente.
                        </div>

                        <x-button-web text="VOLVER AL INICIO" @click="step = 1" class="" />
                    </div>
                </div>
            </div>

            <div x-show="step != 'complete'" x-cloak>
                <!-- Top Navigation -->
                <div class="w-full border-b border-borderminicard py-4">
                    <div class="uppercase tracking-wide text-xs font-bold text-colorsubtitleform mb-1 leading-tight"
                        x-text="`Paso: ${step} de 5`" x-cloak x-show="step < 6"></div>
                    <div class="w-full flex flex-col md:flex-row md:items-center md:justify-between">
                        <div class="w-full md:flex-1">
                            <div x-show="step === 1">
                                <div class="text-lg font-bold text-colorsubtitleform leading-tight">
                                    Perfil de la empresa</div>
                            </div>
                            <div x-show="step === 2">
                                <div class="text-lg font-bold text-colorsubtitleform leading-tight">
                                    Configuración de precios y multimedia</div>
                            </div>
                            <div x-show="step === 3">
                                <div class="text-lg font-bold text-colorsubtitleform leading-tight">
                                    Datos adicionales</div>
                            </div>
                            <div x-show="step === 4">
                                <div class="text-lg font-bold text-colorsubtitleform leading-tight">
                                    Agregar sucursales</div>
                            </div>
                            <div x-show="step === 5">
                                <div class="text-lg font-bold text-colorsubtitleform leading-tight">
                                    Configuración facturación</div>
                            </div>
                            <div x-show="step === 6">
                                <div class="text-lg font-bold text-colorsubtitleform leading-tight">
                                    Finalizar configuración</div>
                            </div>
                        </div>

                        <div class="w-full flex items-center md:w-64 md:flex-shrink-0" x-cloak x-show="step < 6">
                            <div class="w-full flex-1 bg-white rounded-full mr-2">
                                <div class="w-full flex-1 rounded-full bg-green-500 text-xs leading-none h-2 text-center text-white"
                                    :style="'width: ' + parseInt(step / 5 * 100) + '%'"></div>
                            </div>
                            <div class="text-xs w-10 flex-shrink-0 text-colorsubtitleform text-end"
                                x-text="parseInt(step / 5 * 100) +'%'"></div>
                        </div>
                    </div>
                </div>

                <div class="py-10 w-full">
                    <div x-transition:enter="opacity-0 scale-95 transform transition-transform ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-105" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="opacity-0 absolute"
                        x-transition:leave-start="opacity-0 scale-100 transition-transform ease-out duration-500"
                        x-transition:leave-end="opacity-0 scale-95" x-cloak style="display:none;" x-show="step === 1">
                        <div class="grid grid-cols-1 gap-2">
                            <div class="w-full">
                                <x-label value="RUC :" />
                                <div class="w-full inline-flex gap-1">
                                    <x-input class="block w-full flex-1 input-number-none"
                                        wire:keydown.enter.prevent="searchclient" type="number" x-model="document"
                                        wire:model.defer="document" onkeypress="return validarNumero(event, 11)"
                                        onkeydown="disabledEnter(event)" />
                                    <x-button-add class="px-2.5 " wire:click="searchclient"
                                        wire:loading.attr="disabled">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="11" cy="11" r="8"></circle>
                                            <path d="m21 21-4.3-4.3"></path>
                                        </svg>
                                    </x-button-add>
                                </div>
                                <x-jet-input-error for="document" />
                            </div>

                            <div class="w-full">
                                <x-label value="Razón Social :" />
                                <x-input class="block w-full" wire:model.defer="name" placeholder="Razón social..." />
                                <x-jet-input-error for="name" />
                            </div>

                            <div class="w-full">
                                <x-label value="Dirección :" />
                                <x-input class="block w-full" wire:model.defer="direccion" placeholder="Dirección..." />
                                <x-jet-input-error for="direccion" />
                            </div>

                            <div class="w-full">
                                <x-label value="Ubigeo :" />
                                <div id="parentubigeo_id" class="relative" x-init="selectUbigeo">
                                    <x-select class="block w-full" x-ref="selectubigeo" id="ubigeo_id"
                                        data-minimum-results-for-search="3">
                                        <x-slot name="options">
                                            @foreach ($ubigeos as $item)
                                                <option value="{{ $item->id }}">
                                                    {{ $item->region }} / {{ $item->provincia }} /
                                                    {{ $item->distrito }} / {{ $item->ubigeo_reniec }}</option>
                                            @endforeach
                                        </x-slot>
                                    </x-select>
                                    <x-icon-select />
                                </div>
                                <x-jet-input-error for="ubigeo_id" />
                            </div>

                            <div class="w-full">
                                <x-label value="Estado :" />
                                <x-disabled-text class="input-text" :text="$estado ?? '-'" />
                                <x-jet-input-error for="estado" />
                            </div>

                            <div class="w-full">
                                <x-label value="Condición :" />
                                <x-disabled-text class="input-text" :text="$condicion ?? '-'" />
                                <x-jet-input-error for="condicion" />
                            </div>

                            <div class="w-full">
                                <x-label value="IGV (%):" />
                                <x-input class="block w-full input-number-none" type="number" wire:model.defer="igv"
                                    type="number" placeholder="0.00" onkeypress="return validarDecimal(event, 5)" />
                                <x-jet-input-error for="igv" />
                            </div>
                        </div>
                    </div>

                    <div x-transition:enter="opacity-0 scale-95 transform transition-transform ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-105" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="opacity-0 absolute"
                        x-transition:leave-start="opacity-0 scale-100 transition-transform ease-out duration-500"
                        x-transition:leave-end="opacity-0 scale-95" x-cloak style="display:none;"
                        x-show="step === 2">
                        @if (Module::isEnabled('Ventas'))
                            <div class="w-full items-start flex flex-col gap-2">
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
                                    <x-jet-input-error for="uselistprice" />
                                </div>
                                <div class="w-full">
                                    <x-label value="Etiqueta de producto promocionado :" />
                                    <div class="w-full relative" id="parentviewtextopromocion"
                                        x-init="SelectTextoPromocion">
                                        <x-select class="block w-full" x-ref="selecttextopromo"
                                            id="viewtextopromocion" data-parent="parentviewtextopromocion">
                                            <x-slot name="options">
                                                <option value="0">
                                                    TEXTO POR DEFECTO</option>
                                                <option value="1">
                                                    MOSTRAR TEXTO DE "PROMOCIÓN"
                                                </option>
                                                <option value="2">
                                                    MOSTRAR TEXTO DE "LIQUIDACIÓN"
                                                </option>
                                                <option value="3">
                                                    MOSTRAR TEXTO DE "OFERTA"
                                                </option>
                                            </x-slot>
                                        </x-select>
                                        <x-icon-select />
                                    </div>
                                    <x-jet-input-error for="viewtextopromocion" />
                                </div>
                        @endif

                        @if (Module::isEnabled('Ventas') || Module::isEnabled('Almacen'))
                            <div class="w-full">
                                <x-label-check for="autogeneratesku">
                                    <x-input x-model="generatesku" name="autogeneratesku" type="checkbox"
                                        id="autogeneratesku" />AUTOGENERAR SKU DEL PRODUCTO</x-label-check>
                                <x-jet-input-error for="generatesku" />
                            </div>

                            <div class="block">
                                <x-label-check for="viewpriceantes">
                                    <x-input x-model="viewpriceantes" name="viewpriceantes" type="checkbox"
                                        id="viewpriceantes" />
                                    MOSTRAR PRECIO ANTERIOR EN PRODUCTOS OFERTADOS
                                </x-label-check>
                                <x-jet-input-error for="viewpriceantes" />
                            </div>
                        @endif

                        @if (Module::isEnabled('Marketplace'))
                            <div class="w-full block">
                                <x-label-check for="viewalmacens">
                                    <x-input x-model="viewalmacens" name="viewalmacens" type="checkbox"
                                        id="viewalmacens" />
                                    MOSTRAR ALMACÉNES DEL PRODUCTO EN GALERÍA DE TIENDA VIRTUAL
                                </x-label-check>
                                <x-jet-input-error for="viewalmacens" />
                            </div>

                            <div class="w-full block">
                                <x-label-check for="viewalmacensdetalle">
                                    <x-input x-model="viewalmacensdetalle" name="viewalmacensdetalle" type="checkbox"
                                        id="viewalmacensdetalle" />
                                    MOSTRAR ALMACÉNES EN DETALLE DEL PRODUCTO DE TIENDA VIRTUAL
                                </x-label-check>
                                <x-jet-input-error for="viewalmacensdetalle" />
                            </div>

                            <div class="w-full block">
                                <x-label-check for="viewlogomarca">
                                    <x-input name="viewlogomarca" x-model="viewlogomarca" type="checkbox"
                                        id="viewlogomarca" />
                                    <div class="flex flex-col flex-1">
                                        MOSTRAR LOGO DE MARCA SOBRE IMÁGEN DEL PRODUCTO EN TIENDA VIRTUAL
                                        <small class="font-medium text-xs tracking-normal text-colorsubtitleform">
                                            Se recomienda subir imágenes en formato PNG de los productos.</small>
                                    </div>
                                </x-label-check>
                                <x-jet-input-error for="tipocambioauto" />
                            </div>
                        @endif

                        <div class="block">
                            <x-label-check for="usepricedolar">
                                <x-input x-model="usepricedolar" id="usepricedolar" type="checkbox"
                                    @change="changePricedolar" />USAR PRECIO EN DÓLARES</x-label-check>
                            <x-jet-input-error for="usepricedolar" />

                            <div x-show="!cambioauto">
                                <div x-show="openpricedolar" style="display: none;">
                                    <x-input class="w-full" type="number" x-model="tipocambio" placeholder="0.00"
                                        min="0" step="0.0001" />
                                </div>
                            </div>

                            <div x-show="cambioauto">
                                <x-disabled-text text="" x-text="tipocambio" />
                            </div>

                            <x-jet-input-error for="tipocambio" />

                            <div x-show="loadingdolar" style="display: none;">
                                <x-loading-next />
                            </div>
                        </div>

                        <div class="w-full animate__animated animate__fadeInDown" x-show="openpricedolar">
                            <x-label-check for="viewpricedolar">
                                <x-input x-model="viewpricedolar" name="viewpricedolar" type="checkbox"
                                    id="viewpricedolar" />VER PRECIO EN DÓLARES</x-label-check>
                            <x-jet-input-error for="viewpricedolar" />
                        </div>

                        <div class="w-full animate__animated animate__fadeInDown" x-show="openpricedolar">
                            <x-label-check for="tipocambioauto">
                                <x-input name="tipocambioauto" x-model="cambioauto" type="checkbox"
                                    id="tipocambioauto" @change="changeAutomatico" />
                                <div class="flex flex-col flex-1">
                                    ACTUALIZAR TIPO CAMBIO AUTOMÁTICO
                                    <small class="font-medium text-xs tracking-normal text-colorsubtitleform">
                                        El tipo de cambio se actualizará todos los dias a las 12:00 am
                                        hrs</small>
                                </div>
                            </x-label-check>
                            <x-jet-input-error for="tipocambioauto" />
                        </div>
                    </div>

                    <div class="w-full mt-2">
                        <x-label-check for="usemarca_agua">
                            <x-input x-model="usemarkagua" type="checkbox" x-on:change="openmark = !openmark"
                                id="usemarca_agua" />
                            <div class="flex flex-col flex-1">
                                AGREGAR MARCA DE AGUA EN IMAGENES DE PRODUCTOS
                                <small class="font-medium text-xs tracking-normal text-colorsubtitleform">
                                    Se recomienda usar imagen transparente PNG</small>
                            </div>
                        </x-label-check>
                        <x-jet-input-error for="usemarkagua" />

                        <div class="grid grid-cols-1 xs:grid-cols-2 gap-2 items-start mt-2" style="display: none;"
                            x-show="openmark" x-cloak>
                            <div class="w-full text-center">
                                <x-simple-card class="w-full h-40 md:max-w-xs mb-1 !shadow-none">
                                    <template x-if="markagua">
                                        <img id="markagua" class="object-scale-down block w-full h-full"
                                            :src="markagua" />
                                    </template>
                                    <template x-if="!markagua">
                                        <x-icon-file-upload class="w-full h-full" />
                                    </template>
                                </x-simple-card>
                                <x-jet-input-error for="markagua" />

                                <div class="w-full flex gap-1 flex-wrap justify-center">
                                    <template x-if="markagua">
                                        <x-button class="inline-flex " @click="resetmarkagua"
                                            wire:loading.attr="disabled">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline-block"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 6h18" />
                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                                <line x1="10" x2="10" y1="11" y2="17" />
                                                <line x1="14" x2="14" y1="11" y2="17" />
                                            </svg>
                                            LIMPIAR
                                        </x-button>
                                    </template>

                                    <x-input-file for="fileMark" titulo="SELECCIONAR MARCA AGUA"
                                        wire:loading.class="disabled:opacity-25" class="">
                                        <input type="file" class="hidden" id="fileMark" accept="image/png"
                                            @change="loadmarkagua" />
                                    </x-input-file>
                                </div>
                            </div>

                            <div class="w-full grid grid-cols-1 gap-1 items-start">
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
                                    <x-jet-input-error for="alignmark" />
                                </div>
                                <div class="w-full">
                                    <x-label value="Ancho :" />
                                    <x-input class="block w-full" wire:model.defer="widthmark" type="number"
                                        step="1" onkeypress="return validarNumero(event, 3)" />
                                    <x-jet-input-error for="widthmark" />
                                </div>
                                <div class="w-full">
                                    <x-label value="Alto :" />
                                    <x-input class="block w-full" wire:model.defer="heightmark" type="number"
                                        step="1" onkeypress="return validarNumero(event, 3)" />
                                    <x-jet-input-error for="heightmark" />
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- @endif --}}

                    <div class="w-full grid sm:grid-cols-2 gap-3 my-5">
                        <div class="w-full text-center">
                            <div
                                class="w-full h-60 relative mb-2 shadow-md shadow-shadowminicard rounded-xl overflow-hidden">
                                <template x-if="image">
                                    <img id="image" class="object-scale-down block w-full h-full"
                                        :src="image" />
                                </template>
                                <template x-if="!image">
                                    <x-icon-file-upload class="w-full h-full" />
                                </template>
                            </div>

                            <div class="w-full flex gap-1 flex-wrap justify-center">
                                <template x-if="image">
                                    <x-button class="inline-flex " @click="resetlogo" wire:loading.attr="disabled">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline-block"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M3 6h18" />
                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                            <line x1="10" x2="10" y1="11" y2="17" />
                                            <line x1="14" x2="14" y1="11" y2="17" />
                                        </svg>
                                        LIMPIAR
                                    </x-button>
                                </template>

                                <x-input-file for="fileInput" titulo="SELECCIONAR LOGO"
                                    wire:loading.class="disabled:opacity-25" class="">
                                    <input type="file" class="hidden" id="fileInput" accept="image/*"
                                        name="photo" @change="loadlogo" />
                                </x-input-file>
                            </div>
                        </div>
                        <div class="w-full text-center">
                            <div
                                class="w-full h-60 relative mb-2 shadow-md shadow-shadowminicard rounded-xl overflow-hidden">
                                <template x-if="icono">
                                    <img id="icono" class="object-scale-down block w-full h-full"
                                        :src="icono" />
                                </template>
                                <template x-if="!icono">
                                    <x-icon-file-upload class="w-full h-full" />
                                </template>
                            </div>

                            <div class="w-full flex gap-1 flex-wrap justify-center">
                                <template x-if="icono">
                                    <x-button class="inline-flex " @click="reseticono" wire:loading.attr="disabled">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline-block"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M3 6h18" />
                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                            <line x1="10" x2="10" y1="11" y2="17" />
                                            <line x1="14" x2="14" y1="11" y2="17" />
                                        </svg>
                                        LIMPIAR
                                    </x-button>
                                </template>

                                <x-input-file for="fileInputIcono" titulo="SELECCIONAR ICONO WEB"
                                    wire:loading.class="disabled:opacity-25" class="">
                                    <input type="file" class="hidden" id="fileInputIcono" accept=".ico"
                                        name="photo" @change="loadicono" />
                                </x-input-file>
                            </div>
                        </div>
                    </div>
                </div>

                <div x-transition:enter="opacity-0 scale-95 transform transition-transform ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-105" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="opacity-0 absolute"
                    x-transition:leave-start="opacity-0 scale-100 transition-transform ease-out duration-500"
                    x-transition:leave-end="opacity-0 scale-95" x-cloak style="display:none;" x-show="step === 3"k>
                    <div class="w-full flex flex-col gap-10">

                        <div class="w-full grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <div class="w-full">
                                <x-label value="Correo :" />
                                <x-input class="block w-full" wire:model.defer="email" type="email"
                                    placeholder="@" />
                                <x-jet-input-error for="email" />
                            </div>
                            <div class="w-full">
                                <x-label value="Página web :" />
                                <x-input class="block w-full" wire:model.defer="web"
                                    placeholder="www.misitioweb.com" />
                                <x-jet-input-error for="web" />
                            </div>

                            @if (module::isEnabled('Marketplace'))
                                <div class="w-full">
                                    <x-label value="Link whatsApp :" />
                                    <x-input class="block w-full" wire:model.defer="whatsapp" />
                                    <x-jet-input-error for="whatsapp" />
                                </div>

                                <div class="w-full">
                                    <x-label value="Link Facebook :" />
                                    <x-input class="block w-full" wire:model.defer="facebook" />
                                    <x-jet-input-error for="facebook" />
                                </div>

                                <div class="w-full">
                                    <x-label value="Link YouTube :" />
                                    <x-input class="block w-full" wire:model.defer="youtube" />
                                    <x-jet-input-error for="youtube" />
                                </div>

                                <div class="w-full">
                                    <x-label value="Link Instagram :" />
                                    <x-input class="block w-full" wire:model.defer="instagram" />
                                    <x-jet-input-error for="instagram" />
                                </div>

                                <div class="w-full">
                                    <x-label value="Link Tik Tok :" />
                                    <x-input class="block w-full" wire:model.defer="tiktok" />
                                    <x-jet-input-error for="tiktok" />
                                </div>
                            @endif
                        </div>

                        <div class="w-full grid grid-cols-1 sm:grid-cols-2 gap-2">
                            @if (module::isEnabled('Ventas') || module::isEnabled('Almacen'))
                                <form wire:submit.prevent="addalmacen"
                                    class="w-full grid gap-2 border border-borderminicard rounded-xl p-2">
                                    <div class="w-full">
                                        <x-label value="Almacén :" />
                                        <x-input class="block w-full" wire:model.defer="namealmacen" />
                                        <x-jet-input-error for="namealmacen" />
                                    </div>
                                    <div class="flex items-start justify-end">
                                        <x-button class="p-3" type="submit" wire:loading.attr="disabled">
                                            AGREGAR</x-button>
                                    </div>
                                    <div>
                                        <x-jet-input-error for="almacens" />
                                        @if (count($almacens) > 0)
                                            <div class="w-full flex flex-wrap gap-2">
                                                @foreach ($almacens as $index => $item)
                                                    <x-simple-card
                                                        class="size-24 cursor-pointer flex flex-col justify-center items-center gap-1 p-1 rounded-xl">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="size-6 block mx-auto text-colorsubtitleform"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="1.5" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <path
                                                                d="M13 22C12.1818 22 11.4002 21.6588 9.83691 20.9764C8.01233 20.18 6.61554 19.5703 5.64648 19H2M13 22C13.8182 22 14.5998 21.6588 16.1631 20.9764C20.0544 19.2779 22 18.4286 22 17V6.5M13 22L13 11M4 6.5L4 9.5" />
                                                            <path
                                                                d="M9.32592 9.69138L6.40472 8.27785C4.80157 7.5021 4 7.11423 4 6.5C4 5.88577 4.80157 5.4979 6.40472 4.72215L9.32592 3.30862C11.1288 2.43621 12.0303 2 13 2C13.9697 2 14.8712 2.4362 16.6741 3.30862L19.5953 4.72215C21.1984 5.4979 22 5.88577 22 6.5C22 7.11423 21.1984 7.5021 19.5953 8.27785L16.6741 9.69138C14.8712 10.5638 13.9697 11 13 11C12.0303 11 11.1288 10.5638 9.32592 9.69138Z" />
                                                            <path d="M18.1366 4.01563L7.86719 8.98485" />
                                                            <path d="M2 13H5" />
                                                            <path d="M2 16H5" />
                                                        </svg>

                                                        <h1
                                                            class="text-[10px] font-semibold text-colorsubtitleform text-center leading-3 uppercase">
                                                            {{ $item }}</h1>
                                                        <x-button-delete
                                                            wire:click="removealmacen({{ $index }})"
                                                            wire:loading.attr="disabled" />
                                                    </x-simple-card>
                                                @endforeach
                                            </div>
                                            <x-jet-input-error for="almacens" />
                                        @else
                                            <p class="text-colorsubtitleform text-xs">
                                                No existen almacenes agregados...</p>
                                        @endif
                                    </div>
                                </form>
                            @endif

                            <form wire:submit.prevent="addphone"
                                class="grid grid-cols-1 gap-2 border border-borderminicard rounded-xl p-2">
                                <div class="w-full">
                                    <x-label value="Teléfono /Celular :" />
                                    <x-input class="block w-full input-number-none" wire:model.defer="telefono"
                                        type="number" onkeypress="return validarNumero(event, 9)" />
                                    <x-jet-input-error for="telefono" />
                                </div>

                                <div class="flex items-start justify-end">
                                    <x-button class="p-3" type="submit" wire:loading.attr="disabled">
                                        AGREGAR</x-button>
                                </div>
                                <x-jet-input-error for="telephones" />

                                @if (count($telephones) > 0)
                                    <div class="w-full flex flex-wrap gap-2">
                                        @foreach ($telephones as $index => $item)
                                            <x-simple-card
                                                class="size-24 cursor-pointer flex flex-col justify-center items-center gap-1 p-1 rounded-xl">
                                                <svg class="text-green-600 size-6" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="1.5" stroke-linecap="round">
                                                    <path
                                                        d="M9.1585 5.71223L8.75584 4.80625C8.49256 4.21388 8.36092 3.91768 8.16405 3.69101C7.91732 3.40694 7.59571 3.19794 7.23592 3.08785C6.94883 3 6.6247 3 5.97645 3C5.02815 3 4.554 3 4.15597 3.18229C3.68711 3.39702 3.26368 3.86328 3.09497 4.3506C2.95175 4.76429 2.99278 5.18943 3.07482 6.0397C3.94815 15.0902 8.91006 20.0521 17.9605 20.9254C18.8108 21.0075 19.236 21.0485 19.6496 20.9053C20.137 20.7366 20.6032 20.3131 20.818 19.8443C21.0002 19.4462 21.0002 18.9721 21.0002 18.0238C21.0002 17.3755 21.0002 17.0514 20.9124 16.7643C20.8023 16.4045 20.5933 16.0829 20.3092 15.8362C20.0826 15.6393 19.7864 15.5077 19.194 15.2444L18.288 14.8417C17.6465 14.5566 17.3257 14.4141 16.9998 14.3831C16.6878 14.3534 16.3733 14.3972 16.0813 14.5109C15.7762 14.6297 15.5066 14.8544 14.9672 15.3038C14.4304 15.7512 14.162 15.9749 13.834 16.0947C13.5432 16.2009 13.1588 16.2403 12.8526 16.1951C12.5071 16.1442 12.2426 16.0029 11.7135 15.7201C10.0675 14.8405 9.15977 13.9328 8.28011 12.2867C7.99738 11.7577 7.85602 11.4931 7.80511 11.1477C7.75998 10.8414 7.79932 10.457 7.90554 10.1663C8.02536 9.83828 8.24905 9.56986 8.69643 9.033C9.14586 8.49368 9.37058 8.22402 9.48939 7.91891C9.60309 7.62694 9.64686 7.3124 9.61719 7.00048C9.58618 6.67452 9.44362 6.35376 9.1585 5.71223Z" />
                                                </svg>

                                                <h1 class="text-xs text-colorsubtitleform">
                                                    {{ formatTelefono($item) }}</h1>
                                                <x-button-delete wire:click="removephone({{ $index }})"
                                                    wire:loading.attr="disabled" />
                                            </x-simple-card>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-colorsubtitleform text-xs">No existen telefono agregados...</p>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>

                <div x-transition:enter="opacity-0 scale-95 transform transition-transform ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-105" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="opacity-0 absolute"
                    x-transition:leave-start="opacity-0 scale-100 transition-transform ease-out duration-500"
                    x-transition:leave-end="opacity-0 scale-95" x-cloak style="display:none;" x-show="step === 4">
                    <div class="w-full flex justify-end">
                        <x-button class=" p-3" wire:click="openmodalsucursal">
                            {{ __('AGREGAR NUEVA SUCURSAL') }}</x-button>
                    </div>

                    @if (count($sucursals) > 0)
                        <p class="text-colorsubtitleform text-xs">SELECCIONAR SUCURSALES PARA CONTINUAR...</p>
                        <x-jet-input-error for="selectedsucursals" />
                        <div class="w-full flex flex-col gap-2 mt-3">
                            @foreach ($sucursals as $index => $item)
                                <x-simple-card x-data="{ rowselected: false }" wire:key="cardsuc_{{ $item['id'] }}"
                                    class="w-full cursor-default text-xs flex flex-col gap-2 p-1 sm:p-3 rounded-xl {{ $item['default'] > 0 ? 'bg-fondominicard !border-next-600' : '' }}">
                                    @if ($item['default'] > 0)
                                        <div
                                            class="text-xs text-colortitleform align-middle flex w-full items-center gap-1">
                                            <x-icon-default class="inline-block flex-shrink-0 m-auto align-middle" />
                                            <h1 class="w-full flex-1">PREDETERMINADO</h1>
                                        </div>
                                    @else
                                        <div class="inline-block py-1">
                                            <x-input class="cursor-pointer" type="checkbox" x-model="rowselected"
                                                name="selectedsucursals" wire:model.defer="selectedsucursals"
                                                id="showsucursal_{{ $item['id'] }}" value="{{ $item['codigo'] }}"
                                                wire:key="showsucursal_{{ $item['id'] }}" />
                                            <x-label
                                                class="inline-block pl-2 cursor-pointer font-semibold text-colortitleform"
                                                value="SELECCIONAR" for="showsucursal_{{ $item['id'] }}" />
                                        </div>
                                    @endif

                                    <div>
                                        <x-label value="DESCRIPCIÓN:" class="font-semibold" />
                                        <p class="text-colorsubtitleform leading-3 uppercase">
                                            {{ $item['descripcion'] }}</p>
                                    </div>

                                    <div>
                                        <x-label value="DIRECCIÓN :" class="font-semibold" />
                                        <p class="text-colorsubtitleform uppercase">
                                            {{ $item['distrito'] }}, {{ $item['provincia'] }},
                                            {{ $item['departamento'] }}
                                            <br>
                                            {{ $item['direccion'] }}
                                        </p>
                                    </div>

                                    @if ($item['default'] <= 0)
                                        <div>
                                            <x-label value="CÓDIGO ESTABLECIMIENTO :" class="font-semibold" />
                                            <p class="text-colorsubtitleform leading-3">
                                                {{ $item['codigo'] }}</p>
                                        </div>
                                    @endif

                                    <div class="w-full">
                                        <x-label value="COMPROBANTES DE VENTA:" class="font-semibold" />
                                        @if (count($item['seriecomprobantes']) > 0)
                                            <div class="w-full overflow-auto">
                                                <x-table class="rounded-lg">
                                                    <x-slot name="body">
                                                        @foreach ($item['seriecomprobantes'] as $index => $value)
                                                            <tr class="text-colorsubtitleform">
                                                                <td
                                                                    class="p-1.5 py-2.5 text-[10px] align-middle leading-none">
                                                                    {{ $value['typecomprobante']['descripcion'] }}
                                                                </td>
                                                                <td
                                                                    class="p-1.5 py-2.5 font-semibold text-center align-middle flex items-center gap-2">
                                                                    @if ($value['default'] > 0)
                                                                        <x-icon-default class="inline-block" />
                                                                    @endif
                                                                    {{ $value['serie'] }}
                                                                </td>
                                                                <td class="p-1.5 py-2.5 text-center text-[10px]">
                                                                    CONTADOR: {{ $value['contador'] }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </x-slot>
                                                </x-table>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="w-full">
                                        <x-label value="CAJAS DE PAGO:" class="font-semibold" />
                                        @if (count($item['boxes']) > 0)
                                            <div class="w-full flex flex-wrap gap-2">
                                                @foreach ($item['boxes'] as $box)
                                                    <x-minicard size="md" class="text-xs">
                                                        {{ $box['name'] }}

                                                        <h1 class="text-lg text-colorsubtitleform font-semibold">
                                                            <small class="leading-none text-xs font-medium">
                                                                S/. </small>
                                                            {{ number_format($box['apertura'], 2, '.', ', ') }}
                                                        </h1>

                                                        {{-- <x-slot name="buttons">
                                                                <x-button-delete
                                                                    wire:key="removeboxmodal_{{ $index }}"
                                                                    wire:click="removebox({{ $index }})"
                                                                    wire:loading.attr="disabled" />
                                                            </x-slot> --}}
                                                    </x-minicard>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                    <div class="w-full flex items-end gap-2 justify-end">
                                        @if ($item['default'] > 0)
                                            <x-button-edit wire:click="editsucursal('{{ $item['id'] }}')"
                                                wire:loading.attr="disabled"
                                                wire:key="editsucursaldefault_{{ $item['id'] }}" />
                                        @else
                                            <x-button-edit x-cloak x-show="rowselected"
                                                wire:click="editsucursal('{{ $item['id'] }}')"
                                                wire:loading.attr="disabled"
                                                wire:key="editsuc_{{ $item['id'] }}" />
                                            <x-button-delete x-cloak x-show="rowselected"
                                                wire:click="removesucursal('{{ $item['id'] }}')"
                                                wire:loading.attr="disabled"
                                                wire:key="deletesuc_{{ $item['id'] }}" />
                                        @endif
                                    </div>
                                </x-simple-card>
                            @endforeach
                        </div>
                    @else
                        <p class="text-colorsubtitleform text-xs">No existen sucursales agregados...</p>
                    @endif
                </div>

                <div x-transition:enter="opacity-0 scale-95 transform transition-transform ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-105" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="opacity-0 absolute"
                    x-transition:leave-start="opacity-0 scale-100 transition-transform ease-out duration-500"
                    x-transition:leave-end="opacity-0 scale-95" x-cloak style="display:none;" x-show="step === 5">
                    <form wire:submit.prevent="validatestep('5')" class="w-full flex flex-col gap-2">
                        @if (module::isEnabled('Facturacion'))
                            <div class="w-full grid gap-2 grid-cols-1">
                                <div class="w-full">
                                    <x-label value="Afectación IGV :" />
                                    <div class="w-full relative" id="parentafectacionigv" x-init="SelectAfectacionIGV"
                                        wire:ignore>
                                        <x-select class="block w-full" x-ref="selectafectacionigv"
                                            id="afectacionigv">
                                            <x-slot name="options">
                                                <option value="0">EXONERAR IGV</option>
                                                <option value="1">INCLUIR IGV</option>
                                            </x-slot>
                                        </x-select>
                                        <x-icon-select />
                                    </div>
                                    <x-jet-input-error for="afectacionigv" />
                                </div>
                                <div class="w-full">
                                    <x-label value="Modo envío SUNAT :" />
                                    <div class="w-full relative" id="parentsendmode" x-init="SelectMode"
                                        wire:ignore>
                                        <x-select class="block w-full" x-ref="selectmode" id="sendmode">
                                            <x-slot name="options">
                                                <option value="0">MODO PRUEBAS</option>
                                                <option value="1">MODO PRODUCCIÓN</option>
                                            </x-slot>
                                        </x-select>
                                        <x-icon-select />
                                    </div>
                                    <x-jet-input-error for="sendmode" />
                                </div>
                                <div class="w-full">
                                    <x-label value="Usuario SOL :" />
                                    <template x-if="sendmode > 0">
                                        <x-input class="block w-full" x-model="usuariosol"
                                            placeholder="Ingrese usuario SOL Sunat..." />
                                    </template>
                                    <template x-if="sendmode <= 0">
                                        <x-disabled-text x-text="usuariosol" text="" class="input-text" />
                                    </template>
                                    <x-jet-input-error for="usuariosol" />
                                </div>
                                <div class="w-full">
                                    <x-label value="Clave SOL :" />
                                    <template x-if="sendmode > 0">
                                        <x-input class="block w-full" x-model="clavesol"
                                            placeholder="Ingrese clave SOL Sunat..." type="password" />
                                    </template>
                                    <template x-if="sendmode <= 0">
                                        <x-disabled-text x-text="clavesol" text="" class="input-text" />
                                    </template>
                                    <x-jet-input-error for="clavesol" />
                                </div>
                                <div class="w-full">
                                    <x-label value="Client ID (Guías Remisión):" />
                                    <template x-if="sendmode > 0">
                                        <x-input class="block w-full" x-model="clientid"
                                            placeholder="Ingrese client id..." />
                                    </template>
                                    <template x-if="sendmode <= 0">
                                        <x-disabled-text x-text="clientid" text="" class="input-text" />
                                    </template>
                                    <x-jet-input-error for="clientid" />
                                </div>
                                <div class="w-full">
                                    <x-label value="Client secret (Guías Remisión):" />
                                    <template x-if="sendmode > 0">
                                        <x-input class="block w-full" x-model="clientsecret"
                                            placeholder="Ingrese client secret..." />
                                    </template>
                                    <template x-if="sendmode <= 0">
                                        <x-disabled-text x-text="clientsecret" text="" class="input-text" />
                                    </template>
                                    <x-jet-input-error for="clientsecret" />
                                </div>
                                <div class="w-full">
                                    <x-label value="Clave certificado digital :" />
                                    <template x-if="sendmode > 0">
                                        <x-input class="block w-full" x-model="passwordcert"
                                            placeholder="Contraseña del certificado..." type="password" />
                                    </template>
                                    <template x-if="sendmode <= 0">
                                        <x-disabled-text text="••••••••" class="input-text" />
                                    </template>
                                    <x-jet-input-error for="passwordcert" />
                                </div>
                            </div>
                            <div x-cloak x-show="sendmode == 1" class="relative w-full xs:max-w-xs text-center">
                                @if (isset($cert))
                                    <x-icon-file-upload type="filesuccess" :uploadname="$cert->getClientOriginalName()" class="w-40 h-full" />
                                @else
                                    <x-icon-file-upload type="code" text="PFX" class="w-40 h-full" />
                                @endif

                                <div class="w-full flex gap-1 flex-wrap justify-center">
                                    <x-input-file :for="$idcert" titulo="CARGAR CERTIFICADO DIGITAL"
                                        wire:loading.remove class=" p-3">
                                        <input type="file" class="hidden" wire:model="cert"
                                            id="{{ $idcert }}" accept=".pfx" />
                                    </x-input-file>

                                    @if (isset($cert))
                                        <x-button class="inline-flex  p-3" wire:loading.attr="disabled"
                                            wire:click="clearCert">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline-block"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 6h18" />
                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                                <line x1="10" x2="10" y1="11" y2="17" />
                                                <line x1="14" x2="14" y1="11" y2="17" />
                                            </svg>
                                            LIMPIAR
                                        </x-button>
                                    @endif
                                </div>
                                <x-jet-input-error for="cert" class="text-center" />
                            </div>
                        @else
                            <p class="text-colorsubtitleform text-xs">No se encontraron existen opciones</p>
                        @endif
                        <input class="hidden" style="display: none" type="submit">
                    </form>
                </div>

                <div x-transition:enter="opacity-0 scale-95 transform transition-transform ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-105" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="opacity-0 absolute"
                    x-transition:leave-start="opacity-0 scale-100 transition-transform ease-out duration-500"
                    x-transition:leave-end="opacity-0 scale-95" x-cloak style="display:none;" x-show="step === 6">
                    @if (count($empresa))
                        <x-simple-card class="p-3 rounded-xl">
                            <template x-if="image">
                                <img class="object-scale-down block w-auto max-w-full h-28 mx-auto"
                                    :src="image" />
                            </template>
                            <h1 class="font-semibold text-center text-2xl text-colorlabel">
                                {{ $empresa['name'] }}</h1>
                            <p class="text-colorsubtitleform text-xs leading-3 text-center">
                                {{ $empresa['document'] }}</p>
                            <p class="text-colorsubtitleform text-xs leading-3 text-center">
                                {{ $empresa['direccion'] }} <br>
                                {{ $empresa['distrito'] }},
                                {{ $empresa['provincia'] }},
                                {{ $empresa['departamento'] }}
                            </p>

                            @if ($empresa['email'])
                                <p class="text-colorsubtitleform text-xs text-center">
                                    {{ $empresa['email'] }}</p>
                            @endif
                            @if ($empresa['web'])
                                <p class="text-colorsubtitleform text-xs text-center">
                                    {{ $empresa['web'] }}</p>
                            @endif

                            @if (!empty($empresa['whatsapp']))
                                <p class="text-colorsubtitleform text-xs text-center">
                                    <b>WHATSAPP: </b>{{ $empresa['whatsapp'] }}
                                </p>
                            @endif
                            @if (!empty($empresa['facebook']))
                                <p class="text-colorsubtitleform text-xs text-center">
                                    <b>FACEBOOK: </b>{{ $empresa['facebook'] }}
                                </p>
                            @endif
                            @if (!empty($empresa['youtube']))
                                <p class="text-colorsubtitleform text-xs text-center">
                                    <b>YOUTUBE: </b>{{ $empresa['youtube'] }}
                                </p>
                            @endif
                            @if (!empty($empresa['instagram']))
                                <p class="text-colorsubtitleform text-xs text-center">
                                    <b>INSTAGRAM: </b>{{ $empresa['instagram'] }}
                                </p>
                            @endif
                            @if (!empty($empresa['tiktok']))
                                <p class="text-colorsubtitleform text-xs text-center">
                                    <b>TIK TOK: </b>{{ $empresa['tiktok'] }}
                                </p>
                            @endif



                            <p class="text-colorsubtitleform text-xs text-center">
                                <b>IGV: </b>{{ $empresa['igv'] }}%
                            </p>

                            <template x-if="icono">
                                <img class="object-scale-down block w-12 h-12 mx-auto" :src="icono" />
                            </template>
                        </x-simple-card>

                        @if (count($telephones) > 0)
                            <div class="mt-3 w-full flex flex-wrap gap-2">
                                @foreach ($telephones as $index => $item)
                                    <x-simple-card
                                        class="w-24 h-24 text-green-600 cursor-pointer flex flex-col justify-center items-center gap-1 p-1 rounded-xl">
                                        <span class="block w-6 h-6">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                color="currentColor" fill="currentColor" stroke="currentColor"
                                                stroke-width="2" stroke-linejoin="round">
                                                <path
                                                    d="M3.77762 11.9424C2.8296 10.2893 2.37185 8.93948 2.09584 7.57121C1.68762 5.54758 2.62181 3.57081 4.16938 2.30947C4.82345 1.77638 5.57323 1.95852 5.96 2.6524L6.83318 4.21891C7.52529 5.46057 7.87134 6.08139 7.8027 6.73959C7.73407 7.39779 7.26737 7.93386 6.33397 9.00601L3.77762 11.9424ZM3.77762 11.9424C5.69651 15.2883 8.70784 18.3013 12.0576 20.2224M12.0576 20.2224C13.7107 21.1704 15.0605 21.6282 16.4288 21.9042C18.4524 22.3124 20.4292 21.3782 21.6905 19.8306C22.2236 19.1766 22.0415 18.4268 21.3476 18.04L19.7811 17.1668C18.5394 16.4747 17.9186 16.1287 17.2604 16.1973C16.6022 16.2659 16.0661 16.7326 14.994 17.666L12.0576 20.2224Z" />
                                            </svg>
                                        </span>
                                        <h1 class="text-xs">{{ formatTelefono($item) }}</h1>
                                    </x-simple-card>
                                @endforeach
                            </div>
                        @endif

                        <x-simple-card class="p-3 rounded-xl mt-3">
                            <p
                                class="text-xs w-full {{ $empresa['uselistprice'] == '1' ? 'text-green-600' : 'text-colorerror' }}">
                                @if ($empresa['uselistprice'])
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" color="currentColor"
                                        fill="none" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="w-4 h-4 inline-block text-green-600">
                                        <path d="M5 14.5C5 14.5 6.5 14.5 8.5 18C8.5 18 14.0588 8.83333 19 7" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" color="currentColor"
                                        fill="none" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="w-4 h-4 inline-block text-colorerror">
                                        <path d="M19.0005 4.99988L5.00045 18.9999M5.00045 4.99988L19.0005 18.9999" />
                                    </svg>
                                @endif
                                USAR LISTA DE PRECIOS
                            </p>
                            <p class="text-xs w-full text-green-600">
                                @if ($empresa['viewtextopromocion'] >= 0)
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" color="currentColor"
                                        fill="none" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="w-4 h-4 inline-block text-green-600">
                                        <path d="M5 14.5C5 14.5 6.5 14.5 8.5 18C8.5 18 14.0588 8.83333 19 7" />
                                    </svg>
                                @endif
                                ETIQUETA DE PRODUCTO PROMOCIONADO
                                @if ($empresa['viewtextopromocion'] == \App\Models\Empresa::TITLE_PROMOCION)
                                    ("PROMOCIÓN")
                                @elseif($empresa['viewtextopromocion'] == \App\Models\Empresa::TITLE_PROMO_LIQUIDACION)
                                    ( "LIQUIDACIÓN")
                                @else
                                    ("PREDETERMINADO")
                                @endif
                            </p>
                            <p
                                class="text-xs w-full {{ $empresa['generatesku'] == '1' ? 'text-green-600' : 'text-colorerror' }}">
                                @if ($empresa['generatesku'])
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" color="currentColor"
                                        fill="none" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="w-4 h-4 inline-block text-green-600">
                                        <path d="M5 14.5C5 14.5 6.5 14.5 8.5 18C8.5 18 14.0588 8.83333 19 7" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" color="currentColor"
                                        fill="none" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="w-4 h-4 inline-block text-colorerror">
                                        <path d="M19.0005 4.99988L5.00045 18.9999M5.00045 4.99988L19.0005 18.9999" />
                                    </svg>
                                @endif
                                GENERAR SKU DE PRODUCTOS DE MANERA AUTOMÁTICA
                            </p>
                            <p
                                class="text-xs w-full {{ $empresa['viewpriceantes'] == '1' ? 'text-green-600' : 'text-colorerror' }}">
                                @if ($empresa['viewpriceantes'])
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" color="currentColor"
                                        fill="none" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="w-4 h-4 inline-block text-green-600">
                                        <path d="M5 14.5C5 14.5 6.5 14.5 8.5 18C8.5 18 14.0588 8.83333 19 7" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" color="currentColor"
                                        fill="none" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="w-4 h-4 inline-block text-colorerror">
                                        <path d="M19.0005 4.99988L5.00045 18.9999M5.00045 4.99988L19.0005 18.9999" />
                                    </svg>
                                @endif
                                MOSTRAR PRECIO ANTERIOR EN PRODUCTOS OFERTADOS
                            </p>
                            <p
                                class="text-xs w-full {{ $empresa['viewlogomarca'] == '1' ? 'text-green-600' : 'text-colorerror' }}">
                                @if ($empresa['viewlogomarca'])
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" color="currentColor"
                                        fill="none" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="w-4 h-4 inline-block text-green-600">
                                        <path d="M5 14.5C5 14.5 6.5 14.5 8.5 18C8.5 18 14.0588 8.83333 19 7" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" color="currentColor"
                                        fill="none" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="w-4 h-4 inline-block text-colorerror">
                                        <path d="M19.0005 4.99988L5.00045 18.9999M5.00045 4.99988L19.0005 18.9999" />
                                    </svg>
                                @endif
                                MOSTRAR LOGO DE MARCA EN PRODUCTOS
                            </p>

                            @if (Module::isEnabled('Marketplace'))
                                <p
                                    class="text-xs w-full {{ $empresa['viewalmacens'] == '1' ? 'text-green-600' : 'text-colorerror' }}">
                                    @if ($empresa['viewalmacens'])
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            color="currentColor" fill="none" stroke="currentColor"
                                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                            class="w-4 h-4 inline-block text-green-600">
                                            <path d="M5 14.5C5 14.5 6.5 14.5 8.5 18C8.5 18 14.0588 8.83333 19 7" />
                                        </svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            color="currentColor" fill="none" stroke="currentColor"
                                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                            class="w-4 h-4 inline-block text-colorerror">
                                            <path
                                                d="M19.0005 4.99988L5.00045 18.9999M5.00045 4.99988L19.0005 18.9999" />
                                        </svg>
                                    @endif
                                    MOSTRAR ALMACÉNES DEL PRODUCTO EN GALERÍA DE TIENDA VIRTUAL
                                </p>
                                <p
                                    class="text-xs w-full {{ $empresa['viewalmacensdetalle'] == '1' ? 'text-green-600' : 'text-colorerror' }}">
                                    @if ($empresa['viewalmacensdetalle'])
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            color="currentColor" fill="none" stroke="currentColor"
                                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                            class="w-4 h-4 inline-block text-green-600">
                                            <path d="M5 14.5C5 14.5 6.5 14.5 8.5 18C8.5 18 14.0588 8.83333 19 7" />
                                        </svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            color="currentColor" fill="none" stroke="currentColor"
                                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                            class="w-4 h-4 inline-block text-colorerror">
                                            <path
                                                d="M19.0005 4.99988L5.00045 18.9999M5.00045 4.99988L19.0005 18.9999" />
                                        </svg>
                                    @endif
                                    MOSTRAR ALMACÉNES EN DETALLE DEL PRODUCTO DE TIENDA VIRTUAL
                                </p>
                            @endif
                            <p
                                class="text-xs w-full {{ $empresa['usepricedolar'] == '1' ? 'text-green-600' : 'text-colorerror' }}">
                                @if ($empresa['usepricedolar'])
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" color="currentColor"
                                        fill="none" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="w-4 h-4 inline-block text-green-600">
                                        <path d="M5 14.5C5 14.5 6.5 14.5 8.5 18C8.5 18 14.0588 8.83333 19 7" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" color="currentColor"
                                        fill="none" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="w-4 h-4 inline-block text-colorerror">
                                        <path d="M19.0005 4.99988L5.00045 18.9999M5.00045 4.99988L19.0005 18.9999" />
                                    </svg>
                                @endif
                                USAR PRECIO EN DOLARES
                                @if ($empresa['tipocambio'])
                                    [{{ $empresa['tipocambio'] }}]
                                @endif
                            </p>
                            <p
                                class="text-xs w-full {{ $empresa['viewpricedolar'] == '1' ? 'text-green-600' : 'text-colorerror' }}">
                                @if ($empresa['viewpricedolar'])
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" color="currentColor"
                                        fill="none" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="w-4 h-4 inline-block text-green-600">
                                        <path d="M5 14.5C5 14.5 6.5 14.5 8.5 18C8.5 18 14.0588 8.83333 19 7" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" color="currentColor"
                                        fill="none" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="w-4 h-4 inline-block text-colorerror">
                                        <path d="M19.0005 4.99988L5.00045 18.9999M5.00045 4.99988L19.0005 18.9999" />
                                    </svg>
                                @endif
                                MOSTRAR PRECIO EN DOLARES EN PRODUCTOS
                            </p>
                            <p
                                class="text-xs w-full {{ $empresa['tipocambioauto'] == '1' ? 'text-green-600' : 'text-colorerror' }}">
                                @if ($empresa['tipocambioauto'])
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" color="currentColor"
                                        fill="none" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="w-4 h-4 inline-block text-green-600">
                                        <path d="M5 14.5C5 14.5 6.5 14.5 8.5 18C8.5 18 14.0588 8.83333 19 7" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" color="currentColor"
                                        fill="none" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="w-4 h-4 inline-block text-colorerror">
                                        <path d="M19.0005 4.99988L5.00045 18.9999M5.00045 4.99988L19.0005 18.9999" />
                                    </svg>
                                @endif
                                ACTUALIZAR TIPO CAMBIO AUTOMATICO (12:00 am hrs)
                            </p>
                        </x-simple-card>

                        @if (count($almacens) > 0)
                            <div class="mt-3 w-full flex flex-wrap gap-2">
                                @foreach ($almacens as $index => $item)
                                    <x-simple-card
                                        class="w-28 h-28 text-colorsubtitleform cursor-pointer flex flex-col justify-center items-center gap-1 p-1 rounded-xl">
                                        <span class="block w-6 h-6">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                color="currentColor" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linejoin="round">
                                                <path
                                                    d="M13 22C12.1818 22 11.4002 21.6588 9.83691 20.9764C8.01233 20.18 6.61554 19.5703 5.64648 19H2M13 22C13.8182 22 14.5998 21.6588 16.1631 20.9764C20.0544 19.2779 22 18.4286 22 17V6.5M13 22L13 11M4 6.5L4 9.5" />
                                                <path
                                                    d="M9.32592 9.69138L6.40472 8.27785C4.80157 7.5021 4 7.11423 4 6.5C4 5.88577 4.80157 5.4979 6.40472 4.72215L9.32592 3.30862C11.1288 2.43621 12.0303 2 13 2C13.9697 2 14.8712 2.4362 16.6741 3.30862L19.5953 4.72215C21.1984 5.4979 22 5.88577 22 6.5C22 7.11423 21.1984 7.5021 19.5953 8.27785L16.6741 9.69138C14.8712 10.5638 13.9697 11 13 11C12.0303 11 11.1288 10.5638 9.32592 9.69138Z" />
                                                <path d="M18.1366 4.01563L7.86719 8.98485" />
                                                <path d="M2 13H5" />
                                                <path d="M2 16H5" />
                                            </svg>
                                        </span>
                                        <h1 class="text-xs uppercase text-center leading-3">{{ $item }}
                                        </h1>
                                    </x-simple-card>
                                @endforeach
                            </div>
                        @endif

                        @php
                            $sucfilters = array_filter($sucursals, function ($item) use ($selectedsucursals) {
                                return in_array($item['codigo'], $selectedsucursals);
                            });
                        @endphp

                        @if (count($sucfilters) > 0)
                            <div class="w-full flex flex-col gap-2 mt-3">
                                @foreach ($sucfilters as $index => $item)
                                    <x-simple-card
                                        class="w-full cursor-default text-xs flex flex-col gap-2 p-3 rounded-xl {{ $item['default'] > 0 ? 'bg-fondominicard !border-next-600' : '' }}">
                                        @if ($item['default'] > 0)
                                            <h1 class="text-xs text-colortitleform align-middle">
                                                <x-icon-default class="inline-block m-auto align-middle" />
                                                TIENDA PRINCIPAL
                                            </h1>
                                        @endif

                                        <div>
                                            <x-label value="DESCRIPCIÓN:" class="font-semibold" />
                                            <p class="text-colorsubtitleform uppercase leading-3">
                                                {{ $item['descripcion'] }}</p>
                                        </div>

                                        <div>
                                            <x-label value="DIRECCIÓN :" class="font-semibold" />
                                            <p class="text-colorsubtitleform uppercase leading-3">
                                                {{ $item['direccion'] }}
                                                <br>
                                                {{ $item['distrito'] }}, {{ $item['provincia'] }},
                                                {{ $item['departamento'] }}
                                            </p>
                                        </div>

                                        @if ($item['default'] <= 0)
                                            <div>
                                                <x-label value="CÓDIGO ESTABLECIMIENTO :" class="font-semibold" />
                                                <p class="text-colorsubtitleform leading-3">
                                                    {{ $item['codigo'] }}</p>
                                            </div>
                                            {{-- <div>
                                                    <x-label value="TIPO ESTABLECIMIENTO :" class="font-semibold" />
                                                    <p class="text-colorsubtitleform leading-3">
                                                        [{{ $item['cod_tipo'] }}] - {{ $item['tipo'] }}</p>
                                                </div> --}}
                                        @endif

                                        <div class="w-full">
                                            <x-label value="COMPROBANTES DE VENTA:" class="font-semibold" />
                                            @if (count($item['seriecomprobantes']) > 0)
                                                <div class="w-full overflow-auto">
                                                    <x-table class="rounded-lg">
                                                        <x-slot name="body">
                                                            @foreach ($item['seriecomprobantes'] as $index => $value)
                                                                <tr class="text-colorsubtitleform">
                                                                    <td
                                                                        class="p-1.5 py-2.5 text-[10px] align-middle leading-none">
                                                                        {{ $value['typecomprobante']['descripcion'] }}
                                                                    </td>
                                                                    <td
                                                                        class="p-1.5 py-2.5 font-semibold text-center flex items-center gap-2">
                                                                        @if ($value['default'] > 0)
                                                                            <x-icon-default class="inline-block" />
                                                                        @endif
                                                                        {{ $value['serie'] }}
                                                                    </td>
                                                                    <td class="p-1.5 py-2.5 text-center text-[10px]">
                                                                        CONTADOR: {{ $value['contador'] }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </x-slot>
                                                    </x-table>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="w-full">
                                            <x-label value="CAJAS DE PAGO:" class="font-semibold" />
                                            @if (count($item['boxes']) > 0)
                                                <div class="w-full flex flex-wrap gap-2">
                                                    @foreach ($item['boxes'] as $box)
                                                        <x-minicard size="md" class="text-xs">
                                                            {{ $box['name'] }}

                                                            <h1 class="text-lg text-colorsubtitleform font-semibold">
                                                                <small class="leading-none text-xs font-medium">
                                                                    S/. </small>
                                                                {{ number_format($box['apertura'], 2, '.', ', ') }}
                                                            </h1>
                                                        </x-minicard>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </x-simple-card>
                                @endforeach
                            </div>
                        @endif

                        @if (module::isEnabled('Facturacion'))
                            <x-simple-card class="p-3 rounded-xl mt-3">
                                <x-label>
                                    <b>MODO ENVÍO: </b>
                                    @if ($empresa['sendmode'])
                                        PRODUCCIÓN
                                    @else
                                        PRUEBAS
                                    @endif
                                </x-label>

                                <x-label>
                                    <b>USUARIO SOL: </b>{{ $empresa['usuariosol'] }}
                                </x-label>

                                <x-label>
                                    <b>CLAVE SOL: </b>{{ $empresa['clavesol'] }}
                                </x-label>

                                <x-label>
                                    <b>CLIENT ID: </b>{{ $empresa['clientid'] }}
                                </x-label>

                                <x-label>
                                    <b>CLIENT SECRET: </b>{{ $empresa['clientsecret'] }}
                                </x-label>

                                <x-label>
                                    <b>CLAVE CERTIFICADO: </b>{{ $empresa['passwordcert'] }}
                                </x-label>

                                @if (isset($cert))
                                    <div class="relative w-32 text-center">
                                        <x-icon-file-upload type="filesuccess" :uploadname="$cert->getClientOriginalName()"
                                            class="w-full h-full" />
                                    </div>
                                @endif
                            </x-simple-card>
                        @endif

                        @if ($empresa['usemarkagua'])
                            <x-simple-card class="p-3 rounded-xl mt-3">
                                <p class="text-xs w-full text-green-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" color="currentColor"
                                        fill="none" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="w-4 h-4 inline-block text-green-600">
                                        <path d="M5 14.5C5 14.5 6.5 14.5 8.5 18C8.5 18 14.0588 8.83333 19 7" />
                                    </svg>
                                    USAR MARCA DE AGUA EN PRODUCTOS
                                </p>

                                <img id="markagua"
                                    class="object-scale-down block w-28 h-28 shadow-md p-2 rounded-xl my-2"
                                    :src="markagua" />

                                <x-label>
                                    <b>DIMENSIONES: </b>{{ $empresa['widthmark'] }} x {{ $empresa['heightmark'] }}
                                </x-label>
                                <x-label class="uppercase">
                                    <b>ALINEACION: </b>{{ $empresa['alignmark'] }}
                                </x-label>
                            </x-simple-card>
                        @endif

                        <x-jet-input-error for="markagua" />
                    @endif
                </div>
            </div>
        </div>

        <div class="sticky bottom-0 left-0 right-0 bg-fondominicard rounded-t-lg">
            <div class="w-full py-2 md:pt-3 px-3 md:px-0 grid grid-cols-2 gap-2 justify-between">
                <div class="">
                    <x-button wire:loading.attr="disabled" class="inline-flex items-center gap-2" x-show="step > 1"
                        x-cloak @click="step--">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                            color="currentColor" fill="none" class="w-6 h-6 inline-block">
                            <path d="M4 12L20 12" />
                            <path d="M8.99996 17C8.99996 17 4.00001 13.3176 4 12C3.99999 10.6824 9 7 9 7" />
                        </svg>
                        {{ __('Previus') }}
                    </x-button>
                </div>

                <div class=" text-right">
                    <x-button class="inline-flex items-center gap-2" wire:loading.attr="disabled"
                        x-show="step < 6" @click="$wire.validatestep(step)">
                        {{ __('Next') }}
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                            color="currentColor" fill="none" class="w-6 h-6 inline-block">
                            <path d="M20 12L4 12" />
                            <path d="M15 17C15 17 20 13.3176 20 12C20 10.6824 15 7 15 7" />
                        </svg>
                    </x-button>
                    <x-button type="submit" wire:click="save" wire:loading.attr="disabled"
                        class="inline-block " {{--  @click="step = 'complete'" --}} x-cloak x-show="step === 6">
                        {{ __('Save') }}
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                            color="currentColor" fill="none" class="w-6 h-6 inline-block">
                            <path
                                d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z" />
                            <path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7" />
                            <path d="M7 3v4a1 1 0 0 0 1 1h7" />
                        </svg>
                    </x-button>
                </div>
            </div>
        </div>
    </div>
</div>

<x-jet-dialog-modal wire:model="open" maxWidth="4xl">
    <x-slot name="title">{{ __('Nueva sucursal') }}</x-slot>

    <x-slot name="content">
        <form wire:submit.prevent="addsucursal" class="flex flex-col gap-2">
            <div class="w-full">
                <x-label value="Nombre :" />
                <x-input class="block w-full" wire:model.defer="namesucursal" tabindex="1" />
                <x-jet-input-error for="namesucursal" />
            </div>

            <div class="w-full">
                <x-label value="Dirección :" />
                <x-input class="block w-full" wire:model.defer="direccionsucursal" tabindex="2" />
                <x-jet-input-error for="direccionsucursal" />
            </div>

            <div class="w-full grid grid-cols-1 sm:grid-cols-3 gap-2">
                <div class="w-full sm:col-span-2">
                    <x-label value="Ubigeo :" />
                    <div class="relative" x-init="selectUbigeoSucursal" id="parentubigeosucursal_id" wire:ignore>
                        <x-select class="block w-full" id="ubigeosucursal_id" data-dropdown-parent="null"
                            data-minimum-results-for-search="3" x-ref="selectub" tabindex="3">
                            <x-slot name="options">
                                @if (count($ubigeos) > 0)
                                    @foreach ($ubigeos as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->region }} / {{ $item->provincia }}
                                            / {{ $item->distrito }} / {{ $item->ubigeo_reniec }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="ubigeosucursal_id" />
                </div>
                <div class="w-full">
                    <x-label value="Código anexo :" />
                    <x-input class="block w-full input-number-none" type="number" wire:model.defer="codeanexo"
                        onkeypress="return validarNumero(event, 4)" tabindex="4" />
                    <x-jet-input-error for="codeanexo" />
                </div>
            </div>

            <x-simple-card class="w-full p-2">
                <h1 class="text-[10px] font-semibold text-colorlabel pb-2">
                    AGREGAR COMPROBANTES DE VENTA</h1>
                <div class="w-full grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="flex flex-col gap-2">
                        <div class="w-full">
                            <x-label value="Tipo comprobante :" />
                            <div id="parenttypecomprobante_id" class="relative" x-init="select2Comprobante">
                                <x-select class="block w-full" id="typecomprobante_id" x-ref="comprobantesuc"
                                    tabindex="5">
                                    <x-slot name="options">
                                        @if (count($typecomprobantes) > 0)
                                            @foreach ($typecomprobantes as $item)
                                                <option value="{{ $item->id }}"
                                                    data-code="{{ $item->code }}"
                                                    data-referencia="{{ $item->referencia }}"
                                                    data-sendsunat="{{ $item->isSunat() }}">
                                                    {{ $item->descripcion }}</option>
                                            @endforeach
                                        @endif
                                    </x-slot>
                                </x-select>
                                <x-icon-select />
                            </div>
                            <x-jet-input-error for="typecomprobante_id" />
                        </div>
                        <div class="w-full">
                            <x-label value="Serie :" />
                            <div class="w-full relative">
                                <x-input wire:keydown.enter.prevent="addseriecomprobante" class="block w-full !pl-6"
                                    x-model="serie" maxlength="4" tabindex="6" />
                                <span x-text="indicio"
                                    class="absolute h-full pr-0.5 w-4 text-end text-sm flex items-center justify-end top-0 left-2.5 bottom-0 text-colorsubtitleform"></span>
                            </div>
                            <x-jet-input-error for="serie" />
                            <x-jet-input-error for="seriecompleta" />
                        </div>
                        <div class="w-full">
                            <x-label value="Contador :" />
                            <x-input wire:keydown.enter.prevent="addseriecomprobante"
                                class="block w-full input-number-none" wire:model.defer="contador" type="number"
                                min="0" tabindex="7" onkeypress="return validarNumero(event)" />
                            <x-jet-input-error for="contador" />
                            <x-jet-input-error for="seriecomprobantes" />
                        </div>
                        <div class="w-full flex justify-end">
                            <x-button wire:click="addseriecomprobante" wire:loading.attr="disabled"
                                tabindex="8">{{ __('Agregar') }}</x-button>
                        </div>
                    </div>
                    <div class="w-full sm:col-span-2 flex flex-wrap gap-2">
                        @foreach ($seriecomprobantes as $index => $value)
                            <x-minicard size="lg">
                                <small class="text-center leading-none text-[10px]">
                                    {{ $value['typecomprobante']['descripcion'] }}</small>
                                <h1 class="text-sm text-colorsubtitleform font-semibold py-1">
                                    {{ $value['serie'] }}</h1>
                                <p class="text-[10px] leading-none text-center text-colorsubtitleform font-medium">
                                    CONTADOR <br>
                                    <span
                                        class="text-lg leading-none">{{ decimalOrInteger($value['contador']) }}</span>
                                </p>

                                <x-slot name="buttons">
                                    @if ($value['default'] > 0)
                                        <x-icon-default class="mr-auto" />
                                    @endif

                                    <x-button-delete wire:key="removeseriemodal_{{ $index }}"
                                        wire:click="removeserie({{ $index }})"
                                        wire:loading.attr="disabled" />
                                </x-slot>
                            </x-minicard>
                        @endforeach
                    </div>
                </div>
            </x-simple-card>

            <x-simple-card class="w-full p-2">
                <h1 class="text-[10px] font-semibold text-colorlabel pb-2">
                    AGREGAR CAJAS DE PAGO</h1>
                <div class="w-full grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="w-full flex flex-col gap-2">
                        <div class="w-full">
                            <x-label value="Nombre caja :" />
                            <x-input wire:keydown.enter.prevent="addbox" class="block w-full"
                                wire:model.defer="boxname" placeholder="Nombre de caja..." tabindex="99" />
                            <x-jet-input-error for="boxname" />
                        </div>
                        <div class="w-full">
                            <x-label value="Monto predeterminado apertura :" />
                            <x-input wire:keydown.enter.prevent="addbox" class="block w-full input-number-none"
                                wire:model.defer="apertura" type="number"
                                onkeypress="return validarDecimal(event, 8)" tabindex="100" />
                            <x-jet-input-error for="apertura" />
                            <x-jet-input-error for="boxes" />
                        </div>
                        <div class="w-full flex justify-end">
                            <x-button type="button" wire:click="addbox" wire:loading.attr="disabled"
                                tabindex="9">
                                {{ __('Agregar') }}</x-button>
                        </div>
                    </div>
                    <div class="w-full sm:col-span-2 flex flex-wrap gap-2">
                        @foreach ($boxes as $index => $value)
                            <x-minicard size="md" class="text-xs">
                                {{ $value['name'] }}

                                <h1 class="text-lg text-colorsubtitleform font-semibold">
                                    <small class="leading-none text-xs font-medium">
                                        S/. </small>
                                    {{ number_format($value['apertura'], 2, '.', ', ') }}
                                </h1>

                                <x-slot name="buttons">
                                    <x-button-delete wire:key="removeboxmodal_{{ $value['id'] }}"
                                        wire:click="removebox('{{ $value['id'] }}')"
                                        wire:loading.attr="disabled" />
                                </x-slot>
                            </x-minicard>
                        @endforeach
                    </div>
                </div>
            </x-simple-card>

            <div class="w-full flex gap-2 pt-4 justify-end">
                <x-button class="p-3" type="submit" wire:loading.attr="disabled">
                    {{ __('Save') }}</x-button>
                <x-button class="p-3" wire:click="addsucursal(true)" wire:loading.attr="disabled">
                    {{ __('Save and close') }}</x-button>
            </div>
        </form>
    </x-slot>
</x-jet-dialog-modal>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('startsystem', () => ({
            typesucursal_id: @entangle('typesucursal_id').defer,
            ubigeosucursal_id: @entangle('ubigeosucursal_id').defer,
            viewpriceantes: @entangle('viewpriceantes').defer,
            viewlogomarca: @entangle('viewlogomarca').defer,
            viewespecificaciones: @entangle('viewespecificaciones').defer,
            ubigeo_id: @entangle('ubigeo_id').defer,
            uselistprice: @entangle('uselistprice').defer,
            usemarkagua: @entangle('usemarkagua').defer,
            viewtextopromocion: @entangle('viewtextopromocion').defer,
            usepricedolar: @entangle('usepricedolar').defer,
            viewpricedolar: @entangle('viewpricedolar').defer,
            generatesku: @entangle('generatesku').defer,
            tipocambio: @entangle('tipocambio').defer,
            sendmode: @entangle('sendmode').defer,
            cambioauto: @entangle('tipocambioauto').defer,
            loadingdolar: false,
            openpricedolar: false,
            openvalidatemail: false,
            step: @entangle('step').defer,
            document: '',
            sendmode: @entangle('sendmode').defer,
            afectacionigv: @entangle('afectacionigv').defer,
            alignmark: @entangle('alignmark').defer,
            image: null,
            icono: @entangle('icono').defer,
            openmark: false,
            markagua: null,
            usuariosol: @entangle('usuariosol').defer,
            clavesol: @entangle('clavesol').defer,
            clientid: @entangle('clientid').defer,
            clientsecret: @entangle('clientsecret').defer,
            passwordcert: @entangle('passwordcert').defer,
            typecomprobante_id: @entangle('typecomprobante_id').defer,
            serie: @entangle('serie').defer,
            indicio: @entangle('indicio').defer,
            viewalmacens: @entangle('viewalmacens').defer,
            viewalmacensdetalle: @entangle('viewalmacensdetalle').defer,

            init() {

                this.$watch("alignmark", (value) => {
                    this.selectPO.val(value).trigger("change");
                });
                this.$watch("ubigeo_id", (value) => {
                    this.selectU.val(value).trigger("change");
                });
                this.$watch("typecomprobante_id", (value) => {
                    this.selectTCP.val(value).trigger("change");
                });
                this.$watch("viewtextopromocion", (value) => {
                    this.selectTP.val(value).trigger("change");
                });
                this.$watch("typesucursal_id", (value) => {
                    this.selectTS.val(value).trigger("change");
                });
                this.$watch("ubigeosucursal_id", (value) => {
                    this.selectUB.val(value).trigger("change");
                });
                this.$watch("afectacionigv", (value) => {
                    this.selectAfe.val(value).trigger("change");
                });
                this.$watch("uselistprice", (value) => {
                    this.selectMP.val(value).trigger("change");
                });

                this.$watch("sendmode", (value) => {
                    this.selectMode.val(value).trigger("change");
                    if (value > 0) {
                        @this.clearCert();
                        this.usuariosol = '';
                        this.clavesol = '';
                        this.clientid = '';
                        this.clientsecret = '';
                        this.passwordcert = '';
                    } else {
                        this.usuariosol = '{{ \App\Models\Empresa::USER_SOL_PRUEBA }}';
                        this.clavesol = '{{ \App\Models\Empresa::PASSWORD_SOL_PRUEBA }}';
                        this.clientid = '{{ \App\Models\Empresa::CLIENT_ID_GRE_PRUEBA }}';
                        this.clientsecret =
                            '{{ \App\Models\Empresa::CLIENT_SECRET_GRE_PRUEBA }}';
                        this.passwordcert =
                            '{{ \App\Models\Empresa::PASSWORD_CERT_PRUEBA }}';
                    }
                });

                Livewire.hook('message.processed', () => {
                    this.selectMP.select2({
                        templateResult: formatOption
                    }).val(this.uselistprice).trigger('change');
                    this.selectTP.select2().val(this.viewtextopromocion).trigger('change');
                    this.selectTCP.select2().val(this.typecomprobante_id).trigger('change');
                    this.selectU.select2().val(this.ubigeo_id).trigger('change');
                    this.selectPO.select2().val(this.alignmark).trigger('change');
                });
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
            },
            async getRUC() {
                try {
                    const response = await fetch('api/consulta-ruc/' + this.document);
                    const data = await response.json();
                    this.ubigeo_id = data.result.ubigeo_id;

                } catch (error) {
                    console.error('Error al obtener el ruc', error);
                    this.tipocambio = null;
                    this.loadingdolar = false;
                    this.openpricedolar = true;
                }
            },
            loadlogo() {
                let file = document.getElementById('fileInput').files[0];
                var reader = new FileReader();
                reader.onload = (e) => {
                    this.image = e.target.result;
                    this.$wire.image = reader.result;
                };
                reader.readAsDataURL(file);

                if (file) {
                    let imageName = file.name;
                    let imageExtension = file.name.split('.').pop();
                    this.$wire.extencionimage = imageExtension;
                }
            },
            resetlogo() {
                this.image = null;
                this.$wire.image = null;
                this.$wire.extencionimage = null;
            },
            loadicono() {
                let file = document.getElementById('fileInputIcono').files[0];
                var reader = new FileReader();
                reader.onload = (e) => {
                    this.icono = e.target.result;
                    this.$wire.icono = reader.result;
                };
                reader.readAsDataURL(file);

                if (file) {
                    let imageName = file.name;
                    let imageExtension = file.name.split('.').pop();
                    this.$wire.extencionicono = imageExtension;
                }
            },
            reseticono() {
                this.icono = null;
                this.$wire.icono = null;
                this.$wire.extencionicono = null;
            },
            loadmarkagua() {
                let file = document.getElementById('fileMark').files[0];
                var reader = new FileReader();
                reader.onload = (e) => {
                    this.markagua = e.target.result;
                    this.$wire.markagua = reader.result;
                };
                reader.readAsDataURL(file);

                if (file) {
                    let imageExtension = file.name.split('.').pop();
                    this.$wire.extencionmarkagua = imageExtension;
                }
            },
            resetmarkagua() {
                this.markagua = null;
                this.$wire.markagua = null;
                this.$wire.extencionmarkagua = null;
            }
            // ,
            // validatestep() {
            //     @this.validatestep(this.step);
            // }
        }))
    })

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
    }

    function selectUbigeo() {
        this.selectU = $(this.$refs.selectubigeo).select2();
        this.selectU.val(this.ubigeo_id).trigger("change");
        this.selectU.on("select2:select", (event) => {
            this.ubigeo_id = event.target.value;
        }).on('select2:open', function(e) {
            const evt = "scroll.select2";
            $(e.target).parents().off(evt);
            $(window).off(evt);
        });
    }

    function SelectMode() {
        this.selectMode = $(this.$refs.selectmode).select2();
        this.selectMode.val(this.sendmode).trigger("change");
        this.selectMode.on("select2:select", (event) => {
            this.sendmode = event.target.value;
        }).on('select2:open', function(e) {
            const evt = "scroll.select2";
            $(e.target).parents().off(evt);
            $(window).off(evt);
        });
    }

    function SelectAfectacionIGV() {
        this.selectAfe = $(this.$refs.selectafectacionigv).select2();
        this.selectAfe.val(this.afectacionigv).trigger("change");
        this.selectAfe.on("select2:select", (event) => {
            this.afectacionigv = event.target.value;
        }).on('select2:open', function(e) {
            const evt = "scroll.select2";
            $(e.target).parents().off(evt);
            $(window).off(evt);
        });
    }

    function selectUbigeoSucursal() {
        this.selectUB = $(this.$refs.selectub).select2();
        this.selectUB.val(this.ubigeosucursal_id).trigger("change");
        this.selectUB.on("select2:select", (event) => {
            this.ubigeosucursal_id = event.target.value;
        }).on('select2:open', function(e) {
            const evt = "scroll.select2";
            $(e.target).parents().off(evt);
            $(window).off(evt);
        });
    }

    function typeSucursal() {
        this.selectTS = $(this.$refs.selecttypesucursal).select2();
        this.selectTS.val(this.typesucursal_id).trigger("change");
        this.selectTS.on("select2:select", (event) => {
            this.typesucursal_id = event.target.value;
        }).on('select2:open', function(e) {
            const evt = "scroll.select2";
            $(e.target).parents().off(evt);
            $(window).off(evt);
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
    }

    function select2Comprobante() {
        this.selectTCP = $(this.$refs.comprobantesuc).select2();
        this.selectTCP.val(this.typecomprobante_id).trigger("change");
        this.selectTCP.on("select2:select", (e) => {
            this.typecomprobante_id = e.target.value;
            const paramsData = $(e.target).select2('data')[0];
            if (paramsData) {
                const atributtes = paramsData.element.dataset;
                let code = atributtes.code;
                let referencia = atributtes.referencia;
                let sendsunat = atributtes.sendsunat;
                switch (code) {
                    case '01':
                        this.serie = '001';
                        this.indicio = 'F';
                        break;
                    case '03':
                        this.serie = '001';
                        this.indicio = 'B';
                        break;
                    case '07':
                        this.serie = referencia == '01' ? '01' : '01';
                        this.indicio = referencia == '01' ? 'FC' : 'BC';
                        break;
                    case '09':
                        this.serie = sendsunat > 0 ? '001' : '001';
                        this.indicio = sendsunat > 0 ? 'T' : 'E';
                        break;
                    case 'VT':
                        this.serie = '01';
                        this.indicio = 'TK';
                        break;
                    default:
                        this.serie = null;
                        this.indicio = null;
                        break;
                }
            }
        }).on('select2:open', function(e) {
            const evt = "scroll.select2";
            $(e.target).parents().off(evt);
            $(window).off(evt);
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
