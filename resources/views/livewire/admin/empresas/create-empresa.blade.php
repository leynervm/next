<div>
    <form wire:submit.prevent="save" class="w-full flex flex-col gap-8 relative" x-data="createempresa">
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
                    <x-input class="block w-full" wire:model.defer="name" placeholder="Razón social..." />
                    <x-jet-input-error for="name" />
                </div>

                <div class="w-full sm:col-span-2 xl:col-span-1">
                    <x-label value="Dirección :" />
                    <x-input class="block w-full" wire:model.defer="direccion" placeholder="Dirección..."/>
                    <x-jet-input-error for="direccion" />
                </div>

                <div class="w-full sm:col-span-2">
                    <x-label value="Ubigeo :" />
                    {{-- 20201987297 --}}
                    <div id="parentubigeoempresa_id" class="relative" wire:ignore>
                        <x-select class="block w-full" x-ref="selectubigeo" id="ubigeoempresa_id"
                            data-minimum-results-for-search="3">
                            <x-slot name="options">
                                @foreach ($ubigeos as $item)
                                    <option value="{{ $item->id }}">{{ $item->region }} / {{ $item->provincia }} /
                                        {{ $item->distrito }}</option>
                                @endforeach
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="ubigeo_id" />
                </div>

                <div class="w-full">
                    <x-label value="Teléfono /Celular :" />
                    <x-input class="block w-full" wire:model.defer="telefono" placeholder="Teléfono / Celaular..." />
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
                    <x-input class="block w-full" wire:model.defer="email" placeholder="@" />
                    <x-jet-input-error for="email" />
                </div>

                <div class="w-full">
                    <x-label value="Web :" />
                    <x-input class="block w-full" wire:model.defer="web" placeholder="www.misitioweb.com" />
                    <x-jet-input-error for="web" />
                </div>

                <div class="w-full">
                    <x-label value="Porcentaje IGV :" />
                    <x-input class="block w-full" wire:model.defer="igv" type="number" placeholder="0.00" />
                    <x-jet-input-error for="igv" />
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
                                ACTUALIZAR TIPO CAMBIO AUTOMÁTICO
                            </x-label-check>
                            <x-jet-input-error for="tipocambioauto" />
                        </div>
                    @endif

                    <div class="w-full">
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
                                placeholder="Usuario SOL Sunat..." />
                            <x-jet-input-error for="usuariosol" />
                        </div>
                        <div class="w-full">
                            <x-label value="Clave SOL :" />
                            <x-input class="block w-full" wire:model.defer="clavesol"
                                placeholder="Clave SOL Sunat..." />
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

        <div wire:loading.flex wire:target="save" class="loading-overlay rounded hidden">
            <x-loading-next />
        </div>
    </form>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('createempresa', () => ({
                ubigeo_id: @entangle('ubigeo_id').defer,
                usepricedolar: @entangle('usepricedolar').defer,
                viewpricedolar: @entangle('viewpricedolar').defer,
                tipocambio: @entangle('tipocambio').defer,
                cambioauto: @entangle('tipocambioauto').defer,
                loadingdolar: false,
                openpricedolar: false,
                openvalidatemail: false,

                init() {
                    this.usepricedolar = false;
                    this.viewpricedolar = false;
                    this.cambioauto = false;
                    this.selectUbigeo();
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
                selectUbigeo() {
                    this.selectU = $(this.$refs.selectubigeo).select2();
                    this.selectU.val(this.ubigeo_id).trigger("change");
                    this.selectU.on("select2:select", (event) => {
                        this.ubigeo_id = event.target.value;
                    }).on('select2:open', function(e) {
                        const evt = "scroll.select2";
                        $(e.target).parents().off(evt);
                        $(window).off(evt);
                    });
                },
                async getTipocambio() {
                    try {
                        this.loadingdolar = true;
                        const response = await fetch('/admin/tipocambio');
                        const data = await response.json();
                        this.tipocambio = data.precioVenta;
                        this.loadingdolar = false;
                        this.openpricedolar = true;

                    } catch (error) {
                        console.error('Error al obtener el tipo de cambio', error);
                        this.tipocambio = null;
                        this.loadingdolar = false;
                        this.openpricedolar = true;
                    }
                },
            }))
        });

        window.addEventListener('selectubigeo', data => {
            let selectubigeo = document.querySelector('[x-ref="selectubigeo"]');
            $(selectubigeo).val(data.detail).trigger('change');
            // $(selectubigeo).select2().trigger('change');
        })

        document.addEventListener('livewire:load', function() {
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
</div>
