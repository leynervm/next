<div>
    <x-card-next titulo="Información del cliente">
        <div class="w-full flex gap-2 flex-wrap sm:flex-nowrap">
            <div class="w-full sm:w-1/2">
                <x-label value="Cliente / Razón social" textSize="xs" />
                <p class="inline-block text-xs bg-fondospancardproduct text-textspancardproduct p-1 rounded">
                    {{ $client->document }} - {{ $client->name }}</p>

                <x-label value="Direcciones :" textSize="xs" class="mt-2" />
                @if ($client->direccions)
                    @foreach ($client->direccions as $dir)
                        <div class="mb-1">
                            <div
                                class="inline-flex items-center justify-center gap-1 bg-fondospancardproduct text-textspancardproduct p-1 rounded">
                                <span class="w-3 h-3 block">
                                    <svg class="w-full h-full" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 10c0 4.418-8 12-8 12s-8-7.582-8-12a8 8 0 1116 0z" />
                                        <path d="M12 11a1 1 0 100-2 1 1 0 000 2z" />
                                    </svg>
                                </span>
                                <p class="inline-flex text-[10px] font-semibold">{{ $dir->name }}</p>
                                <div class="ml-2">
                                    <x-button-edit wire:click="editDireccion({{ $dir->id }})"
                                        wire:loading.attr="disabled" wire:target="editDireccion"></x-button-edit>
                                    <x-button-delete wire:click="confirmdeletedireccion({{ $dir->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="confirmdeletedireccion"></x-button-delete>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

                <div class="w-full mt-3">
                    <x-button size="xs" class="" wire:click="openmodaldireccion" wire:loading.attr="disabled"
                        wire:target="openmodaldireccion">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 9.2C16 13.177 9 20 9 20S2 13.177 2 9.2C2 5.224 5.134 2 9 2s7 3.224 7 7.2z" />
                            <path d="M9 10a1 1 0 100-2 1 1 0 000 2z" />
                            <path d="M16 19h3m3 0h-3m0 0v-3m0 3v3" />
                        </svg>
                    </x-button>
                </div>

                <x-label value="Lista precio :" textSize="xs" class="mt-2" />
                <p class="inline-block text-xs bg-fondospancardproduct text-textspancardproduct p-1 rounded">
                    {{ $client->pricetype->name }}</p>

                <x-label value="Origen :" textSize="xs" class="mt-2" />
                <p class="inline-block text-xs bg-fondospancardproduct text-textspancardproduct p-1 rounded">
                    {{ $client->channelsale->name }}</p>


            </div>
            <div class="w-full sm:w-1/2">
                <x-label value="Fecha nacimiento / Sexo:" textSize="xs" />
                <p class="inline-block text-xs bg-fondospancardproduct text-textspancardproduct p-1 rounded uppercase">
                    {{ $client->nacimiento? Carbon\Carbon::parse($client->nacimiento)->locale('es')->isoFormat('DD MMMM Y'): '- - ' }}

                    @if ($client->sexo == 'E')
                        (EMPRESARIAL)
                    @elseif($client->sexo == 'M')
                        (MASCULINO)
                    @elseif($client->sexo == 'F')
                        (FEMENINO)
                    @else
                        (- - -)
                    @endif
                </p>

                <x-label value="Correo :" textSize="xs" class="mt-2" />
                <p class="inline-block text-xs bg-fondospancardproduct text-textspancardproduct p-1 rounded">
                    {{ $client->email ?? '- - -' }}</p>

                <x-label value="Teléfonos :" textSize="xs" class="mt-2" />
                @if (count($client->telephones))
                    <div class="w-full flex gap-1 flex-wrap">
                        @foreach ($client->telephones as $telef)
                            <div
                                class="inline-flex items-center justify-center gap-1 bg-green-100 text-green-500 p-1 rounded">
                                <span class="w-3 h-3 block">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path
                                            d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                                    </svg>
                                </span>
                                <span class="text-[10px] font-semibold">{{ $telef->phone }}</span>
                                <div class="ml-2">
                                    <x-button-edit wire:click="editTelefono({{ $telef->id }})"
                                        wire:loading.attr="disabled" wire:target="editTelefono"></x-button-edit>
                                    <x-button-delete wire:click="confirmdeletephone({{ $telef->id }})"
                                        wire:loading.attr="disabled" wire:target="confirmdeletephone"></x-button-delete>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="w-full mt-3">
                    <x-button size="xs" class="" wire:click="openmodalphone" wire:loading.attr="disabled"
                        wire:target="openmodalphone">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M16.243 5.243h3m3 0h-3m0 0v-3m0 3v3M18.118 14.702L14 15.5c-2.782-1.396-4.5-3-5.5-5.5l.77-4.13L7.815 2H4.064c-1.128 0-2.016.932-1.847 2.047.42 2.783 1.66 7.83 5.283 11.453 3.805 3.805 9.286 5.456 12.302 6.113 1.165.253 2.198-.655 2.198-1.848v-3.584l-3.882-1.479z" />
                        </svg>
                    </x-button>
                </div>
            </div>
        </div>
        <div class="w-full flex items-center justify-end gap-1 mt-3">
            <x-button-edit wire:click="edit({{ $client->id }})" wire:loading.attr="disabled"
                wire:target="edit"></x-button-edit>
            <x-button-delete wire:click="confirmdelete({{ $client->id }})" wire:loading.attr="disabled"
                wire:target="confirmdelete"></x-button-delete>
        </div>
    </x-card-next>

    @if (Str::length(trim($client->document)) == 11)
        @if (count($client->contacts))
            <x-card-next titulo="Contactos" class="mt-3">
                <div class="w-full flex flex-col lg:flex-row gap-3 justify-between items-end">
                    <div class="w-full flex gap-2 flex-wrap">
                        @foreach ($client->contacts as $item)
                            <div
                                class="w-full sm:w-48 p-1 inline-flex flex-wrap font-semibold text-center text-textcardnext text-[10px] bg-fondominicard border rounded-lg shadow shadow-shadowminicard hover:shadow-md hover:shadow-shadowminicard cursor-pointer transition ease-in-out duration-150">
                                <p class="block w-full  text-textspancardproduct text-center p-1 rounded-lg">
                                    ({{ $item->document }})
                                    - {{ $item->name }}</p>

                                @if (count($item->telephones))
                                    <div class="mt-1 w-full flex items-center justify-around gap-1 flex-wrap">
                                        @foreach ($item->telephones as $phone)
                                            <div
                                                class="inline-flex items-center justify-center gap-1 bg-green-100 text-green-500 p-1 rounded">
                                                <span class="w-3 h-3 block">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path
                                                            d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                                                    </svg>
                                                </span>
                                                <span class="text-[10px] font-semibold">{{ $phone->phone }}</span>
                                                <div class="ml-2">
                                                    <x-button-edit
                                                        wire:click="editphonecontacto({{ $item->id }}, {{ $phone->id }})"
                                                        wire:loading.attr="disabled"
                                                        wire:target="editphonecontacto"></x-button-edit>
                                                    <x-button-delete
                                                        wire:click="confirmdeletephone({{ $phone->id }})"
                                                        wire:loading.attr="disabled"
                                                        wire:target="confirmdeletephone"></x-button-delete>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="w-full flex items-end justify-between mt-1">
                                    <x-mini-button class="" size="xs"
                                        wire:click="openmodalphonecontacto({{ $item->id }})"
                                        wire:loading.attr="disabled" wire:target="openmodalphonecontacto">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path
                                                d="M16.243 5.243h3m3 0h-3m0 0v-3m0 3v3M18.118 14.702L14 15.5c-2.782-1.396-4.5-3-5.5-5.5l.77-4.13L7.815 2H4.064c-1.128 0-2.016.932-1.847 2.047.42 2.783 1.66 7.83 5.283 11.453 3.805 3.805 9.286 5.456 12.302 6.113 1.165.253 2.198-.655 2.198-1.848v-3.584l-3.882-1.479z" />
                                        </svg>
                                    </x-mini-button>
                                    <div>
                                        <x-button-edit wire:click="editcontacto({{ $item->id }})"
                                            wire:loading.attr="disabled" wire:target="editcontacto"></x-button-edit>
                                        <x-button-delete wire:click="confirmdeletecontacto({{ $item->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="confirmdeletecontacto"></x-button-delete>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <x-button type="button" size="xs" class="" wire:loading.attr="disabled"
                        wire:target="openmodalcontacto" wire:click="openmodalcontacto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 20v-1a7 7 0 017-7v0a7 7 0 017 7v1" />
                            <path d="M13 14v0a5 5 0 015-5v0a5 5 0 015 5v.5" />
                            <path d="M8 12a4 4 0 100-8 4 4 0 000 8zM18 9a3 3 0 100-6 3 3 0 000 6z" />
                        </svg>
                    </x-button>
                </div>
            </x-card-next>
        @endif
    @endif

    @if ($client->user)
        <x-card-next titulo="Usuario web" class="w-96 mt-3">
            <x-minicard title="" size="lg">
                <span class="w-10 h-10 mx-auto bg-neutral-600 text-white rounded-full p-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2z" />
                        <path
                            d="M4.271 18.346S6.5 15.5 12 15.5s7.73 2.846 7.73 2.846M12 12a3 3 0 100-6 3 3 0 000 6z" />
                    </svg>
                </span>
                <p class="text-xs truncate overflow-hidden text-center">{{ $client->user->email }}</p>
                <x-slot name="buttons">
                    <x-button-edit></x-button-edit>
                    <x-button-delete></x-button-delete>
                </x-slot>
            </x-minicard>
        </x-card-next>
    @endif

    <x-jet-dialog-modal wire:model="open" maxWidth="3xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar cliente') }}
            <x-button-add wire:click="$toggle('open')" wire:loading.attr="disabled">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </x-button-add>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update" id="form_edit_client">
                <div class="w-full sm:grid grid-cols-3 gap-2">
                    <div class="w-full">
                        <x-label value="DNI / RUC :" />
                        <div class="w-full inline-flex">
                            <x-input class="block w-full" wire:model="client.document" />
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
                        <x-jet-input-error for="client.document" />
                    </div>
                    <div class="w-full sm:col-span-2 mt-2 sm:mt-0">
                        <x-label value="Cliente (Razón Social) :" />
                        <x-input class="block w-full" wire:model.defer="client.name"
                            placeholder="Nombres / razón social del cliente" />
                        <x-jet-input-error for="client.name" />
                    </div>
                    <div class="w-full mt-2 sm:mt-0">
                        <x-label value="Correo :" />
                        <x-input class="block w-full" wire:model.defer="client.email"
                            placeholder="Correo del cliente..." />
                        <x-jet-input-error for="client.email" />
                    </div>
                    <div class="w-full mt-2 sm:mt-0">
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
                    <div class="w-full mt-2 sm:mt-0">
                        <x-label value="Fecha nacimiento :" />
                        <x-input type="date" class="block w-full" wire:model.defer="client.nacimiento"
                            placeholder="Correo del cliente..." />
                        <x-jet-input-error for="client.nacimiento" />
                    </div>
                    <div class="w-full mt-2 sm:mt-0">
                        <x-label value="Lista precio :" />
                        <x-select class="block w-full" wire:model.defer="client.pricetype_id"
                            id="edit_pricetype_id">
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
                    <div class="w-full mt-2 sm:mt-0">
                        <x-label value="Canal registro :" />
                        <x-select class="block w-full" wire:model.defer="client.channelsale_id"
                            id="edit_channelsale_id">
                            <x-slot name="options">
                                @if (count($channelsales))
                                    @foreach ($channelsales as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-jet-input-error for="client.channelsale_id" />
                    </div>

                </div>

                <div class="w-full flex flex-row pt-4 gap-2 justify-end text-right">
                    <x-button type="submit" size="xs" class="" wire:loading.attr="disabled"
                        wire:target="update">
                        {{ __('ACTUALIZAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <x-jet-dialog-modal wire:model="openphone" maxWidth="3xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Nuevo teléfono') }}
            <x-button-add wire:click="$toggle('openphone')" wire:loading.attr="disabled">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </x-button-add>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="savetelefono">
                <div class="w-full">
                    <x-label value="Teléfono :" />
                    <x-input class="block w-full" wire:model.defer="newtelefono" placeholder="+51 999 999 999"
                        maxlength="9" />
                    <x-jet-input-error for="newtelefono" />
                    <x-jet-input-error for="client.id" />
                </div>

                <div class="w-full flex flex-row pt-4 gap-2 justify-end text-right">
                    <x-button type="submit" size="xs" class="" wire:loading.attr="disabled"
                        wire:target="savetelefono">
                        {{ __('REGISTRAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <x-jet-dialog-modal wire:model="opendireccion" maxWidth="3xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Nueva dirección') }}
            <x-button-add wire:click="$toggle('opendireccion')" wire:loading.attr="disabled">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </x-button-add>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="saveDireccion">
                <div class="w-full ">
                    <x-label value="Ubigeo :" />
                    <x-select class="block w-full" wire:model.defer="ubigeo_id" id="edit_ubigeo_id">
                        <x-slot name="options">

                        </x-slot>
                    </x-select>
                    <x-jet-input-error for="ubigeo_id" />
                </div>
                <div class="w-full mt-2 sm:mt-0">
                    <x-label value="Dirección :" />
                    <x-input class="block w-full" wire:model.defer="newdireccion"
                        placeholder="Ingrese nueva direccion..." />
                    <x-jet-input-error for="newdireccion" />
                    <x-jet-input-error for="client.id" />
                </div>

                <div class="w-full flex flex-row pt-4 gap-2 justify-end text-right">
                    <x-button type="submit" size="xs" class="" wire:loading.attr="disabled"
                        wire:target="saveDireccion">
                        {{ __('REGISTRAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <x-jet-dialog-modal wire:model="openphonecontacto" maxWidth="3xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Teléfono contacto') }}
            <x-button-add wire:click="$toggle('openphonecontacto')" wire:loading.attr="disabled">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </x-button-add>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="savetelefonocontacto">
                <div class="w-full">
                    <x-label value="Teléfono :" />
                    <x-input class="block w-full" wire:model.defer="newtelefonocontacto"
                        placeholder="+51 999 999 999" maxlength="9" />
                    <x-jet-input-error for="newtelefonocontacto" />
                    <x-jet-input-error for="contact.id" />
                </div>

                <div class="w-full flex flex-row pt-4 gap-2 justify-end text-right">
                    <x-button type="submit" size="xs" class="" wire:loading.attr="disabled"
                        wire:target="savetelefonocontacto">
                        {{ __('REGISTRAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <x-jet-dialog-modal wire:model="opencontact" maxWidth="3xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Contacto cliente') }}
            <x-button-add wire:click="$toggle('opencontact')" wire:loading.attr="disabled">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </x-button-add>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="savecontacto">

                <div class="w-full gap-2 flex-wrap sm:flex-nowrap flex">
                    <div class="w-full sm:w-1/3">
                        <x-label value="DNI :" />
                        <div class="w-full inline-flex">
                            <x-input class="block w-full" wire:model="documentcontacto" maxlength="8" />
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
                        <x-jet-input-error for="documentcontacto" />
                    </div>

                    <div class="w-full sm:w-2/3">
                        <x-label value="Nombres contacto :" />
                        <x-input class="block w-full" wire:model.defer="namecontacto"
                            placeholder="Nombres del contacto..." />
                        <x-jet-input-error for="namecontacto" />
                    </div>
                </div>

                @if (!$contact)
                    <div class="w-full sm:w-1/2 mt-2">
                        <x-label value="Teléfono :" />
                        <x-input class="block w-full" type="number" wire:model.defer="telefonocontacto"
                            placeholder="+51 999 999 999" maxlength="9" />
                        <x-jet-input-error for="telefonocontacto" />
                    </div>
                @endif

                <div class="w-full flex flex-row pt-4 gap-2 justify-end text-right">
                    <x-button type="submit" size="xs" class="" wire:loading.attr="disabled"
                        wire:target="savecontacto">
                        {{ __('REGISTRAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        document.addEventListener("livewire:load", () => {

            renderSelect2();

            $("#edit_ubigeoclient_id").on("change", (e) => {
                deshabilitarSelects();
                @this.set('client.ubigeo_id', e.target.value);
            });

            $("#edit_pricetype_id").on("change", (e) => {
                deshabilitarSelects();
                @this.set('client.pricetype_id', e.target.value);
            });

            $("#edit_channelsale_id").on("change", (e) => {
                deshabilitarSelects();
                @this.set('client.channelsale_id', e.target.value);
            });

            window.addEventListener('client.confirmDelete', data => {
                swal.fire({
                    title: 'Eliminar registro con nombre: ' + data.detail.name,
                    text: "Se eliminará un registro de la base de datos",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.emitTo('admin.clients.view-client', 'delete', data.detail.id);
                    }
                })
            })

            window.addEventListener('client.confirmDeletecontacto', data => {
                swal.fire({
                    title: 'Eliminar registro con nombre: ' + data.detail.name,
                    text: "Se eliminará un registro de la base de datos",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.emitTo('admin.clients.view-client', 'deletecontacto', data.detail
                            .id);
                    }
                })
            })

            window.addEventListener('client.confirmDeletedireccion', data => {
                swal.fire({
                    title: 'Eliminar registro con nombre: ' + data.detail.name,
                    text: "Se eliminará un registro de la base de datos",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.emitTo('admin.clients.view-client', 'deletedireccion', data.detail
                            .id);
                    }
                })
            })

            window.addEventListener('client.confirmDeletephone', data => {
                swal.fire({
                    title: 'Eliminar wire:número telefónico: ' + data.detail.phone,
                    text: "Se eliminará un registro de la base de datos",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.emitTo('admin.clients.view-client', 'deletephone', data.detail.id);
                    }
                })
            })

            window.addEventListener('render-editclient-select2', () => {
                renderSelect2();
            });

            function renderSelect2() {
                var formulario = document.getElementById("form_edit_client");
                var selects = formulario.getElementsByTagName("select");

                for (var i = 0; i < selects.length; i++) {
                    if (selects[i].id !== "") {
                        $("#" + selects[i].id).select2({
                            placeholder: "Seleccionar...",
                            minimumResultsForSearch: Infinity
                        });
                    }
                }
            }

            function deshabilitarSelects() {
                var formulario = document.getElementById("form_edit_client");
                var selects = formulario.getElementsByTagName("select");

                for (var i = 0; i < selects.length; i++) {
                    selects[i].disabled = true;
                }
            }

        })
    </script>
</div>
