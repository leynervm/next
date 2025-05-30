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
            {{ __('Nuevo entorno atención') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save" class="w-full flex flex-col gap-2">
                <div class="">
                    <x-label value="Entorno Atención :" />
                    <x-input class="block w-full" wire:model.defer="name"
                        placeholder="Ingrese entorno de atención..." />
                    <x-jet-input-error for="name" />
                </div>

                <div>
                    <x-label-check for="requiredirection">
                        <x-input wire:model.defer="requiredirection" name="requiredirection" type="checkbox"
                            id="requiredirection" />REQUIERE AGREGAR DIRECCIÓN</x-label-check>
                    <x-jet-input-error for="requiredirection" />
                </div>

                <div class="w-full flex pt-4 gap-2 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('Save') }}
                    </x-button>
                    <x-button type="button" wire:click="save(true)" wire:loading.attr="disabled">
                        {{ __('Save and close') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>
</div>
