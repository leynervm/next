<div>
    <form wire:submit.prevent="save" class="w-full flex flex-col gap-8 relative" x-data="createempresa">
        <x-form-card titulo="DATOS EMPRESA">
            <div class="bg-body p-3 w-full grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-2">
                <div class="w-full">
                    <x-label value="RUC :" />
                    <div class="w-full inline-flex gap-1">
                        <x-input class="block w-full numeric prevent" wire:keydown.enter="searchclient" type="number"
                            wire:model.defer="document" onkeypress="return validarNumero(event, 11)" />
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
                    <x-input class="block w-full" wire:model.defer="direccion" placeholder="Dirección..." />
                    <x-jet-input-error for="direccion" />
                </div>

                <div class="w-full sm:col-span-2">
                    <x-label value="Ubigeo :" />
                    {{-- 20201987297 --}}
                    <div id="parentubigeoempresa_id" class="relative" x-init="selectUbigeo">
                        <x-select class="block w-full" x-ref="selectubigeo" id="ubigeoempresa_id"
                            data-minimum-results-for-search="3">
                            <x-slot name="options">
                                @foreach ($ubigeos as $item)
                                    <option value="{{ $item->id }}">{{ $item->region }} / {{ $item->provincia }} /
                                        {{ $item->distrito }} /  {{ $item->ubigeo_reniec }}</option>
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
            <x-form-card titulo="OTRAS OPCIONES" subtitulo="Seleccionar las opciones según su preferencia de uso.">
                <div class="w-full items-start flex flex-col gap-1">

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

            <x-form-card titulo="LOGOTIPO & ICONO">
                <div class="w-full grid gap-3 xs:grid-cols-2">
                    <div class="relative w-full text-center " x-data="{ isUploadingLogo: false, progress: 0 }"
                        x-on:livewire-upload-start="isUploadingLogo = true"
                        x-on:livewire-upload-finish="isUploadingLogo = false"
                        x-on:livewire-upload-error="$wire.emit('errorImage'), isUploadingLogo = false"
                        x-on:livewire-upload-progress="progress = $event.detail.progress">

                        <div wire:loading.flex wire:target="logo" class="loading-overlay rounded hidden">
                            <x-loading-next />
                        </div>

                        @if (isset($logo))
                            <x-simple-card class="w-full h-40 md:max-w-md mx-auto mb-1 border border-borderminicard">
                                <img class="w-full h-full object-scale-down animate__animated animate__fadeIn animate__faster"
                                    src="{{ $logo->temporaryUrl() }}" />
                            </x-simple-card>
                        @else
                            <x-icon-file-upload class="w-36 h-36 text-gray-300" />
                        @endif

                        <div class="w-full flex gap-2 flex-wrap justify-center">
                            @if (isset($logo))
                                <x-button class="inline-flex" wire:loading.attr="disabled"
                                    wire:click="clearLogo">LIMPIAR
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
                            @else
                                <x-input-file :for="$idlogo" titulo="SELECCIONAR LOGO" wire:loading.remove
                                    wire:target="logo">
                                    <input type="file" class="hidden" wire:model.defer="logo"
                                        id="{{ $idlogo }}" accept="image/jpg, image/jpeg, image/png" />
                                </x-input-file>
                            @endif
                        </div>
                        <x-jet-input-error wire:loading.remove wire:target="logo" for="logo"
                            class="text-center" />
                    </div>

                    <div class="relative w-full text-center">
                        <div wire:loading.flex wire:target="icono, clearIcono" class="loading-overlay rounded hidden">
                            <x-loading-next />
                        </div>

                        @if (isset($icono))
                            <div>
                                <x-simple-card
                                    class="w-full h-40 md:max-w-md mx-auto mb-1 border border-borderminicard">
                                    <img x-bind:src="URL.createObjectURL(icono)"
                                        class="w-full h-full object-scale-down animate__animated animate__fadeIn animate__faster">
                                </x-simple-card>
                            </div>
                        @else
                            <x-icon-file-upload class="w-36 h-36 text-gray-300" />
                        @endif

                        <div class="w-full flex gap-1 flex-wrap justify-center">
                            @if (isset($icono))
                                <x-button class="inline-flex px-6" wire:loading.attr="disabled"
                                    wire:click="clearIcono">LIMPIAR
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
                            @else
                                <x-input-file for="{{ $idicono }}" titulo="SELECCIONAR ÍCONO" wire:loading.remove
                                    wire:target="icono">
                                    <input type="file" class="hidden" wire:model.defer="icono"
                                        id="{{ $idicono }}" accept=".ico"
                                        @change="icono = $event.target.files[0]" />
                                </x-input-file>
                            @endif
                        </div>
                        <x-jet-input-error for="icono" class="text-center" />
                    </div>
                </div>
            </x-form-card>
        </div>

        @if (Module::isEnabled('Facturacion'))
            <x-form-card titulo="DATOS FACTURACIÓN">
                <div class="w-full flex flex-col gap-2">
                    <div class="w-full grid gap-2 sm:grid-cols-2 xl:grid-cols-4">
                        <div class="w-full">
                            <x-label value="Modo envío SUNAT :" />
                            <div class="w-full relative" x-init="SelectMode" wire:ignore>
                                <x-select class="block w-full" x-ref="selectmode" id="sendmode"
                                    data-dropdown-parent="null">
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
                            <x-label value="Clave certificado digital :" />
                            <x-input class="block w-full" wire:model.defer="passwordcert"
                                placeholder="Contraseña del certificado..." type="password" />
                            <x-jet-input-error for="passwordcert" />
                        </div>
                        <div class="w-full">
                            <x-label value="Usuario SOL :" />
                            <x-input class="block w-full" wire:model.defer="usuariosol"
                                placeholder="Usuario SOL Sunat..." />
                            <x-jet-input-error for="usuariosol" />
                        </div>
                        <div class="w-full">
                            <x-label value="Clave SOL :" />
                            <x-input class="block w-full" wire:model.defer="clavesol"
                                placeholder="Clave SOL Sunat..." type="password" />
                            <x-jet-input-error for="clavesol" />
                        </div>
                        <div class="w-full">
                            <x-label value="Client ID (Guías Remisión):" />
                            <x-input class="block w-full" wire:model.defer="clientid"
                                placeholder="Ingrese client id..." />
                            <x-jet-input-error for="clientid" />
                        </div>
                        <div class="w-full">
                            <x-label value="Client secret (Guías Remisión):" />
                            <x-input class="block w-full" wire:model.defer="clientsecret"
                                placeholder="Ingrese client secret..." />
                            <x-jet-input-error for="clientsecret" />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="relative w-full xs:max-w-xs text-center">
                            <div wire:loading.flex wire:target="cert"
                                class="loading-overlay rounded shadow border border-borderminicard hidden">
                                <x-loading-next />
                            </div>

                            @if (isset($cert))
                                <x-icon-file-upload type="filesuccess" :uploadname="$cert->getClientOriginalName()"
                                    class="w-36 h-36 text-gray-300 animate__animated animate__fadeIn animate__faster" />
                            @else
                                <x-icon-file-upload type="code" text="PFX" class="w-36 h-36 text-gray-300" />
                            @endif

                            <div class="w-full flex gap-1 flex-wrap justify-center">
                                @if (isset($cert))
                                    <x-button class="inline-flex px-6" wire:loading.attr="disabled"
                                        wire:click="clearCert">LIMPIAR
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
                                @else
                                    <x-input-file :for="$idcert" titulo="CARGAR CERTIFICADO DIGITAL"
                                        wire:loading.remove wire:target="cert">
                                        <input type="file" class="hidden" wire:model="cert"
                                            id="{{ $idcert }}" accept=".pfx" />
                                    </x-input-file>
                                @endif
                            </div>
                            <x-jet-input-error wire:loading.remove wire:target="cert" for="cert"
                                class="text-center" />
                        </div>
                    </div>
                </div>
            </x-form-card>
        @endif

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
                sendmode: @entangle('sendmode').defer,
                cambioauto: @entangle('tipocambioauto').defer,
                loadingdolar: false,
                openpricedolar: false,
                openvalidatemail: false,

                init() {
                    this.usepricedolar = false;
                    this.viewpricedolar = false;
                    this.cambioauto = false;
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
            });
        }


        document.addEventListener('livewire:load', function() {
            Livewire.on('errorImage', data => {
                console.log('Error al cargar ', data);
            })

            // Livewire.on('iconload', (inputId) => {
            //     var input = $('#' + inputId);
            //     var file = input[0].files[0];

            //     if (file) {
            //         var reader = new FileReader();
            //         reader.onload = function(e) {
            //             $('#uploadForm + img').remove();
            //             $('#uploadForm').html('<img src="' + e.target.result +
            //                 '" class="w-full h-full object-scale-down"/>');
            //             // document.getElementById('iconopreview').src = e.target.result;
            //         };
            //         // @this.isUploadingIcono = false;
            //         reader.readAsDataURL(file);
            //     }

            //     // document.getElementById(fileicono).addEventListener('change', function() {

            //     //     var file = this.files[0];
            //     //     if (file) {

            //     //         var reader = new FileReader();
            //     //         reader.onload = function(e) {
            //     //             console.log(e.target);
            //     //             @this.iconobase64 = e.target.result;
            //     //             $('#uploadForm + img').remove();
            //     //             $('#uploadForm').html('<img src="' + e.target.result +
            //     //                 '" class="w-full h-full object-scale-down"/>');
            //     //             // document.getElementById('iconopreview').src = e.target.result;
            //     //         };
            //     //         // Livewire.emitTo('admin.empresas.show-empresa', 'iconloaded');
            //     //         reader.readAsDataURL(file);
            //     //     }
            //     // });
            // });

        })
    </script>
</div>
