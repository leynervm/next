<div>
    <div wire:loading.flex class="fixed loading-overlay hidden">
        <x-loading-next />
    </div>

    <form wire:submit.prevent="save" class="w-full flex flex-col gap-8" x-data="{ addcontacto: @entangle('addcontacto').defer }">
        <x-form-card titulo="REGISTRAR PROVEEDOR">
            <div class="w-full grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2">
                <div class="w-full xs:col-span-2 sm:col-span-1 lg:col-span-2">
                    <x-label value="RUC :" />
                    <div class="w-full inline-flex gap-1">
                        <x-input class="block w-full flex-1 input-number-none" type="number" wire:model.defer="document"
                            wire:keydown.enter.prevent="searchclient" onkeypress="return validarNumero(event, 11)" />
                        <x-button-add class="px-2 flex-shrink-0" wire:click="searchclient" wire:loading.attr="disabled"
                            wire:target="searchclient">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8" />
                                <path d="m21 21-4.3-4.3" />
                            </svg>
                        </x-button-add>
                    </div>
                    <x-jet-input-error for="document" />
                </div>

                <div class="w-full xs:col-span-2 sm:col-span-2">
                    <x-label value="Razón Social :" />
                    <x-input class="block w-full" wire:model.defer="name" />
                    <x-jet-input-error for="name" />
                </div>

                <div class="w-full xs:col-span-2 sm:col-span-3 lg:col-span-2">
                    <x-label value="Dirección, calle, avenida :" />
                    <x-input class="block w-full" wire:model.defer="direccion" />
                    <x-jet-input-error for="direccion" />
                </div>

                <div class="w-full xs:col-span-2 sm:col-span-2">
                    <x-label value="Ubigeo :" />
                    <div id="parentubigeoproveedor_id" class="relative" x-data="{ ubigeo_id: @entangle('ubigeo_id').defer }" x-init="select2UbigeoAlpine"
                        wire:ignore>
                        <x-select class="block w-full" x-ref="select" wire:model.defer="ubigeo_id"
                            id="ubigeoproveedor_id" data-minimum-results-for-search="3">
                            <x-slot name="options">
                                @if (count($ubigeos))
                                    @foreach ($ubigeos as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->region }} / {{ $item->provincia }} / {{ $item->distrito }} /
                                            {{ $item->ubigeo_reniec }}
                                        </option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="ubigeo_id" />
                </div>

                <div class="w-full">
                    <x-label value="Tipo proveedor :" />
                    <div id="parentproveedortype_id" class="relative" x-data="{ proveedortype_id: @entangle('proveedortype_id').defer }" x-init="select2TypeproveedorAlpine">
                        <x-select class="block w-full" x-ref="select" wire:model.defer="proveedortype_id"
                            id="proveedortype_id">
                            <x-slot name="options">
                                @if (count($proveedortypes))
                                    @foreach ($proveedortypes as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="proveedortype_id" />
                </div>

                <div class="w-full">
                    <x-label value="Teléfono :" />
                    <x-input class="block w-full input-number-none" wire:model.defer="telefono" type="number"
                        onkeypress="return validarNumero(event, 9)" />
                    <x-jet-input-error for="telefono" />
                </div>

                <div class="w-full">
                    <x-label value="Correo :" />
                    <x-input class="block w-full" wire:model.defer="email" placeholder="Correo del proveedor..."
                        type="email" />
                    <x-jet-input-error for="email" />
                </div>

                <div class="w-full xs:col-span-2 sm:col-span-2">
                    <x-label-check for="addcontacto">
                        <x-input x-model="addcontacto" type="checkbox" id="addcontacto" />
                        AGREGAR CONTACTO
                    </x-label-check>
                </div>
            </div>
        </x-form-card>

        <x-form-card x-show="addcontacto" x-cloak style="display:none;" titulo="AGREGAR CONTACTO">
            <div class="w-full sm:grid sm:grid-cols-3 gap-2 rounded">
                <div class="w-full">
                    <x-label value="DNI :" />
                    <div class="w-full inline-flex gap-1">
                        <x-input class="block w-full flex-1 input-number-none" wire:model.defer="document2"
                            type="number" wire:keydown.enter.prevent="searchcontacto"
                            onkeypress="return validarNumero(event, 8)" />
                        <x-button-add class="px-2 flex-shrink-0" wire:click="searchcontacto"
                            wire:loading.attr="disabled" wire:target="searchcontacto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8" />
                                <path d="m21 21-4.3-4.3" />
                            </svg>
                        </x-button-add>
                    </div>
                    <x-jet-input-error for="document2" />
                </div>

                <div class="w-full sm:col-span-2 mt-2 sm:mt-0">
                    <x-label value="Nombres representante :" />
                    <x-input class="block w-full" wire:model.defer="name2" />
                    <x-jet-input-error for="name2" />
                </div>

                <div class="w-full mt-2 sm:mt-0">
                    <x-label value="Teléfono :" />
                    <x-input class="block w-full input-number-none" wire:model.defer="telefono2" type="number"
                        onkeypress="return validarNumero(event, 9)" />
                    <x-jet-input-error for="telefono2" />
                </div>
            </div>
        </x-form-card>

        <div class="w-full flex pt-4 justify-end gap-2">
            <x-button type="button" wire:click="save(false)" wire:loading.attr="disabled">
                {{ __('Save') }}</x-button>
            <x-button type="submit" wire:loading.attr="disabled">
                {{ __('Save and close') }}</x-button>
        </div>
    </form>

    <script>
        function select2UbigeoAlpine() {
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

        function select2TypeproveedorAlpine() {
            this.selectTP = $(this.$refs.select).select2();
            this.selectTP.val(this.proveedortype_id).trigger("change");
            this.selectTP.on("select2:select", (event) => {
                this.proveedortype_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("proveedortype_id", (value) => {
                this.selectTP.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectTP.select2().val(this.proveedortype_id).trigger('change');
            });
        }

        // function consultasunat(document) {
        //     $('#searchingclient').toggleClass('hidden');
        //     axios.get('/admin/consulta-sunat/' + document, {
        //             responseType: 'json'
        //         })
        //         .then(function(response) {
        //             console.log(response);
        //             if (response.status == 200) {
        //                 console.log(response.data.original);
        //                 $('#nameproveedor').val(response.data.original.name);
        //                 $('#direccionproveedor').val(response.data.direccion);
        //                 $('#ubigeoproveedor_id').val(response.data.ubigeo);
        //                 $('#telefonoproveedor').val(response.data.telefono);
        //                 $('#emailproveedor').val(response.data.email);
        //                 $('#searchingclient').toggleClass('hidden');
        //             }
        //         })
        //         .catch(function(error) {
        //             console.log(error);
        //         });
        // }
    </script>
</div>
