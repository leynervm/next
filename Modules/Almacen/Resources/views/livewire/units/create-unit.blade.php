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
                <x-label value="Unidad medida :" />
                <x-input class="block w-full" wire:model.defer="name" placeholder="Ingrese nombre unidad..." />
                <x-jet-input-error for="name" />

                <x-label value="Código :" class="mt-2"/>
                <x-input class="block w-full" wire:model.defer="code" placeholder="Ingrese nombre unidad..." />
                <x-jet-input-error for="code" />

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
