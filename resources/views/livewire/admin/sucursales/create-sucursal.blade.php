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
                    <div class="relative" x-data="{ ubigeo_id: @entangle('ubigeo_id').defer }" x-init="selectUbigeos" id="parentubigeosucursal_id"
                        wire:ignore>
                        <x-select class="block w-full" id="ubigeosucursal_id" data-dropdown-parent="null"
                            data-minimum-results-for-search="3" x-ref="selectub">
                            <x-slot name="options">
                                @if (count($ubigeos))
                                    @foreach ($ubigeos as $item)
                                        <option value="{{ $item->id }}">{{ $item->region }} / {{ $item->provincia }}
                                            / {{ $item->distrito }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="ubigeo_id" />
                </div>

                <div class="w-full">
                    <x-label value="Tipo establecimiento :" />
                    <div class="relative" x-data="{ typesucursal_id: @entangle('typesucursal_id').defer }" x-init="typeSucursal">
                        <x-select class="block w-full" x-ref="selecttypesucursal" id="typesucursal_id"
                            data-dropdown-parent="null">
                            <x-slot name="options">
                                @foreach ($typesucursals as $item)
                                    <option value="{{ $item->id }}">[{{ $item->code }}]
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="typesucursal_id" />
                </div>

                <div class="w-full">
                    <x-label value="Código anexo :" />
                    <x-input class="block w-full" wire:model.defer="codeanexo" placeholder="Anexo de sucursal..."
                        maxlength="4" />
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

                <div class="w-full flex flex-row gap-2 pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled" wire:target="save">
                        {{ __('Save') }}</x-button>
                    <x-button wire:click="save(true)" wire:loading.attr="disabled" wire:target="save">
                        {{ __('Save and close') }}</x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>


    <script>
        function selectUbigeos() {
            this.selectUB = $(this.$refs.selectub).select2();
            this.selectUB.val(this.ubigeo_id).trigger("change");
            this.selectUB.on("select2:select", (event) => {
                this.ubigeo_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("ubigeo_id", (value) => {
                this.selectUB.val(value).trigger("change");
            });
        }

        function typeSucursal() {
            this.selectTS = $(this.$refs.selecttypesucursal).select2();
            this.selectTS.val(this.typesucursal_id).trigger("change");
            this.selectTS.on("select2:select", (event) => {
                this.typesucursal_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("typesucursal_id", (value) => {
                this.selectTS.val(value).trigger("change");
            });

            Livewire.hook('message.processed', () => {
                this.selectTS.select2().val(this.typesucursal_id).trigger('change');
            });
        }
    </script>
</div>
