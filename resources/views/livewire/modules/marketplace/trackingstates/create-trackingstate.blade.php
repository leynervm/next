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
            {{ __('Actualizar estado de pedido') }}
            <x-button-close-modal wire:click="$toggle('open')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save" class="w-full flex flex-col gap-2">
                <div class="w-full">
                    <x-label value="Nombre :" />
                    <x-input class="block w-full" wire:model.defer="name" placeholder="Descripción del estado..." />
                    <x-jet-input-error for="name" />
                </div>

                <div class="w-full">
                    <div class="w-14 h-14 relative overflow-hidden rounded-full">
                        <input type="color" wire:model.defer="background"
                            class="w-[140%] h-[140%] border-none border-0 outline-none absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2" />
                    </div>
                    <x-label value="SELECCIONAR COLOR" />
                    <x-jet-input-error for="background" />
                </div>

                <div class="block">
                    <x-label-check for="finish">
                        <x-input wire:model.defer="finish" type="checkbox" id="finish" value="1" />
                        FINALIZA SEGUIMIENTO DEL PEDIDO
                    </x-label-check>
                    <x-jet-input-error for="finish" />
                </div>

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('REGISTRAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>
</div>
