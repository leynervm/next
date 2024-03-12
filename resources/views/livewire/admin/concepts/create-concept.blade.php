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
            <x-button-close-modal wire:click="$toggle('open')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save" class="w-full flex flex-col gap-2">
                <div class="w-full">
                    <x-label value="Tipo movimiento :" />
                    @if (count(getTiypemovimientos()))
                        <div class="w-full flex flex-wrap gap-2 items-start">
                            @foreach (getTiypemovimientos() as $movimiento)
                                <x-input-radio class="py-2" for="{{ $movimiento->value }}" :text="$movimiento->value">
                                    <input wire:model.defer="typemovement" class="sr-only peer peer-disabled:opacity-25"
                                        type="radio" id="{{ $movimiento->value }}" name="typemovement"
                                        value="{{ $movimiento->value }}" />
                                </x-input-radio>
                            @endforeach
                        </div>
                    @endif
                    <x-jet-input-error for="typemovement" />
                </div>
                <div class="w-full">
                    <x-label value="Descripción concepto :" />
                    <x-input class="block w-full" wire:model.defer="name"
                        placeholder="Descripción del concepto pago..." />
                    <x-jet-input-error for="name" />
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
