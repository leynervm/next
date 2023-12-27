<div>
    <div class="w-full relative flex flex-col gap-8">
        <x-form-card titulo="DATOS PROVEEDOR" subtitulo="Complete todos los campos para registrar un nuevo proveedor.">
            <form wire:submit.prevent="update" class="w-full flex flex-col gap-2 bg-body p-3 rounded">
                <div class="w-full sm:grid sm:grid-cols-3 gap-2 bg-body p-3 rounded">
                    <div class="w-full">
                        <x-label value="RUC :" />
                        <div class="w-full inline-flex gap-1">
                            <x-disabled-text :text="$proveedor->document" class="w-full" />
                            {{-- <x-input class="block w-full prevent" wire:model.defer="proveedor.document"
                                wire:keydown.enter="searchclient" /> --}}
                            <x-button-add class="px-2" wire:click="searchclient" wire:loading.attr="disabled"
                                wire:target="searchclient">
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
                            placeholder="Correo del cliente..." />
                        <x-jet-input-error for="proveedor.email" />
                    </div>
                </div>

                <div class="w-full flex gap-2 justify-end">
                    <x-button-secondary wire:click="$emit('proveedor.confirmDelete', {{ $proveedor }})"
                        wire:loading.attr="disabled">ELIMINAR PROVEEDOR</x-button-secondary>

                    <x-button type="submit" wire:loading.attr="disabled" wire:target="update">
                        {{ __('ACTUALIZAR') }}
                    </x-button>
                </div>

            </form>
        </x-form-card>

        <x-form-card titulo="TELÉFONOS CLIENTE">
            @if (count($proveedor->telephones))
                <div class="w-full flex flex-wrap gap-1">
                    @foreach ($proveedor->telephones as $item)
                        <x-minicard-phone phone="{{ $item->phone }}">
                            <x-slot name="footer">
                                <x-button-edit wire:click="editphone({{ $item->id }})"
                                    wire:loading.attr="disabled" />
                                <x-button-delete wire:click="$emit('proveedor.confirmDeletephone',{{ $item }})"
                                    wire:loading.attr="disabled" />
                            </x-slot>
                        </x-minicard-phone>
                    @endforeach
                </div>
            @endif

            <div class="w-full pt-4 flex justify-end">
                <x-button wire:click="openmodalphone" wire:loading.attr="disabled">
                    AGREGAR TELEFONO
                </x-button>
            </div>
        </x-form-card>

        <x-form-card titulo="REPRESENTANTES"
            subtitulo="Complete todos los campos, nos permitirá contactarse con la empresa.">
            <div class="w-full h-full flex flex-col">
                @if (count($proveedor->contacts))
                    <div class="w-full flex flex-col gap-2">
                        @foreach ($proveedor->contacts as $item)
                            <div class="w-full text-xs bg-body rounded p-2 shadow-md shadow-shadowform">

                                <p class="text-colorsubtitleform text-[10px] font-semibold">
                                    <span class="text-colortitleform">({{ $item->document }}) </span>
                                    {{ $item->name }}
                                </p>

                                @if ($item->telephone)
                                    <x-span-text :text="'TELÉFONO :' . $item->telephone->phone" class="leading-3" />
                                @endif

                                <div class="w-full flex flex-wrap gap-1 items-end justify-end mt-1">
                                    <x-button-edit wire:click="editrepresentante({{ $item->id }})"
                                        wire:loading.attr="disabled" />
                                    <x-button-delete
                                        wire:click="$emit('proveedor.confirmDeleterepresentante',{{ $item }})"
                                        wire:loading.attr="disabled" />
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="w-full flex pt-4 justify-end">
                    <x-button wire:click="openmodalrepresentante" wire:loading.attr="disabled">
                        AGREGAR REPRESENTANTE
                    </x-button>
                </div>
            </div>
        </x-form-card>

        <div wire:loading.flex class="loading-overlay rounded hidden">
            <x-loading-next />
        </div>
    </div>


    <x-jet-dialog-modal wire:model="openrepresentante" maxWidth="3xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Representante proveedor') }}
            <x-button-close-modal wire:click="$toggle('openrepresentante')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form x-data="{ searchingcontacto: false }" wire:submit.prevent="saverepresentante"
                class="relative w-full flex flex-col gap-2">

                <div class="w-full flex flex-wrap sm:flex-nowrap gap-2">
                    <div class="w-full sm:w-64 flex-shrink-0">
                        <x-label value="DNI :" />
                        <div class="w-full inline-flex gap-1">
                            <x-input class="block w-full prevent" wire:model.defer="document2" maxlength="8"
                                wire:keydown.enter="searchcontacto" />
                            <x-button-add class="px-2" wire:click="searchcontacto" wire:loading.attr="disabled"
                                wire:target="searchcontacto">
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
                    <div class="w-full flex-shrink-1">
                        <x-label value="Nombres representante :" />
                        <x-input class="block w-full" wire:model.defer="name2"
                            placeholder="Nombres del representante..." />
                        <x-jet-input-error for="name2" />
                    </div>
                </div>

                <div class="w-full sm:w-64">
                    <x-label value="Teléfono :" />
                    <x-input class="block w-full" wire:model.defer="telefono2" placeholder="+51 999 999 999"
                        maxlength="9" />
                    <x-jet-input-error for="telefono2" />
                </div>

                <div class="w-full flex flex-row pt-4 gap-2 justify-end text-right">
                    <x-button type="submit" wire:loading.attr="disabled" wire:target="saverepresentante">
                        @if ($contact)
                            {{ __('ACTUALIZAR') }}
                        @else
                            {{ __('REGISTRAR') }}
                        @endif
                    </x-button>
                </div>

                <div wire:loading.flex class="loading-overlay rounded hidden">
                    <x-loading-next />
                </div>

            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <x-jet-dialog-modal wire:model="openphone" maxWidth="xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Teléfono') }}
            <x-button-close-modal wire:click="$toggle('openphone')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="savephone">
                <div class="w-full">
                    <x-label value="Teléfono :" />
                    <x-input class="block w-full" wire:model.defer="newtelefono" placeholder="+51 999 999 999"
                        maxlength="9" />
                    <x-jet-input-error for="newtelefono" />
                </div>

                <div class="w-full flex flex-row pt-4 gap-2 justify-end text-right">
                    <x-button type="submit" wire:loading.attr="disabled" wire:target="savephone">
                        @if ($telephone)
                            {{ __('ACTUALIZAR') }}
                        @else
                            {{ __('REGISTRAR') }}
                        @endif
                    </x-button>
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

        // $('#editubigeoproveedor_id').select2()
        //     .on("change", function(e) {
        //         $('.select2').attr("disabled", true);
        //         @this.set('proveedor.ubigeo_id', e.target.value);
        //     }).on('select2:open', function(e) {
        //         const evt = "scroll.select2";
        //         $(e.target).parents().off(evt);
        //         $(window).off(evt);
        //     });

        // $('#editproveedortype_id').select2()
        //     .on("change", function(e) {
        //         $('.select2').attr("disabled", true);
        //         @this.set('proveedor.proveedortype_id', e.target.value);
        //     }).on('select2:open', function(e) {
        //         const evt = "scroll.select2";
        //         $(e.target).parents().off(evt);
        //         $(window).off(evt);
        //     });

        document.addEventListener('livewire:load', () => {
            Livewire.on('proveedor.confirmDelete', data => {
                swal.fire({
                    title: 'Eliminar proveedor con RUC : ' + data.document,
                    text: "Se eliminará un registro de la base de datos",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.delete(data.id);
                    }
                })
            });

            Livewire.on('proveedor.confirmDeletephone', data => {
                swal.fire({
                    title: 'Eliminar número telefónico: ' + data.phone,
                    text: "Se eliminará un registro de la base de datos",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.deletephone(data.id);
                    }
                })
            });

            Livewire.on('proveedor.confirmDeleterepresentante', data => {
                swal.fire({
                    title: 'Eliminar representate con nombres: ' + data.name,
                    text: "Se eliminará un registro de la base de datos",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.deleterepresentante(data.id);
                    }
                })
            });

            // document.addEventListener('render-select2-editproveedores', () => {
            //     $('.select2').select2();
            // });
        })
    </script>
</div>
