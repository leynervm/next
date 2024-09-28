<div>
    <x-button-next titulo="Registrar" wire:click="$set('open', true)">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" x2="12" y1="5" y2="19" />
            <line x1="5" x2="19" y1="12" y2="12" />
        </svg>
    </x-button-next>
    <x-jet-dialog-modal wire:model="open" maxWidth="3xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Nuevo cliente') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save" class="relative" x-data="data">
                <div class="w-full grid grid-cols-1 sm:grid-cols-3 gap-2">
                    <div class="w-full">
                        <x-label value="DNI / RUC :" />
                        @if ($exists)
                            <div class="w-full inline-flex relative">
                                <x-disabled-text :text="$document" class="w-full block" />
                                <x-button-close-modal class="btn-desvincular" wire:click="limpiarcliente"
                                    wire:loading.attr="disabled" />
                            </div>
                        @else
                            <div class="w-full inline-flex gap-1">
                                <x-input class="block flex-1 w-full inpunt-number-none" x-model="document"
                                    @keyup="togglecontact($event.target.value)" wire:model.defer="document"
                                    wire:keydown.enter.prevent="searchclient"
                                    onkeypress="return validarNumero(event, 11)" />
                                <x-button-add class="px-2" wire:click="searchclient" wire:loading.attr="disabled">
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
                    <div class="w-full sm:col-span-2">
                        <x-label value="Cliente (Razón Social) :" />
                        <x-input class="block w-full" wire:model.defer="name" />
                        <x-jet-input-error for="name" />
                    </div>

                    <div class="w-full sm:col-span-3">
                        <x-label value="Dirección, calle, avenida :" />
                        <x-input class="block w-full" wire:model.defer="direccion"
                            placeholder="Dirección del cliente..." />
                        <x-jet-input-error for="direccion" />
                    </div>

                    <div class="w-full sm:col-span-2 relative">
                        <x-label value="Ubigeo :" />
                        <div class="relative" x-init="select2Ubigeo" wire:ignore>
                            <x-select class="block w-full" x-ref="select" wire:model.defer="ubigeo_id"
                                id="ubigeoclient_id" data-minimum-results-for-search="3" data-dropdown-parent="null">
                                <x-slot name="options">
                                    @if (count($ubigeos))
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
                        <x-jet-input-error for="ubigeo_id" />
                    </div>

                    <div class="w-full">
                        <x-label value="Correo :" />
                        <x-input class="block w-full" wire:model.defer="email" type="email" />
                        <x-jet-input-error for="email" />
                    </div>

                    <div class="w-full">
                        <x-label value="Género :" />
                        <div class="relative" id="parentsexo_id" x-init="select2Sexo" wire:ignore>
                            <x-select class="block w-full" id="sexo_id" x-ref="selectsexo"
                                data-dropdown-parent="null">
                                <x-slot name="options">
                                    <option value="E">EMPRESARIAL</option>
                                    <option value="M">MASCULINO</option>
                                    <option value="F">FEMENINO</option>
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="sexo" />
                    </div>

                    <div class="w-full">
                        <x-label value="Fecha nacimiento :" />
                        <x-input type="date" class="block w-full" wire:model.defer="nacimiento" />
                        <x-jet-input-error for="nacimiento" />
                    </div>

                    @if (Module::isEnabled('Ventas'))
                        @if (mi_empresa()->usarlista())
                            <div class="w-full">
                                <x-label value="Lista precio :" />
                                <div class="w-full relative" x-data="{ pricetype_id: @entangle('pricetype_id').defer }" x-init="pricetype"
                                    wire:ignore>
                                    <x-select class="block w-full iconselect" x-ref="selectpricetype"
                                        id="pricetype_id" data-dropdown-parent="null">
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
                        @endif
                    @endif

                    <div class="w-full">
                        <x-label value="Teléfono :" />
                        <x-input class="block w-full input-number-none" type="number" wire:model.defer="telefono"
                            onkeypress="return validarNumero(event, 9)" />
                        <x-jet-input-error for="telefono" />
                    </div>

                    <div class="w-full" x-show="contact" x-cloak style="display: none;">
                        <x-label-check for="addcontacto">
                            <x-input x-model="addcontacto" type="checkbox" id="addcontacto" />
                            AGREGAR CONTACTO
                        </x-label-check>
                    </div>
                </div>

                <x-form-card x-show="addcontacto" titulo="AGREGAR CONTACTO">
                    <div class="w-full sm:grid grid-cols-3 gap-2">
                        <div class="w-full">
                            <x-label value="DNI :" />
                            <div class="w-full inline-flex gap-1">
                                <x-input class="block w-full flex-1 input-number-none"
                                    wire:model.defer="documentContact" wire:keydown.enter.prevent="searchcontacto"
                                    onkeypress="return validarNumero(event, 8)" type="number" />
                                <x-button-add class="px-2" wire:click="searchcontacto"
                                    wire:loading.attr="disabled">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <circle cx="11" cy="11" r="8" />
                                        <path d="m21 21-4.3-4.3" />
                                    </svg>
                                </x-button-add>
                            </div>
                            <x-jet-input-error for="documentContact" />
                        </div>
                        <div class="w-full sm:col-span-2 mt-2 sm:mt-0">
                            <x-label value="Nombres contacto :" />
                            <x-input class="block w-full" wire:model.defer="nameContact" />
                            <x-jet-input-error for="nameContact" />
                        </div>
                        <div class="w-full mt-2 sm:mt-0">
                            <x-label value="Teléfono :" />
                            <x-input class="block w-full input-number-none" type="number"
                                wire:model.defer="telefonoContact" onkeypress="return validarNumero(event, 9)" />
                            <x-jet-input-error for="telefonoContact" />
                        </div>
                    </div>
                </x-form-card>

                @if (module::isEnabled('Marketplace'))
                    @if ($user)
                        <p class="text-xs text-colorsubtitleform my-2 mt-8">
                            Actualmente el cliente está registrado en nuestra tienda web...</p>
                        <x-simple-card class="w-48 mt-3 flex flex-col gap-1 rounded-xl cursor-default p-3 py-5">
                            <h1 class="font-semibold text-sm leading-4 text-primary text-center">
                                {{ $user->name }}</h1>

                            <x-label class="font-semibold text-center" value="EMAIL" />
                            <p class="text-xs text-center">{{ $user->email }}</p>
                        </x-simple-card>
                    @endif
                @endif


                <div class="w-full flex flex-wrap gap-2 pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('Save') }}</x-button>
                    <x-button wire:click="save(true)" wire:loading.attr="disabled">
                        {{ __('Save and close') }}</x-button>
                </div>

                <div wire:loading.flex class="loading-overlay fixed hidden">
                    <x-loading-next />
                </div>

            </form>
        </x-slot>
    </x-jet-dialog-modal>


    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('data', () => ({
                contact: false,
                document: @entangle('document').defer,
                ubigeo_id: @entangle('ubigeo_id').defer,
                sexo: @entangle('sexo').defer,
                addcontacto: @entangle('addcontacto').defer,

                togglecontact(value) {
                    if (value.trim().length == 11) {
                        this.contact = true;
                    } else {
                        this.contact = false;
                        this.addcontacto = false;
                    }
                }
            }))
        });

        function select2Ubigeo() {
            this.select = $(this.$refs.select).select2();
            this.select.val(this.ubigeo_id).trigger("change");
            this.select.on("select2:select", (event) => {
                this.ubigeo_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("ubigeo_id", (value) => {
                this.select.val(value).trigger("change");
            });
        }

        function select2Sexo() {
            this.selectS = $(this.$refs.selectsexo).select2();
            this.selectS.val(this.sexo).trigger("change");
            this.selectS.on("select2:select", (event) => {
                this.sexo = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("sexo", (value) => {
                this.selectS.val(value).trigger("change");
            });
        }

        function pricetype() {
            this.selectP = $(this.$refs.selectpricetype).select2();
            this.selectP.val(this.pricetype_id).trigger("change");
            this.selectP.on("select2:select", (event) => {
                this.pricetype_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("pricetype_id", (value) => {
                this.selectP.val(value).trigger("change");
            });
        }
    </script>
</div>
