<div>
    <x-button-next titulo="Registrar Rango" wire:click="$set('open', true)">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" x2="12" y1="5" y2="19" />
            <line x1="5" x2="19" y1="12" y2="12" />
        </svg>
    </x-button-next>
    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Nuevo rango precio') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save">
                <div class="w-full grid grid-cols-2 gap-2">
                    <div class="w-full">
                        <x-label value="Rango inicio :" />
                        <x-input class="block w-full input-number-none" wire:model.defer="desde" type="number"
                            step="0.01" min="0" onkeypress="return validarDecimal(event, 9)" />
                        <x-jet-input-error for="desde" />
                    </div>
                    <div class="w-full">
                        <x-label value="Rango final :" />
                        <x-input class="block w-full input-number-none" wire:model.defer="hasta" type="number"
                            step="0.01" min="0" onkeypress="return validarDecimal(event, 9)" />
                        <x-jet-input-error for="hasta" />
                    </div>
                    <div class="w-full">
                        <x-label value="Porcentaje ganancia (%) :" />
                        <x-input class="block w-full input-number-none" wire:model.defer="incremento" type="number"
                            step="0.01" min="0" onkeypress="return validarDecimal(event, 5)" />
                        <x-jet-input-error for="incremento" />
                    </div>
                </div>

                <div class="w-full flex pt-4 justify-end gap-2">
                    <x-button wire:click="save(true)" wire:loading.attr="disabled">
                        {{ __('Save') }}</x-button>
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('Save and close') }}</x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>
</div>
