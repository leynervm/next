<div x-data="configuracion">
    <div wire:loading.flex class="loading-overlay hidden fixed">
        <x-loading-next />
    </div>

    <x-form-card titulo="DATOS FACTURACIÓN">
        <form wire:submit.prevent="update" class="w-full flex flex-col gap-2">
            <div class="w-full grid gap-2 sm:grid-cols-2 xl:grid-cols-4">
                <div class="w-full">
                    <x-label value="Modo envío SUNAT :" />
                    <div class="w-full relative" id="parentsendmode" x-init="SelectMode" wire:ignore>
                        <x-select class="block w-full" x-ref="selectmode" id="sendmode">
                            <x-slot name="options">
                                <option value="0">MODO PRUEBAS</option>
                                <option value="1">MODO PRODUCCIÓN</option>
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="empresa.sendmode" />
                </div>
                <div class="w-full">
                    <x-label value="Afectación IGV :" />
                    <div class="w-full relative" id="parentafectacionigv" x-init="SelectAfectacionIGV" wire:ignore>
                        <x-select class="block w-full" x-ref="selectafectacionigv" id="afectacionigv">
                            <x-slot name="options">
                                <option value="0">EXONERAR IGV</option>
                                <option value="1">INCLUIR IGV</option>
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="empresa.afectacionigv" />
                </div>
                <div class="w-full">
                    <x-label value="Usuario SOL :" />
                    <template x-if="sendmode > 0">
                        <x-input class="block w-full" x-model="usuariosol" placeholder="Ingrese usuario SOL Sunat..." />
                    </template>
                    <template x-if="sendmode <= 0">
                        <x-disabled-text x-text="usuariosol" text="" />
                    </template>
                    <x-jet-input-error for="empresa.usuariosol" />
                </div>
                <div class="w-full">
                    <x-label value="Clave SOL :" />
                    <template x-if="sendmode > 0">
                        <x-input class="block w-full" x-model="clavesol" placeholder="Ingrese clave SOL Sunat..."
                            type="password" />
                    </template>
                    <template x-if="sendmode <= 0">
                        <x-disabled-text x-text="clavesol" text="" />
                    </template>
                    <x-jet-input-error for="empresa.clavesol" />
                </div>
                <div class="w-full">
                    <x-label value="Client ID (Guías Remisión):" />
                    <template x-if="sendmode > 0">
                        <x-input class="block w-full" x-model="clientid" placeholder="Ingrese client id..." />
                    </template>
                    <template x-if="sendmode <= 0">
                        <x-disabled-text x-text="clientid" text="" />
                    </template>
                    <x-jet-input-error for="empresa.clientid" />
                </div>
                <div class="w-full">
                    <x-label value="Client secret (Guías Remisión):" />
                    <template x-if="sendmode > 0">
                        <x-input class="block w-full" x-model="clientsecret" placeholder="Ingrese client secret..." />
                    </template>
                    <template x-if="sendmode <= 0">
                        <x-disabled-text x-text="clientsecret" text="" />
                    </template>
                    <x-jet-input-error for="empresa.clientsecret" />
                </div>
                <div class="w-full">
                    <x-label value="Clave certificado digital :" />
                    <template x-if="sendmode > 0">
                        <x-input class="block w-full" x-model="passwordcert" placeholder="Contraseña del certificado..."
                            type="password" />
                    </template>
                    <template x-if="sendmode <= 0">
                        <x-disabled-text text="••••••••" />
                    </template>
                    <x-jet-input-error for="empresa.passwordcert" />
                </div>
            </div>
            <div class="w-full">
                <div class="relative w-full xs:max-w-xs text-center">
                    @if (isset($cert))
                        <x-icon-file-upload type="filesuccess" :uploadname="$cert->getClientOriginalName()" class="w-36 h-auto text-gray-300" />
                    @else
                        @if ($empresa->cert)
                            <x-icon-file-upload type="filesuccess" :uploadname="$empresa->cert"
                                class="w-36 h-auto text-gray-300" />
                        @else
                            <x-icon-file-upload type="code" text="PFX" class="w-36 h-auto text-gray-300" />
                        @endif
                    @endif

                    <div class="w-full flex gap-1 flex-wrap justify-center">
                        <x-input-file :for="$idcert" :titulo="$empresa->cert ? 'CAMBIAR CERTIFICADO DIGITAL' : 'CARGAR CERTIFICADO DIGITAL'" wire:loading.remove class="!rounded-lg">
                            <input type="file" class="hidden" wire:model="cert" id="{{ $idcert }}"
                                accept=".pfx" />
                        </x-input-file>

                        @if (isset($cert))
                            <x-button class="inline-flex !rounded-lg" wire:loading.attr="disabled"
                                wire:click="clearCert">
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
                        @endif

                        @if (!isset($cert) && $empresa->cert)
                            <x-button wire:click="deletecert({{ $empresa->id }})" wire:loading.attr="disabled"
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
                                ELIMINAR CERTIFICADO DIGITAL
                            </x-button>
                        @endif
                    </div>
                    <x-jet-input-error for="cert" class="text-center" />
                </div>
            </div>
            <div class="w-full flex justify-end">
                <x-button type="submit">{{ __('ACTUALIZAR') }}</x-button>
            </div>
        </form>
    </x-form-card>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('configuracion', () => ({
                sendmode: @entangle('empresa.sendmode').defer,
                afectacionigv: @entangle('empresa.afectacionigv').defer,
                usuariosol: @entangle('empresa.usuariosol').defer,
                clavesol: @entangle('empresa.clavesol').defer,
                clientid: @entangle('empresa.clientid').defer,
                clientsecret: @entangle('empresa.clientsecret').defer,
                passwordcert: @entangle('empresa.passwordcert').defer,

                init() {
                    this.$watch("sendmode", value => {
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
                }
            }))
        });


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
                    // this.usuariosol = '';
                    // this.clavesol = '';
                    // this.clientid = '';
                    // this.clientsecret = '';
                    // this.passwordcert = '';
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
    </script>
</div>
