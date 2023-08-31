<div>
    <x-button-next titulo="Registrar Lista Precio" wire:click="$set('open', true)">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" x2="12" y1="5" y2="19" />
            <line x1="5" x2="19" y1="12" y2="12" />
        </svg>
    </x-button-next>
    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Nueva lista precio') }}
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
                <x-label value="Lista precio :" />
                <x-input class="block w-full" wire:model.defer="name" placeholder="Nombre de lista precio..." />
                <x-jet-input-error for="name" />

                <div class="w-full flex flex-wrap sm:flex-nowrap gap-2 mt-2">
                    <div class="w-full sm:w-1/2">
                        <x-label value="Porcentaje ganancia (%) :" />
                        <x-input class="block w-full" wire:model.defer="ganancia" type="number" step="0.1"
                            min="0" />
                        <x-jet-input-error for="ganancia" />
                    </div>
                    <div class="w-full sm:w-1/2">
                        <x-label value="Redondear decimales :" />
                        <x-input class="block w-full" wire:model.defer="decimalrounded" type="number" step="1"
                            min="0" max="4" />
                        <x-jet-input-error for="decimalrounded" />
                    </div>
                </div>

                <div class="mt-3 mb-1">
                    <x-label textSize="[10px]"
                        class="inline-flex items-center tracking-widest font-semibold gap-2 cursor-pointer bg-next-100 rounded-lg p-1"
                        for="default">
                        <x-input wire:model.defer="default" name="default" type="checkbox" id="default" />
                        SELECCIONAR COMO PREDETERMINADO
                    </x-label>
                </div>
                <x-jet-input-error for="default" />

                <div class="mt-1 mb-1">
                    <x-label textSize="[10px]"
                        class="inline-flex items-center tracking-widest font-semibold gap-2 cursor-pointer bg-next-100 rounded-lg p-1"
                        for="web">
                        <x-input wire:model.defer="web" name="web" type="checkbox" id="web" />
                        PREDETERMINADO PARA VENTAS POR INTERNET
                    </x-label>
                </div>
                <x-jet-input-error for="web" />


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
