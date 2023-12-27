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
            <x-button-close-modal wire:click="$toggle('open')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save">
                <x-label value="Descripción :" />
                <x-input class="block w-full" wire:model.defer="name" placeholder="Ingrese descripción garantía..." />
                <x-jet-input-error for="name" />

                <x-label class="mt-2" value="Tiempo predeterminado (Letras):" />
                <x-input class="block w-full" wire:model.defer="timestring"
                    placeholder="Ingrese tiempo garantía en letras..." />
                <x-jet-input-error for="timestring" />

                <x-label class="mt-2" value="Tiempo predeterminado (Meses):" />
                <x-input type="number" class="block w-full" wire:model.defer="time" step="1" min="1" />
                <x-jet-input-error for="time" />

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('REGISTRAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>
</div>
