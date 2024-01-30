<div>
    <form wire:submit.prevent="save" class="mt-3 max-w-7xl md:p-3 mx-auto shadow-xl rounded">
        <div class="flex flex-wrap lg:flex-nowrap justify-between gap-3">
            <x-card-next titulo="Prioridad" class="w-full sm:w-60 border border-next-500">
                @if (count($prioridades))
                    <div class="inline-flex justify-between flex-wrap gap-1">
                        @foreach ($prioridades as $item)
                            <x-input-radio for="priority_{{ $item->name }}" :text="$item->name">
                                <input wire:model.defer="priority_id" class="sr-only peer peer-disabled:opacity-25"
                                    type="radio" id="priority_{{ $item->name }}" name="priority"
                                    value="{{ $item->id }}" />
                            </x-input-radio>
                        @endforeach
                    </div>
                @endif
                <x-jet-input-error for="priority_id" />
            </x-card-next>

            <x-card-next titulo="Entorno atención" class="w-full sm:w-60 border border-next-500">
                @if (count($area->entornos))
                    <div class="inline-flex justify-between flex-wrap gap-1">
                        @foreach ($area->entornos as $item)
                            <x-input-radio for="entorno_{{ $item->name }}" :text="$item->name">
                                <input wire:model.defer="entorno_id" class="sr-only peer" type="radio"
                                    id="entorno_{{ $item->name }}" name="order_entorno" value="{{ $item->id }}" />
                            </x-input-radio>
                        @endforeach
                    </div>
                @endif
                <x-jet-input-error for="entorno_id" />
            </x-card-next>

            <x-card-next titulo="Atención" class="w-full sm:w-80 border border-next-500">
                @if (count($area->atencions))
                    <div class="inline-flex justify-between flex-wrap gap-1">
                        @foreach ($area->atencions as $item)
                            <x-input-radio for="atencion_{{ $item->name }}" :text="$item->name">
                                <input wire:model.defer="atencion_id" class="sr-only peer" type="radio"
                                    id="atencion_{{ $item->name }}" name="order_atencion"
                                    value="{{ $item->id }}" />
                            </x-input-radio>
                        @endforeach
                    </div>
                @endif
                <x-jet-input-error for="atencion_id" />
            </x-card-next>

            <x-card-next titulo="Condición atención" class="w-full sm:w-80 border border-next-500">
                @if (count($conditions))
                    <div class="inline-flex justify-between flex-wrap gap-1">
                        @foreach ($conditions as $item)
                            <x-input-radio for="condition_{{ $item->name }}" :text="$item->name">
                                <input wire:model.defer="condition_id" class="sr-only peer" type="radio"
                                    id="condition_{{ $item->name }}" name="order_condition"
                                    value="{{ $item->id }}" />
                            </x-input-radio>
                        @endforeach
                    </div>
                @endif
                <x-jet-input-error for="condition_id" />
            </x-card-next>
        </div>

        <x-card-next titulo="Cliente" class="mt-3 border border-next-500">
            <div class="flex flex-wrap md:flex-nowrap gap-3">
                <div class="w-full md:w-3/5 flex gap-3">
                    <div class="hidden lg:block w-40 h-40">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 21a6 6 0 0 0-12 0" />
                            <circle cx="12" cy="11" r="4" />
                            <rect width="18" height="18" x="3" y="3" rx="2" />
                        </svg>
                    </div>

                    <div class="w-full">
                        <x-label value="Buscar Cliente :" />
                        <div class="w-full inline-flex">
                            <x-select class="block w-full" id="orderclient_id" wire:model.defer="client_id"
                                name="orderclient_id">
                                <x-slot name="options">
                                    @if (count($clients))
                                        @foreach ($clients as $item)
                                            <option value="{{ $item->id }}">{{ $item->document }} -
                                                {{ $item->name }}</option>
                                        @endforeach
                                    @endif
                                </x-slot>
                            </x-select>
                            <x-button-add class="px-2" wire:click="$set('openModalClient', true)">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M14 19a6 6 0 0 0-12 0" />
                                    <circle cx="8" cy="9" r="4" />
                                    <line x1="19" x2="19" y1="8" y2="14" />
                                    <line x1="22" x2="16" y1="11" y2="11" />
                                </svg>
                            </x-button-add>
                        </div>
                        <x-jet-input-error for="client_id" />

                        <div class="w-full mt-2">
                            <x-label value="Dirección :" />
                            <x-select class="block w-full" id="orderdireccion_id" wire:model.defer="direccion"
                                name="direccion">
                                <x-slot name="options">
                                    @if (count($client->direccions))
                                        @foreach ($client->direccions as $item)
                                            <option value="{{ $item->id }}"
                                                @if ($loop->first) selected @endif>{{ $item->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </x-slot>
                            </x-select>
                            {{-- <x-input class="block w-full" wire:model.defer="direccion"
                                placeholder="Ingrese nombre de prioridad..." /> --}}
                            <x-jet-input-error for="direccion" />
                        </div>

                        @if ($mensaje)
                            <div class="w-full mt-2">
                                <x-label value="Mensaje :" />
                                <x-input class="block w-full" wire:model.defer="mensaje" disabled readonly />
                                <x-jet-input-error for="mensaje" />
                            </div>
                        @endif

                    </div>
                </div>
                <div class="w-full md:w-2/5 flex flex-col gap-3 justify-between">

                    <div class="flex gap-2 flex-wrap">
                        @if (count($client->telephones))
                            @foreach ($client->telephones as $item)
                                <x-minicard title="" :content="$item->phone">
                                    <x-slot name="title">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mx-auto"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path
                                                d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                                            <path d="M14.05 2a9 9 0 0 1 8 7.94" />
                                            <path d="M14.05 6A5 5 0 0 1 18 10" />
                                        </svg>
                                    </x-slot>
                                </x-minicard>
                            @endforeach
                        @endif
                    </div>
                    <div class="w-full">
                        <x-button type="button" size="xs" class="ml-auto"
                            wire:click="$set('openModalTelefono', true)" wire:loading.attr="disabled" wire:target="">
                            {{ __('AGREGAR TELÉFONO') }}
                        </x-button>
                    </div>
                </div>
            </div>
        </x-card-next>

        @if (count($client->contacts))
            <x-card-next titulo="Contactos"
                class="mt-3 border border-next-500 animate__animated animate__bounceIn animate__faster">
                <div class="w-full flex flex-col lg:flex-row gap-3 justify-between items-end">
                    <div class="w-full flex gap-2 flex-wrap">
                        @foreach ($client->contacts as $item)
                            <div class="w-full sm:w-52">
                                <x-cardcontacto-radio wire:model.defer="contact_id" class="w-full sm:w-48"
                                    id="rdo_contact_{{ $item->id }}" :phones="$item->telephones" name="card_contact"
                                    :text="$item->name" :document="$item->document" value="{{ $item->id }}" />
                            </div>
                        @endforeach
                    </div>

                    <x-button type="button" size="xs" class="lg:w-48" wire:loading.attr="disabled"
                        wire:target="" wire:click="$set('openModalContacto', true)">
                        {{ __('AGREGAR CONTACTO') }}
                    </x-button>

                </div>
                <x-jet-input-error for="contact_id" />
            </x-card-next>
        @endif

        @if ($atencion->equipamentrequire)
            <x-card-next titulo="Información equipo"
                class="mt-3 border border-next-500 animate__animated animate__bounceIn animate__faster">
                <div class="sm:grid lg:grid-cols-4 gap-2">
                    <div class="w-full">
                        <x-label value="Equipo :" />
                        <x-select class="block w-full" wire:model.defer="equipo_id">
                            <x-slot name="options">
                                @if (count($equipos))
                                    @foreach ($equipos as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-jet-input-error for="equipo_id" />
                    </div>

                    <div class="w-full mt-2 sm:mt-0">
                        <x-label value="Marca :" />
                        <x-select class="block w-full" wire:model.defer="marca_id">
                            <x-slot name="options">
                                @if (count($condition->centerservices))
                                    @foreach ($condition->centerservices as $item)
                                        <option value="{{ $item->marca_id }}">{{ $item->marca->name }} -
                                            {{ $item->name }}</option>
                                    @endforeach
                                @else
                                    @if (count($marcas))
                                        @foreach ($marcas as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    @endif
                                @endif
                            </x-slot>
                        </x-select>
                        <x-jet-input-error for="marca_id" />
                    </div>
                    <div class="w-full mt-2 sm:mt-0">
                        <x-label value="Modelo :" />
                        <x-input class="block w-full" wire:model.defer="modelo"
                            placeholder="Ingrese modelo del equipo..." />
                        <x-jet-input-error for="modelo" />
                    </div>
                    <div class="w-full mt-2 sm:mt-0">
                        <x-label value="Serie :" />
                        <x-input class="block w-full" wire:model.defer="serie"
                            placeholder="Ingrese serie del equipo..." />
                        <x-jet-input-error for="serie" />
                    </div>
                    <div class="mt-2 sm:mt-0">
                        <x-label value="Estado equipo :" />
                        <x-select class="block w-full" wire:model.defer="stateinicial">
                            <x-slot name="options">
                                <option value="0">INOPERATIVO</option>
                                <option value="1">OPERATIVO</option>
                            </x-slot>
                        </x-select>
                        <x-jet-input-error for="stateinicial" />
                    </div>
                    <div class="col-span-3 mt-2 sm:mt-0">
                        <x-label value="Descripción física y estado de suministros del equipo :" />
                        <div class="w-full inline-flex items-start">
                            <x-text-area wire:model.defer="descripcion" class="w-full" name="descripcion"
                                rows="4" placeholder="Ingrese descripción del equipo...">
                            </x-text-area>
                            {{-- <x-input class="block w-full" multiline="true" wire:model.defer="descripcion"
                                placeholder="Ingrese descripción del equipo..." /> --}}
                            <x-button-add class="px-2" wire:click="$set('opemModalAutollenado', true)">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <rect width="8" height="4" x="8" y="2"
                                        rx="1" ry="1" />
                                    <path d="M10.42 12.61a2.1 2.1 0 1 1 2.97 2.97L7.95 21 4 22l.99-3.95 5.43-5.44Z" />
                                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-5.5" />
                                    <path d="M4 13.5V6a2 2 0 0 1 2-2h2" />
                                </svg>
                            </x-button-add>
                        </div>
                        <x-jet-input-error for="descripcion" />
                    </div>
                </div>
            </x-card-next>
        @endif

        <x-card-next titulo="Detalles atención" class="mt-3 border border-next-500">
            <x-text-area wire:model.defer="detalle" class="w-full" name="detalle" rows="4">
            </x-text-area>
            <x-jet-input-error for="detalle" />
        </x-card-next>

        @if ($entorno->requiredirection)
            <x-card-next titulo="Lugar atención"
                class="mt-3 border border-next-500 animate__animated animate__bounceIn animate__faster">
                <div class="sm:grid lg:grid-cols-3 gap-2">
                    <div class="w-full">
                        <x-label value="Ubigeo :" />
                        <x-select class="block w-full" wire:model.defer="ubigeolugar_id">
                            <x-slot name="options">
                                {{-- @if (count($atenciones))
                                    @foreach ($atenciones as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                @endif --}}
                            </x-slot>
                        </x-select>
                        <x-jet-input-error for="ubigeolugar_id" />
                    </div>

                    <div class="w-full mt-2 sm:mt-0">
                        <x-label value="Dirección :" />
                        <x-input class="block w-full" wire:model.defer="direccionlugar"
                            placeholder="Ingrese dirección del lugar atención..." />
                        <x-jet-input-error for="direccionlugar" />
                    </div>
                    <div class="w-full mt-2 sm:mt-0">
                        <x-label value="Referencia :" />
                        <x-input class="block w-full" wire:model.defer="referencia"
                            placeholder="Ingrese referencia del lugar atención..." />
                        <x-jet-input-error for="referencia" />
                    </div>
                    <div class="mt-2 sm:mt-0">
                        <x-label value="Fecha / Hora Visita :" />
                        <x-input class="block w-full" wire:model.defer="datevisita" type="datetime-local" />
                        <x-jet-input-error for="datevisita" />
                    </div>
                </div>
            </x-card-next>
        @endif

        <div class="w-full py-2 text-center">
            <p wire:loading class="text-xs tracking-widest shadow-lg text-next-500 rounded-lg bg-white p-1 px-2">
                Cargando...</p>
        </div>

        <x-jet-input-error for="estate.id" />
        <x-jet-input-error for="moneda.id" />


        <div class="w-full flex flex-row pt-4 gap-2 justify-end text-right">
            <x-button type="submit" size="xs" class="" wire:loading.attr="disabled" wire:target="save">
                {{ __('REGISTRAR') }}
            </x-button>
        </div>
    </form>

    <div class="mt-3 max-w-7xl md:p-3 mx-auto shadow-xl rounded">
        @livewire('soporte::orders.latest-orders')
    </div>


    <x-jet-dialog-modal wire:model="openModalClient" maxWidth="3xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Nuevo cliente') }}
            <x-button-close-modal wire:click="$toggle('openModalClient')" wire:loading.attr="disabled"/>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="saveClient">
                <div class="w-full sm:grid grid-cols-3 gap-2">
                    <div class="w-full">
                        <x-label value="DNI / RUC :" />
                        <div class="w-full inline-flex">
                            <x-input class="block w-full" wire:model="documentclient" placeholder="" />
                            <x-button-add class="px-2" wire:click="">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M14 19a6 6 0 0 0-12 0" />
                                    <circle cx="8" cy="9" r="4" />
                                    <line x1="19" x2="19" y1="8" y2="14" />
                                    <line x1="22" x2="16" y1="11" y2="11" />
                                </svg>
                            </x-button-add>
                        </div>
                        <x-jet-input-error for="documentclient" />
                    </div>
                    <div class="w-full sm:col-span-2 mt-2 sm:mt-0">
                        <x-label value="Cliente (Razón Social) :" />
                        <x-input class="block w-full" wire:model.defer="nameclient"
                            placeholder="Nombres / razón social del cliente" />
                        <x-jet-input-error for="nameclient" />
                    </div>
                    <div class="w-full mt-2 sm:mt-0">
                        <x-label value="Ubigeo :" />
                        <x-select class="block w-full" wire:model.defer="ubigeo_id" id="ubigeo_id">
                            <x-slot name="options">

                            </x-slot>
                        </x-select>
                        <x-jet-input-error for="ubigeo_id" />
                    </div>
                    <div class="w-full sm:col-span-2 mt-2 sm:mt-0">
                        <x-label value="Dirección :" />
                        <x-input class="block w-full" wire:model.defer="direccionClient"
                            placeholder="Dirección del cliente..." />
                        <x-jet-input-error for="direccionClient" />
                    </div>
                    <div class="w-full mt-2 sm:mt-0">
                        <x-label value="Correo :" />
                        <x-input class="block w-full" wire:model.defer="emailClient"
                            placeholder="Correo del cliente..." />
                        <x-jet-input-error for="emailClient" />
                    </div>
                    <div class="w-full mt-2 sm:mt-0">
                        <x-label value="Sexo :" />
                        <x-select class="block w-full" wire:model.defer="sexoClient" id="sexoClient">
                            <x-slot name="options">
                                <option value="M">MASCULINO</option>
                                <option value="F">FEMENINO</option>
                            </x-slot>
                        </x-select>
                        <x-jet-input-error for="sexoClient" />
                    </div>
                    <div class="w-full mt-2 sm:mt-0">
                        <x-label value="Fecha nacimiento :" />
                        <x-input type="date" class="block w-full" wire:model.defer="nacimientoClient"
                            placeholder="Correo del cliente..." />
                        <x-jet-input-error for="nacimientoClient" />
                    </div>
                    <div class="w-full mt-2 sm:mt-0">
                        <x-label value="Lista precio :" />
                        <x-select class="block w-full" wire:model.defer="pricetype_id" id="pricetype_id">
                            <x-slot name="options">
                                {{-- @if (count($atenciones))
                                        @foreach ($atenciones as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    @endif --}}
                            </x-slot>
                        </x-select>
                        <x-jet-input-error for="pricetype_id" />
                    </div>
                    <div class="w-full mt-2 sm:mt-0">
                        <x-label value="Canal registro :" />
                        <x-select class="block w-full" wire:model.defer="channelsale_id" id="channelsale_id">
                            <x-slot name="options">
                                {{-- @if (count($atenciones))
                                        @foreach ($atenciones as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    @endif --}}
                            </x-slot>
                        </x-select>
                        <x-jet-input-error for="channelsale_id" />
                    </div>
                    <div class="w-full mt-2 sm:mt-0">
                        <x-label value="Teléfono :" />
                        <x-input class="block w-full" wire:model.defer="telefonoClient" placeholder="+51 999 999 999"
                            maxlength="9" />
                        <x-jet-input-error for="telefonoClient" />
                    </div>
                </div>

                @if ($mostrarcontacto)
                    <div class="animate__animated animate__bounceIn animate__faster">
                        <x-title-next titulo="Contacto" class="my-3" />

                        <div class="w-full sm:grid grid-cols-3 gap-2">
                            <div class="w-full">
                                <x-label value="DNI :" />
                                <div class="w-full inline-flex">
                                    <x-input class="block w-full" wire:model.defer="documentContact"
                                        maxlength="8" />
                                    <x-button-add class="px-2" wire:click="">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M14 19a6 6 0 0 0-12 0" />
                                            <circle cx="8" cy="9" r="4" />
                                            <line x1="19" x2="19" y1="8" y2="14" />
                                            <line x1="22" x2="16" y1="11" y2="11" />
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
                        wire:target="saveClient">
                        {{ __('REGISTRAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <x-jet-dialog-modal wire:model="openModalTelefono" maxWidth="3xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Nuevo cliente') }}
            <x-button-add wire:click="$toggle('openModalTelefono')" wire:loading.attr="disabled">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </x-button-add>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="saveTelefono">
                <div class="w-full mt-2 sm:mt-0">
                    <x-label value="Teléfono :" />
                    <x-input class="block w-full" wire:model.defer="newtelefono" placeholder="+51 999 999 999"
                        maxlength="9" />
                    <x-jet-input-error for="newtelefono" />
                    <x-jet-input-error for="client" />
                </div>

                <div class="w-full flex flex-row pt-4 gap-2 justify-end text-right">
                    <x-button type="submit" size="xs" class="" wire:loading.attr="disabled"
                        wire:target="saveTelefono">
                        {{ __('REGISTRAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <x-jet-dialog-modal wire:model="openModalContacto" maxWidth="3xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Nuevo cliente') }}
            <x-button-add wire:click="$toggle('openModalContacto')" wire:loading.attr="disabled">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </x-button-add>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="saveContacto">

                <div class="w-full gap-2 flex-wrap sm:flex-nowrap flex">
                    <div class="w-full sm:w-1/3">
                        <x-label value="DNI :" />
                        <div class="w-full inline-flex">
                            <x-input class="block w-full" wire:model="newdocumentcontacto" maxlength="8" />
                            <x-button-add class="px-2" wire:click="">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M14 19a6 6 0 0 0-12 0" />
                                    <circle cx="8" cy="9" r="4" />
                                    <line x1="19" x2="19" y1="8" y2="14" />
                                    <line x1="22" x2="16" y1="11" y2="11" />
                                </svg>
                            </x-button-add>
                        </div>
                        <x-jet-input-error for="newdocumentcontacto" />
                    </div>

                    <div class="w-full sm:w-2/3 mt-2 sm:mt-0">
                        <x-label value="Nombres contacto :" />
                        <x-input class="block w-full" wire:model.defer="newnamecontacto"
                            placeholder="Nombres del contacto..." />
                        <x-jet-input-error for="newnamecontacto" />
                    </div>
                </div>

                <div class="w-full sm:w-1/2 mt-2 sm:mt-0">
                    <x-label value="Teléfono :" />
                    <x-input class="block w-full" wire:model.defer="newtelefonocontacto"
                        placeholder="+51 999 999 999" maxlength="9" />
                    <x-jet-input-error for="newtelefonocontacto" />
                </div>

                <div class="w-full flex flex-row pt-4 gap-2 justify-end text-right">
                    <x-button type="submit" size="xs" class="" wire:loading.attr="disabled"
                        wire:target="saveContacto">
                        {{ __('REGISTRAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <x-jet-dialog-modal wire:model="opemModalAutollenado" maxWidth="3xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Autocompletar especificaciones del equipo') }}
            <x-button-add wire:click="$toggle('opemModalAutollenado')" wire:loading.attr="disabled">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </x-button-add>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="send_autollenado" class="text-xs">
                @if (count($caracteristicas))
                    @foreach ($caracteristicas as $item)
                        <fieldset class="w-full border p-2 rounded-sm border-next-500 mb-2">
                            <legend class="text-next-500 text-sm px-1">{{ $item->name }}</legend>
                            <div class="w-full flex gap-2 flex-wrap">
                                @if (count($item->especificacions))
                                    <x-label class="flex items-center gap-1 cursor-pointer bg-next-100 rounded-lg p-1"
                                        for="ninguno_{{ $item->id }}">
                                        <x-input wire:model.defer="autocomplete.{{ $item->id }}"
                                            name="especificaciones_{{ $item->id }}[]" type="radio"
                                            id="ninguno_{{ $item->id }}" value="" />
                                        Ninguno
                                    </x-label>
                                    @foreach ($item->especificacions as $especificacion)
                                        <x-label
                                            class="flex items-center gap-1 cursor-pointer bg-next-100 rounded-lg p-1"
                                            for="especificacion_{{ $item->id }}{{ $especificacion->id }}">
                                            <x-input wire:model.defer="autocomplete.{{ $item->id }}"
                                                name="especificaciones_{{ $item->id }}[]" type="radio"
                                                id="especificacion_{{ $item->id }}{{ $especificacion->id }}"
                                                value="{{ $item->name }} : {{ $especificacion->name }}" />
                                            {{ $especificacion->name }}
                                        </x-label>
                                    @endforeach
                                @endif
                            </div>
                        </fieldset>
                    @endforeach
                @endif

                <fieldset class="w-full border p-2 rounded-sm border-next-500 mb-2">
                    <legend class="text-next-500 text-sm px-1">CARGADOR</legend>
                    <div class="w-full flex mt-2 sm:mt-0">
                        <x-label value="S/N° :" class="w-12" />
                        <x-input class="block w-full" wire:model.defer="autocomplete.cargador" />
                    </div>
                </fieldset>

                <fieldset class="w-full border p-2 rounded-sm border-next-500 mb-2">
                    <legend class="text-next-500 text-sm px-1">SUMINISTROS</legend>
                    <div class="w-full flex gap-2 flex-wrap">
                        <div class="w-full flex mt-2 sm:mt-0">
                            <x-label value="Tintas :" class="w-20" />
                            <x-input class="block w-full" wire:model.defer="autocomplete.tintas" />
                        </div>
                        <div class="w-full flex mt-2 sm:mt-0">
                            <x-label value="Contador páginas :" class="w-20" />
                            <x-input class="block w-full" wire:model.defer="autocomplete.contadorpaginas" />
                        </div>
                        <div class="w-full flex mt-2 sm:mt-0">
                            <x-label value="Toner :" class="w-20" />
                            <x-input class="block w-full" wire:model.defer="autocomplete.toner" />
                        </div>
                    </div>
                </fieldset>

                <div class="w-full flex flex-row pt-4 gap-2 justify-end text-right">
                    <x-button type="submit" size="xs" class="" wire:loading.attr="disabled"
                        wire:target="">
                        {{ __('CONFIRMAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>


    <script>
        document.addEventListener('livewire:load', function() {

            Livewire.onPageExpired((response, message) => {
                console.log(message);
                console.log(response);
                console.log("sesion expirada");
            });

            var toastMixin = Swal.mixin({
                toast: true,
                icon: "success",
                title: "Mensaje",
                position: "top-right",
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener("mouseenter", Swal.stopTimer);
                    toast.addEventListener("mouseleave", Swal.resumeTimer);
                },
            });

            $('#orderclient_id').select2({
                placeholder: "Seleccionar..."
            });

            $('#orderdireccion_id').select2({
                placeholder: "Seleccionar..."
            });

            $('#orderclient_id').on("change", function() {
                @this.set('client_id', this.value);
            });

            $('#orderdireccion_id').on("change", function() {
                @this.set('direccion', this.value);
            });

            window.addEventListener('render-select2', event => {
                $('#orderclient_id').select2({
                    placeholder: "Seleccionar..."
                });
                $('#orderdireccion_id').select2({
                    placeholder: "Seleccionar..."
                });
                console.log("Condition Selected : " + @this.condition_id);
                // @this.set('condition_id', @this.condition_id);
                // console.log("Direccion Selected : " + @this.direccion);
            });


            $('input').on('change', function(e) {
                
                if ((this.getAttribute("name") == "order_atencion")) {
                    e.target.setAttribute("disabled", true);
                    // $("#subcategoryproducto_id").attr("disabled", true);
                    @this.set('atencion_id', this.value);
                }

                if ((this.getAttribute("name") == "order_entorno")) {
                    e.target.setAttribute("disabled", true);
                    @this.set('entorno_id', this.value);
                }

                if ((this.getAttribute("name") == "order_condition")) {
                    e.target.setAttribute("disabled", true);
                    @this.set('condition_id', this.value);
                }

            });


            // window.addEventListener('soporte::status.confirmDelete', data => {
            //     swal.fire({
            //         title: 'Eliminar registro con nombre: ' + data.detail.name,
            //         text: "Se eliminará un registro de la base de datos",
            //         icon: 'question',
            //         showCancelButton: true,
            //         confirmButtonColor: '#0FB9B9',
            //         cancelButtonColor: '#d33',
            //         confirmButtonText: 'Confirmar',
            //         cancelButtonText: 'Cancelar'
            //     }).then((result) => {
            //         if (result.isConfirmed) {
            //             Livewire.emit('deleteStatus', data.detail.id);
            //         }
            //     })
            // })



            // window.addEventListener('soporte::status.deleted', event => {
            //     toastMixin.fire({
            //         title: 'Eliminado correctamente'
            //     });
            // })
        })
    </script>
</div>
