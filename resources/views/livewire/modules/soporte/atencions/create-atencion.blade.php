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
            {{ __('Nueva Atención') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save" class="w-full flex flex-col gap-2">
                <div>
                    <x-label value="Nombre Atención :" />
                    <x-input class="block w-full" wire:model.defer="name" placeholder="Ingrese nombre de atención..." />
                    <x-jet-input-error for="name" />
                </div>

                <div>
                    <x-label-check for="equipamentrequire">
                        <x-input wire:model.defer="equipamentrequire" name="equipamentrequire" type="checkbox"
                            id="equipamentrequire" value="1" />REQUIERE AGREGAR EQUIPO</x-label-check>
                    <x-jet-input-error for="equipamentrequire" />
                </div>

                <x-label class="mt-4" value="ÁREA RESPONSABLE" />
                @if (count($areaworks))
                    <div class="flex flex-wrap gap-2">
                        @foreach ($areaworks as $item)
                            <x-input-radio class="py-2" for="areawork_{{ $item->id }}" :text="$item->name">
                                <input wire:model.defer="selectedareaworks"
                                    class="sr-only peer peer-disabled:opacity-25" type="checkbox"
                                    id="areawork_{{ $item->id }}" name="areaworks" value="{{ $item->id }}" />
                            </x-input-radio>
                        @endforeach
                    </div>
                @endif
                <x-jet-input-error for="selectedareaworks" />

                <x-label class="mt-6" value="ENTORNO ATENCIÓN" />
                @if (count($entornos))
                    <div class="flex flex-wrap gap-2">
                        @foreach ($entornos as $item)
                            <x-input-radio class="py-2" for="entorno_{{ $item->id }}" :text="$item->name">
                                <input wire:model.defer="selectedentornos" class="sr-only peer peer-disabled:opacity-25"
                                    type="checkbox" id="entorno_{{ $item->id }}" name="entornos"
                                    value="{{ $item->id }}" />
                            </x-input-radio>
                        @endforeach
                    </div>
                @endif
                <x-jet-input-error for="selectedentornos" />

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
