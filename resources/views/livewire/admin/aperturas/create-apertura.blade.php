<div>
    <div>
        <x-button-next titulo="Registrar" wire:click="$set('open', true)">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" x2="12" y1="5" y2="19" />
                <line x1="5" x2="19" y1="12" y2="12" />
            </svg>
        </x-button-next>
    </div>

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Aperturar caja') }}
            <x-button-close-modal wire:click="$toggle('open')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save">
                <div class="w-full">
                    <x-label value="Seleccionar caja :" />
                    <div x-data="{ caja_id: @entangle('caja_id') }" x-init="select2CajaAlpine" wire:ignore class="relative">
                        <x-select class="block w-full" x-ref="select" id="aperturacaja_id" data-dropdown-parent="null">
                            <x-slot name="options">
                                @if (count($cajas))
                                    @foreach ($cajas as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="caja_id" />
                </div>

                <div class="w-full mt-2">
                    <x-label value="Fecha cierre :" />
                    <x-input class="block w-full" wire:model.defer="expiredate" type="datetime-local" />
                    <x-jet-input-error for="expiredate" />
                </div>

                <div class="w-full mt-2">
                    <x-label value="Saldo inicial :" />
                    <x-input class="block w-full" wire:model.defer="startmount" type="number" step="0.01"
                        min="0" />
                    <x-jet-input-error for="startmount" />
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
        function select2CajaAlpine() {
            this.select2 = $(this.$refs.select).select2();
            this.select2.val(this.caja_id).trigger("change");
            this.select2.on("select2:select", (event) => {
                this.caja_id = event.target.value;
            })
            this.$watch('caja_id', (value) => {
                this.select2.val(value).trigger("change");
            });
        }
    </script>
</div>
