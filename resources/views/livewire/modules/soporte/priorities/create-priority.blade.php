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
            {{ __('Nueva Prioridad Atención') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save" class="w-full flex flex-col gap-2">
                <div class="w-full">
                    <x-label value="Nombre :" />
                    <x-input class="block w-full" wire:model.defer="name"
                        placeholder="Ingrese nombre de prioridad..." />
                    <x-jet-input-error for="name" />
                </div>

                <div class="w-full">
                    <x-label value="Seleccionar color :" />
                    <input wire:model.defer="color" type="color" value="#000000" class="size-16 rounded-lg" />
                    <x-jet-input-error for="color" />
                </div>

                {{-- <x-label class="mt-2 inline-flex items-center gap-1 cursor-pointer" for="default">
                    <x-input wire:model.defer="default" type="checkbox" id="default" value="1" />
                    Definir valor como predeterminado
                </x-label>
                <x-jet-input-error for="default" /> --}}

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
