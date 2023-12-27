<div>
    <form wire:submit.prevent="update" class="w-full flex flex-col gap-8">
        <x-form-card titulo="DATOS EMPRESA">
            <div class="bg-body p-3 w-full grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-2">
                <div class="w-full">
                    <x-label value="RUC :" />
                    <div class="w-full inline-flex gap-1">
                        <x-input class="block w-full numeric prevent" wire:keydown.enter="searchclient" type="number"
                            wire:model.defer="empresa.document" />
                        <x-button-add class="px-2" wire:click="searchclient" wire:loading.attr="disabled"
                            wire:target="searchclient">
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
                    <div id="parentubigeoempresa_id">
                        <x-select class="block w-full" wire:model.defer="empresa.ubigeo_id" id="ubigeoempresa_id"
                            data-minimum-results-for-search="3">
                            <x-slot name="options">
                                @foreach ($ubigeos as $item)
                                    <option value="{{ $item->id }}">{{ $item->region }} / {{ $item->provincia }} /
                                        {{ $item->distrito }}</option>
                                @endforeach
                            </x-slot>
                        </x-select>
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
                    <x-input class="block w-full" wire:model.defer="empresa.email" />
                    <x-jet-input-error for="empresa.correo" />
                </div>

                <div class="w-full">
                    <x-label value="Web :" />
                    <x-input class="block w-full" wire:model.defer="empresa.web" placeholder="www.misitioweb.com" />
                    <x-jet-input-error for="empresa.web" />
                </div>

                <div class="w-full">
                    <x-label value="Monto adelanto máximo :" />
                    <x-input class="block w-full" wire:model.defer="empresa.montoadelanto" type="number"
                        placeholder="0.00" />
                    <x-jet-input-error for="empresa.montoadelanto" />
                </div>
            </div>
        </x-form-card>

        <x-form-card titulo="TELÉFONOS" subtitulo="Agregue números de teléfono para contactarse.">
            <div class="w-full flex flex-col gap-3">
                @if (count($empresa->telephones))
                    <div class="w-full flex gap-2 flex-wrap">
                        @foreach ($empresa->telephones as $item)
                            <x-minicard title="" :content="$item->phone">
                                <x-slot name="title">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mx-auto" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path
                                            d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                                        <path d="M14.05 2a9 9 0 0 1 8 7.94" />
                                        <path d="M14.05 6A5 5 0 0 1 18 10" />
                                    </svg>
                                </x-slot>
                                <x-slot name="buttons">
                                    <x-button-edit wire:click="editphone({{ $item->id }})"
                                        wire:loading.attr="disabled" wire:target="editphone"></x-button-edit>
                                    <x-button-delete wire:click="deletephone({{ $item->id }})"
                                        wire:loading.attr="disabled" wire:target="deletephone"></x-button-delete>
                                </x-slot>
                            </x-minicard>
                        @endforeach
                    </div>
                @endif

                <div class="w-full mt-3 flex justify-end">
                    <x-button size="xs" class="" wire:click="openmodalphone" wire:loading.attr="disabled"
                        wire:target="openmodalphone">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M16.243 5.243h3m3 0h-3m0 0v-3m0 3v3M18.118 14.702L14 15.5c-2.782-1.396-4.5-3-5.5-5.5l.77-4.13L7.815 2H4.064c-1.128 0-2.016.932-1.847 2.047.42 2.783 1.66 7.83 5.283 11.453 3.805 3.805 9.286 5.456 12.302 6.113 1.165.253 2.198-.655 2.198-1.848v-3.584l-3.882-1.479z" />
                        </svg>
                    </x-button>
                </div>
            </div>
        </x-form-card>

        <div class="w-full flex flex-col xl:flex-row gap-8">
            <x-form-card titulo="ICONO & LOGO">
                <div class="w-full flex gap-3 flex-wrap">
                    <div class="relative w-full xs:w-48 text-center bg-body shadow-md shadow-shadowminicard rounded p-1"
                        x-data="{ isUploadingLogo: @entangle('isUploadingLogo'), progress: 0 }" x-on:livewire-upload-start="isUploadingLogo = true"
                        x-on:livewire-upload-finish="isUploadingLogo = false"
                        x-on:livewire-upload-error="$wire.emit('errorImage'), isUploadingLogo = false"
                        x-on:livewire-upload-progress="progress = $event.detail.progress">

                        <div x-show="isUploadingLogo" wire:loading wire:target="isUploadingPublicKey,deletelogo"
                            class="loading-overlay rounded">
                            <x-loading-next />
                        </div>

                        @if (isset($logo))
                            <div class="w-full h-32 md:max-w-md mx-auto mb-1 duration-300">
                                <img class="w-full h-full object-scale-down" src="{{ $logo->temporaryUrl() }}" />
                            </div>
                        @else
                            @if ($empresa->image)
                                <div class="w-full h-32 md:max-w-md mx-auto mb-1 duration-300">
                                    <img class="w-full h-full object-scale-down"
                                        src="{{ Storage::url('images/company/' . $empresa->image->url) }}" />
                                </div>
                            @else
                                <x-icon-file-upload class="w-24 h-24 text-gray-300" />
                            @endif
                        @endif

                        <div class="w-full flex gap-1 flex-wrap justify-center">
                            @if (!$empresa->image)
                                <x-input-file :for="$idlogo" titulo="SELECCIONAR LOGO" wire:loading.remove
                                    wire:target="logo">
                                    <input type="file" class="hidden" wire:model.defer="logo"
                                        id="{{ $idlogo }}" accept="image/jpg, image/jpeg, image/png" />

                                    @if (isset($logo))
                                        <x-slot name="clear">
                                            <x-button class="inline-flex px-6" wire:loading.attr="disabled"
                                                wire:target="clearLogo" wire:click="clearLogo">
                                                LIMPIAR
                                            </x-button>
                                        </x-slot>
                                    @endif
                                </x-input-file>
                            @endif

                            @if (!isset($logo) && $empresa->image)
                                <x-button wire:click="deletelogo({{ $empresa->image->id }})"
                                    wire:loading.attr="disabled" wire:target="deletelogo">
                                    ELIMINAR LOGO
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline-block"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 6h18" />
                                        <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                        <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                        <line x1="10" x2="10" y1="11" y2="17" />
                                        <line x1="14" x2="14" y1="11" y2="17" />
                                    </svg>
                                </x-button>
                            @endif
                        </div>
                        <x-jet-input-error wire:loading.remove wire:target="logo" for="logo"
                            class="text-center" />
                    </div>
                    <div class="relative w-full xs:w-48 text-center bg-body shadow-md shadow-shadowminicard rounded p-1"
                        x-data="{ isUploadingIcono: @entangle('isUploadingIcono'), progress: 0 }" x-on:livewire-upload-start="isUploadingIcono = true"
                        x-on:livewire-upload-finish="$wire.emit('iconload', {{ $idicono }}),isUploadingIcono = false"
                        x-on:livewire-upload-error="$wire.emit('erroricono'), isUploadingIcono = false"
                        x-on:livewire-upload-progress="progress = $event.detail.progress">

                        <div x-show="isUploadingIcono" wire:loading
                            wire:target="isUploadingIcono,iconload, deleteicono" class="loading-overlay rounded">
                            <x-loading-next />
                        </div>

                        @if (isset($icono))
                            <div wire:ignore id="uploadForm"
                                class="w-full h-32 md:max-w-md mx-auto mb-1 duration-300">
                                <img class="w-full h-full object-scale-down" />
                            </div>
                        @else
                            @if ($empresa->icono)
                                <div class="w-full h-32 md:max-w-md mx-auto mb-1 duration-300">
                                    <img class="w-full h-full object-scale-down"
                                        src="{{ Storage::url('images/company/' . $empresa->icono) }}" />
                                </div>
                            @else
                                <x-icon-file-upload class="w-24 h-24 text-gray-300" />
                            @endif
                        @endif

                        <div class="w-full flex gap-1 flex-wrap justify-center">
                            @if (!$empresa->icono)
                                <x-input-file for="{{ $idicono }}" titulo="SELECCIONAR ÍCONO"
                                    wire:loading.remove wire:target="icono">
                                    <input type="file" class="hidden" wire:model.defer="icono"
                                        id="{{ $idicono }}" accept=".ico" />

                                    @if (isset($icono))
                                        <x-slot name="clear">
                                            <x-button class="inline-flex px-6" wire:loading.attr="disabled"
                                                wire:target="clearIcono" wire:click="clearIcono">
                                                LIMPIAR
                                            </x-button>
                                        </x-slot>
                                    @endif
                                </x-input-file>
                            @endif

                            @if (!isset($icono) && $empresa->icono)
                                <x-button wire:click="deleteicono({{ $empresa->id }})" wire:loading.attr="disabled"
                                    wire:target="deleteicono">
                                    ELIMINAR ICONO
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline-block"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 6h18" />
                                        <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                        <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                        <line x1="10" x2="10" y1="11" y2="17" />
                                        <line x1="14" x2="14" y1="11" y2="17" />
                                    </svg>
                                </x-button>
                            @endif
                        </div>
                        <x-jet-input-error wire:loading.remove wire:target="icono" for="icono"
                            class="text-center" />
                    </div>
                </div>
            </x-form-card>

            <x-form-card titulo="OTRAS OPCIONES" subtitulo="Seleccionar las opciones según su preferencia de uso.">
                <div class="w-full items-start flex flex-col gap-1">

                    @if (Module::isEnabled('Ventas'))
                        <div class="block">
                            <x-label-check for="uselistprice">
                                <x-input wire:model.defer="empresa.uselistprice" name="uselistprice" value="1"
                                    type="checkbox" id="uselistprice" />
                                USAR LISTA DE PRECIOS
                            </x-label-check>
                            <x-jet-input-error for="empresa.uselistprice" />
                        </div>

                        <div class="block" x-data="{ openpricedolar: {{ $empresa->usepricedolar ? 'true' : 'false' }} }">
                            <x-label-check for="usepricedolar">
                                <x-input wire:model.defer="empresa.usepricedolar" id="usepricedolar" value="1"
                                    type="checkbox" x-on:change="openpricedolar = !openpricedolar"
                                    wire:change="$emit('searchpricedolar', $event.target)" />
                                USAR PRECIO EN DÓLARES
                            </x-label-check>
                            <x-jet-input-error for="empresa.usepricedolar" />
                            <div x-show="openpricedolar" wire:loading.remove wire:target="searchpricedolar">
                                <x-input class="block w-full" type="number" wire:model.defer="empresa.tipocambio"
                                    placeholder="0.00" min="0" step="0.0001" />
                            </div>
                            <x-jet-input-error for="empresa.tipocambio" />

                            <div wire:loading.block wire:target="searchpricedolar">
                                <x-loading-next />
                            </div>
                        </div>

                        <div class="block">
                            <x-label-check for="viewpricedolar">
                                <x-input wire:model.defer="empresa.viewpricedolar" name="viewpricedolar"
                                    value="1" type="checkbox" id="viewpricedolar" />
                                VER PRECIO EN DÓLARES
                            </x-label-check>
                            <x-jet-input-error for="empresa.viewpricedolar" />
                        </div>

                        <div class="block">
                            <x-label-check for="tipocambioauto">
                                <x-input wire:model.defer="empresa.tipocambioauto" name="tipocambioauto"
                                    value="1" type="checkbox" id="tipocambioauto" />
                                ACTUALIZAR TIPO CAMBIO AUTOMÁTICO
                            </x-label-check>
                            <x-jet-input-error for="empresa.tipocambioauto" />
                        </div>
                    @endif

                    <div class="block" x-data="{ openvalidatemail: {{ $empresa->validatemail ? 'true' : 'false' }} }">
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
                    </div>
                </div>
            </x-form-card>
        </div>

        @if (Module::isEnabled('Facturacion'))
            <div class="w-full flex flex-col xl:flex-row gap-8">
                <x-form-card titulo="DATOS FACTURACIÓN">
                    <div class="w-full flex flex-col xs:flex-row gap-2 gap-x-3">
                        <div class="w-full">
                            <x-label value="Usuario SOL :" />
                            <x-input class="block w-full" wire:model.defer="empresa.usuariosol"
                                placeholder="Ingrese usuario SOL Sunat..." />
                            <x-jet-input-error for="empresa.usuariosol" />
                        </div>
                        <div class="w-full">
                            <x-label value="Clave SOL :" />
                            <x-input class="block w-full" wire:model.defer="empresa.clavesol"
                                placeholder="Ingrese clave SOL Sunat..." />
                            <x-jet-input-error for="empresa.clavesol" />
                        </div>
                    </div>
                </x-form-card>

                <x-form-card titulo="CERTIFICADOS FACTURACIÓN"
                    subtitulo="Cargar los archivos para el envío de comprobantes electrónicos.">
                    <div class="w-full flex gap-3 flex-wrap">
                        <div class="relative w-full xs:w-48 text-center bg-body shadow-md shadow-shadowminicard rounded p-1"
                            x-data="{ isUploadingPublicKey: @entangle('isUploadingPublicKey'), progress: 0 }" x-on:livewire-upload-start="isUploadingPublicKey = true"
                            x-on:livewire-upload-finish="isUploadingPublicKey = false"
                            x-on:livewire-upload-error="$wire.emit('publickey'), isUploadingPublicKey = false"
                            x-on:livewire-upload-progress="progress = $event.detail.progress">

                            <div x-show="isUploadingPublicKey" wire:loading
                                wire:target="isUploadingPublicKey,deletepublickey" class="loading-overlay rounded">
                                <x-loading-next />
                            </div>

                            @if (isset($publickey))
                                <x-icon-file-upload type="filesuccess" :uploadname="$publickey->getClientOriginalName()"
                                    class="w-24 h-24 text-gray-300" />
                            @else
                                @if ($empresa->publickey)
                                    <x-icon-file-upload type="filesuccess" :uploadname="$empresa->publickey"
                                        class="w-24 h-24 text-gray-300" />
                                @else
                                    <x-icon-file-upload type="code" text="PEM"
                                        class="w-24 h-24 text-gray-300" />
                                @endif
                            @endif

                            <div class="w-full flex gap-1 flex-wrap justify-center">
                                @if (!$empresa->publickey)
                                    <x-input-file :for="$idpublickey" titulo="SUBIR CLAVE PÚBLICA" wire:loading.remove
                                        wire:target="publickey">
                                        <input type="file" class="hidden" wire:model="publickey"
                                            id="{{ $idpublickey }}" accept=".pem" />

                                        @if (isset($publickey))
                                            <x-slot name="clear">
                                                <x-button class="inline-flex px-6" wire:loading.attr="disabled"
                                                    wire:target="clearPublickey" wire:click="clearPublickey">
                                                    LIMPIAR
                                                </x-button>
                                            </x-slot>
                                        @endif
                                    </x-input-file>
                                @endif

                                @if (!isset($publickey) && $empresa->publickey)
                                    <x-button wire:click="deletepublickey({{ $empresa->id }})"
                                        wire:loading.attr="disabled" wire:target="deletepublickey">
                                        ELIMINAR CLAVE PÚBLICA
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline-block"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M3 6h18" />
                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                            <line x1="10" x2="10" y1="11" y2="17" />
                                            <line x1="14" x2="14" y1="11" y2="17" />
                                        </svg>
                                    </x-button>
                                @endif
                            </div>
                            <x-jet-input-error wire:loading.remove wire:target="publickey" for="publickey"
                                class="text-center" />
                        </div>

                        <div class="relative w-full xs:w-48 text-center bg-body shadow-md shadow-shadowminicard rounded p-1"
                            x-data="{ isUploadingPrivateKey: @entangle('isUploadingPrivateKey'), progress: 0 }" x-on:livewire-upload-start="isUploadingPrivateKey = true"
                            x-on:livewire-upload-finish="$wire.emit('privatekey', {{ $idicono }}),isUploadingPrivateKey = false"
                            x-on:livewire-upload-error="$wire.emit('privatekey'), isUploadingPrivateKey = false"
                            x-on:livewire-upload-progress="progress = $event.detail.progress">

                            <div x-show="isUploadingPrivateKey" wire:loading
                                wire:target="isUploadingPrivateKey,deleteprivatekey" class="loading-overlay rounded">
                                <x-loading-next />
                            </div>

                            @if (isset($privatekey))
                                <x-icon-file-upload type="filesuccess" :uploadname="$privatekey->getClientOriginalName()"
                                    class="w-24 h-24 text-gray-300" />
                            @else
                                @if ($empresa->privatekey)
                                    <x-icon-file-upload type="filesuccess" :uploadname="$empresa->privatekey"
                                        class="w-24 h-24 text-gray-300" />
                                @else
                                    <x-icon-file-upload type="code" text="PEM"
                                        class="w-24 h-24 text-gray-300" />
                                @endif
                            @endif

                            <div class="w-full flex gap-1 flex-wrap justify-center">
                                @if (!$empresa->privatekey)
                                    <x-input-file for="{{ $idprivatekey }}" titulo="SUBIR CLAVE PRIVADA"
                                        wire:loading.remove wire:target="privatekey">
                                        <input type="file" class="hidden" wire:model="privatekey"
                                            id="{{ $idprivatekey }}" accept=".pem" />

                                        @if (isset($privatekey))
                                            <x-slot name="clear">
                                                <x-button class="inline-flex px-6" wire:loading.attr="disabled"
                                                    wire:target="clearPrivatekey" wire:click="clearPrivatekey">
                                                    LIMPIAR
                                                </x-button>
                                            </x-slot>
                                        @endif
                                    </x-input-file>
                                @endif

                                @if (!isset($privatekey) && $empresa->privatekey)
                                    <x-button wire:click="deleteprivatekey({{ $empresa->id }})"
                                        wire:loading.attr="disabled" wire:target="deleteprivatekey">
                                        ELIMINAR CLAVE PRIVADA
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline-block"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M3 6h18" />
                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                            <line x1="10" x2="10" y1="11" y2="17" />
                                            <line x1="14" x2="14" y1="11" y2="17" />
                                        </svg>
                                    </x-button>
                                @endif
                            </div>
                            <x-jet-input-error wire:loading.remove wire:target="privatekey" for="privatekey"
                                class="text-center" />
                        </div>
                    </div>
                </x-form-card>
            </div>
        @endif

        <div class="w-full flex justify-end">
            <x-button type="submit">{{ __('ACTUALIZAR') }}</x-button>
        </div>
    </form>

    <x-jet-dialog-modal wire:model="openphone" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Nuevo teléfono') }}
            <x-button-close-modal wire:click="$toggle('openphone')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="savetelefono">
                <div class="w-full">
                    <x-label value="Teléfono :" />
                    <x-input class="block w-full" wire:model.defer="phone" placeholder="+51 999 999 999"
                        maxlength="9" />
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


    @section('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                $("#ubigeoempresa_id").select2().on("change", function(e) {
                    e.target.setAttribute("disabled", true);
                    @this.ubigeo_id = e.target.value;
                }).on('select2:open', function(e) {
                    const evt = "scroll.select2";
                    $(e.target).parents().off(evt);
                    $(window).off(evt);
                });


                window.addEventListener('render-select-empresa', () => {
                    $("#ubigeoempresa_id").select2()
                });

                Livewire.on('searchpricedolar', event => {
                    let checked = $(event).is(':checked');
                    if (checked) {
                        @this.searchpricedolar();
                    }
                });


                function getTipoCambio(inputId) {
                    axios.get('/admin/tipocambio', {
                            responseType: 'json'
                        })
                        .then(function(response) {
                            // console.log(response);
                            if (response.status == 200) {
                                @this.empresa.tipocambio = response.data.precioVenta;
                            }
                        })
                        .catch(function(error) {
                            // handle error
                            console.log(error);
                        });
                }


                Livewire.on('iconload', (inputId) => {

                    // console.log(inputId);
                    // var fileicono = @this.idicono;
                    var input = $('#' + inputId);

                    var file = input[0].files[0];

                    if (file) {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            $('#uploadForm + img').remove();
                            $('#uploadForm').html('<img src="' + e.target.result +
                                '" class="w-full h-full object-scale-down"/>');
                            // document.getElementById('iconopreview').src = e.target.result;
                        };
                        // @this.isUploadingIcono = false;
                        reader.readAsDataURL(file);

                    }

                    // document.getElementById(fileicono).addEventListener('change', function() {

                    //     var file = this.files[0];
                    //     if (file) {

                    //         var reader = new FileReader();
                    //         reader.onload = function(e) {
                    //             console.log(e.target);
                    //             @this.iconobase64 = e.target.result;
                    //             $('#uploadForm + img').remove();
                    //             $('#uploadForm').html('<img src="' + e.target.result +
                    //                 '" class="w-full h-full object-scale-down"/>');
                    //             // document.getElementById('iconopreview').src = e.target.result;
                    //         };
                    //         // Livewire.emitTo('admin.empresas.show-empresa', 'iconloaded');
                    //         reader.readAsDataURL(file);
                    //     }
                    // });
                });

            })
        </script>
    @endsection
</div>
