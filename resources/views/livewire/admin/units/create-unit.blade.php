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
            {{ __('Nueva unidad medida') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save" class="w-full flex flex-col gap-2">
                <div>
                    <x-label value="Unidad medida :" />
                    <x-input class="block w-full" wire:model.defer="name" placeholder="Ingrese nombre unidad..." />
                    <x-jet-input-error for="name" />
                </div>

                <div>
                    <x-label value="Código :" />
                    <x-input class="block w-full" wire:model.defer="code" placeholder="Codigo unidad..." />
                    <x-jet-input-error for="code" />
                </div>

                {{-- <div class="w-full flex gap-2">
                    <div class="w-1/2 mt-2">
                        <x-label value="Abreviatura :" />
                        <x-input class="block w-full" wire:model.defer="abreviatura"
                            placeholder="Ingrese nombre unidad..." />
                        <x-jet-input-error for="abreviatura" />
                    </div>
                    <div class="w-1/2 mt-2">
                       
                    </div>
                </div> --}}

                <div class="w-full flex flex-wrap gap-1 pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('Save') }}</x-button>
                    <x-button wire:click="save(true)" wire:loading.attr="disabled">
                        {{ __('Save and close') }}</x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>
</div>
