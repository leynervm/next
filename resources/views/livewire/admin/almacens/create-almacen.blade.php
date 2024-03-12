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
            {{ __('Nuevo almacén') }}
            <x-button-close-modal wire:click="$toggle('open')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save" class="relative">
                <div class="w-full">
                    <x-label value="Nombre :" />
                    <x-input class="block w-full" wire:model.defer="name" placeholder="Descripcion de caja..." />
                    <x-jet-input-error for="name" />
                </div>

                <div class="w-full mt-2">
                    <x-label-check for="default">
                        <x-input wire:model.defer="default" name="default" value="1" type="checkbox"
                            id="default" />
                        SELECCIONAR COMO PREDETERMINADO
                    </x-label-check>
                    <x-jet-input-error for="default" />
                </div>

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('REGISTRAR') }}
                    </x-button>
                </div>

                <div wire:loading.flex class="loading-overlay rounded hidden">
                    <x-loading-next />
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>
</div>
