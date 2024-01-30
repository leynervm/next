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
            {{ __('Nueva forma pago') }}
            <x-button-close-modal wire:click="$toggle('open')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save">
                <x-label value="Forma pago :" />
                <x-input class="block w-full" wire:model.defer="name"
                    placeholder="Ingrese descripción de forma pago..." />
                <x-jet-input-error for="name" />


                <div class="block mt-1">
                    <x-label-check for="default">
                        <x-input wire:model.defer="default" name="default" value="1" type="checkbox"
                            id="default" />SELECCIONAR COMO PREDETERMINADO </x-label-check>
                    <x-jet-input-error for="default" />
                </div>

                <x-label value="Asignar cuentas pago :" class="mt-2 underline" />

                @if (count($cuentas))
                    <div class="w-full flex gap-1 flex-wrap mt-1">
                        @foreach ($cuentas as $item)
                            <x-label-check for="{{ $item->id }}">
                                <x-input wire:model.defer="selectedCuentas" name="default" value="{{ $item->id }}"
                                    type="checkbox" id="{{ $item->id }}" />
                                {{ $item->account }} ({{ $item->descripcion }} - {{ $item->banco->name }})
                            </x-label-check>
                        @endforeach
                    </div>
                @endif

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('REGISTRAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>
</div>
