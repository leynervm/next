<div class="">
    <x-form-card titulo="DATOS SUCURSAL">
        <form wire:submit.prevent="update" class="w-full flex flex-col gap-2">
            <div class="w-full grid grid-cols-1 xl:grid-cols-2 gap-2">
                <div class="w-full xl:col-span-2">
                    <x-label value="Nombre :" />
                    <x-input class="block w-full" wire:model.defer="sucursal.name" placeholder="Nombre de sucursal..." />
                    <x-jet-input-error for="sucursal.name" />
                </div>

                <div class="w-full">
                    <x-label value="Dirección :" />
                    <x-input class="block w-full" wire:model.defer="sucursal.direccion"
                        placeholder="Dirección de sucursal..." />
                    <x-jet-input-error for="sucursal.direccion" />
                </div>

                <div class="w-full">
                    <x-label value="Ubigeo :" />
                    <div id="parentubs_id" class="relative" x-data="{ ubigeo_id: @entangle('sucursal.ubigeo_id').defer }" x-init="select2Ubigeo">
                        <x-select class="block w-full" wire:model.defer="sucursal.ubigeo_id" x-ref="selectubigeosuc"
                            id="ubs_id" data-minimum-results-for-search="3">
                            <x-slot name="options">
                                @if (count($ubigeos))
                                    @foreach ($ubigeos as $item)
                                        <option value="{{ $item->id }}">{{ $item->region }} / {{ $item->provincia }}
                                            / {{ $item->distrito }} / {{ $item->ubigeo_reniec }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="sucursal.ubigeo_id" />
                </div>

                <div class="w-full">
                    <x-label value="Código anexo :" />
                    <x-input class="block w-full" wire:model.defer="sucursal.codeanexo"
                        placeholder="Anexo de sucursal..." maxlength="4" />
                    <x-jet-input-error for="sucursal.codeanexo" />
                </div>

                <div class="w-full xl:col-span-2">
                    <x-label-check for="editdefault">
                        <x-input wire:model.defer="sucursal.default" value="1" type="checkbox" id="editdefault" />
                        SELECCIONAR COMO PREDETERMINADO
                    </x-label-check>
                    <x-jet-input-error for="sucursal.default" />
                    <x-jet-input-error for="sucursal.empresa_id" />
                </div>
            </div>

            <div class="w-full flex pt-4 justify-end">
                <x-button type="submit" wire:loading.attr="disabled">
                    {{ __('ACTUALIZAR') }}
                </x-button>
            </div>
        </form>
    </x-form-card>

    <script>
        function select2Ubigeo() {
            this.selectUB = $(this.$refs.selectubigeosuc).select2();
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
            Livewire.hook('message.processed', () => {
                this.selectUB.select2().val(this.ubigeo_id).trigger('change');
            });
        }
    </script>
</div>
