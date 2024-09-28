<div class="w-full h-[calc(100vh-145px)] overflow-hidden m-auto" x-data="{ typesucursal_id: @entangle('typesucursal_id').defer, ubigeosucursal_id: @entangle('ubigeosucursal_id').defer }" id="form-configuracion">

    <style>
        [x-cloak] {
            display: none;
        }

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

    <div x-cloak class="grid w-full h-full overflow-y-auto lg:py-10" x-data="app()">
        <div
            class="w-full border border-borderminicard lg:my-auto md:max-w-2xl mx-auto p-2 lg:p-4 shadow-md shadow-shadowminicard rounded-xl">
            <div x-cloak x-show="step === 'complete'">
                <div class="w-full bg-white rounded-lg p-10 flex items-center shadow justify-between">
                    <div class="w-full">
                        <svg class="mb-4 h-20 w-20 text-green-500 mx-auto" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>

                        <h2 class="text-2xl mb-4 text-gray-800 text-center font-bold">Registration Success</h2>

                        <div class="text-gray-600 mb-8">
                            Thank you. We have sent you an email to demo@demo.test. Please click the link in the message
                            to activate your account.
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

                <!-- Step Content -->
                <div class="py-10 w-full">
                    <div style="display:none;" x-show="step === 1" x-cloak class="animate__animated animate__bounceIn">
                        <div class="bg-body grid grid-cols-1 gap-2">
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
                                                <option value="{{ $item->id }}">{{ $item->region }} /
                                                    {{ $item->provincia }} /
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

                    <div style="display:none;" x-show="step === 2" x-cloak
                        class="animate__animated animate__bounceIn">
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
                                            </x-slot>
                                        </x-select>
                                        <x-icon-select />
                                    </div>
                                    <x-jet-input-error for="viewtextopromocion" />
                                </div>

                                <div class="block">
                                    <x-label-check for="viewpriceantes">
                                        <x-input x-model="viewpriceantes" name="viewpriceantes" type="checkbox"
                                            id="viewpriceantes" />
                                        MOSTRAR PRECIO ANTERIOR EN PRODUCTOS OFERTADOS
                                    </x-label-check>
                                    <x-jet-input-error for="viewpriceantes" />
                                </div>

                                {{-- <div class="block">
                                    <x-label-check for="viewespecificaciones">
                                        <x-input x-model="viewespecificaciones" name="viewespecificaciones"
                                            type="checkbox" id="viewespecificaciones" />
                                        MOSTRAR ESPECFICACIONES DEL PRODUCTO
                                    </x-label-check>
                                    <x-jet-input-error for="viewespecificaciones" />
                                </div> --}}

                                <div class="block">
                                    <x-label-check for="viewlogomarca">
                                        <x-input x-model="viewlogomarca" name="viewlogomarca" type="checkbox"
                                            id="viewlogomarca" />
                                        MOSTRAR LOGO MARCA EN PRODUCTOS
                                    </x-label-check>
                                    <x-jet-input-error for="viewlogomarca" />
                                </div>

                                <div class="block">
                                    <x-label-check for="usepricedolar">
                                        <x-input x-model="usepricedolar" id="usepricedolar" type="checkbox"
                                            @change="changePricedolar" />USAR PRECIO EN DÓLARES</x-label-check>
                                    <x-jet-input-error for="usepricedolar" />

                                    <div x-show="!cambioauto">
                                        <div x-show="openpricedolar" style="display: none;">
                                            <x-input class="w-full" type="number" x-model="tipocambio"
                                                placeholder="0.00" min="0" step="0.0001" />
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
                                            <small class="font-medium text-xs tracking-normal">
                                                El tipo de cambio se actualizará todos los dias a las 12:00 am
                                                hrs</small>
                                        </div>
                                    </x-label-check>
                                    <x-jet-input-error for="tipocambioauto" />
                                </div>
                            </div>
                        @endif

                        <div class="w-full mt-2" x-data="loadimage()">
                            <x-label-check for="usemarca_agua">
                                <x-input x-model="usemarkagua" type="checkbox" x-on:change="openmark = !openmark"
                                    id="usemarca_agua" />
                                <div class="flex flex-col flex-1">
                                    AGREGAR MARCA DE AGUA EN IMAGENES DE PRODUCTOS
                                    <small class="font-medium text-xs tracking-normal">
                                        Se recomienda usar imagen transparente PNG</small>
                                </div>
                            </x-label-check>
                            <x-jet-input-error for="usemarkagua" />

                            <div class="grid grid-cols-1 xs:grid-cols-2 gap-2 items-start mt-2" style="display: none;"
                                x-show="openmark" x-cloak>
                                <div class="w-full">
                                    <x-simple-card class="w-full h-40 md:max-w-xs mb-1 !shadow-none">
                                        <template x-if="markagua">
                                            <img id="markagua" class="object-scale-down block w-full h-full"
                                                :src="markagua" />
                                        </template>
                                        <template x-if="!markagua">
                                            <x-icon-file-upload class="w-full h-full !my-0" />
                                        </template>
                                    </x-simple-card>
                                    <x-jet-input-error for="markagua" />

                                    <div class="w-full flex gap-2 flex-wrap justify-center">
                                        <template x-if="markagua">
                                            <x-button class="inline-flex " @click="reset"
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
                                            wire:loading.class="disabled:opacity-25" class="">
                                            <input type="file" class="hidden" wire:model="markagua"
                                                id="fileMark" accept="image/png" @change="loadlogo" />
                                        </x-input-file>
                                    </div>
                                </div>

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

                        <div class="w-full grid sm:grid-cols-2 gap-3 my-5">
                            <div class="w-full text-center">
                                <div
                                    class="w-full h-60 relative mb-2 shadow-md shadow-shadowminicard rounded-xl overflow-hidden">
                                    <template x-if="image">
                                        <img id="image" class="object-scale-down block w-full h-full"
                                            :src="image" />
                                    </template>
                                    <template x-if="!image">
                                        <x-icon-file-upload class="w-full h-full !my-0" />
                                    </template>
                                </div>
                                <label for="fileInput" type="button"
                                    class="cursor-pointer text-[10px] inine-flex justify-between items-center focus:outline-none hover:ring-2 hover:ring-ringbutton py-2 px-4 rounded-lg shadow-sm text-left text-colorbutton bg-fondobutton hover:bg-fondohoverbutton hover:text-colorhoverbutton font-semibold tracking-widest">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="inline-flex flex-shrink-0 w-6 h-6 -mt-1 mr-1" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="0" y="0" stroke="none"></rect>
                                        <path
                                            d="M5 7h1a2 2 0 0 0 2 -2a1 1 0 0 1 1 -1h6a1 1 0 0 1 1 1a2 2 0 0 0 2 2h1a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-9a2 2 0 0 1 2 -2" />
                                        <circle cx="12" cy="13" r="3" />
                                    </svg>
                                    SELECCIONAR LOGO
                                </label>

                                <input name="photo" id="fileInput" accept="image/*" class="hidden" type="file"
                                    @change="loadlogo">
                            </div>
                            <div class="w-full text-center">
                                <div
                                    class="w-full h-60 relative mb-2 shadow-md shadow-shadowminicard rounded-xl overflow-hidden">
                                    <template x-if="icono">
                                        <img id="icono" class="object-scale-down block w-full h-full"
                                            :src="icono" />
                                    </template>
                                    <template x-if="!icono">
                                        <x-icon-file-upload class="w-full h-full !my-0" />
                                    </template>
                                </div>

                                <label for="fileInputIcono" type="button"
                                    class="cursor-pointer text-[10px] inine-flex justify-between items-center focus:outline-none hover:ring-2 hover:ring-ringbutton py-2 px-4 rounded-lg shadow-sm text-left text-colorbutton bg-fondobutton hover:bg-fondohoverbutton hover:text-colorhoverbutton font-semibold tracking-widest">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="inline-flex flex-shrink-0 w-6 h-6 -mt-1 mr-1" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="0" y="0" stroke="none"></rect>
                                        <path
                                            d="M5 7h1a2 2 0 0 0 2 -2a1 1 0 0 1 1 -1h6a1 1 0 0 1 1 1a2 2 0 0 0 2 2h1a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-9a2 2 0 0 1 2 -2" />
                                        <circle cx="12" cy="13" r="3" />
                                    </svg>
                                    SELECCIONAR ICONO WEB
                                </label>

                                <input name="icono" id="fileInputIcono" accept=".ico" class="hidden"
                                    type="file" @change="loadicono">
                            </div>
                        </div>
                    </div>

                    <div style="display:none;" x-show="step === 3" x-cloak
                        class="animate__animated animate__bounceIn">
                        <div class="w-full bg-body flex flex-col gap-10">

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

                            @if (module::isEnabled('Ventas') || module::isEnabled('Almacen'))
                                <form wire:submit.prevent="addalmacen" class="w-full grid gap-2">
                                    <div class="w-full">
                                        <x-label value="Descripcion almacén :" />
                                        <x-input class="block w-full" wire:model.defer="namealmacen" />
                                        <x-jet-input-error for="namealmacen" />
                                    </div>
                                    <div class="flex justify-end">
                                        <x-button class=" p-3" type="submit" wire:loading.attr="disabled">
                                            AGREGAR ALMACÉN</x-button>
                                    </div>
                                    <div>
                                        <x-jet-input-error for="almacens" />
                                        @if (count($almacens) > 0)
                                            <div class="w-full flex flex-wrap gap-2">
                                                @foreach ($almacens as $index => $item)
                                                    <x-simple-card
                                                        class="w-28 h-28 cursor-pointer flex flex-col justify-center items-center gap-1 p-1 rounded-xl">
                                                        <h1
                                                            class="text-xs text-colorlabel text-center leading-3 uppercase">
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

                            <form wire:submit.prevent="addphone" class="grid grid-cols-1 gap-2">
                                <div class="w-full">
                                    <x-label value="Teléfono /Celular :" />
                                    <x-input class="block w-full input-number-none" wire:model.defer="telefono"
                                        type="number" onkeypress="return validarNumero(event, 9)" />
                                    <x-jet-input-error for="telefono" />
                                </div>

                                <div class="flex justify-end">
                                    <x-button class=" p-3" type="submit" wire:loading.attr="disabled">
                                        AGREGAR TELÉFONO</x-button>
                                </div>
                                <x-jet-input-error for="telephones" />

                                @if (count($telephones) > 0)
                                    <div class="w-full flex flex-wrap gap-2">
                                        @foreach ($telephones as $index => $item)
                                            <x-simple-card
                                                class="w-24 h-24 cursor-pointer flex flex-col justify-center items-center gap-1 p-1 rounded-xl">
                                                <h1 class="text-xs text-colorlabel">{{ formatTelefono($item) }}</h1>
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

                    <div style="display:none;" x-show="step === 4" x-cloak
                        class="animate__animated animate__bounceIn">
                        <div class="w-full flex justify-end">
                            <x-button class=" p-3" wire:click="$toggle('open')">
                                {{ __('AGREGAR NUEVA SUCURSAL') }}</x-button>
                        </div>

                        @if (count($sucursals) > 0)
                            <p class="text-colorsubtitleform text-xs">SELECCIONAR SUCURSALES PARA CONTINUAR...</p>
                            <x-jet-input-error for="selectedsucursals" />
                            <div class="w-full flex flex-col gap-2 mt-3">
                                @foreach ($sucursals as $index => $item)
                                    <x-simple-card
                                        class="w-full cursor-default text-xs flex flex-col gap-2 p-3 rounded-xl {{ $item['default'] > 0 ? 'bg-fondominicard !border-next-600' : '' }}">
                                        @if ($item['default'] > 0)
                                            <h1 class="text-xs text-colortitleform align-middle">
                                                <x-icon-default class="inline-block m-auto align-middle" />
                                                TIENDA PRINCIPAL
                                            </h1>
                                        @else
                                            <div class="inline-block py-1">
                                                <x-input class="cursor-pointer" type="checkbox"
                                                    name="selectedsucursals[]" wire:model.defer="selectedsucursals"
                                                    id="{{ $item['codigo'] }}" value="{{ $item['codigo'] }}" />
                                                <x-label
                                                    class="inline-block pl-2 cursor-pointer font-semibold text-colortitleform"
                                                    value="SELECCIONAR" for="{{ $item['codigo'] }}" />
                                            </div>
                                            <div>
                                                <x-label value="DESCRIPCIÓN:" class="font-semibold" />
                                                <p class="text-colorsubtitleform leading-3 uppercase">
                                                    {{ $item['descripcion'] }}</p>
                                            </div>
                                        @endif

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
                                            <div>
                                                <x-label value="TIPO ESTABLECIMIENTO :" class="font-semibold" />
                                                <p class="text-colorsubtitleform leading-3">
                                                    [{{ $item['cod_tipo'] }}] - {{ $item['tipo'] }}</p>
                                            </div>
                                        @endif

                                        <div class="w-full flex items-end gap-2 justify-end">
                                            {{-- @if ($item['default'])
                                                <x-icon-default wire:loading.attr="disabled"
                                                    class="cursor-pointer hover:!text-next-500" />
                                            @else
                                                <x-icon-default wire:loading.attr="disabled"
                                                    class="!text-gray-400 inline-block cursor-pointer hover:!text-next-500" />
                                            @endif --}}
                                            @if ($item['default'] <= 0)
                                                <x-button-delete wire:click="removesucursal({{ $index }})"
                                                    wire:loading.attr="disabled" />
                                            @endif
                                        </div>
                                    </x-simple-card>
                                @endforeach
                            </div>
                        @else
                            <p class="text-colorsubtitleform text-xs">No existen sucursales agregados...</p>
                        @endif
                    </div>

                    <div style="display:none;" x-show="step === 5" x-cloak
                        class="animate__animated animate__bounceIn">
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
                                            <x-disabled-text x-text="clientsecret" text=""
                                                class="input-text" />
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
                                        <x-icon-file-upload type="filesuccess" :uploadname="$cert->getClientOriginalName()"
                                            class="w-40 h-full text-gray-300" />
                                    @else
                                        <x-icon-file-upload type="code" text="PFX"
                                            class="w-40 h-full text-gray-300" />
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
                                                    <line x1="10" x2="10" y1="11"
                                                        y2="17" />
                                                    <line x1="14" x2="14" y1="11"
                                                        y2="17" />
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

                    <div style="display:none;" x-show="step === 6" x-cloak
                        class="animate__animated animate__bounceIn">
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
                                        <b>WHATSAPP: </b>{{ $empresa['whatsapp'] }}</p>
                                @endif
                                @if (!empty($empresa['facebook']))
                                    <p class="text-colorsubtitleform text-xs text-center">
                                        <b>FACEBOOK: </b>{{ $empresa['facebook'] }}</p>
                                @endif
                                @if (!empty($empresa['instagram']))
                                    <p class="text-colorsubtitleform text-xs text-center">
                                        <b>INSTAGRAM: </b>{{ $empresa['instagram'] }}</p>
                                @endif
                                @if (!empty($empresa['tiktok']))
                                    <p class="text-colorsubtitleform text-xs text-center">
                                        <b>TIK TOK: </b>{{ $empresa['tiktok'] }}</p>
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
                                    USAR LISTA DE PRECIOS
                                </p>
                                <p class="text-xs w-full text-green-600">
                                    @if ($empresa['viewtextopromocion'] >= 0)
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            color="currentColor" fill="none" stroke="currentColor"
                                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
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
                                    class="text-xs w-full {{ $empresa['viewpriceantes'] == '1' ? 'text-green-600' : 'text-colorerror' }}">
                                    @if ($empresa['viewpriceantes'])
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
                                    MOSTRAR PRECIO ANTERIOR EN PRODUCTOS OFERTADOS
                                </p>
                                <p
                                    class="text-xs w-full {{ $empresa['viewlogomarca'] == '1' ? 'text-green-600' : 'text-colorerror' }}">
                                    @if ($empresa['viewlogomarca'])
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
                                    MOSTRAR LOGO MARCA EN PRODUCTOS
                                </p>
                                <p
                                    class="text-xs w-full {{ $empresa['usepricedolar'] == '1' ? 'text-green-600' : 'text-colorerror' }}">
                                    @if ($empresa['usepricedolar'])
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
                                    USAR PRECIO EN DOLARES
                                    @if ($empresa['tipocambio'])
                                        [{{ $empresa['tipocambio'] }}]
                                    @endif
                                </p>
                                <p
                                    class="text-xs w-full {{ $empresa['viewpricedolar'] == '1' ? 'text-green-600' : 'text-colorerror' }}">
                                    @if ($empresa['viewpricedolar'])
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
                                    MOSTRAR PRECIO EN DOLARES
                                </p>
                                <p
                                    class="text-xs w-full {{ $empresa['tipocambioauto'] == '1' ? 'text-green-600' : 'text-colorerror' }}">
                                    @if ($empresa['tipocambioauto'])
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
                                $filteredSucursals = array_filter($sucursals, function ($item) use (
                                    $selectedsucursals,
                                ) {
                                    return in_array($item['codigo'], $selectedsucursals);
                                });
                            @endphp

                            @if (count($filteredSucursals) > 0)
                                <div class="w-full flex flex-col gap-2 mt-3">
                                    @foreach ($filteredSucursals as $index => $item)
                                        <x-simple-card
                                            class="w-full cursor-default text-xs flex flex-col gap-2 p-3 rounded-xl {{ $item['default'] > 0 ? 'bg-fondominicard !border-next-600' : '' }}">
                                            @if ($item['default'] > 0)
                                                <h1 class="text-xs text-colortitleform align-middle">
                                                    <x-icon-default class="inline-block m-auto align-middle" />
                                                    TIENDA PRINCIPAL
                                                </h1>
                                            @else
                                                <div>
                                                    <x-label value="DESCRIPCIÓN:" class="font-semibold" />
                                                    <p class="text-colorsubtitleform uppercase leading-3">
                                                        {{ $item['descripcion'] }}</p>
                                                </div>
                                            @endif

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
                                                <div>
                                                    <x-label value="TIPO ESTABLECIMIENTO :" class="font-semibold" />
                                                    <p class="text-colorsubtitleform leading-3">
                                                        [{{ $item['cod_tipo'] }}] - {{ $item['tipo'] }}</p>
                                                </div>
                                            @endif
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
                                                class="w-full h-full text-gray-300" />
                                        </div>
                                    @endif
                                </x-simple-card>
                            @endif

                            @if ($empresa['usemarkagua'])
                                <x-simple-card class="p-3 rounded-xl mt-3">
                                    <p class="text-xs w-full text-green-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            color="currentColor" fill="none" stroke="currentColor"
                                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
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

            <div class="fixed bottom-0 left-0 right-0 py-2 md:py-3 px-5 md:px-0 bg-fondominicard border-t border-borderminicard"
                x-cloak>
                <div class="max-w-3xl mx-auto">
                    <div class="flex gap-2 justify-end lg:justify-between">
                        <div class="lg:w-1/2">
                            <x-button wire:loading.attr="disabled" class="inline-flex " x-show="step > 1" x-cloak
                                @click="step--">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                    color="currentColor" fill="none" class="w-6 h-6 inline-block">
                                    <path d="M4 12L20 12" />
                                    <path d="M8.99996 17C8.99996 17 4.00001 13.3176 4 12C3.99999 10.6824 9 7 9 7" />
                                </svg>
                                {{ __('Previus') }}
                            </x-button>
                        </div>

                        <div class="lg:w-1/2 text-right">
                            <x-button class="inline-flex " wire:loading.attr="disabled" x-show="step < 6"
                                @click="$wire.validatestep(step)">
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
    </div>

    <x-jet-dialog-modal wire:model="open">
        <x-slot name="title">
            {{ __('Nueva sucursal') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="addsucursal" class="flex flex-col gap-2">
                <div class="w-full">
                    <x-label value="Nombre :" />
                    <x-input class="block w-full" wire:model.defer="namesucursal"
                        placeholder="Nombre de sucursal..." />
                    <x-jet-input-error for="namesucursal" />
                </div>

                <div class="w-full">
                    <x-label value="Dirección :" />
                    <x-input class="block w-full" wire:model.defer="direccionsucursal"
                        placeholder="Dirección de sucursal..." />
                    <x-jet-input-error for="direccionsucursal" />
                </div>

                <div class="w-full">
                    <x-label value="Ubigeo :" />
                    <div class="relative" x-init="selectUbigeoSucursal" id="parentubigeosucursal_id" wire:ignore>
                        <x-select class="block w-full" id="ubigeosucursal_id" data-dropdown-parent="null"
                            data-minimum-results-for-search="3" x-ref="selectub">
                            <x-slot name="options">
                                @if (count($ubigeos) > 0)
                                    @foreach ($ubigeos as $item)
                                        <option value="{{ $item->id }}">{{ $item->region }} /
                                            {{ $item->provincia }}
                                            / {{ $item->distrito }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="ubigeosucursal_id" />
                </div>

                <div class="w-full">
                    <x-label value="Tipo establecimiento :" />
                    <div class="relative" x-init="typeSucursal" wire:ignore>
                        <x-select class="block w-full" x-ref="selecttypesucursal" id="typesucursal_id"
                            data-dropdown-parent="null">
                            <x-slot name="options">
                                @foreach ($typesucursals as $item)
                                    <option value="{{ $item->id }}">[{{ $item->code }}]
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="typesucursal_id" />
                </div>

                <div class="w-full">
                    <x-label value="Código anexo :" />
                    <x-input class="block w-full input-number-none" type="number" wire:model.defer="codeanexo"
                        placeholder="Anexo de sucursal..." onkeypress="return validarNumero(event, 4)" />
                    <x-jet-input-error for="codeanexo" />
                </div>

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
        function app() {
            return {
                viewpriceantes: @entangle('viewpriceantes').defer,
                viewlogomarca: @entangle('viewlogomarca').defer,
                viewespecificaciones: @entangle('viewespecificaciones').defer,
                ubigeo_id: @entangle('ubigeo_id').defer,
                uselistprice: @entangle('uselistprice').defer,
                viewtextopromocion: @entangle('viewtextopromocion').defer,
                usepricedolar: @entangle('usepricedolar').defer,
                viewpricedolar: @entangle('viewpricedolar').defer,
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
                    reader.onload = (e) => this.image = e.target.result;
                    reader.readAsDataURL(file);

                    if (file) {
                        let imageName = file.name;
                        let imageExtension = file.name.split('.').pop();
                        this.getBase64(file, (result) => {
                            @this.set('image', result);
                            @this.set('extencionimage', imageExtension);
                        });
                    }
                },
                loadicono() {
                    let file = document.getElementById('fileInputIcono').files[0];
                    var reader = new FileReader();
                    reader.onload = (e) => this.icono = e.target.result;
                    reader.readAsDataURL(file);

                    if (file) {
                        let imageName = file.name;
                        let imageExtension = file.name.split('.').pop();
                        this.getBase64(file, (result) => {
                            @this.set('icono', result);
                            @this.set('extencionicono', imageExtension);
                        });
                    }
                },
                getBase64(file, callback) {
                    const reader = new FileReader();
                    reader.readAsDataURL(file);
                    reader.onload = () => callback(reader.result);
                    reader.onerror = error => console.error('Error: ', error);
                }
                // ,
                // validatestep() {
                //     @this.validatestep(this.step);
                // }
            }
        }

        function loadimage() {
            return {
                usemarkagua: @entangle('usemarkagua').defer,
                loadlogo() {
                    let file = document.getElementById('fileMark').files[0];
                    var reader = new FileReader();
                    reader.onload = (e) => this.markagua = e.target.result;
                    reader.readAsDataURL(file);
                },
                reset() {
                    this.markagua = null;
                    @this.clearMark();
                }
            }
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
            this.$watch("ubigeo_id", (value) => {
                this.selectU.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectU.select2().val(this.ubigeo_id).trigger('change');
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
                    this.clientsecret = '{{ \App\Models\Empresa::CLIENT_SECRET_GRE_PRUEBA }}';
                    this.passwordcert = '{{ \App\Models\Empresa::PASSWORD_CERT_PRUEBA }}';
                }
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
            this.$watch("afectacionigv", (value) => {
                this.selectAfe.val(value).trigger("change");
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
            this.$watch("ubigeosucursal_id", (value) => {
                this.selectUB.val(value).trigger("change");
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
            this.$watch("typesucursal_id", (value) => {
                this.selectTS.val(value).trigger("change");
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
