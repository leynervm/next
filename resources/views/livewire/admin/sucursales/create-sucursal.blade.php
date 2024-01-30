<div>
    <x-button-next titulo="Registrar" wire:click="$set('open', true)">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" x2="12" y1="5" y2="19" />
            <line x1="5" x2="19" y1="12" y2="12" />
        </svg>
    </x-button-next>
    <x-jet-dialog-modal wire:model="open" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Nueva sucursal') }}
            <x-button-close-modal wire:click="$toggle('open')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save" class="flex flex-col gap-2">
                <div class="w-full">
                    <x-label value="Nombre :" />
                    <x-input class="block w-full" wire:model.defer="name" placeholder="Nombre de sucursal..." />
                    <x-jet-input-error for="name" />
                </div>

                <div class="w-full">
                    <x-label value="Dirección :" />
                    <x-input class="block w-full" wire:model.defer="direccion" placeholder="Dirección de sucursal..." />
                    <x-jet-input-error for="direccion" />
                </div>

                <div class="w-full">
                    <x-label value="Ubigeo :" />
                    <x-select class="block w-full" wire:model.defer="ubigeo_id" id="ubigeosucursal_id"
                        data-dropdown-parent="null" data-minimum-results-for-search="3">
                        <x-slot name="options">
                            @if (count($ubigeos))
                                @foreach ($ubigeos as $item)
                                    <option value="{{ $item->id }}">{{ $item->region }} / {{ $item->provincia }}
                                        / {{ $item->distrito }}</option>
                                @endforeach
                            @endif
                        </x-slot>
                    </x-select>
                    <x-jet-input-error for="ubigeo_id" />
                </div>

                <div class="w-full">
                    <x-label value="Código anexo :" />
                    <x-input class="block w-full" wire:model.defer="codeanexo" placeholder="Anexo de sucursal..." maxlength="4" />
                    <x-jet-input-error for="codeanexo" />
                </div>

                <div class="block">
                    <x-label-check for="default">
                        <x-input wire:model.defer="default" value="1" type="checkbox" id="default" />
                        SELECCIONAR COMO PREDETERMINADO
                    </x-label-check>
                    <x-jet-input-error for="default" />
                    <x-jet-input-error for="empresa.id" />
                </div>

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled" wire:target="save">
                        {{ __('REGISTRAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>


    <script>
        document.addEventListener('livewire:load', function() {
            $('#ubigeosucursal_id').select2().on("change", function(e) {
                $('#ubigeosucursal_id').attr("disabled", true);
                @this.set('ubigeo_id', e.target.value);
            });

            document.addEventListener('render-create-sucursal', () => {
                $('#ubigeosucursal_id').select2();
            });

        })
    </script>
</div>
