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
            <x-button-add wire:click="$toggle('open')" wire:loading.attr="disabled">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </x-button-add>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save" id="form_create_client" class="relative" x-data="{ searchingclient: @entangle('searchingclient') }">
                <div class="w-full sm:grid grid-cols-3 gap-2">
                    <div class="w-full">
                        <x-label value="DNI / RUC :" />
                        <div class="w-full inline-flex gap-1">
                            <x-input class="block w-full prevent" wire:model="document"
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

                    <div class="w-full sm:col-span-2 mt-2 sm:mt-0">
                        <x-label value="Ubigeo :" />
                        <x-select class="block w-full" wire:model.defer="ubigeo_id" id="ubigeoclient_id"
                            data-placeholder="Seleccionar" data-minimum-results-for-search="3">
                            <x-slot name="options">
                                @if (count($ubigeos))
                                    @foreach ($ubigeos as $item)
                                        <option value="{{ $item->id }}">{{ $item->region }} / {{ $item->provincia }}
                                            /
                                            {{ $item->distrito }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-jet-input-error for="ubigeo_id" />
                    </div>

                    <div class="w-full mt-2 sm:mt-0">
                        <x-label value="Correo :" />
                        <x-input class="block w-full" wire:model.defer="email" placeholder="Correo del cliente..." />
                        <x-jet-input-error for="email" />
                    </div>
                    <div class="w-full mt-2 sm:mt-0">
                        <x-label value="Género :" />
                        <x-select class="block w-full" wire:model.defer="sexo" id="sexoclient_id"
                            data-placeholder="Seleccionar" data-minimum-results-for-search="Infinity">
                            <x-slot name="options">
                                <option value="E">EMPRESARIAL</option>
                                <option value="M">MASCULINO</option>
                                <option value="F">FEMENINO</option>
                            </x-slot>
                        </x-select>
                        <x-jet-input-error for="sexo" />
                    </div>
                    <div class="w-full mt-2 sm:mt-0">
                        <x-label value="Fecha nacimiento :" />
                        <x-input type="date" class="block w-full" wire:model.defer="nacimiento"
                            placeholder="Correo del cliente..." />
                        <x-jet-input-error for="nacimiento" />
                    </div>
                    <div class="w-full mt-2 sm:mt-0">
                        <x-label value="Lista precio :" />
                        <x-select class="block w-full" wire:model.defer="pricetype_id" id="pricetype_id"
                            data-placeholder="Seleccionar" data-minimum-results-for-search="Infinity">
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
                    <div class="w-full mt-2 sm:mt-0">
                        <x-label value="Teléfono :" />
                        <x-input class="block w-full" wire:model.defer="telefono" placeholder="+51 999 999 999"
                            maxlength="9" />
                        <x-jet-input-error for="telefono" />
                    </div>
                </div>

                @if ($mostrarcontacto)
                    <div class="animate__animated animate__bounceIn animate__faster">
                        <x-title-next titulo="Contacto" class="my-3" />

                        <div class="w-full sm:grid grid-cols-3 gap-2">
                            <div class="w-full">
                                <x-label value="DNI :" />
                                <div class="w-full inline-flex gap-1">
                                    <x-input class="block w-full" wire:model.defer="documentContact"
                                        maxlength="8" />
                                    <x-button-add class="px-2" wire:click="searchcontacto"
                                        wire:loading.attr="disabled" wire:target="searchcontacto">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
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
                                <x-input class="block w-full" wire:model.defer="telefonoContact"
                                    placeholder="+51 999 999 999" maxlength="9" />
                                <x-jet-input-error for="telefonoContact" />
                            </div>
                        </div>
                    </div>
                @endif

                <div class="w-full flex flex-row pt-4 gap-2 justify-end text-right">
                    <x-button type="submit" size="xs" class="" wire:loading.attr="disabled"
                        wire:target="save">
                        {{ __('REGISTRAR') }}
                    </x-button>
                </div>

                <div x-show="searchingclient" wire:loading wire:loading.flex wire:target="searchclient,searchcontacto"
                    class="loading-overlay rounded">
                    <x-loading-next />
                </div>

            </form>
        </x-slot>
    </x-jet-dialog-modal>


    <script>
        document.addEventListener("livewire:load", () => {

            renderSelect2();

            $("#ubigeoclient_id").on("change", (e) => {
                deshabilitarSelects();
                @this.ubigeo_id = e.target.value;
            });

            $("#pricetype_id").on("change", (e) => {
                deshabilitarSelects();
                @this.pricetype_id = e.target.value;
            });

            $("#sexoclient_id").on("change", (e) => {
                deshabilitarSelects();
                @this.sexo = e.target.value;
            });

            window.addEventListener('render-client-select2', () => {
                renderSelect2();
            });

            function renderSelect2() {
                var formulario = document.getElementById("form_create_client");
                var selects = formulario.getElementsByTagName("select");

                for (var i = 0; i < selects.length; i++) {
                    if (selects[i].id !== "") {
                        $("#" + selects[i].id).select2();
                    }
                }
            }

            function deshabilitarSelects() {
                var formulario = document.getElementById("form_create_client");
                var selects = formulario.getElementsByTagName("select");

                for (var i = 0; i < selects.length; i++) {
                    selects[i].disabled = true;
                }
            }

        })
    </script>


</div>
