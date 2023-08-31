<div>

    <x-button-next titulo="Registrar" wire:click="$set('open', true)">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" x2="12" y1="5" y2="19" />
            <line x1="5" x2="19" y1="12" y2="12" />
        </svg>
    </x-button-next>
    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Nuevo tipo equipo') }}
            <x-button-add wire:click="$toggle('open')" wire:loading.attr="disabled">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </x-button-add>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save">

                <div x-data="{ isUploading: @entangle('isUploading'), progress: 0 }" x-on:livewire-upload-start="isUploading = true"
                    x-on:livewire-upload-finish="isUploading = false"
                    x-on:livewire-upload-error="$wire.emit('errorImage'), isUploading = false"
                    x-on:livewire-upload-progress="progress = $event.detail.progress">

                    <div x-show="isUploading" class="loading-overlay">
                        <div class="spinner"></div>
                    </div>

                    @if (isset($logo))
                        <div class="w-full h-60 md:max-w-md mx-auto mb-1 duration-300">
                            <img class="w-full h-full object-scale-down" src="{{ $logo->temporaryUrl() }}"
                                alt="">
                        </div>
                    @endif

                    <x-input-file for="{{ $identificador }}" titulo="SELECCIONAR IMAGEN" wire:loading.remove
                        wire:target="logo">
                        <input type="file" class="hidden" wire:model="logo" id="{{ $identificador }}"
                            accept="image/jpg, image/jpeg, image/png" />

                        @if (isset($logo))
                            <x-slot name="clear">
                                <x-button class="inline-flex px-6" size="xs" wire:loading.attr="disabled"
                                    wire:target="clearImage" wire:click="clearImage">
                                    Limpiar
                                </x-button>
                            </x-slot>
                        @endif
                    </x-input-file>
                </div>
                <x-jet-input-error wire:loading.remove wire:target="logo" for="logo" class="text-center" />


                <x-label class="mt-3" value="Tipo equipo :" />
                <x-input class="block w-full" wire:model.defer="name"
                    placeholder="Ingrese nombre del tipo de equipo..." />
                <x-jet-input-error for="name" />

                <div class="w-full flex flex-row pt-4 gap-2 justify-end text-right">
                    <x-button type="submit" size="xs" wire:loading.attr="disabled" wire:target="save">
                        {{ __('REGISTRAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

</div>
