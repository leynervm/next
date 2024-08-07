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
            {{ __('Nueva forma pago') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save" class="w-full flex flex-col gap-2">
                <div class="w-full">
                    <x-label value="Forma pago :" />
                    <div class="w-full flex flex-wrap gap-2">
                        <x-input-radio class="py-2" for="efectivo" text="EFECTIVO">
                            <input wire:model.defer="type" class="sr-only peer peer-disabled:opacity-25" type="radio"
                                id="efectivo" name="type" value="0" />
                        </x-input-radio>
                        <x-input-radio class="py-2" for="transferencia" text="TRANSFERENCIA">
                            <input wire:model.defer="type" class="sr-only peer peer-disabled:opacity-25" type="radio"
                                id="transferencia" name="type" value="1" />
                        </x-input-radio>
                    </div>
                    <x-jet-input-error for="type" />
                </div>

                <div class="w-full">
                    <x-label value="Medio pago :" />
                    <x-input class="block w-full" wire:model.defer="name"
                        placeholder="Ingrese descripción del pago..." />
                    <x-jet-input-error for="name" />
                </div>

                <div class="block">
                    <x-label-check for="default">
                        <x-input wire:model.defer="default" name="default" value="1" type="checkbox"
                            id="default" />SELECCIONAR COMO PREDETERMINADO</x-label-check>
                    <x-jet-input-error for="default" />
                </div>

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('Save') }}</x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>
</div>
