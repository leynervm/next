<div>
    <form wire:submit.prevent="save" class="mt-3 max-w-7xl mx-auto">
        <x-card-next titulo="Centro Servicios" class="w-full border border-next-500 ">
            <div class="w-full sm:grid grid-cols-3 gap-2">
                <div class="w-full">
                    <x-label value="DNI / RUC :" />
                    <div class="w-full inline-flex">
                        <x-input class="block w-full" wire:model="document" placeholder="" />
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
                    <x-jet-input-error for="document" />
                </div>
                <div class="w-full sm:col-span-2 mt-2 sm:mt-0">
                    <x-label value="Cliente (Razón Social) :" />
                    <x-input class="block w-full" wire:model.defer="name"
                        placeholder="Nombres / razón social del cliente" />
                    <x-jet-input-error for="name" />
                </div>
                <div class="w-full mt-2 sm:mt-0">
                    <x-label value="Ubigeo :" />
                    <x-select class="block w-full" wire:model.defer="ubigeo_id">
                        <x-slot name="options">

                        </x-slot>
                    </x-select>
                    <x-jet-input-error for="ubigeo_id" />
                </div>
                <div class="w-full sm:col-span-2 mt-2 sm:mt-0">
                    <x-label value="Dirección :" />
                    <x-input class="block w-full" wire:model.defer="direccion" placeholder="Dirección del centro atención..." />
                    <x-jet-input-error for="direccion" />
                </div>
                <div class="w-full mt-2 sm:mt-0">
                    <x-label value="Marca :" />
                    <x-select class="block w-full" wire:model.defer="marca_id">
                        <x-slot name="options">
                            @if (count($marcas))
                                @foreach ($marcas as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            @endif
                        </x-slot>
                    </x-select>
                    <x-jet-input-error for="marca_id" />
                </div>
                <div class="w-full mt-2 sm:mt-0">
                    <x-label value="Condición atención :" />
                    <x-select class="block w-full" wire:model.defer="condition_id">
                        <x-slot name="options">
                            @if (count($conditions))
                                @foreach ($conditions as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            @endif
                        </x-slot>
                    </x-select>
                    <x-jet-input-error for="condition_id" />
                </div>
                <div class="w-full mt-2 sm:mt-0">
                    <x-label value="Moneda :" />
                    <x-select class="block w-full" wire:model.defer="moneda_id">
                        <x-slot name="options">
                            {{-- @if (count($monedas))
                                @foreach ($monedas as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            @endif --}}
                        </x-slot>
                    </x-select>
                    <x-jet-input-error for="moneda_id" />
                </div>
                <div class="w-full mt-2 sm:mt-0">
                    <x-label value="Correo :" />
                    <x-input class="block w-full" wire:model.defer="email" placeholder="Correo del cliente..." />
                    <x-jet-input-error for="email" />
                </div>
                <div class="w-full mt-2 sm:mt-0">
                    <x-label value="Teléfono :" />
                    <x-input class="block w-full" wire:model.defer="telefono" placeholder="+51 999 999 999"
                        maxlength="9" />
                    <x-jet-input-error for="telefono" />
                </div>
            </div>
        </x-card-next>

        @if ($mostrarcontacto)
            <x-card-next titulo="Contacto"
                class="w-full border border-next-500 mt-3 animate__animated animate__bounceIn animate__faster">
                <div class="w-full sm:grid grid-cols-3 gap-2">
                    <div class="w-full">
                        <x-label value="DNI :" />
                        <div class="w-full inline-flex">
                            <x-input class="block w-full" wire:model.defer="documentContact" maxlength="8" />
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
                        <x-input class="block w-full" wire:model.defer="telefonoContact" placeholder=""
                            maxlength="9" />
                        <x-jet-input-error for="telefonoContact" />
                    </div>
                </div>
            </x-card-next>
        @endif

        <div class="w-full flex flex-row pt-4 gap-2 justify-end text-right">
            <x-button type="submit" size="xs" class="" wire:loading.attr="disabled" wire:target="save">
                {{ __('REGISTRAR') }}
            </x-button>
        </div>

    </form>
</div>
