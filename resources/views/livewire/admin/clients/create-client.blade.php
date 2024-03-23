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
            <x-button-close-modal wire:click="$toggle('open')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save" class="relative" x-data="data">
                <div class="w-full sm:grid grid-cols-3 gap-2">
                    <div class="w-full">
                        <x-label value="DNI / RUC :" />
                        @if ($exists)
                            <div class="w-full inline-flex relative">
                                <x-disabled-text :text="$document" class="w-full block" />
                                <x-button-close-modal
                                    class="hover:animate-none !text-red-500 hover:!bg-transparent focus:!bg-transparent hover:!ring-0 focus:!ring-0 absolute right-0 top-1"
                                    wire:click="limpiarcliente" wire:loading.attr="disabled" />
                            </div>
                        @else
                            <div class="w-full inline-flex gap-1">
                                <x-input class="block w-full prevent" x-model="document"
                                    @keyup="togglecontact($event.target.value)" wire:model.defer="document"
                                    wire:keydown.enter="searchclient" onkeypress="return validarNumero(event, 11)"
                                    onkeydown="disabledEnter(event)" />
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
                    <div class="w-full sm:col-span-2 mt-2 sm:mt-0">
                        <x-label value="Cliente (Razón Social) :" />
                        <x-input class="block w-full" wire:model.defer="name"
                            placeholder="Nombres / razón social del cliente" />
                        <x-jet-input-error for="name" />
                    </div>

                    <div class="w-full sm:col-span-3 mt-2 sm:mt-0">
                        <x-label value="Dirección, calle, avenida :" />
                        <x-input class="block w-full" wire:model.defer="direccion"
                            placeholder="Dirección del cliente..." />
                        <x-jet-input-error for="direccion" />
                    </div>

                    <div class="w-full sm:col-span-2 mt-2 sm:mt-0 relative">
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

                    <div class="w-full mt-2 sm:mt-0">
                        <x-label value="Correo :" />
                        <x-input class="block w-full" wire:model.defer="email" placeholder="Correo del cliente..." />
                        <x-jet-input-error for="email" />
                    </div>
                    <div class="w-full mt-2 sm:mt-0">
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

                    <div class="w-full mt-2 sm:mt-0">
                        <x-label value="Fecha nacimiento :" />
                        <x-input type="date" class="block w-full" wire:model.defer="nacimiento" />
                        <x-jet-input-error for="nacimiento" />
                    </div>

                    @if (Module::isEnabled('Ventas'))
                        @if (mi_empresa()->usarlista())
                            <div class="w-full mt-2 sm:mt-0">
                                <x-label value="Lista precio :" />
                                <x-select class="block w-full iconselect" wire:model.defer="pricetype_id"
                                    id="pricetype_id" data-dropdown-parent="null">
                                    <x-slot name="options">
                                        @if (count($pricetypes))
                                            @foreach ($pricetypes as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        @endif
                                    </x-slot>
                                </x-select>
                                <x-jet-input-error for="pricetype_id" />
                            </div>
                        @endif
                    @endif

                    <div class="w-full mt-2 sm:mt-0">
                        <x-label value="Teléfono :" />
                        <x-input class="block w-full" wire:model.defer="telefono" placeholder="+51 999 999 999"
                            maxlength="9" onkeypress="return validarNumero(event, 9)" />
                        <x-jet-input-error for="telefono" />
                    </div>
                </div>

                <div class="animate__animated animate__fadeInDown" x-show="contact">
                    <x-title-next titulo="Contacto" class="my-3" />

                    <div class="w-full sm:grid grid-cols-3 gap-2">
                        <div class="w-full">
                            <x-label value="DNI :" />
                            <div class="w-full inline-flex gap-1">
                                <x-input class="block w-full" wire:model.defer="documentContact"
                                    wire:keydown.enter="searchcontacto" onkeypress="return validarNumero(event, 8)"
                                    type="number" onkeydown="disabledEnter(event)" />
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
                            <x-input class="block w-full" wire:model.defer="nameContact"
                                placeholder="Nombres del contacto..." />
                            <x-jet-input-error for="nameContact" />
                        </div>
                        <div class="w-full mt-2 sm:mt-0">
                            <x-label value="Teléfono :" />
                            <x-input class="block w-full" wire:model.defer="telefonoContact" type="number"
                                onkeypress="return validarNumero(event, 9)" />
                            <x-jet-input-error for="telefonoContact" />
                        </div>
                    </div>
                </div>

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('REGISTRAR') }}
                    </x-button>
                </div>

                <div wire:loading.flex wire:target="searchclient,searchcontacto"
                    class="loading-overlay rounded hidden">
                    <x-loading-next />
                </div>

            </form>
        </x-slot>
    </x-jet-dialog-modal>


    <script>
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


        document.addEventListener('alpine:init', () => {
            Alpine.data('data', () => ({
                contact: false,
                document: @entangle('document').defer,
                ubigeo_id: @entangle('ubigeo_id').defer,
                sexo: @entangle('sexo').defer,

                togglecontact(value) {
                    if (value.trim().length == 11) {
                        this.contact = true;
                    } else {
                        this.contact = false;
                    }
                }
            }))
        });
    </script>
</div>
