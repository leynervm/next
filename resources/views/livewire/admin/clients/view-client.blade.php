<div class="w-full flex flex-col gap-8">
    <x-form-card titulo="DATOS CLIENTE">
        <form wire:submit.prevent="update" class="w-full flex flex-col gap-2">
            <div class="w-full sm:grid grid-cols-3 gap-2">
                <div class="w-full">
                    <x-label value="RUC :" />
                    <div class="w-full inline-flex gap-1">
                        <x-disabled-text :text="$client->document" class="w-full block flex-1" />
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
                    <x-jet-input-error for="client.document" />
                </div>

                <div class="w-full sm:col-span-2">
                    <x-label value="Razón Social :" />
                    <x-input class="block w-full" wire:model.defer="client.name"
                        placeholder="Razón social del cliente" />
                    <x-jet-input-error for="client.name" />
                </div>

                @if (Module::isEnabled('Ventas'))
                    @if (mi_empresa()->usarLista())
                        <div class="w-full">
                            <x-label value="Lista precio :" />
                            <div class="w-full relative" id="parentpricetypesale_id" x-data="{ pricetype_id: @entangle('client.pricetype_id') }"
                                x-init="pricetype">
                                <x-select class="block w-full" x-ref="pricetypesale" id="pricetypesale_id">
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
                            <x-jet-input-error for="client.pricetype_id" />
                        </div>
                    @endif
                @endif

                <div class="w-full">
                    <x-label value="F. Nacimiento :" />
                    <x-input class="block w-full" type="date" wire:model.defer="client.nacimiento" />
                    <x-jet-input-error for="client.nacimiento" />
                </div>

                <div class="w-full">
                    <x-label value="Género :" />
                    <div class="relative" id="parenteditsexo" x-data="{ sexo: @entangle('client.sexo').defer }" x-init="SexoClient"
                        wire:ignore>
                        <x-select class="block w-full" x-ref="selectsexo" id="editsexo">
                            <x-slot name="options">
                                <option value="E">EMPRESARIAL</option>
                                <option value="M">MASCULINO</option>
                                <option value="F">FEMENINO</option>
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                        <x-jet-input-error for="client.sexo" />
                    </div>

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

                <x-button type="submit" wire:loading.attr="disabled">
                    {{ __('Save') }}</x-button>
            </div>

            <div wire:loading.flex class="loading-overlay fixed hidden">
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
            <div class="w-full grid grid-cols-[repeat(auto-fill,minmax(260px,1fr))] gap-1 self-start">
                @foreach ($client->direccions as $item)
                    <div
                        class="w-full flex flex-col justify-between text-xs bg-body rounded-xl p-2 border {{ $item->isDefault() ? 'shadow-md shadow-next-200 border-next-500' : 'border-borderminicard' }}">
                        <div class="w-full">
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
                        </div>
                        @can('admin.clientes.edit')
                            <div class="w-full flex gap-1 items-end justify-between">
                                @if ($item->isDefault())
                                    <x-icon-default />
                                @else
                                    <x-icon-default wire:click="usedefault({{ $item->id }})"
                                        wire:loading.attr="disabled"
                                        class="!text-gray-500 hover:!text-gray-400 cursor-pointer transition ease-in-out duration-150" />
                                @endif

                                <div class="inline-flex gap-1">
                                    <x-button-edit wire:click="editdireccion({{ $item->id }})"
                                        wire:loading.attr="disabled" wire:key="editdireccion_{{ $item->id }}" />
                                    <x-button-delete onclick="confirmDeleteDireccion({{ $item }})"
                                        wire:loading.attr="disabled" wire:key="deletedireccion_{{ $item->id }}" />
                                </div>
                            </div>
                        @endcan
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
                            <x-button wire:click="openmodalcontacto" wire:loading.attr="disabled">
                                AGREGAR CONTACTO
                            </x-button>
                        </div>
                    @endcan
                </div>
            </x-form-card>
        @endif
    @endcan

    @if (module::isEnabled('Marketplace'))
        @if ($client->user)
            <x-form-card titulo="USUARIO WEB VINCULADO"
                subtitulo="Puede realizar compras por internet mediante nuestra tienda web.">
                <div class="w-full">
                    <x-simple-card class="w-48 mt-3 flex flex-col gap-1 rounded-xl cursor-default p-3 py-5">
                        <h1 class="font-semibold text-sm leading-4 text-primary text-center">
                            {{ $client->user->name }}</h1>

                        <x-label :value="$client->user->document" class="font-semibold text-center" />

                        <p class="text-xs text-center">{{ $client->user->email }}</p>
                    </x-simple-card>
                </div>
            </x-form-card>
        @else
            @if ($user)
                <div class="w-full">
                    <p class="text-xs text-colorsubtitleform my-2">
                        Actualmente el cliente está registrado en nuestra tienda web...</p>
                    <x-simple-card class="w-48 mt-3 flex flex-col gap-1 rounded-xl cursor-default p-3 py-5">
                        <x-button-next class="mx-auto" titulo="SINCRONIZAR USUARIO WEB" wire:click="sincronizeuser"
                            wire:loading.attr="disabled">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" wire:loading.class="animate-spin">
                                <path
                                    d="M15.1667 0.999756L15.7646 2.11753C16.1689 2.87322 16.371 3.25107 16.2374 3.41289C16.1037 3.57471 15.6635 3.44402 14.7831 3.18264C13.9029 2.92131 12.9684 2.78071 12 2.78071C6.75329 2.78071 2.5 6.90822 2.5 11.9998C2.5 13.6789 2.96262 15.2533 3.77093 16.6093M8.83333 22.9998L8.23536 21.882C7.83108 21.1263 7.62894 20.7484 7.7626 20.5866C7.89627 20.4248 8.33649 20.5555 9.21689 20.8169C10.0971 21.0782 11.0316 21.2188 12 21.2188C17.2467 21.2188 21.5 17.0913 21.5 11.9998C21.5 10.3206 21.0374 8.74623 20.2291 7.39023" />
                            </svg>
                        </x-button-next>

                        <h1 class="font-semibold text-sm leading-4 text-primary text-center">
                            {{ $user->name }}</h1>

                        <x-label :value="$user->document" class="font-semibold text-center" />

                        <p class="text-xs text-center text-colorsubtitleform">{{ $user->email }}</p>
                    </x-simple-card>
                </div>
            @endif
        @endif
    @endif

    <x-jet-dialog-modal wire:model="opencontacto" maxWidth="3xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Contacto cliente') }}
        </x-slot>

        <x-slot name="content">
            <form x-data="{ searchingcontacto: false }" wire:submit.prevent="savecontacto"
                class="relative w-full flex flex-col gap-2">

                <div class="w-full flex flex-wrap sm:flex-nowrap gap-2">
                    <div class="w-full sm:w-64 flex-shrink-0">
                        <x-label value="DNI :" />
                        <div class="w-full inline-flex gap-1">
                            <x-input class="block w-full flex-1 input-number-none" wire:model.defer="document2"
                                maxlength="8" wire:keydown.enter.prevent="searchcontacto"
                                onkeypress="return validarNumero(event, 8)" type="number" />
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
                    <div class="w-full flex-1">
                        <x-label value="Nombres :" />
                        <x-input class="block w-full" wire:model.defer="name2" />
                        <x-jet-input-error for="name2" />
                    </div>
                </div>

                <div class="w-full sm:w-64">
                    <x-label value="Teléfono :" />
                    <x-input class="block w-full input-number-none" wire:model.defer="telefono2" maxlength="9"
                        onkeypress="return validarNumero(event, 9)" type="number" />
                    <x-jet-input-error for="telefono2" />
                </div>

                <div class="w-full flex gap-2 pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('Save') }}</x-button>
                </div>

                <div wire:loading.flex class="loading-overlay fixed hidden">
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
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="savephone">
                <div class="w-full">
                    <x-label value="Teléfono :" />
                    <x-input class="block w-full input-number-none" wire:model.defer="newtelefono" type="number"
                        maxlength="9" onkeypress="return validarNumero(event, 9)" />
                    <x-jet-input-error for="newtelefono" />
                </div>

                <div class="w-full flex justify-end pt-4">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('Save') }}</x-button>
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
                    <x-button type="button" wire:click="savedireccion(true)" wire:loading.attr="disabled">
                        {{ __('Save and close') }}</x-button>
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('Save') }}</x-button>
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

        function SexoClient() {
            this.selectSC = $(this.$refs.selectsexo).select2();
            this.selectSC.val(this.sexo).trigger("change");
            this.selectSC.on("select2:select", (event) => {
                this.sexo = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("sexo", (value) => {
                this.selectSC.val(value).trigger("change");
            });
        }

        function pricetype() {
            this.selectPTS = $(this.$refs.pricetypesale).select2();
            this.selectPTS.val(this.pricetype_id).trigger("change");
            this.selectPTS.on("select2:select", (event) => {
                this.pricetype_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("pricetype_id", (value) => {
                this.selectPTS.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectPTS.select2().val(this.pricetype_id).trigger('change');
            });
        }
    </script>
</div>
