<div>
    <div class="w-full relative flex flex-col gap-8">
        <x-form-card titulo="DATOS PROVEEDOR">
            <form wire:submit.prevent="update" class="w-full flex flex-col gap-2 bg-body p-3 rounded">
                <div class="w-full sm:grid sm:grid-cols-3 gap-2 bg-body p-3 rounded">
                    <div class="w-full">
                        <x-label value="RUC :" />
                        <div class="w-full inline-flex gap-1">
                            <x-disabled-text :text="$proveedor->document" class="w-full flex-1" />
                            <x-button-add class="px-2"
                                wire:click="searchclient('proveedor.document', 'proveedor.name')"
                                wire:loading.attr="disabled">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8" />
                                    <path d="m21 21-4.3-4.3" />
                                </svg>
                            </x-button-add>
                        </div>
                        <x-jet-input-error for="proveedor.document" />
                    </div>

                    <div class="w-full sm:col-span-2">
                        <x-label value="Razón Social :" />
                        <x-input class="block w-full" wire:model.defer="proveedor.name"
                            placeholder="Razón social del proveedor" />
                        <x-jet-input-error for="proveedor.name" />
                    </div>

                    <div class="w-full sm:col-span-3">
                        <x-label value="Dirección, calle, avenida :" />
                        <x-input class="block w-full" wire:model.defer="proveedor.direccion"
                            placeholder="Dirección del cliente..." />
                        <x-jet-input-error for="proveedor.direccion" />
                    </div>

                    <div class="w-full sm:col-span-2">
                        <x-label value="Ubigeo :" />
                        <div id="parenteditubigeoproveedor_id" class="relative" x-data="{ ubigeo_id: @entangle('proveedor.ubigeo_id').defer }"
                            x-init="select2UbigeoAlpine" wire:ignore>
                            <x-select class="block w-full" wire:model.defer="proveedor.ubigeo_id"
                                id="editubigeoproveedor_id" x-ref="select" data-minimum-results-for-search="3">
                                <x-slot name="options">
                                    @if (count($ubigeos))
                                        @foreach ($ubigeos as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->region }} / {{ $item->provincia }} / {{ $item->distrito }}
                                            </option>
                                        @endforeach
                                    @endif
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="proveedor.ubigeo_id" />
                    </div>

                    <div class="w-full">
                        <x-label value="Tipo proveedor :" />
                        <div id="parenteditproveedortype_id" class="relative" x-data="{ proveedortype_id: @entangle('proveedor.proveedortype_id').defer }"
                            x-init="select2TypeproveedorAlpine" wire:ignore>
                            <x-select class="block w-full" x-ref="select" id="editproveedortype_id">
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
                        <x-jet-input-error for="proveedor.proveedortype_id" />
                    </div>

                    <div class="w-full">
                        <x-label value="Correo :" />
                        <x-input class="block w-full" wire:model.defer="proveedor.email"
                            placeholder="Correo del cliente..." type="email" />
                        <x-jet-input-error for="proveedor.email" />
                    </div>
                </div>

                <div class="w-full flex gap-2 justify-end">
                    @can('admin.proveedores.delete')
                        <x-button-secondary onclick="confirmDelete({{ $proveedor }})"
                            wire:loading.attr="disabled">ELIMINAR</x-button-secondary>
                    @endcan

                    <x-button type="submit" wire:loading.attr="disabled" wire:target="update">
                        {{ __('ACTUALIZAR') }}
                    </x-button>
                </div>
            </form>
        </x-form-card>

        @can('admin.proveedores.phones')
            <x-form-card titulo="TELÉFONOS PROVEEDOR">
                @if (count($proveedor->telephones))
                    <div class="w-full flex flex-wrap gap-1">
                        @foreach ($proveedor->telephones as $item)
                            <x-minicard-phone :phone="$item->phone">
                                @can('admin.proveedores.phones.edit')
                                    <x-slot name="footer">
                                        <x-button-edit wire:click="editphone({{ $item->id }})" wire:loading.attr="disabled"
                                            wire:key="edphonp_{{ $item->id }}" />
                                        <x-button-delete onclick="confirmDeletephone({{ $item }})"
                                            wire:loading.attr="disabled" wire:key="dephonp_{{ $item->id }}" />
                                    </x-slot>
                                @endcan
                            </x-minicard-phone>
                        @endforeach
                    </div>
                @endif

                @can('admin.proveedores.phones.edit')
                    <div class="w-full pt-4 flex justify-end">
                        <x-button wire:click="openmodalphone" wire:loading.attr="disabled">
                            AGREGAR TELEFONO
                        </x-button>
                    </div>
                @endcan
            </x-form-card>
        @endcan

        @can('admin.proveedores.contacts')
            <x-form-card titulo="CONTACTOS" subtitulo="Nos permitirá contactarse con el proveedor.">
                <div class="w-full h-full flex flex-col">
                    @if (count($proveedor->contacts))
                        <div class="w-full flex flex-col gap-2">
                            @foreach ($proveedor->contacts as $item)
                                <div class="w-full text-xs bg-body rounded p-2 shadow-md shadow-shadowform">

                                    <p class="text-colorsubtitleform text-[10px] font-medium">
                                        <span class="text-colortitleform">({{ $item->document }}) </span>
                                        {{ $item->name }}
                                    </p>

                                    @if ($item->telephone)
                                        <x-span-text :text="'TELÉFONO :' . formatTelefono($item->telephone->phone)" class="leading-3 !tracking-normal" />
                                    @endif

                                    @can('admin.proveedores.contacts.edit')
                                        <div class="w-full flex flex-wrap gap-1 items-end justify-end mt-1">
                                            <x-button-edit wire:click="editrepresentante({{ $item->id }})"
                                                wire:loading.attr="disabled" wire:key="eprovcont_{{ $item->id }}" />
                                            <x-button-delete onclick="confirmDeleterepresentante({{ $item }})"
                                                wire:loading.attr="disabled" wire:key="dprovcont_{{ $item->id }}" />
                                        </div>
                                    @endcan
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @can('admin.proveedores.contacts.edit')
                        <div class="w-full flex pt-4 justify-end">
                            <x-button wire:click="openmodalrepresentante" wire:loading.attr="disabled">
                                AGREGAR REPRESENTANTE
                            </x-button>
                        </div>
                    @endcan
                </div>
            </x-form-card>
        @endcan

        <div wire:loading.flex class="loading-overlay fixed hidden">
            <x-loading-next />
        </div>
    </div>


    <x-jet-dialog-modal wire:model="openrepresentante" maxWidth="3xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Representante proveedor') }}
        </x-slot>

        <x-slot name="content">
            <form x-data="{ searchingcontacto: false }" wire:submit.prevent="saverepresentante"
                class="relative w-full flex flex-col gap-2">

                <div class="w-full grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
                    <div class="w-full">
                        <x-label value="DNI :" />
                        <div class="w-full flex gap-1">
                            <x-input class="block w-full flex-1 prevent" wire:model.defer="document2" maxlength="8"
                                wire:keydown.enter="searchclient('document2', 'name2')"
                                onkeypress="return validarNumero(event, 8)" onkeydown="disabledEnter(event)" />
                            <x-button-add class="px-2 flex-shrink-0" wire:click="searchclient('document2', 'name2')"
                                wire:loading.attr="disabled">
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

                    <div class="w-full md:col-span-2">
                        <x-label value="Nombres representante :" />
                        <x-input class="block w-full" wire:model.defer="name2" />
                        <x-jet-input-error for="name2" />
                    </div>

                    <div class="w-full">
                        <x-label value="Teléfono :" />
                        <x-input class="block w-full input-number-none" wire:model.defer="telefono2" type="number"
                            onkeypress="return validarNumero(event, 9)" min="0" />
                        <x-jet-input-error for="telefono2" />
                    </div>
                </div>

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled" wire:target="saverepresentante">
                        REGISTRAR</x-button>
                </div>

                <div wire:loading.flex class="loading-overlay fixed hidden">
                    <x-loading-next />
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <x-jet-dialog-modal wire:model="openphone" maxWidth="xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Teléfono') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="savephone">
                <div class="w-full">
                    <x-label value="Teléfono :" />
                    <x-input class="block w-full" wire:model.defer="newtelefono" type="number"
                        onkeypress="return validarNumero(event, 9)" />
                    <x-jet-input-error for="newtelefono" />
                </div>

                <div class="w-full flex flex-row pt-4 gap-2 justify-end text-right">
                    <x-button type="submit" wire:loading.attr="disabled" wire:target="savephone">
                        {{ __('Save') }}</x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

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
        }

        function confirmDelete(proveedor) {
            swal.fire({
                title: 'Eliminar proveedor con RUC ' + proveedor.document,
                text: "Se eliminará un registro de la base de datos",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.delete(proveedor.id);
                }
            })
        }

        function confirmDeletephone(phone) {
            swal.fire({
                title: 'Eliminar número telefónico ' + phone.phone,
                text: "Se eliminará un registro de la base de datos",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.deletephone(phone.id);
                }
            })
        }

        function confirmDeleterepresentante(contact) {
            swal.fire({
                title: 'Eliminar contacto ' + contact.name,
                text: "Se eliminará un registro de la base de datos",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.deleterepresentante(contact.id);
                }
            })
        }
    </script>
</div>
