<div>
    <form wire:submit.prevent="save">
        <x-card-next titulo="Datos empresa" class="block w-full mt-3">
            <div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                <div class="w-full">
                    <x-label value="RUC :" />
                    <div class="w-full inline-flex">
                        <x-input class="block w-full" wire:model="document" />
                        <x-button-add class="px-2" wire:click="searchclient" wire:loading.attr="disabled"
                            wire:target="searchclient">
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
                <div class="w-full sm:col-span-2">
                    <x-label value="Razón Social :" />
                    <x-input class="block w-full" wire:model.defer="name" placeholder="Ingrese razón social..." />
                    <x-jet-input-error for="name" />
                </div>
                <div class="w-full lg:col-span-2">
                    <x-label value="Dirección :" />
                    <x-input class="block w-full" wire:model.defer="direccion" placeholder="Ingrese dirección..." />
                    <x-jet-input-error for="direccion" />
                </div>
                <div class="w-full">
                    <x-label value="Ubigeo :" />
                    <div id="parentUbigeo">
                        <x-select class="block w-full" wire:model.defer="ubigeo_id" id="ubigeoempresa_id"
                            data-dropdown-parent="#parentUbigeo" data-placeholder="Seleccionar"
                            data-minimum-results-for-search="3">
                            <x-slot name="options">
                                @foreach ($ubigeos as $item)
                                    <option value="{{ $item->id }}">{{ $item->region }} / {{ $item->provincia }} /
                                        {{ $item->distrito }}</option>
                                @endforeach
                            </x-slot>
                        </x-select>
                    </div>
                    <x-jet-input-error for="ubigeo_id" />
                </div>

                <div class="w-full">
                    <x-label value="Teléfono /Celular :" />
                    <x-input class="block w-full" wire:model.defer="telefono" />
                    <x-jet-input-error for="telefono" />
                </div>

                <div class="w-full">
                    <x-label value="Estado :" />
                    <x-input class="block w-full" wire:model.defer="estado" />
                    <x-jet-input-error for="estado" />
                </div>
                <div class="w-full">
                    <x-label value="Condición :" />
                    <x-input class="block w-full" wire:model.defer="condicion" />
                    <x-jet-input-error for="condicion" />
                </div>
                <div class="w-full">
                    <x-label value="Correo :" />
                    <x-input class="block w-full" wire:model.defer="correo" placeholder="@" />
                    <x-jet-input-error for="correo" />
                </div>
                <div class="w-full">
                    <x-label value="Web :" />
                    <x-input class="block w-full" wire:model.defer="web" placeholder="www.misitioweb.com" />
                    <x-jet-input-error for="web" />
                </div>
                <div class="w-full">
                    <x-label value="Monto adelanto máximo :" />
                    <x-input class="block w-full" wire:model.defer="montoadelanto" type="number" placeholder="0.00"
                        min="0" step="0.01" />
                    <x-jet-input-error for="montoadelanto" />
                </div>
            </div>
        </x-card-next>

        <x-card-next titulo="Logos empresa" class="block w-full mt-3">
            <div class="w-full flex gap-2 flex-wrap">
                <div class="relative w-full sm:w-72 text-center shadow-md rounded p-1" x-data="{ isUploadingLogo: @entangle('isUploadingLogo'), progress: 0 }"
                    x-on:livewire-upload-start="isUploadingLogo = true"
                    x-on:livewire-upload-finish="isUploadingLogo = false"
                    x-on:livewire-upload-error="$wire.emit('errorImage'), isUploadingLogo = false"
                    x-on:livewire-upload-progress="progress = $event.detail.progress">

                    <div x-show="isUploadingLogo" wire:loading wire:target="isUploadingPublicKey"
                        class="loading-overlay rounded">
                        <x-loading-next />
                    </div>

                    @if (isset($logo))
                        <div class="w-full h-32 md:max-w-md mx-auto mb-1 duration-300">
                            <img class="w-full h-full object-scale-down" src="{{ $logo->temporaryUrl() }}" />
                        </div>
                    @else
                        <x-icon-file-upload class="w-24 h-24 text-gray-300" />
                    @endif

                    <x-input-file :for="$idlogo" titulo="SELECCIONAR LOGO" wire:loading.remove wire:target="logo">
                        <input type="file" class="hidden" wire:model="logo" id="{{ $idlogo }}"
                            accept="image/jpg, image/jpeg, image/png" />

                        @if (isset($logo))
                            <x-slot name="clear">
                                <x-button class="inline-flex px-6" wire:loading.attr="disabled"
                                    wire:target="clearLogo" wire:click="clearLogo">
                                    LIMPIAR
                                </x-button>
                            </x-slot>
                        @endif
                    </x-input-file>

                    <x-jet-input-error wire:loading.remove wire:target="logo" for="logo" class="text-center" />
                </div>
                <div class="relative w-full sm:w-72 text-center shadow-md rounded p-1" x-data="{ isUploadingIcono: @entangle('isUploadingIcono'), progress: 0 }"
                    x-on:livewire-upload-start="isUploadingIcono = true"
                    x-on:livewire-upload-finish="isUploadingIcono = false"
                    x-on:livewire-upload-error="$wire.emit('icono'), isUploadingIcono = false"
                    x-on:livewire-upload-progress="progress = $event.detail.progress">

                    <div x-show="isUploadingIcono" wire:loading wire:target="isUploadingIcono"
                        class="loading-overlay rounded">
                        <x-loading-next />
                    </div>

                    @if (isset($icono))
                        <div class="w-full h-32 md:max-w-md mx-auto mb-1 duration-300">
                            <img class="w-full h-full object-scale-down" src="{{ $icono->temporaryUrl() }}" />
                        </div>
                    @else
                        <x-icon-file-upload class="w-24 h-24 text-gray-300" />
                    @endif

                    <x-input-file :for="$idicono" titulo="SELECCIONAR ÍCONO" wire:loading.remove
                        wire:target="icono">
                        <input type="file" class="hidden" wire:model="icono" id="{{ $idicono }}"
                            accept=".ico" />

                        @if (isset($icono))
                            <x-slot name="clear">
                                <x-button class="inline-flex px-6" wire:loading.attr="disabled"
                                    wire:target="clearIcono" wire:click="clearIcono">
                                    LIMPIAR
                                </x-button>
                            </x-slot>
                        @endif
                    </x-input-file>

                    <x-jet-input-error wire:loading.remove wire:target="icono" for="icono" class="text-center" />
                </div>
            </div>
        </x-card-next>

        <div class="w-full flex flex-wrap md:flex-nowrap gap-3">
            <x-card-next titulo="Facturación electrónica" class="block w-full mt-3">
                <div class="w-full flex flex-wrap sm:flex-nowrap md:flex-wrap xl:flex-nowrap gap-2">
                    <div class="w-full">
                        <x-label value="Usuario SOL :" />
                        <x-input class="block w-full" wire:model.defer="usuariosol"
                            placeholder="Ingrese usuario SOL Sunat..." />
                        <x-jet-input-error for="correo" />
                    </div>
                    <div class="w-full">
                        <x-label value="Clave SOL :" />
                        <x-input class="block w-full" wire:model.defer="clavesol"
                            placeholder="Ingrese clave SOL Sunat..." />
                        <x-jet-input-error for="web" />
                    </div>
                </div>
                <div class="w-full flex flex-wrap sm:flex-nowrap md:flex-wrap xl:flex-nowrap gap-2 mt-2">
                    <div class="relative w-full text-center shadow-md rounded p-1" x-data="{ isUploadingPublicKey: @entangle('isUploadingPublicKey'), progress: 0 }"
                        x-on:livewire-upload-start="isUploadingPublicKey = true"
                        x-on:livewire-upload-finish="isUploadingPublicKey = false"
                        x-on:livewire-upload-error="$wire.emit('publickey'), isUploadingPublicKey = false"
                        x-on:livewire-upload-progress="progress = $event.detail.progress">

                        <div x-show="isUploadingPublicKey" wire:loading wire:target="isUploadingPublicKey"
                            class="loading-overlay rounded">
                            <x-loading-next />
                        </div>

                        @if (isset($publickey))
                            <x-icon-file-upload type="filesuccess" :uploadname="$publickey->getClientOriginalName()"
                                class="w-24 h-24 text-gray-300" />

                            {{-- <p class="text-sm font-semibold uppercase text-gray-500">
                                    {{ \App\Helpers\FormatoPersonalizado::getExtencionFile($publickey->getClientOriginalName()) }}
                                </p> --}}
                        @else
                            <x-icon-file-upload type="code" text="PEM" class="w-24 h-24 text-gray-300" />
                        @endif

                        <x-input-file :for="$idpublickey" titulo="SELECCIONAR CLAVE PÚBLICA (.PEM)" wire:loading.remove
                            wire:target="publickey">
                            <input type="file" class="hidden" wire:model="publickey" id="{{ $idpublickey }}"
                                accept=".pem" />

                            @if (isset($publickey))
                                <x-slot name="clear">
                                    <x-button class="inline-flex px-6" wire:loading.attr="disabled"
                                        wire:target="clearInput('publickey')" wire:click="clearInput('publickey')">
                                        LIMPIAR
                                    </x-button>
                                </x-slot>
                            @endif
                        </x-input-file>

                        <x-jet-input-error wire:loading.remove wire:target="publickey" for="publickey"
                            class="text-center" />
                    </div>

                    <div class="relative w-full text-center shadow-md rounded p-1" x-data="{ isUploadingPrivateKey: @entangle('isUploadingPrivateKey'), progress: 0 }"
                        x-on:livewire-upload-start="isUploadingPrivateKey = true"
                        x-on:livewire-upload-finish="isUploadingPrivateKey = false"
                        x-on:livewire-upload-error="$wire.emit('privatekey'), isUploadingPrivateKey = false"
                        x-on:livewire-upload-progress="progress = $event.detail.progress">

                        <div x-show="isUploadingPrivateKey" wire:loading wire:target="isUploadingPrivateKey"
                            class=" loading-overlay rounded">
                            <x-loading-next />
                        </div>

                        @if (isset($privatekey))
                            <x-icon-file-upload type="filesuccess" :uploadname="$privatekey->getClientOriginalName()"
                                class="w-24 h-24 text-gray-300" />
                            {{-- <p class="text-sm font-semibold uppercase text-gray-500">
                                    {{ \App\Helpers\FormatoPersonalizado::getExtencionFile($privatekey->getClientOriginalName()) }}
                                </p> --}}
                        @else
                            <x-icon-file-upload type="code" text="PEM" class="w-24 h-24 text-gray-300" />
                        @endif

                        <x-input-file :for="$idprivatekey" titulo="SELECCIONAR CLAVE PRIVADA (.PEM)" wire:loading.remove
                            wire:target="privatekey">
                            <input type="file" class="hidden" wire:model="privatekey" id="{{ $idprivatekey }}"
                                accept=".pem" />

                            @if (isset($privatekey))
                                <x-slot name="clear">
                                    <x-button class="inline-flex px-6" wire:loading.attr="disabled"
                                        wire:target="clearInput('privatekey')" wire:click="clearInput('privatekey')">
                                        LIMPIAR
                                    </x-button>
                                </x-slot>
                            @endif
                        </x-input-file>

                        <x-jet-input-error wire:loading.remove wire:target="privatekey" for="privatekey"
                            class="text-center" />
                    </div>
                </div>
            </x-card-next>

            <x-card-next titulo="Configuracion adicional" class="block w-full mt-3">
                <div class="w-full items-start flex flex-col gap-1 flex-wrap">

                    <div class="block">
                        <x-label-check for="uselistprice">
                            <x-input wire:model.defer="uselistprice" name="uselistprice" value="1"
                                type="checkbox" id="uselistprice" />
                            USAR LISTA DE PRECIOS
                        </x-label-check>
                        <x-jet-input-error for="uselistprice" />
                    </div>

                    <div class="block">
                        <x-label-check for="usepricedolar">
                            <x-input wire:model.defer="usepricedolar" name="usepricedolar" value="1"
                                type="checkbox" id="usepricedolar" />
                            USAR PRECIO EN DÓLARES
                        </x-label-check>
                        <x-jet-input-error for="usepricedolar" />

                        <div class="hidden" id="parentusepricedolar" wire:ignore>
                            <x-input class="block w-full" type="number" wire:model.defer="tipocambio"
                                placeholder="0.00" min="0" step="0.0001" />
                        </div>
                        <x-jet-input-error for="tipocambio" />
                    </div>

                    <div class="block">
                        <x-label-check for="viewpricedolar">
                            <x-input wire:model.defer="viewpricedolar" name="viewpricedolar" value="1"
                                type="checkbox" id="viewpricedolar" />
                            VER PRECIO EN DÓLARES
                        </x-label-check>
                        <x-jet-input-error for="viewpricedolar" />
                    </div>

                    <div class="block">
                        <x-label-check for="tipocambioauto">
                            <x-input wire:model.defer="tipocambioauto" name="tipocambioauto" value="1"
                                type="checkbox" id="tipocambioauto" />
                            ACTUALIZAR TIPO CAMBIO AUTOMÁTICO
                        </x-label-check>
                        <x-jet-input-error for="tipocambioauto" />
                    </div>

                    <div class="block">
                        <x-label-check for="validatemail">
                            <x-input wire:model.defer="validatemail" name="validatemail" value="1"
                                type="checkbox" id="validatemail" />
                            VALIDAR CORREO CORPORATIVO PARA DASHBOARD
                        </x-label-check>
                        <x-jet-input-error for="validatemail" />

                        <div class="hidden w-full" id="parentvalidatemail">
                            <x-input class="block w-full" wire:model.defer="dominiocorreo"
                                placeholder="Ingrese dominio web del correo a validar..." />
                            <x-jet-input-error for="dominiocorreo" />
                        </div>
                    </div>
                </div>
            </x-card-next>
        </div>

        <x-loading-next wire:loading />


        <div class="w-full mt-3 flex justify-end">
            <x-button type="submit">REGISTRAR</x-button>
        </div>
    </form>

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
                    initToggle(true);
                });


                initToggle();

                function initToggle(isHydrate = false) {
                    toggleInput('#validatemail', '#parentvalidatemail', isHydrate);
                    toggleInput('#usepricedolar', '#parentusepricedolar', isHydrate, true);
                }

                function toggleInput(inputId, contentId, isHydrate, getTipo = false) {
                    if (isHydrate) {

                        let status = $(inputId).is(':checked');
                        status ? $(contentId).slideDown('fast') : $(contentId).slideUp(
                            'fast');
                    } else {
                        $(inputId).on('change', function() {

                            let status = $(this).is(':checked');
                            status ? $(contentId).slideDown('fast') : $(contentId).slideUp(
                                'fast');

                            if (status) {
                                if (getTipo) {
                                    @this.tipocambio = null;
                                    @this.searchpricedolar();
                                    // getTipoCambio(inputId);

                                }
                            }
                        })
                    }
                }

                function getTipoCambio(inputId) {
                    axios.get('/admin/tipocambio', {
                            responseType: 'json'
                        })
                        .then(function(response) {
                            // console.log(response);
                            if (response.status == 200) {
                                console.log(response.data.precioVenta);
                                @this.tipocambio = response.data.precioVenta;
                            }
                        })
                        .catch(function(error) {
                            // handle error
                            console.log(error);
                        });
                }

            })
        </script>
    @endsection
</div>
