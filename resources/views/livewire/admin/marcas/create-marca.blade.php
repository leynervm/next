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
                <div class="w-full relative">
                    <div wire:loading.flex class="loading-overlay rounded hidden">
                        <x-loading-next />
                    </div>

                    @if (isset($logo))
                        <x-simple-card class="w-40 h-40 md:max-w-md mx-auto mb-1 border border-borderminicard">
                            <img class="w-full h-full object-scale-down animate__animated animate__fadeIn animate__faster"
                                src="{{ $logo->temporaryUrl() }}" />
                        </x-simple-card>
                    @else
                        <x-icon-file-upload type="file" class="w-36 h-36 text-gray-300" />
                    @endif

                    <div class="w-full flex flex-wrap gap-2 justify-center">
                        @if (isset($logo))
                            <x-button class="inline-flex" wire:loading.attr="disabled"
                                wire:click="clearImage">LIMPIAR</x-button>
                        @else
                            <x-input-file for="{{ $identificador }}" titulo="SELECCIONAR IMAGEN" wire:loading.remove
                                wire:target="logo">
                                <input type="file" class="hidden" wire:model="logo" id="{{ $identificador }}"
                                    accept="image/jpg, image/jpeg, image/png" />
                            </x-input-file>
                        @endif
                    </div>
                </div>
                <x-jet-input-error for="logo" class="text-center" />

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
