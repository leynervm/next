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
            {{ __('Nuevo concepto pago') }}
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
                <x-label value="Descripción concepto :" />
                <x-input class="block w-full" wire:model.defer="name" placeholder="Descripción del concepto pago..." />
                <x-jet-input-error for="name" />

                <fieldset class="border border-solid border-next-300 mt-2">
                    <legend class="text-xs text-next-500 tracking-wider block">Asignar predeterminado</legend>

                    <div class="w-full flex gap-1 items-start p-1">

                        <div class="block">
                            <x-label-check for="ninguno">
                                <x-input wire:model.defer="default" name="default" type="radio" value="0"
                                    id="ninguno" class="checked:rounded-full" />
                                NINGUNO
                            </x-label-check>
                        </div>

                        <div class="block">
                            <x-label-check for="ventas">
                                <x-input wire:model.defer="default" name="default" type="radio" value="1"
                                    id="ventas" class="checked:rounded-full" />
                                PAGO VENTAS
                            </x-label-check>
                        </div>

                        <div class="block">
                            <x-label-check for="internet">
                                <x-input wire:model.defer="default" name="default" type="radio" value="2"
                                    id="internet" class="checked:rounded-full" />
                                PAGO INTERNET
                            </x-label-check>
                        </div>

                        <div class="block">
                            <x-label-check for="cuota">
                                <x-input wire:model.defer="default" name="default" type="radio" value="3"
                                    id="cuota" class="checked:rounded-full" />
                                PAGO CUOTA
                            </x-label-check>
                        </div>
                    </div>
                </fieldset>
                <x-jet-input-error for="default" />


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
