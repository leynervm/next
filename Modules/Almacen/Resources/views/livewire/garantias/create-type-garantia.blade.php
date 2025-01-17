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
            {{ __('Nueva garantía producto') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save(true)" class="w-full flex flex-col gap-2">
                <div>
                    <x-label value="Descripción :" />
                    <x-input class="block w-full" wire:model.defer="name"
                        placeholder="Ingrese descripción garantía..." />
                    <x-jet-input-error for="name" />
                </div>

                <div>
                    <x-label value="Medida garantía :" />
                    <div class="w-full flex flex-wrap gap-2">
                        <x-input-radio class="py-2" for="month" text="MESES">
                            <input wire:model.defer="datecode" class="sr-only peer peer-disabled:opacity-25"
                                type="radio" id="month" name="datecode" value="MM" />
                        </x-input-radio>
                        <x-input-radio class="py-2" for="year" text="AÑOS">
                            <input wire:model.defer="datecode" class="sr-only peer peer-disabled:opacity-25"
                                type="radio" id="year" name="datecode" value="YYYY" />
                        </x-input-radio>
                    </div>
                    <x-jet-input-error for="datecode" />
                </div>

                <div>
                    <x-label value="Tiempo :" />
                    <x-input type="number" class="block w-full input-number-none" wire:model.defer="timewarranty" step="1" min="0"
                        onkeypress="return validarNumero(event)" />
                    <x-jet-input-error for="timewarranty" />
                </div>

                <div class="w-full flex gap-2 flex-wrap pt-4 justify-end">
                    <x-button type="button" wire:click="save" wire:loading.attr="disabled">
                        {{ __('Save') }}</x-button>
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('Save and close') }}</x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>
</div>
