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
            {{ __('Nueva cuenta pago') }}
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
                <div x-data="{ banco_id: @entangle('banco_id') }" x-init="select2BancoAlpine" wire:ignore>
                    <x-label value="Tipo banco :" />
                    <x-select class="block w-full" x-ref="select" id="banco_id" data-dropdown-parent="null">
                        @if (count($bancos))
                            <x-slot name="options">
                                @foreach ($bancos as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </x-slot>
                        @endif
                    </x-select>
                    <x-jet-input-error for="banco_id" />
                </div>

                <div class="mt-2">
                    <x-label value="N° Cuenta :" class="mt-2" />
                    <x-input class="block w-full" wire:model.defer="account" placeholder="N° cuenta pago..."
                        type="number" maxlength="15" />
                    <x-jet-input-error for="account" />
                </div>

                <div class="mt-2">
                    <x-label value="Descripción cuenta :" />
                    <x-input class="block w-full" wire:model.defer="descripcion"
                        placeholder="Descripción de cuenta pago..." />
                    <x-jet-input-error for="descripcion" />
                </div>

                {{-- <div class="block mt-2">
                    <x-label-check for="default">
                        <x-input wire:model.defer="default" name="default" type="checkbox" value="1"
                            id="default" />
                        SELECCIONAR COMO PREDETERMINADO
                    </x-label-check>
                    <x-jet-input-error for="default" />
                </div> --}}

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled" wire:target="save">
                        {{ __('REGISTRAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>


    <script>
        function select2BancoAlpine() {
            this.select2 = $(this.$refs.select).select2();
            this.select2.val(this.banco_id).trigger("change");
            this.select2.on("select2:select", (event) => {
                this.banco_id = event.target.value;
            })
            this.$watch('banco_id', (value) => {
                this.select2.val(value).trigger("change");
            });
        }
    </script>
</div>
