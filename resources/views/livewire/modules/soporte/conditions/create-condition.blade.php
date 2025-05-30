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
            {{ __('Nueva Condición Atención') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save" class="w-full flex flex-col gap-2">
                <div>
                    <x-label value="Condición atención :" />
                    <x-input class="block w-full" wire:model.defer="name"
                        placeholder="Ingrese descripción de condición..." />
                    <x-jet-input-error for="name" />
                </div>

                <div>
                    <x-label-check for="flagpagable">
                        <x-input wire:model.defer="flagpagable" name="flagpagable" type="checkbox" id="flagpagable"
                            value="1" />DEFINIR COMO PAGABLE</x-label-check>
                    <x-jet-input-error for="flagpagable" />
                </div>

                {{-- <x-label class="mt-6" value="Asignar marcas autorizadas" /> --}}

                <div class="w-full flex gap-2 pt-4 justify-end">
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
