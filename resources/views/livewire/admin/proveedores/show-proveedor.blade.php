<div>
    <div class="mx-auto lg:max-w-4xl xl:max-w-7xl py-10 lg:px-10 animate__animated animate__fadeIn animate__faster">
        <div class="w-full flex flex-wrap gap-8 xl:flex-nowrap">

            <x-form-card titulo="DATOS PROVEEDOR"
                subtitulo="Complete todos los campos para registrar un nuevo proveedor.">

                <form x-data="{ searchingclient: false }" wire:submit.prevent="update"
                    class="w-full flex flex-col gap-2 bg-body p-3 rounded">
                    <div class="w-full">
                        <x-label value="RUC :" />
                        <div class="w-full inline-flex gap-1">
                            <x-input class="block w-full prevent" wire:model.defer="proveedor.document"
                                wire:keydown.enter="searchclient" />
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
                        <div id="parentProveedor1">
                            <x-select class="block w-full select2" wire:model.defer="proveedor.ubigeo_id"
                                id="editubigeoproveedor_id" data-placeholder="Seleccionar"
                                data-minimum-results-for-search="3" data-dropdown-parent="#parentProveedor1">
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
                        </div>
                        <x-jet-input-error for="proveedor.ubigeo_id" />
                    </div>

                    <div class="w-full">
                        <x-label value="Tipo proveedor :" />
                        <div id="parentProveedor2">
                            <x-select class="block w-full select2" wire:model.defer="proveedor.proveedortype_id"
                                id="editproveedortype_id" data-placeholder="Seleccionar"
                                data-minimum-results-for-search="Infinity" data-dropdown-parent="#parentProveedor2">
                                <x-slot name="options">
                                    @if (count($proveedortypes))
                                        @foreach ($proveedortypes as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    @endif
                                </x-slot>
                            </x-select>
                        </div>
                        <x-jet-input-error for="proveedor.proveedortype_id" />
                    </div>

                    <div class="w-full">
                        <x-label value="Correo :" />
                        <x-input class="block w-full" wire:model.defer="proveedor.email"
                            placeholder="Correo del cliente..." />
                        <x-jet-input-error for="proveedor.email" />
                    </div>

                    @if (count($proveedor->telephones))
                        <div class="flex flex-wrap gap-1">
                            @foreach ($proveedor->telephones as $item)
                                <x-minicard-phone phone="{{ $item->phone }}">
                                    <x-slot name="footer">
                                        <x-button-edit wire:click="editphone({{ $item->id }})"
                                            wire:loading.attr="disabled" wire:target="editphone"></x-button-edit>
                                        <x-button-delete wire:click="$emit('proveedor.confirmDeletephone',{{ $item }})"
                                            wire:loading.attr="disabled"
                                            wire:target="confirmDeletephone,deletephone"></x-button-delete>
                                    </x-slot>
                                </x-minicard-phone>
                            @endforeach
                        </div>
                    @endif

                    <div class="w-full flex flex-row gap-2 justify-between text-right">
                        <x-button size="xs" wire:click="openmodalphone" wire:loading.attr="disabled"
                            wire:target="openmodalphone">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M16.243 5.243h3m3 0h-3m0 0v-3m0 3v3M18.118 14.702L14 15.5c-2.782-1.396-4.5-3-5.5-5.5l.77-4.13L7.815 2H4.064c-1.128 0-2.016.932-1.847 2.047.42 2.783 1.66 7.83 5.283 11.453 3.805 3.805 9.286 5.456 12.302 6.113 1.165.253 2.198-.655 2.198-1.848v-3.584l-3.882-1.479z" />
                            </svg>
                        </x-button>

                        <x-button type="submit" wire:loading.attr="disabled" wire:target="update">
                            {{ __('ACTUALIZAR') }}
                        </x-button>
                    </div>

                    <div x-show="searchingclient" wire:loading wire:loading.flex wire:target="searchclient"
                        class="loading-overlay rounded">
                        <x-loading-next />
                    </div>
                </form>
            </x-form-card>

            <x-form-card titulo="REPRESENTANTES"
                subtitulo="Complete todos los campos, nos permitirá contactarse con la empresa.">

                <div class="w-full h-full flex flex-col">
                    @if (count($proveedor->contacts))
                        <div class="w-full flex flex-col gap-2">
                            @foreach ($proveedor->contacts as $item)
                                <div class="w-full text-xs bg-body rounded p-3 shadow-md shadow-shadowform">

                                    <p class="text-colorsubtitleform text-[10px] font-semibold">
                                        <span class="text-colortitleform">({{ $item->document }}) </span>
                                        {{ $item->name }}
                                    </p>

                                    @if ($item->telephone)
                                        <p
                                            class="inline-block bg-fondospancardproduct text-textspancardproduct p-0.5 rounded text-[10px] font-semibold">
                                            TELÉFONO : {{ $item->telephone->phone }}</p>
                                    @endif

                                    <div class="w-full flex flex-wrap gap-1 items-end justify-end mt-1">
                                        <x-button-edit wire:click="editrepresentante({{ $item->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="editrepresentante"></x-button-edit>
                                        <x-button-delete
                                            wire:click="$emit('proveedor.confirmDeleterepresentante',{{ $item }})"
                                            wire:loading.attr="disabled"
                                            wire:target="confirmDeleterepresentante, deleterepresentante"></x-button-delete>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="mt-auto w-full flex pt-4 gap-2 justify-end text-right">
                        <x-button size="xs" wire:click="openmodalrepresentante" wire:loading.attr="disabled"
                            wire:target="openmodalrepresentante">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M1 20v-1a7 7 0 017-7v0a7 7 0 017 7v1" />
                                <path d="M13 14v0a5 5 0 015-5v0a5 5 0 015 5v.5" />
                                <path d="M8 12a4 4 0 100-8 4 4 0 000 8zM18 9a3 3 0 100-6 3 3 0 000 6z" />
                            </svg>
                        </x-button>
                    </div>
                </div>
            </x-form-card>
        </div>
        <div class="w-full flex justify-end mt-3">
            <x-button wire:click="$emit('proveedor.confirmDelete', {{ $proveedor }})" wire:loading.attr="disabled"
                wire:target="confirmDelete, delete">ELIMINAR PROVEEDOR</x-button>
        </div>
    </div>


    <x-jet-dialog-modal wire:model="openrepresentante" maxWidth="3xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Representante proveedor') }}
            <x-button-add wire:click="$toggle('openrepresentante')" wire:loading.attr="disabled">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </x-button-add>
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
                    <x-button type="submit" size="xs" class="" wire:loading.attr="disabled"
                        wire:target="saverepresentante">
                        @if ($contact)
                            {{ __('ACTUALIZAR') }}
                        @else
                            {{ __('REGISTRAR') }}
                        @endif
                    </x-button>
                </div>

                <div x-show="searchingcontacto" wire:loading wire:loading.flex wire:target="searchcontacto"
                    class="loading-overlay rounded">
                    <x-loading-next />
                </div>

            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <x-jet-dialog-modal wire:model="openphone" maxWidth="xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Teléfono') }}
            <x-button-add wire:click="$toggle('openphone')" wire:loading.attr="disabled">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </x-button-add>
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
                    <x-button type="submit" size="xs" wire:loading.attr="disabled" wire:target="savephone">
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

    @section('scripts')
        <script>
            $('#editubigeoproveedor_id').select2()
                .on("change", function(e) {
                    $('.select2').attr("disabled", true);
                    @this.set('proveedor.ubigeo_id', e.target.value);
                }).on('select2:open', function(e) {
                    const evt = "scroll.select2";
                    $(e.target).parents().off(evt);
                    $(window).off(evt);
                });

            $('#editproveedortype_id').select2()
                .on("change", function(e) {
                    $('.select2').attr("disabled", true);
                    @this.set('proveedor.proveedortype_id', e.target.value);
                }).on('select2:open', function(e) {
                    const evt = "scroll.select2";
                    $(e.target).parents().off(evt);
                    $(window).off(evt);
                });

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
                        Livewire.emitTo('admin.proveedores.show-proveedor', 'delete', data.id);
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
                        Livewire.emitTo('admin.proveedores.show-proveedor', 'deletephone', data.id);
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
                        Livewire.emitTo('admin.proveedores.show-proveedor', 'deleterepresentante', data.id);
                    }
                })
            });

            document.addEventListener('render-select2-editproveedores', () => {
                $('.select2').select2();
                // $('.select2').attr("disabled", false);
            });
        </script>
    @endsection
</div>
