<div>
    <form wire:submit.prevent="save" id="form_create_proveedor"
        class="mx-auto lg:max-w-4xl xl:max-w-7xl py-10 lg:px-10 animate__animated animate__fadeIn animate__faster"
        x-data="{ searchingclient: false }">

        <div class="w-full flex flex-wrap gap-8 xl:flex-nowrap">
            <x-form-card titulo="REGISTRAR PROVEEDOR"
                subtitulo="Complete todos los campos para registrar un nuevo proveedor.">

                <div class="w-full flex flex-col gap-2 bg-body p-3 rounded">
                    <div class="w-full">
                        <x-label value="RUC :" />
                        <div class="w-full inline-flex gap-1">
                            <x-input class="block w-full prevent" wire:model.defer="document"
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
                        <x-jet-input-error for="document" />
                    </div>
                    <div class="w-full sm:col-span-2">
                        <x-label value="Razón Social :" />
                        <x-input class="block w-full" wire:model.defer="name"
                            placeholder="Razón social del proveedor" />
                        <x-jet-input-error for="name" />
                    </div>

                    <div class="w-full sm:col-span-3">
                        <x-label value="Dirección, calle, avenida :" />
                        <x-input class="block w-full" wire:model.defer="direccion"
                            placeholder="Dirección del cliente..." />
                        <x-jet-input-error for="direccion" />
                    </div>

                    <div class="w-full sm:col-span-2">
                        <x-label value="Ubigeo :" />
                        <div id="parentProveedor1">
                            <x-select class="block w-full select2" wire:model.defer="ubigeo_id" id="ubigeoproveedor_id"
                                data-placeholder="Seleccionar" data-minimum-results-for-search="3"
                                data-dropdown-parent="#parentProveedor1">
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
                        <x-jet-input-error for="ubigeo_id" />
                    </div>

                    <div class="w-full">
                        <x-label value="Tipo proveedor :" />
                        <div id="parentProveedor2">
                            <x-select class="block w-full select2" wire:model.defer="proveedortype_id"
                                id="proveedortype_id" data-placeholder="Seleccionar"
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
                        <x-jet-input-error for="proveedortype_id" />
                    </div>

                    <div class="w-full">
                        <x-label value="Teléfono :" />
                        <x-input class="block w-full" wire:model.defer="telefono" placeholder="+51 999 999 999"
                            maxlength="9" />
                        <x-jet-input-error for="telefono" />
                    </div>

                    <div class="w-full">
                        <x-label value="Correo :" />
                        <x-input class="block w-full" wire:model.defer="email" placeholder="Correo del cliente..." />
                        <x-jet-input-error for="email" />
                    </div>
                </div>

                <div x-show="searchingclient" wire:loading wire:loading.flex wire:target="searchclient"
                    class="loading-overlay rounded">
                    <x-loading-next />
                </div>
            </x-form-card>

            <x-form-card x-data="{ searchingcontacto: false }" titulo="REPRESENTANTE"
                subtitulo="Complete todos los campos, nos permitirá contactarse con la empresa.">

                <div class="w-full flex flex-col gap-2 bg-body p-3 rounded">
                    <div class="w-full">
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
                    <div class="w-full sm:col-span-2 mt-2 sm:mt-0">
                        <x-label value="Nombres representante :" />
                        <x-input class="block w-full" wire:model.defer="name2"
                            placeholder="Nombres del representante..." />
                        <x-jet-input-error for="name2" />
                    </div>
                    <div class="w-full mt-2 sm:mt-0">
                        <x-label value="Teléfono :" />
                        <x-input class="block w-full" wire:model.defer="telefono2" placeholder="+51 999 999 999"
                            maxlength="9" />
                        <x-jet-input-error for="telefono2" />
                    </div>
                </div>

                <div x-show="searchingcontacto" wire:loading wire:loading.flex wire:target="searchcontacto"
                    class="loading-overlay rounded">
                    <x-loading-next />
                </div>
            </x-form-card>
        </div>

        <div class="w-full flex flex-row pt-4 gap-2 justify-end text-right">
            <x-button type="submit" size="xs" class="" wire:loading.attr="disabled" wire:target="save">
                {{ __('REGISTRAR') }}
            </x-button>
        </div>

    </form>

    @section('scripts')
        <script>
            $('#ubigeoproveedor_id').select2()
                .on("change", function(e) {
                    $('.select2').attr("disabled", true);
                    @this.ubigeo_id = e.target.value;
                }).on('select2:open', function(e) {
                    const evt = "scroll.select2";
                    $(e.target).parents().off(evt);
                    $(window).off(evt);
                });

            $('#proveedortype_id').select2()
                .on("change", function(e) {
                    $('.select2').attr("disabled", true);
                    @this.proveedortype_id = e.target.value;
                }).on('select2:open', function(e) {
                    const evt = "scroll.select2";
                    $(e.target).parents().off(evt);
                    $(window).off(evt);
                });

            document.addEventListener('render-select2-proveedores', () => {
                $('.select2').select2();
            });

            // $("#btnsearchproveedor").click(function() {
            //     consultasunat($('#documentproveedor').val().trim());
            // });

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
    @endsection

</div>
