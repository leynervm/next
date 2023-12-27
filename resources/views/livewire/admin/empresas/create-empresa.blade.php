<div>
    <form wire:submit.prevent="save" class="w-full flex flex-col gap-8">
        <x-form-card titulo="DATOS EMPRESA">
            <div class="bg-body p-3 w-full grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-2">
                <div class="w-full">
                    <x-label value="RUC :" />
                    <div class="w-full inline-flex gap-1">
                        <x-input class="block w-full numeric prevent" wire:keydown.enter="searchclient" type="number"
                            wire:model.defer="document" />
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
                    <x-jet-input-error for="document" />
                </div>

                <div class="w-full sm:col-span-2">
                    <x-label value="Razón Social :" />
                    <x-input class="block w-full" wire:model.defer="name" />
                    <x-jet-input-error for="name" />
                </div>

                <div class="w-full sm:col-span-2 xl:col-span-1">
                    <x-label value="Dirección :" />
                    <x-input class="block w-full" wire:model.defer="direccion" />
                    <x-jet-input-error for="direccion" />
                </div>

                <div class="w-full sm:col-span-2">
                    <x-label value="Ubigeo :" />
                    {{-- 20201987297 --}}
                    <div id="parentubigeoempresa_id">
                        <x-select class="block w-full" wire:model.defer="ubigeo_id" id="ubigeoempresa_id"
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
                    <x-disabled-text :text="$estado ?? '-'" />
                    <x-jet-input-error for="estado" />
                </div>

                <div class="w-full">
                    <x-label value="Condición :" />
                    <x-disabled-text :text="$condicion ?? '-'" />
                    <x-jet-input-error for="condicion" />
                </div>

                <div class="w-full">
                    <x-label value="Correo :" />
                    <x-input class="block w-full" wire:model.defer="email" />
                    <x-jet-input-error for="correo" />
                </div>

                <div class="w-full">
                    <x-label value="Web :" />
                    <x-input class="block w-full" wire:model.defer="web" placeholder="www.misitioweb.com" />
                    <x-jet-input-error for="web" />
                </div>

                <div class="w-full">
                    <x-label value="Monto adelanto máximo :" />
                    <x-input class="block w-full" wire:model.defer="montoadelanto" type="number" placeholder="0.00" />
                    <x-jet-input-error for="montoadelanto" />
                </div>
            </div>
        </x-form-card>

        <div class="w-full flex flex-col xl:flex-row gap-8">
            <x-form-card titulo="ICONO & LOGO">
                <div class="w-full flex gap-3 flex-wrap">
                    <div class="relative w-full xs:w-48 text-center bg-body shadow-md shadow-shadowminicard rounded p-1"
                        x-data="{ isUploadingLogo: @entangle('isUploadingLogo'), progress: 0 }" x-on:livewire-upload-start="isUploadingLogo = true"
                        x-on:livewire-upload-finish="isUploadingLogo = false"
                        x-on:livewire-upload-error="$emit('errorImage'), isUploadingLogo = false"
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
                            <x-icon-file-upload class="w-24 h-24 text-gray-300" />
                        @endif

                        <div class="w-full flex gap-1 flex-wrap justify-center">
                            <x-input-file :for="$idlogo" titulo="SELECCIONAR LOGO" wire:loading.remove
                                wire:target="logo">
                                <input type="file" class="hidden" wire:model.defer="logo" id="{{ $idlogo }}"
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
                            <x-icon-file-upload class="w-24 h-24 text-gray-300" />
                        @endif

                        <div class="w-full flex gap-1 flex-wrap justify-center">
                            <x-input-file for="{{ $idicono }}" titulo="SELECCIONAR ÍCONO" wire:loading.remove
                                wire:target="icono">
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
                        </div>
                        <x-jet-input-error wire:loading.remove wire:target="icono" for="icono"
                            class="text-center" />
                    </div>
                </div>
            </x-form-card>

            <x-form-card titulo="OTRAS OPCIONES" subtitulo="Seleccionar las opciones según su preferencia de uso.">
                <div class="w-full items-start flex flex-col gap-1 flex-wrap bg-body p-3">

                    @if (Module::isEnabled('Ventas'))
                        <div class="block">
                            <x-label-check for="uselistprice">
                                <x-input wire:model.defer="uselistprice" name="uselistprice" value="1"
                                    type="checkbox" id="uselistprice" />
                                USAR LISTA DE PRECIOS
                            </x-label-check>
                            <x-jet-input-error for="uselistprice" />
                        </div>

                        <div class="block" x-data="{ openpricedolar: false }">
                            <x-label-check for="usepricedolar">
                                <x-input wire:model.defer="usepricedolar" name="usepricedolar" value="1"
                                    type="checkbox" id="usepricedolar" x-on:change="openpricedolar = !openpricedolar"
                                    wire:change="$emit('searchpricedolar', $event.target)" />
                                USAR PRECIO EN DÓLARES
                            </x-label-check>
                            <x-jet-input-error for="usepricedolar" />

                            <div x-show="openpricedolar" wire:loading.remove wire:target="searchpricedolar">
                                <x-input class="block w-full" type="number" wire:model.defer="tipocambio"
                                    placeholder="0.00" min="0" step="0.0001" />
                            </div>
                            <x-jet-input-error for="tipocambio" />

                            <div wire:loading.block wire:target="searchpricedolar">
                                <x-loading-next />
                            </div>
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
                    @endif

                    <div class="block" x-data="{ openvalidatemail: false }">
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
                            <x-input class="block w-full" wire:model.defer="usuariosol"
                                placeholder="Ingrese usuario SOL Sunat..." />
                            <x-jet-input-error for="usuariosol" />
                        </div>
                        <div class="w-full">
                            <x-label value="Clave SOL :" />
                            <x-input class="block w-full" wire:model.defer="clavesol"
                                placeholder="Ingrese clave SOL Sunat..." />
                            <x-jet-input-error for="clavesol" />
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
                                <x-icon-file-upload type="code" text="PEM" class="w-24 h-24 text-gray-300" />
                            @endif

                            <div class="w-full flex gap-1 flex-wrap justify-center">
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
                                <x-icon-file-upload type="code" text="PEM" class="w-24 h-24 text-gray-300" />
                            @endif

                            <div class="w-full flex gap-1 flex-wrap justify-center">
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
                            </div>
                            <x-jet-input-error wire:loading.remove wire:target="privatekey" for="privatekey"
                                class="text-center" />
                        </div>
                    </div>
                </x-form-card>
            </div>
        @endif

        {{-- <div class="w-full">
            @if ($errors->any())
                <div class="flex flex-col gap-1 my-3">
                    @foreach ($errors->all() as $error)
                        <p class="text-red-500 text-xs inline-block font-semibold bg-red-100 p-0.5 rounded">
                            {{ $error }}</p>
                    @endforeach
                </div>
            @endif
        </div> --}}

        <div class="w-full flex justify-end">
            <x-button type="submit">REGISTRAR</x-button>
        </div>

        <div wire:loading wire:target="save" class="relative">
            <x-loading-next />
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
                });


                Livewire.on('searchpricedolar', event => {
                    let checked = $(event).is(':checked');
                    if (checked) {
                        @this.searchpricedolar();
                    }
                });

                Livewire.on('iconload', (inputId) => {

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
