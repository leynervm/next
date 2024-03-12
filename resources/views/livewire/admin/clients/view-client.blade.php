<div class="w-full flex flex-col gap-8">
    <x-form-card titulo="DATOS CLIENTE">
        <form wire:submit.prevent="update" class="w-full flex flex-col gap-2">
            <div class="w-full sm:grid grid-cols-3 gap-2">
                <div class="w-full">
                    <x-label value="RUC :" />
                    <div class="w-full inline-flex gap-1">
                        <x-disabled-text :text="$client->document" class="w-full block" />
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
                    <x-jet-input-error for="client.document" />
                </div>

                <div class="w-full sm:col-span-2">
                    <x-label value="Razón Social :" />
                    <x-input class="block w-full" wire:model.defer="client.name"
                        placeholder="Razón social del cliente" />
                    <x-jet-input-error for="client.name" />
                </div>

                @if (mi_empresa()->uselistprice == 1)
                    <div class="w-full">
                        <x-label value="Lista precio :" />
                        <x-select class="block w-full" wire:model.defer="client.pricetype_id"
                            id="editpricetypeclient_id">
                            <x-slot name="options">
                                @if (count($pricetypes))
                                    @foreach ($pricetypes as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-jet-input-error for="client.pricetype_id" />
                    </div>
                @endif

                <div class="w-full">
                    <x-label value="F. Nacimiento :" />
                    <x-input class="block w-full" type="date" wire:model.defer="client.nacimiento"
                        placeholder="Correo del cliente..." />
                    <x-jet-input-error for="client.nacimiento" />
                </div>

                <div class="w-full">
                    <x-label value="Género :" />
                    <x-select class="block w-full" wire:model.defer="client.sexo" id="edit_sexo">
                        <x-slot name="options">
                            <option value="E">EMPRESARIAL</option>
                            <option value="M">MASCULINO</option>
                            <option value="F">FEMENINO</option>
                        </x-slot>
                    </x-select>
                    <x-jet-input-error for="client.sexo" />
                </div>

                <div class="w-full">
                    <x-label value="Correo :" />
                    <x-input class="block w-full" wire:model.defer="client.email" placeholder="Correo del cliente..." />
                    <x-jet-input-error for="client.email" />
                </div>
            </div>

            <div class="w-full pt-4 flex gap-2 justify-end">
                @can('admin.clientes.delete')
                    <x-button-secondary onclick="confirmDelete({{ $client }})"
                        wire:loading.attr="disabled">{{ __('ELIMINAR CLIENTE ') }}</x-button-secondary>
                @endcan

                <x-button type="submit" wire:loading.attr="disabled" wire:target="update">
                    {{ __('ACTUALIZAR') }}
                </x-button>
            </div>

            <div wire:loading wire:loading.flex wire:target="searchclient" class="loading-overlay rounded hidden">
                <x-loading-next />
            </div>
        </form>
    </x-form-card>

    @can('admin.clientes.phones')
        <x-form-card titulo="TELÉFONOS">
            @if (count($client->telephones) > 0)
                <div class="w-full flex flex-wrap gap-1">
                    @foreach ($client->telephones as $item)
                        <x-minicard-phone phone="{{ $item->phone }}">
                            @can('admin.clientes.phones.edit')
                                <x-slot name="footer">
                                    <x-button-edit wire:click="editphone({{ $item->id }})" wire:loading.attr="disabled"
                                        wire:key="editphone_{{ $item->id }}" />
                                    <x-button-delete onclick="confirmDeletephone({{ $item }})"
                                        wire:loading.attr="disabled" wire:key="deletephone_{{ $item->id }}" />
                                </x-slot>
                            @endcan
                        </x-minicard-phone>
                    @endforeach
                </div>
            @endif

            @can('admin.clientes.phones.edit')
                <div class="w-full pt-4 flex justify-end">
                    <x-button wire:click="openmodalphone" wire:loading.attr="disabled">
                        AGREGAR TELEFONO</x-button>
                </div>
            @endcan
        </x-form-card>
    @endcan

    <x-form-card titulo="DIRECCIONES">
        @if (count($client->direccions) > 0)
            <div class="w-full flex flex-wrap gap-1">
                @foreach ($client->direccions as $item)
                    <div class="w-full text-xs bg-body rounded p-2 shadow-md shadow-shadowform">

                        <p class="text-colorlabel text-[10px] font-medium">
                            {{ $item->name }}</p>

                        @if ($item->ubigeo)
                            <p class="text-colorsubtitleform text-[10px] font-medium">
                                {{ $item->ubigeo->region }},
                                {{ $item->ubigeo->provincia }},
                                {{ $item->ubigeo->distrito }} -
                                {{ $item->ubigeo->ubigeo_reniec }}
                            </p>
                        @endif

                        {{-- @can('admin.clientes.contacts.edit') --}}
                        <div class="w-full flex gap-1 items-end justify-between">
                            @if ($item->isDefault())
                                <x-icon-default />
                            @else
                                <x-icon-default wire:click="usedefault({{ $item->id }})"
                                    wire:loading.attr="disabled"
                                    class="!text-gray-500 hover:!text-gray-400 cursor-pointer transition ease-in-out duration-150" />
                            @endif

                            <div class="inline-flex gap-2">
                                <x-button-edit wire:click="editdireccion({{ $item->id }})"
                                    wire:loading.attr="disabled" wire:key="editdireccion_{{ $item->id }}" />
                                <x-button-delete onclick="confirmDeleteDireccion({{ $item }})"
                                    wire:loading.attr="disabled" wire:key="deletedireccion_{{ $item->id }}" />
                            </div>
                        </div>
                        {{-- @endcan --}}
                    </div>
                @endforeach
            </div>
        @endif

        {{-- @can('admin.clientes.phones.edit') --}}
        <div class="w-full pt-4 flex justify-end">
            <x-button wire:click="$toggle('opendireccion')" wire:loading.attr="disabled">
                AGREGAR DIRECCIÓN</x-button>
        </div>
        {{-- @endcan --}}
    </x-form-card>


    @can('admin.clientes.contacts')
        @if (count($client->contacts) || strlen(trim($client->document)) == 11)
            <x-form-card titulo="CONTACTOS" subtitulo="Nos permitirá contactarse con la empresa.">
                <div class="w-full h-full flex flex-col">
                    @if (count($client->contacts))
                        <div class="w-full flex flex-col gap-2">
                            @foreach ($client->contacts as $item)
                                <div class="w-full text-xs bg-body rounded p-2 shadow-md shadow-shadowform">

                                    <p class="text-colorsubtitleform text-[10px] font-medium">
                                        <span class="text-colortitleform">({{ $item->document }}) </span>
                                        {{ $item->name }}
                                    </p>

                                    @if ($item->telephone)
                                        <x-span-text :text="'TELÉFONO : ' . formatTelefono($item->telephone->phone)" class="leading-3 !tracking-normal" />
                                    @endif

                                    @can('admin.clientes.contacts.edit')
                                        <div class="w-full flex flex-wrap gap-1 items-end justify-end mt-1">
                                            <x-button-edit wire:click="editcontacto({{ $item->id }})"
                                                wire:loading.attr="disabled" wire:key="editcontact_{{ $item->id }}" />
                                            <x-button-delete onclick="confirmDeletecontacto({{ $item }})"
                                                wire:loading.attr="disabled" wire:key="deletecontact_{{ $item->id }}" />
                                        </div>
                                    @endcan
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @can('admin.clientes.contacts.edit')
                        <div class="w-full flex pt-4 justify-end">
                            <x-button wire:click="openmodalcontacto" wire:loading.attr="disabled"
                                wire:target="openmodalcontacto">
                                {{-- <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 20v-1a7 7 0 017-7v0a7 7 0 017 7v1" />
                            <path d="M13 14v0a5 5 0 015-5v0a5 5 0 015 5v.5" />
                            <path d="M8 12a4 4 0 100-8 4 4 0 000 8zM18 9a3 3 0 100-6 3 3 0 000 6z" />
                        </svg> --}}
                                AGREGAR CONTACTO
                            </x-button>
                        </div>
                    @endcan
                </div>
            </x-form-card>
        @endif
    @endcan

    @if ($client->user)
        <x-card-next titulo="USUARIO WEB" class="w-96 mt-3">
            <x-minicard title="" size="lg">
                <span class="w-10 h-10 mx-auto bg-neutral-600 text-white rounded-full p-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2z" />
                        <path d="M4.271 18.346S6.5 15.5 12 15.5s7.73 2.846 7.73 2.846M12 12a3 3 0 100-6 3 3 0 000 6z" />
                    </svg>
                </span>
                <p class="text-xs truncate overflow-hidden text-center">{{ $client->user->email }}</p>
                <x-slot name="buttons">
                    <x-button-edit />
                    <x-button-delete />
                </x-slot>
            </x-minicard>
        </x-card-next>
    @endif

    <x-jet-dialog-modal wire:model="opencontacto" maxWidth="3xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Contacto cliente') }}
            <x-button-close-modal wire:click="$toggle('opencontacto')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form x-data="{ searchingcontacto: false }" wire:submit.prevent="savecontacto"
                class="relative w-full flex flex-col gap-2">

                <div class="w-full flex flex-wrap sm:flex-nowrap gap-2">
                    <div class="w-full sm:w-64 flex-shrink-0">
                        <x-label value="DNI :" />
                        <div class="w-full inline-flex gap-1">
                            <x-input class="block w-full prevent" wire:model.defer="document2" maxlength="8"
                                wire:keydown.enter="searchcontacto" onkeypress="return validarNumero(event, 8)"
                                type="number" />
                            <x-button-add class="px-2" wire:click="searchcontacto" wire:loading.attr="disabled">
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
                        maxlength="9" onkeypress="return validarNumero(event, 9)" type="number" />
                    <x-jet-input-error for="telefono2" />
                </div>

                <div class="w-full flex flex-row pt-4 gap-2 justify-end text-right">
                    <x-button type="submit" size="xs" class="" wire:loading.attr="disabled"
                        wire:target="savecontacto">
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
            @if ($telephone)
                {{ __('Actualir teléfono') }}
            @else
                {{ __('Registrar nuevo teléfono') }}
            @endif
            <x-button-close-modal wire:click="$toggle('openphone')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="savephone">
                <div class="w-full">
                    <x-label value="Teléfono :" />
                    <x-input class="block w-full" wire:model.defer="newtelefono" placeholder="+51 999 999 999"
                        maxlength="9" onkeypress="return validarNumero(event, 9)" />
                    <x-jet-input-error for="newtelefono" />
                </div>

                <div class="w-full flex justify-end pt-4">
                    <x-button type="submit" wire:loading.attr="disabled">
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

    <x-jet-dialog-modal wire:model="opendireccion" maxWidth="xl" footerAlign="justify-end">
        <x-slot name="title">
            @if ($direccion)
                {{ __('Actualir dirección') }}
            @else
                {{ __('Registrar dirección') }}
            @endif
            <x-button-close-modal wire:click="$toggle('opendireccion')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="savedireccion" class="w-full flex flex-col gap-2">
                <div class="w-full">
                    <x-label value="Dirección, calle, avenida :" />
                    <x-input class="block w-full" wire:model.defer="namedireccion"
                        placeholder="Dirección del cliente..." maxlength="255" />
                    <x-jet-input-error for="namedireccion" />
                </div>

                <div class="w-full">
                    <x-label value="Ubigeo :" />
                    <div id="parentubgcl" class="relative" x-data="{ ubigeo_id: @entangle('ubigeo_id').defer }" x-init="select2Ubigeo"
                        wire:ignore>
                        <x-select class="block w-full" x-ref="select" wire:model.defer="ubigeo_id" id="ubgcl"
                            data-placeholder="Seleccionar" data-minimum-results-for-search="3"
                            data-dropdown-parent="null">
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
                    <x-jet-input-error for="ubigeo_id" />
                </div>

                <div class="w-full flex justify-end gap-2">
                    <x-button type="submit" wire:loading.attr="disabled">
                        @if ($direccion)
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
        function confirmDelete(client) {
            swal.fire({
                title: 'Eliminar cliente ' + client.name,
                text: "Se eliminará un registro de la base de datos.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.delete(client.id);
                }
            })
        }

        function confirmDeletecontacto(contact) {
            swal.fire({
                title: 'Eliminar contacto ' + contact.name,
                text: "Se eliminará un registro de la base de datos.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.deletecontacto(contact.id);
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

        function confirmDeleteDireccion(direccion) {
            swal.fire({
                title: 'Eliminar dirección ' + direccion.name,
                text: "Se eliminará un registro de dirección del cliente en la base de datos",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.deletedireccion(direccion.id);
                }
            })
        }

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
    </script>
</div>
