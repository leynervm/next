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
            {{ __('Nueva marca') }}
            <x-button-close-modal wire:click="$toggle('open')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save">

                <div x-data="{ isUploading: false, progress: 0 }" x-on:livewire-upload-start="isUploading = true"
                    x-on:livewire-upload-finish="isUploading = false"
                    x-on:livewire-upload-error="$emit('errorImage'), isUploading = false"
                    x-on:livewire-upload-progress="progress = $event.detail.progress" class="w-full relative">

                    @if (isset($logo))
                        <div
                            class="w-full h-60 shadow-md shadow-shadowminicard border rounded-lg border-borderminicard overflow-hidden mb-1 duration-300">
                            <img class="w-full h-full object-scale-down" src="{{ $logo->temporaryUrl() }}"
                                alt="">
                        </div>
                    @else
                        <div
                            class="w-full flex items-center justify-center h-60 shadow-md shadow-shadowminicard border rounded-lg border-borderminicard mb-1">
                            <svg class="text-neutral-500 w-24 h-24" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path
                                    d="M13 3.00231C12.5299 3 12.0307 3 11.5 3C7.02166 3 4.78249 3 3.39124 4.39124C2 5.78249 2 8.02166 2 12.5C2 16.9783 2 19.2175 3.39124 20.6088C4.78249 22 7.02166 22 11.5 22C15.9783 22 18.2175 22 19.6088 20.6088C20.9472 19.2703 20.998 17.147 20.9999 13" />
                                <path
                                    d="M2 14.1354C2.61902 14.0455 3.24484 14.0011 3.87171 14.0027C6.52365 13.9466 9.11064 14.7729 11.1711 16.3342C13.082 17.7821 14.4247 19.7749 15 22" />
                                <path
                                    d="M21 16.8962C19.8246 16.3009 18.6088 15.9988 17.3862 16.0001C15.5345 15.9928 13.7015 16.6733 12 18" />
                                <path
                                    d="M17 4.5C17.4915 3.9943 18.7998 2 19.5 2M22 4.5C21.5085 3.9943 20.2002 2 19.5 2M19.5 2V10" />
                            </svg>
                        </div>
                    @endif

                    <div x-show="isUploading" wire:loading.flex class="loading-overlay rounded">
                        <x-loading-next />
                    </div>

                    <x-input-file for="{{ $identificador }}" titulo="SELECCIONAR IMAGEN" wire:loading.remove
                        wire:target="logo">
                        <input type="file" class="hidden" wire:model="logo" id="{{ $identificador }}"
                            accept="image/jpg, image/jpeg, image/png" />

                        @if (isset($logo))
                            <x-slot name="clear">
                                <x-button class="inline-flex px-6" size="xs" wire:loading.attr="disabled"
                                    wire:target="clearImage" wire:click="clearImage">
                                    LIMPIAR</x-button>
                            </x-slot>
                        @endif
                    </x-input-file>
                </div>
                <x-jet-input-error wire:loading.remove wire:target="logo" for="logo" class="text-center" />

                <x-label class="mt-3" value="Marca :" />
                <x-input class="block w-full" wire:model.defer="name" placeholder="Ingrese nombre de marca..." />
                <x-jet-input-error for="name" />

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('REGISTRAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

</div>
