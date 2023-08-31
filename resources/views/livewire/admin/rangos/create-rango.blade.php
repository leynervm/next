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
            <x-button-add wire:click="$toggle('open')" wire:loading.attr="disabled">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </x-button-add>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save">
                <div class="w-full flex flex-wrap sm:flex-nowrap gap-2">
                    <div class="w-full sm:w-1/2">
                        <x-label value="Precio desde :" />
                        <x-input class="block w-full" wire:model.defer="desde" type="number" step="0.1"
                            min="0" />
                        <x-jet-input-error for="desde" />
                    </div>
                    <div class="w-full sm:w-1/2">
                        <x-label value="Precio hasta :" />
                        <x-input class="block w-full" wire:model.defer="hasta" type="number" step="0.1"
                            min="0"  />
                        <x-jet-input-error for="hasta" />
                    </div>
                </div>

                <div class="md:w-1/2 mt-2">
                    <x-label value="Incremento precio :" />
                    <x-input class="block w-full" wire:model.defer="incremento" type="number" step="0.1"
                        min="0" />
                    <x-jet-input-error for="incremento" />
                </div>


                <div class="w-full flex flex-row pt-4 gap-2 justify-end text-right">
                    <x-button type="submit" size="xs" class="" wire:loading.attr="disabled"
                        wire:target="save">
                        {{ __('REGISTRAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>
</div>
